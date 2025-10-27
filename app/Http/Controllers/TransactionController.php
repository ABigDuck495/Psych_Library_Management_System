<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Thesis;
use App\Models\Penalty;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // Only admin, super-admin, and librarian can view all transactions
        if (!in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian'])) {
            abort(403, 'Unauthorized action.');
        }

        // FIXED: Removed invalid eager load 'copy.thesis'
        $query = Transaction::with(['user', 'copy']);

        // Filters
        if ($request->filled('status')) {
            $query->where('transaction_status', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $transactions = $query->latest()->paginate(50);
        $users = User::where('role', 'user')->get();

        return view('transactions.index', compact('transactions', 'users'));
    }

    public function requestedBooks()
    {
        if (!in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian'])) {
            abort(403, 'Unauthorized action.');
        }

        $requests = Transaction::requested()
            ->with(['user', 'copy'])
            ->latest()
            ->paginate(20);

        return view('transactions.requested-books', compact('requests'));
    }

    public function requestedTheses()
    {
        if (!in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian'])) {
            abort(403, 'Unauthorized action.');
        }

        $requests = Transaction::requested()
            ->where('copy_type', 'like', '%ThesisCopy%')
            ->with(['user', 'copy'])
            ->latest()
            ->paginate(20);

        return view('transactions.requested-theses', compact('requests'));
    }

    public function overdueTransactions()
    {
        if (!in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian'])) {
            abort(403, 'Unauthorized action.');
        }

        $transactions = Transaction::overdue()
            ->with(['user', 'copy'])
            ->latest()
            ->paginate(30);

        return view('transactions.overdue', compact('transactions'));
    }

    public function penaltiesIndex()
    {
        $penalties = Penalty::with(['user', 'transaction.copy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('penalties.index', compact('penalties'));
    }

    public function payPenalty(Penalty $penalty)
    {
        $penalty->delete();
        return redirect()->back()->with('success', 'Penalty paid successfully.');
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'copy_id' => 'required|integer',
            'return_date' => 'nullable|date',
            'transaction_status' => 'required|string|in:borrow,return',
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction created successfully!');
    }

    public function edit($id)
    {
        return view('transactions.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'copy_id' => 'required|integer',
            'return_date' => 'nullable|date',
            'transaction_status' => 'required|string|in:borrow,return',
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->update($request->only([
            'user_id', 'copy_id', 'return_date', 'transaction_status', 'due_date', 'borrow_date', 'copy_type'
        ]));

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully!');
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'copy']);
        return view('transactions.show', compact('transaction'));
    }

    public function requestBook(Request $request, Book $book)
    {
        $user = Auth::user();
        if (!$user) return view('auth.login');

        if ($user->hasPendingRequestForBook($book->id)) {
            return redirect()->back()->with('error', 'You already have a pending request for this book.');
        }

        $reservedCopy = DB::transaction(function () use ($book) {
            $copy = $book->copies()->where('is_available', 1)->lockForUpdate()->first();
            if (!$copy) return null;
            $copy->is_available = false;
            $copy->save();
            return $copy;
        });

        if (!$reservedCopy) {
            return redirect()->back()->with('error', 'No available copies found for this book.');
        }

        Transaction::create([
            'user_id' => $user->id,
            'copy_id' => $reservedCopy->copy_id,
            'copy_type' => get_class($reservedCopy),
            'borrow_date' => now(),
            'due_date' => now()->addDays(14),
            'return_date' => null,
            'transaction_status' => 'requested',
        ]);

        return redirect()->back()->with('success', 'Book request submitted successfully.');
    }

    public function requestThesis(Request $request, Thesis $thesis)
    {
        $user = Auth::user();
        if (!$user) return view('auth.login');

        if ($user->hasPendingRequestForBook($thesis->id)) {
            return redirect()->back()->with('error', 'You already have a pending request for this thesis.');
        }

        $reservedCopy = DB::transaction(function () use ($thesis) {
            $copy = $thesis->copies()->where('is_available', 1)->lockForUpdate()->first();
            if (!$copy) return null;
            $copy->is_available = false;
            $copy->save();
            return $copy;
        });

        if (!$reservedCopy) {
            return redirect()->back()->with('error', 'No available copies found for this thesis.');
        }

        Transaction::create([
            'user_id' => $user->id,
            'copy_id' => $reservedCopy->copy_id,
            'copy_type' => get_class($reservedCopy),
            'borrow_date' => now(),
            'due_date' => now()->addDays(14),
            'return_date' => null,
            'transaction_status' => 'requested',
        ]);

        return redirect()->back()->with('success', 'Thesis request submitted successfully.');
    }

    public function approveRequest(Request $request, Transaction $transaction)
    {
        if ($transaction->transaction_status !== 'requested') {
            return redirect()->back()->with('error', 'Only requested transactions can be approved.');
        }

        $transaction->update([
            'transaction_status' => 'borrowed',
            'borrow_date' => $transaction->borrow_date ?? now(),
            'due_date' => $transaction->due_date ?? now()->addDays(14),
        ]);

        return redirect()->back()->with('success', 'Transaction approved.');
    }

    public function returnBook(Request $request, Transaction $transaction)
    {
        if ($transaction->transaction_status === 'returned') {
            return redirect()->back()->with('error', 'Transaction already returned.');
        }

        DB::transaction(function () use ($transaction) {
            $transaction->update([
                'return_date' => now(),
                'transaction_status' => 'returned',
            ]);

            if ($transaction->copy) {
                $transaction->copy->update(['is_available' => true]);
            }
        });

        return redirect()->back()->with('success', 'Item returned successfully.');
    }

    public function renew(Request $request, Transaction $transaction)
    {
        $user = auth()->user();

        if (!$user || (!in_array($user->role, ['admin','super-admin','librarian']) && $transaction->user_id !== $user->id)) {
            abort(403, 'Unauthorized action.');
        }

        if ($transaction->transaction_status !== 'borrowed') {
            return redirect()->back()->with('error', 'Only borrowed transactions can be renewed.');
        }

        $transaction->due_date = $transaction->due_date->addDays(7);
        $transaction->save();

        return redirect()->back()->with('success', 'Transaction renewed for 7 days.');
    }

    public function markOverdue(Request $request, Transaction $transaction)
    {
        if (!in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian'])) {
            abort(403, 'Unauthorized action.');
        }

        $transaction->update(['transaction_status' => 'overdue']);

        return redirect()->back()->with('success', 'Transaction marked as overdue.');
    }

    public function bookCopy()
    {
        return $this->belongsTo(BookCopy::class, 'copy_id', 'id');
    }

    // 🔹 Thesis Copy Relation
    public function thesisCopy()
    {
        return $this->belongsTo(ThesisCopy::class, 'copy_id', 'id');
    }

    // 🔹 User Relation
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

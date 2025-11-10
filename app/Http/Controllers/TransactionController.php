<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Thesis;
use App\Models\Penalty;
use App\Models\BookCopy;
use App\Models\ThesisCopy;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian'])) {
            abort(403, 'Unauthorized action.');
        }

        // Use polymorphic eager loading
        $query = Transaction::with(['user', 'borrowable' => function ($morphTo) {
            $morphTo->morphWith([
                BookCopy::class => ['book'],
                ThesisCopy::class => ['thesis'],
            ]);
        }]);

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

        $query->orderByRaw("FIELD(transaction_status, 'requested', 'borrowed', 'returned') DESC");
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
            ->with(['user', 'borrowable' => function ($morphTo) {
                $morphTo->morphWith([
                    BookCopy::class => ['book'],
                ]);
            }])
            ->where('borrowable_type', BookCopy::class)
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
            ->with(['user', 'borrowable' => function ($morphTo) {
                $morphTo->morphWith([
                    ThesisCopy::class => ['thesis'],
                ]);
            }])
            ->where('borrowable_type', ThesisCopy::class)
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
            ->with(['user', 'borrowable' => function ($morphTo) {
                $morphTo->morphWith([
                    BookCopy::class => ['book'],
                    ThesisCopy::class => ['thesis'],
                ]);
            }])
            ->latest()
            ->paginate(30);

        return view('transactions.overdue', compact('transactions'));
    }

    public function penaltiesIndex()
    {
        if (!in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian'])) {
            abort(403, 'Unauthorized action.');
        }

        $penalties = Penalty::with(['user', 'transaction.borrowable' => function ($morphTo) {
            $morphTo->morphWith([
                BookCopy::class => ['book'],
                ThesisCopy::class => ['thesis'],
            ]);
        }])
        ->orderBy('created_at', 'desc')
        ->paginate(30);

        return view('penalties.index', compact('penalties'));
    }

    public function payPenalty(Penalty $penalty)
    {
        if (!in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian'])) {
            abort(403, 'Unauthorized action.');
        }

        $penalty->delete();
        return redirect()->back()->with('success', 'Penalty paid successfully.');
    }

    public function create()
    {
        if (!in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian'])) {
            abort(403, 'Unauthorized action.');
        }
        $users = User::all();

        return view('transactions.create', compact('users'));    
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian'])) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'borrowable_id' => 'required|integer',
            'borrowable_type' => 'required|string|in:App\Models\BookCopy,App\Models\ThesisCopy',
            'transaction_status' => 'required|string|in:requested,approved,borrowed',
        ]);

        $transaction = Transaction::create($validated);

        return redirect()->route('transactions.index')->with('success', 'Transaction created successfully!');
    }

    public function edit(Transaction $transaction)
    {
        if (!in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian'])) {
            abort(403, 'Unauthorized action.');
        }

        $transaction->load(['user', 'borrowable' => function ($morphTo) {
            $morphTo->morphWith([
                BookCopy::class => ['book'],
                ThesisCopy::class => ['thesis'],
            ]);
        }]);

        return view('transactions.edit', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        if (!in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian'])) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'transaction_status' => 'required|in:requested,approved,borrowed,returned,overdue,cancelled',
            'borrow_date' => 'required|date',
            'due_date' => 'required|date',
            'return_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($validated, $transaction) {
            $oldStatus = $transaction->transaction_status;
            $newStatus = $validated['transaction_status'];

            // Handle return date logic
            if ($newStatus === 'returned' && !$validated['return_date']) {
                $validated['return_date'] = now();
            } elseif ($newStatus !== 'returned') {
                $validated['return_date'] = null;
            }

            // If marking as returned, make the copy available again
            if ($newStatus === 'returned' && $oldStatus !== 'returned') {
                if ($transaction->borrowable) {
                    $transaction->borrowable->markAsAvailable();
                }
            }

            // If marking as borrowed, make the copy unavailable
            if ($newStatus === 'borrowed' && $oldStatus !== 'borrowed') {
                if ($transaction->borrowable) {
                    $transaction->borrowable->markAsUnavailable();
                }
            }

            $transaction->update($validated);
        });

        return redirect()->route('transactions.show', $transaction)
                        ->with('success', 'Transaction updated successfully.');
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'borrowable' => function ($morphTo) {
            $morphTo->morphWith([
                BookCopy::class => ['book'],
                ThesisCopy::class => ['thesis'],
            ]);
        }]);

        return view('transactions.show', compact('transaction'));
    }
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted.');
    }

    public function requestBook(Request $request, Book $book)
    {
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('login');
    }

    // Check overdue
    if ($user->hasOverdueTransactions()) {
        return redirect()->back()->withErrors('You cannot request new items while you have overdue transactions.');
    }

    // Check pending requests for same book
    if ($user->hasPendingRequestForBook($book->id)) {
        return redirect()->back()->with('error', 'You already have a pending request for this book.');
    }

    // Check if book can be requested
    if (!$book->canBeRequested()) {
        return redirect()->back()->with('error', 'No available copies found for this book.');
    }

    DB::beginTransaction();
    try {
        $availableCopy = $book->getNextAvailableCopy();

        if (!$availableCopy) {
            DB::rollBack();
            return redirect()->back()->with('error', 'No available copies found for this book.');
        }

        // ✅ Create transaction (keep borrowable_id = $availableCopy->book_id as requested)
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'borrowable_id' => $availableCopy->id, // you wanted to keep this
            'borrowable_type' => BookCopy::class,
            'transaction_status' => 'requested',
            'borrow_date' => now(),
            'due_date' => now()->addDays(7),
        ]);

        // Mark the copy as unavailable
        $availableCopy->markAsUnavailable();

        DB::commit();
        return redirect()->back()->with('success', 'Book request submitted successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Failed to process book request: ' . $e->getMessage());
    }
}


    public function requestThesis(Request $request, Thesis $thesis)
    {
        $user = Auth::user();
        if (Auth::user()->hasOverdueTransactions()) {
            return redirect()->back()->withErrors('You cannot request new items while you have overdue transactions.');
        }
        if (!$user) return redirect()->route('login');

        if ($user->hasPendingRequestForThesis($thesis->id)) {
            return redirect()->back()->with('error', 'You already have a pending request for this thesis.');
        }

        if (!$thesis->canBeRequested()) {
            return redirect()->back()->with('error', 'No available copies found for this thesis.');
        }

        DB::beginTransaction();
        try {
            $availableCopy = $thesis->getNextAvailableCopy();
            
            if (!$availableCopy) {
                DB::rollBack();
                return redirect()->back()->with('error', 'No available copies found for this thesis.');
            }

            // Create transaction using polymorphic relationship
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'borrowable_id' => $availableCopy->id,
                'borrowable_type' => ThesisCopy::class,
                'transaction_status' => 'requested',
                'borrow_date' => now(),
                'due_date' => now()->addDays(1),
            ]);

            // Mark copy as unavailable
            $availableCopy->markAsUnavailable();

            DB::commit();
            return redirect()->back()->with('success', 'Thesis request submitted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to process thesis request: ' . $e->getMessage());
        }
    }

    public function approveRequest(Transaction $transaction)
    {
        if (!in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian'])) {
            abort(403, 'Unauthorized action.');
        }

        if ($transaction->transaction_status !== 'requested') {
            return redirect()->back()->with('error', 'Only requested transactions can be approved.');
        }

        $transaction->markAsApproved();
        return redirect()->back()->with('success', 'Transaction approved successfully.');
    }
    public function rejectRequest(Transaction $transaction){
        if (!in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian'])) {
            abort(403, 'Unauthorized action.');
        }

        if ($transaction->transaction_status !== 'requested') {
            return redirect()->back()->with('error', 'Only requested transactions can be approved.');
        }
        $transaction->markAsRejected();
        return redirect()->back()->with('success', 'Transaction rejected successfully');
    }

    public function markAsBorrowed(Transaction $transaction)
    {
        if (!in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian'])) {
            abort(403, 'Unauthorized action.');
        }

        if ($transaction->transaction_status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved transactions can be marked as borrowed.');
        }

        $transaction->markAsBorrowed();
        return redirect()->back()->with('success', 'Transaction marked as borrowed.');
    }

    public function returnBook(Request $request, Transaction $transaction)
    {
        $user = auth()->user();

        if (!$user || (!in_array($user->role, ['admin','super-admin','librarian']) && $transaction->user_id !== $user->id)) {
            abort(403, 'Unauthorized action.');
        }

        if ($transaction->transaction_status !== 'borrowed' && $transaction->transaction_status !== 'overdue') {
            return redirect()->back()->with('error', 'Only borrowed or overdue transactions can be returned.');
        }

        $transaction->markAsReturned();
        return redirect()->back()->with('success', 'Transaction marked as returned.');
    }
    public function returnThesis(Request $request, Transaction $transaction)
    {
        $user = auth()->user();

        if (!$user || (!in_array($user->role, ['admin','super-admin','librarian']) && $transaction->user_id !== $user->id)) {
            abort(403, 'Unauthorized action.');
        }

        if ($transaction->transaction_status !== 'borrowed' && $transaction->transaction_status !== 'overdue') {
            return redirect()->back()->with('error', 'Only borrowed or overdue transactions can be returned.');
        }

        $transaction->markAsReturned();
        return redirect()->back()->with('success', 'Transaction marked as returned.');
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

        if ($transaction->isOverdue()) {
            return redirect()->back()->with('error', 'Overdue transactions cannot be renewed.');
        }

        $transaction->update([
            'due_date' => $transaction->due_date->addDays(7)
        ]);

        return redirect()->back()->with('success', 'Transaction renewed for 7 days.');
    }

    public function markOverdue(Request $request, Transaction $transaction)
    {
        if (!in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian'])) {
            abort(403, 'Unauthorized action.');
        }

        $transaction->markAsOverdue();
        return redirect()->back()->with('success', 'Transaction marked as overdue.');
    }

    public function cancelRequest(Transaction $transaction)
    {
        $user = auth()->user();

        if (!$user || (!in_array($user->role, ['admin','super-admin','librarian']) && $transaction->user_id !== $user->id)) {
            abort(403, 'Unauthorized action.');
        }

        if ($transaction->transaction_status !== 'requested') {
            return redirect()->back()->with('error', 'Only requested transactions can be cancelled.');
        }

        DB::transaction(function () use ($transaction) {
            // Mark copy as available again
            if ($transaction->borrowable) {
                $transaction->borrowable->markAsAvailable();
            }

            $transaction->update(['transaction_status' => 'cancelled']);
        });

        return redirect()->back()->with('success', 'Request cancelled successfully.');
    }

    
}

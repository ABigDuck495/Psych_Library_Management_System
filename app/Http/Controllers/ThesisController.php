<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Thesis;
use App\Models\ThesisCopy;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ThesisController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }
    public function show(Thesis $thesis)
{
    // Load the thesis with its authors
    $thesis->load('authors');
    return view('theses.show', compact('thesis'));
}
    public function index()
    {
        // Load theses with their related authors
        $theses = Thesis::with('authors')->paginate(10);
        return view('theses.index', compact('theses'));
    }

     public function userInterface()
    {
    $theses = Thesis::with('authors')->get();
    $books = collect(); // empty placeholder
    return view('userInterface.index', compact('theses', 'books'));
    }


    public function create()
    {
        // Dropdown options for department field
        $departments = ['AB Psychology', 'BS Psychology'];
        $authors = Author::all();
        return view('theses.create', compact('departments', 'authors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:500',
            'abstract' => 'required|string',
            'year_published' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'author_ids' => 'required|array|min:1',
            'author_ids.*' => 'exists:authors,id',
            'copies_count' => 'required|integer|min:1'
        ]);

        // extract authors and remove before creating the thesis
        $authorIds = $validated['author_ids'] ?? [];
        unset($validated['author_ids']);

        // Create the thesis record
        $thesis = Thesis::create($validated);

        // Link thesis ↔ authors (use sync to ensure exact set)
        $thesis->authors()->sync($authorIds);

        for($i = 0; $i < $request->copies_count; $i++) {
            ThesisCopy::create([
                'thesis_id' => $thesis->id,
                'is_available' => true
            ]);
        }

        return redirect()->route('theses.index')->with('success', 'Thesis added successfully.');
    }

    public function edit(Thesis $thesis)
    {
        $departments = ['AB Psychology', 'BS Psychology'];
        $authors = Author::all();
        $copies_count = $thesis->copies()->count();
        return view('theses.update', compact('thesis', 'departments', 'authors', 'copies_count'));
    }

    public function update(Request $request, Thesis $thesis)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:500',
            'abstract' => 'required|string',
            'year_published' => 'required|integer|min:1900|max:' . date('Y'),
            'department' => 'required|string',
            'author_ids' => 'sometimes|array',
            'author_ids.*' => 'exists:authors,id',
            'copies_count' => 'sometimes|integer|min:1'
        ]);

        $thesis->update(
            collect($validated)->only(['title', 'abstract', 'year_published', 'department'])->toArray()
        );

        if ($request->has('author_ids')) {
            $thesis->authors()->sync($validated['author_ids'] ?? []);
        }

        if ($request->filled('copies_count')) {
            $desired = (int)$request->input('copies_count');
            $existing = $thesis->copies()->count();
            if ($desired > $existing) {
                $toCreate = $desired - $existing;
                for ($i = 0; $i < $toCreate; $i++) {
                    ThesisCopy::create([
                        'thesis_id' => $thesis->id,
                        'is_available' => true
                    ]);
                }
            }
        }

        return redirect()->route('theses.index')->with('success', 'Thesis updated successfully.');
    }

    public function destroy(Thesis $thesis)
    {
        $thesis->delete();
        return redirect()->route('theses.index')->with('success', 'Thesis deleted successfully.');
    }
    public function availableCopies($thesisId){
        $availableCopiesCount = ThesisCopy::where('thesis_id', $thesisId)
                                        ->where('is_available', true)
                                        ->count();
        return $availableCopiesCount;
    }
    
    public function request(Request $request, Thesis $thesis){
        $user = auth()->user();
        if(!$user){
            return view('auth.login');
        }

        $availableCopy = $thesis->getNextAvailableCopy();
        if(!$availableCopy){
            return redirect()->back()->with('error', 'No available copies found for this thesis.');
        }

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'copy_id' => $availableCopy->id,
            'copy_type' => get_class($availableCopy),
            'borrow_date' => now(),
            'due_date' => now()->addDays(7),
            'return_date' => null,
            'transaction_status' => 'requested',
        ]);

        // mark copy unavailable so others can't request it
        $availableCopy->is_available = false;
        $availableCopy->save();

        return redirect()->route('theses.index')->with('success','Thesis request submitted successfully. Please wait for approval.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Thesis;
use App\Models\ThesisCopy;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class ThesisController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $theses = Thesis::with('authors')->paginate(10);
        return view('theses.index', compact('theses'));
    }

    public function userInterface()
    {
        $theses = Thesis::with('authors')->get();
        $books = collect();
        return view('userInterface.index', compact('theses', 'books'));
    }

    public function create()
    {
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
        'department' => 'required|string',
        'authors' => 'required|array|min:1',
        'authors.*.first_name' => 'required|string|max:255',
        'authors.*.last_name' => 'required|string|max:255',
        'copies_count' => 'required|integer|min:1'
    ]);

    DB::transaction(function () use ($validated) {
        // Extract copies_count and authors before creating thesis
        $copiesCount = $validated['copies_count'];
        $authorsData = $validated['authors'];
        
        // Remove non-thesis fields from validated data
        $thesisData = collect($validated)
            ->except(['copies_count', 'authors'])
            ->toArray();

        // Create the thesis
        $thesis = Thesis::create($thesisData);

        // Create authors dynamically
        foreach ($authorsData as $authorData) {
            $author = Author::firstOrCreate([
                'first_name' => $authorData['first_name'],
                'last_name' => $authorData['last_name'],
            ]);
            $thesis->authors()->attach($author->id);
        }

        // Create thesis copies with the thesis_id
        for ($i = 0; $i < $copiesCount; $i++) {
            ThesisCopy::create([
                'thesis_id' => $thesis->id,  
                'is_available' => true
            ]);
        }
    });

    return redirect()->route('theses.index')->with('success', 'Thesis added successfully.');
}

    public function show(Thesis $thesis)
    {
        $thesis->load('authors');
        return view('theses.show', compact('thesis'));
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

        DB::transaction(function () use ($validated, $thesis, $request) {
            // Update thesis
            $thesis->update(collect($validated)->only(['title','abstract','year_published','department'])->toArray());

            // Sync authors
            if (!empty($validated['author_ids'])) {
                $thesis->syncAuthors($validated['author_ids']);
            }

            // Update copies
            if ($request->filled('copies_count')) {
                $desired = (int)$validated['copies_count'];
                $existing = $thesis->copies()->count();

                if ($desired > $existing) {
                    for ($i = 0; $i < ($desired - $existing); $i++) {
                        ThesisCopy::create(['thesis_id' => $thesis->id, 'is_available' => true]);
                    }
                } elseif ($desired < $existing) {
                    ThesisCopy::where('thesis_id', $thesis->id)
                              ->where('is_available', true)
                              ->limit($existing - $desired)
                              ->delete();
                }
            }
        });

        return redirect()->route('theses.index')->with('success', 'Thesis updated successfully.');
    }

    public function destroy(Thesis $thesis)
    {
        DB::transaction(function () use ($thesis) {
            $thesis->copies()->delete();
            $thesis->delete();
        });

        return redirect()->route('theses.index')->with('success', 'Thesis deleted successfully.');
    }

    public function availableCopies($thesisId)
    {
        return ThesisCopy::where('thesis_id', $thesisId)
            ->where('is_available', true)
            ->count();
    }

    public function request(Request $request, Thesis $thesis)
    {
        $user = auth()->user();
        if (!$user) {
            return view('auth.login');
        }

        $availableCopy = $thesis->getNextAvailableCopy();
        if (!$availableCopy) {
            return redirect()->back()->with('error', 'No available copies found for this thesis.');
        }

        DB::transaction(function () use ($user, $availableCopy) {
            Transaction::create([
                'user_id' => $user->id,
                'copy_id' => $availableCopy->id,
                'copy_type' => get_class($availableCopy),
                'borrow_date' => now(),
                'due_date' => now()->addDays(7),
                'return_date' => null,
                'transaction_status' => 'requested',
            ]);

            $availableCopy->is_available = false;
            $availableCopy->save();
        });

        return redirect()->route('theses.index')->with('success', 'Thesis request submitted successfully. Please wait for approval.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Thesis;
use App\Models\ThesisDept;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ThesisController extends Controller
{
    public function index(Request $request)
    {
        $query = Thesis::with(['department', 'authors']);

        if ($request->has('search')) {
            $query->search($request->search);
        }

        if ($request->has('dept_id')) {
            $query->byDepartment($request->dept_id);
        }

        if ($request->has('year')) {
            $query->byYear($request->year);
        }

        $theses = $query->latest()->paginate(20);

        $departments = ThesisDept::all();
        $years = Thesis::select('year_published')->distinct()->orderBy('year_published', 'desc')->pluck('year_published');

        return view('theses.index', compact('theses', 'departments', 'years'));
    }
     public function create()
    {
        $departments = ThesisDept::all();
        $authors = Author::orderByName()->get();
        
        return view('theses.create', compact('departments', 'authors'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'SKU' => 'required|unique:theses',
            'dept_id' => 'required|exists:thesis_dept,id',
            'title' => 'required|string|max:500',
            'abstract' => 'required|string',
            'year_published' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'advisor' => 'nullable|string|max:255',
            'pages' => 'nullable|integer|min:1',
            'author_ids' => 'required|array|min:1',
            'author_ids.*' => 'exists:authors,id',
        ]);

        // Create the thesis
        $thesis = Thesis::create($validated);
        
        // Sync authors
        $thesis->syncAuthors($request->author_ids);

        return redirect()->route('theses.show', $thesis)
                        ->with('success', 'Thesis added successfully');
    }
    public function show(Thesis $thesis)
    {
        $thesis->load(['department', 'authors']);
        
        return view('theses.show', compact('thesis'));
    }

    public function edit(Thesis $thesis)
    {
        $departments = ThesisDept::all();
        $authors = Author::orderByName()->get();
        $thesis->load('authors');

        return view('theses.edit', compact('thesis', 'departments', 'authors'));
    }
    public function update(Request $request, Thesis $thesis)
    {
        $validated = $request->validate([
            'dept_id' => 'required|exists:thesis_dept,id',
            'title' => 'required|string|max:500',
            'abstract' => 'required|string',
            'year_published' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'advisor' => 'nullable|string|max:255',
            'pages' => 'nullable|integer|min:1',
            'author_ids' => 'required|array|min:1',
            'author_ids.*' => 'exists:authors,id',
        ]);

        $thesis->update($validated);
        $thesis->syncAuthors($request->author_ids);

        return redirect()->route('theses.show', $thesis)
                        ->with('success', 'Thesis updated successfully');
    }
    public function destroy(Thesis $thesis)
    {
        $thesis->delete();

        return redirect()->route('theses.index')
                        ->with('success', 'Thesis deleted successfully');
    }

}

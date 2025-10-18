<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Thesis;
use App\Models\ThesisDept;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ThesisController extends Controller
{
    public function show(Thesis $thesis)
{
    // Load the thesis with its authors
    $thesis->load('authors');
    return view('theses.show', compact('thesis'));
}
    public function index()
    {
        // Load theses with their related authors
        $theses = Thesis::with('authors')->get();
        return view('theses.index', compact('theses'));
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
        ]);

        // extract authors and remove before creating the thesis
        $authorIds = $validated['author_ids'] ?? [];
        unset($validated['author_ids']);

        // Create the thesis record
        $thesis = Thesis::create($validated);

        // Link thesis ↔ authors (use sync to ensure exact set)
        $thesis->authors()->sync($authorIds);

        return redirect()->route('theses.index')->with('success', 'Thesis added successfully.');
    }

    public function edit(Thesis $thesis)
    {
        $departments = ['AB Psychology', 'BS Psychology'];
        return view('theses.update', compact('thesis', 'departments'));
    }

    public function update(Request $request, Thesis $thesis)
    {
        $request->validate([
            'title' => 'required',
            'abstract' => 'required',
            'year_published' => 'required|integer',
            'department' => 'required',
        ]);

        $thesis->update([
            'title' => $request->title,
            'abstract' => $request->abstract,
            'year_published' => $request->year_published,
            'department' => $request->department,
        ]);

        return redirect()->route('theses.index')->with('success', 'Thesis updated successfully.');
    }

    public function destroy(Thesis $thesis)
    {
        $thesis->delete();
        return redirect()->route('theses.index')->with('success', 'Thesis deleted successfully.');
    }
}

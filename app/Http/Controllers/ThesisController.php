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
        // Dropdown options for department field (from ENUM)
        $departments = ['AB Psychology', 'BS Psychology'];
        return view('theses.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'abstract' => 'required',
            'year_published' => 'required|integer',
            'department' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        // 1️⃣ Create/find author
        $author = Author::firstOrCreate([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
        ]);

        // 2️⃣ Create thesis
        $thesis = Thesis::create([
            'title' => $request->title,
            'abstract' => $request->abstract,
            'year_published' => $request->year_published,
            'department' => $request->department,
        ]);

        // 3️⃣ Link thesis ↔ author
        $thesis->authors()->attach($author->id);

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

<?php
namespace App\Http\Controllers;

use App\Models\Thesis;
use App\Models\Author;
use Illuminate\Http\Request;

class ThesisAuthorController extends Controller
{
    public function index()
    {
        $theses = Thesis::with('authors')->get();
        return view('thesis_authors.index', compact('theses'));
    }

    public function create()
    {
        $theses = Thesis::all();
        $authors = Author::all();
        return view('thesis_authors.create', compact('theses', 'authors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'thesis_id' => 'required|exists:theses,id',
            'author_ids' => 'required|array',
            'author_ids.*' => 'exists:authors,id'
        ]);

        $thesis = Thesis::findOrFail($request->thesis_id);
        $thesis->authors()->syncWithoutDetaching($request->author_ids);

        return redirect()->route('thesis_authors.index')->with('success', 'Authors assigned to thesis successfully.');
    }

    public function destroy($thesis_id, $author_id)
    {
        $thesis = Thesis::findOrFail($thesis_id);
        $thesis->authors()->detach($author_id);

        return redirect()->route('thesis_authors.index')->with('success', 'Author removed from thesis.');
    }
}

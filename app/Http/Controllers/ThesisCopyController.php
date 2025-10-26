<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\ThesisCopy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ThesisCopyController extends Controller
{
    //
    public function create(){
        $authors = Author::all();
        return view('thesis_copies.create', compact('authors'));
    }
    public function store(Request $request){
    $request->validate([
        'thesis_id' => 'required|integer|exists:theses,id',
        'is_available' => 'required|boolean',
    ]);

    ThesisCopy::create([
        'thesis_id' => $request->thesis_id,
        'is_available' => $request->is_available
    ]);

    return redirect()->route('thesis_copies.index')
                     ->with('success', 'Thesis copy created successfully.');
}
    public function availableCopies($thesisId){
        $availableCopiesCount = ThesisCopy::where('thesis_id', $thesisId)
                                        ->where('is_available', true)
                                        ->count();
        return $availableCopiesCount;
    }
}

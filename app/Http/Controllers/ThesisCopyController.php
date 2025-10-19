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
            'thesis_id' => 'required|integer',
            'status' => 'required|string|max:255',
        ]);

        // Assuming ThesisCopy is a model representing a copy of a thesis
        ThesisCopy::create($request->all());

        return redirect()->route('thesis_copies.index')
                         ->with('success', 'Thesis copy created successfully.');
    }
    public function availableCopies($thesisId){
        $availableCopiesCount = ThesisCopy::where('thesis_id', $thesisId)
                                        ->where('isAvailable', true)
                                        ->count();
        return $availableCopiesCount;
    }
}

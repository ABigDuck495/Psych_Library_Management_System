<?php

namespace App\Http\Controllers;

use App\Models\Penalty;
use Illuminate\Http\Request;

class PenaltyController extends Controller
{
    public function index(){
        return view('penalties.index');
    }
    public function show($id){
        return view('penalties.show', compact('id'));
    }
    public function create(){
        return view('penalties.create');
    }
    public function store(Request $request){
        $validated = $request->validate([
            'transaction_id' => 'required|integer|exists:transactions,id',
            'user_id' => 'required|integer|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'reason' => 'nullable|string',
        ]);

        Penalty::create([
            'transaction_id' => $validated['transaction_id'],
            'user_id' => $validated['user_id'],
            'amount' => $validated['amount'],
            'reason' => $validated['reason'] ?? null,
        ]);
    }
    
}

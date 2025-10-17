<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
 
    public function create(){
        return view('transactions.create');
    }
    public function store(Request $request){
        // Validate and store transaction logic here

        return redirect()->route('transactions.index')->with('success', 'Transaction created successfully!');
    }
    public function index(){
        return view('transactions.index');
    }
    public function edit($id){
        return view('transactions.edit', compact('id'));
    }
    public function update(Request $request, $id){
        // Validate and update transaction logic here
        

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully!');
    }

}

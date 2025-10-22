<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminInterfaceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,super-admin'); // ✅ only admin & super-admin
    }

    public function index()
    {
        return view('adminInterface.index');
    }
}

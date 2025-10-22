<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LibrarianInterfaceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:librarian,admin,super-admin'); // ✅ librarian-level access
    }

    public function index()
    {
        return view('librarianInterface.index');
    }
}

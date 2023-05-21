<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class todoViewController extends Controller
{

    // view
    public function index()
    {
        return view("todo");
    }
}

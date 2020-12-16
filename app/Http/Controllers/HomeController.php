<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Index
     *
     * @return void
     */
    public function index()
    {
        return view('welcome');
    }

    public function create()
    {
        return view('create');
    }
}

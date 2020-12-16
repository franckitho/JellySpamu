<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
class HomeController extends Controller
{
    /**
     * Index
     *
     * @return void
     */
    public function index()
    {
        return Inertia::render("app/views/index.vue");
    }

    public function create()
    {
        return view('create');
    }
}

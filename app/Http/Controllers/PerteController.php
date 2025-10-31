<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PerteController extends Controller
{
    /**
     * Display the pertes management page
     */
    public function index(Request $request)
    {
        return view('pertes.pertes');
    }
}
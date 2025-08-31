<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UtilitiesController extends Controller
{
    public function index()
    {
        return view('utilities.index');
    }

    public function electricity()
    {
        return view('utilities.electricity.index');
    }

    public function gas()
    {
        return view('utilities.gas.index');
    }

    public function waste()
    {
        return view('utilities.waste.index');
    }

    public function infrastructure()
    {
        return view('utilities.infrastructure.index');
    }

    public function fleet()
    {
        return view('utilities.fleet.index');
    }
}

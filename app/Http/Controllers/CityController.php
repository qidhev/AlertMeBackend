<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CityController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('City/Index', [
            'cities' => City::all()
        ]);
    }
}

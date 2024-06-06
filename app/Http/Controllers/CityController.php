<?php

namespace App\Http\Controllers;

use App\Http\Requests\CityRequest;
use App\Models\City;
use App\Modules\GeocoderModule;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class CityController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('City/Index', [
            'cities' => City::all()
        ]);
    }

    public function create(): \Inertia\Response
    {
        return Inertia::render('City/Create');
    }

    public function store(CityRequest $request): RedirectResponse
    {
        $cityData = $request->getData();
        $cityName = (new GeocoderModule(['address' => $cityData['name']]))->getCityByData();

        City::create(['name' => $cityName]);

        return to_route('cities.index');
    }
}

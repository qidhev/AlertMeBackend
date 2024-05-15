<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationRequest;
use App\Models\City;
use App\Models\Location;
use App\Models\TypeLocation;
use App\Modules\GeocoderModule;
use Inertia\Inertia;
use Inertia\Response;

class LocationController extends Controller
{
    public function index(City $city): Response
    {
        return Inertia::render('Location/Index', [
            'city' => $city,
            'locations' => Location::where('city_id', $city->id)->get(),
            'types' => TypeLocation::all()
        ]);
    }

    public function create(City $city): Response
    {
        return Inertia::render('Location/Create', [
            'city' => $city,
            'types' => TypeLocation::all()
        ]);
    }

    public function store(LocationRequest $request, City $city)
    {
        $data = (new GeocoderModule([
            'address' => "{$request->input('city')} {$request->input('street')} {$request->input('house')}"
                                    ]))->getCoordinateByAddress();

        $location = Location::create(
            [
                'name' => $request->validated('name'),
                'email' => $request->validated('email'),
                'phone' => $request->validated('phone'),
                'address' => $data['address'] ?? null,
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'type_id' => $request->validated('type_id'),
                'city_id' => $request->validated('city_id'),
            ]
        );

        return to_route('locations', ['city' => $city]);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Location;
use App\Modules\GeocoderModule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StartController extends Controller
{
    public function startGetData(Request $request)
    {
        $cityName = (new GeocoderModule($request->all()))->getCityByData();

        if (empty($cityName)) {
            return response()->json(['message' => 'Города не существует'], 404);
        }

        $city = City::where('name', $cityName)->first();

        if (empty($city)) {
            return response()->json(
                [
                    'locations' => [],
                    'city' => $cityName,
                    'slug' => Str::slug($cityName),
                ]);
        }

        $locations = Location::where('city_id', $city->id)->get()->map(function ($location) {
            $location->type;
            return $location;
        });

        return response()->json(
            [
                'locations' => $locations,
                'city' => $cityName,
                'slug' => Str::slug($cityName),
            ]
        );
    }
}

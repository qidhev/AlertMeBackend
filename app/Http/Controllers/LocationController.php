<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationRequest;
use App\Models\City;
use App\Models\Location;
use App\Models\TypeLocation;
use App\Models\TypeNotification;
use App\Modules\GeocoderModule;
use App\Modules\MqttModule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class LocationController extends Controller
{
    public function index(City $city): Response
    {
        return Inertia::render('Location/Index', [
            'city' => $city,
            'locations' => Location::where('city_id', $city->id)->orderBy('updated_at', 'DESC')->get(),
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
        $coords = (new GeocoderModule(
            [
                'address' => "{$city->name} {$request->input('street')} {$request->input('house')}"
            ]
        ))->getCoordinateByAddress();


        DB::beginTransaction();

        $data = $request->getData();

        if (empty($data['type_name']) && empty($data['type_id'])) {
            throw new \Exception("Не заполнена информация о тип уведомления");
        }

        if (!empty($data['type_name'])) {
            $type = TypeLocation::create(['name' => $data['type_name']]);
            $data['type_id'] = $type->id;
            unset($data['type_name']);
        }

        try {
            $locationData = array_merge($data, $coords ?? []);

            $location = Location::create($locationData);

            $locations = Location::where('city_id', $request->validated('city_id'))->get();
            $locations->push($location);

            $locations = $locations->map(function ($location) {
                $location->type;
                return $location;
            });

            $this->sendLocationsToMqtt($locations, $request->validated('city_id'));

            DB::commit();

            return to_route('locations.index', ['city' => $city]);
        } catch (\Throwable $throwable) {
            DB::rollBack();

            throw $throwable;
        }
    }

    public function edit(City $city, Location $location): Response
    {
        return Inertia::render('Location/Edit', [
            'city' => $city,
            'location' => $location,
            'types' => TypeLocation::all()
        ]);
    }

    public function update(LocationRequest $request, City $city, Location $location)
    {
        $data = (new GeocoderModule(
            [
                'address' => "{$city->name} {$request->input('street')} {$request->input('house')}"
            ]
        ))->getCoordinateByAddress();

        DB::beginTransaction();

        try {
            $locationData = array_merge($request->getData(), $data ?? []);

            $location->update($locationData);

            $locations = Location::where('city_id', $city->id)->get();
            $locations = $locations->map(function(Location $loc) use ($location) {
                if ($loc->id == $location->id) {
                    $location->type;
                    return $location;
                }

                $loc->type;
                return $loc;
            });

            $this->sendLocationsToMqtt($locations, $request->validated('city_id'));

            DB::commit();

            return to_route('locations.index', ['city' => $city]);
        } catch (\Throwable $throwable) {
            DB::rollBack();

            throw $throwable;
        }
    }

    public function destroy(City $city, Location $location)
    {
        DB::beginTransaction();

        try {
            $location->delete();

            $locations = Location::where('city_id', $city->id)->whereNot(function ($query) use ($location) {
                $query->where('id', $location->id);
            })->get();

            $this->sendLocationsToMqtt($locations, $city->id);

            DB::commit();
            return to_route('locations.index', ['city' => $city]);
        } catch (\Throwable $throwable) {

            DB::rollBack();

            throw $throwable;
        }
    }

    private function sendLocationsToMqtt($locations, $cityId)
    {
        $mqttModule = (new MqttModule(env('MQTT_HOST'), env('MQTT_PORT')))->connect();

        $mqttModule->sendMessage(
            TypeNotification::where('slug', 'locality')->first()->topic . "/" . Str::slug(
                City::find($cityId)->name
            ),
            json_encode([
                            'notification' => $locations,
                            'type'         => 'locality'
                        ])
        );
    }
}

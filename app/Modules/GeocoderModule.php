<?php

namespace App\Modules;

use GuzzleHttp\Client;

class GeocoderModule
{
    private ?float $latitude;
    private ?float $longitude;
    private ?string $address;
    public function __construct(array $data)
    {
        $this->latitude = $data['latitude'] ?? null;
        $this->longitude = $data['longitude'] ?? null;
        $this->address = $data['address'] ?? null;
    }

    public function getCoordinateByAddress(): ?array
    {
        if (empty($this->address))
            throw new \Exception("Нет данных для запроса адреса");

        $client = new Client();
        $response = $client->get('https://geocode-maps.yandex.ru/1.x/', [
            'query' => [
                'apikey' => env('YANDEX_MAP_API'),
                'geocode' => $this->address,
                'format' => 'json',
            ],
        ]);
        $data = json_decode($response->getBody(), true);

        if (!empty($data['response']['GeoObjectCollection']['featureMember'])) {
            $geoObject = $data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject'];
            $kind = $geoObject["metaDataProperty"]["GeocoderMetaData"]["kind"];

            if ($kind !== "house") {
                return null;
            }

            $point = $geoObject['Point']['pos'];

            [$longitude, $latitude] = explode(' ', $point);

            return [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'address' => $geoObject['name']
            ];
        } else {
            return null;
        }
    }
}

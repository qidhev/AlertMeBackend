<?php

namespace App\Http\Requests;

use App\Models\City;
use App\Modules\GeocoderModule;
use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'between:2,100', function ($attribute, $value, $fail) {
                $cityName = (new GeocoderModule(['address' => $value]))->getCityByData();

                if (!$cityName) {
                    $fail('Такого города не существует');
                }

                $city = City::where('name', $cityName)->first();

                if ($city) {
                    $fail('Такой город уже существует');
                }
            }],
        ];
    }

    public function getData(): array
    {
        return [
            'name' => $this->validated('name'),
        ];
    }
}

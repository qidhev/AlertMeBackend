<?php

namespace App\Http\Requests;

use App\Modules\GeocoderModule;
use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'required|string',
            'city' => 'required|string',
            'street' => ['nullable', function ($attribute, $value, $fail) {
                if (empty($this->input('street')) && empty($this->input('house'))) {
                    return;
                }

                if (empty($this->input('street')) xor empty($this->input('house'))) {
                    $fail("Для адреса необходимо заполнить все поля");
                    return;
                }

                $data = (new GeocoderModule(['address' => "{$this->input('city')} {$this->input('street')} {$this->input('house')}"]))->getCoordinateByAddress();
                if (is_null($data)) {
                    $fail("Данный адрес не существуют");
                }
            }],
            'house' => ['nullable', function ($attribute, $value, $fail) {
                if (empty($this->input('street')) && empty($this->input('house'))) {
                    return;
                }

                if (empty($this->input('street')) xor empty($this->input('house'))) {
                    $fail("Для адреса необходимо заполнить все поля");
                    return;
                }

                $data = (new GeocoderModule(['address' => "{$this->input('city')} {$this->input('street')} {$this->input('house')}"]))->getCoordinateByAddress();
                if (is_null($data)) {
                    $fail("Данный адрес не существуют");
                }
            }],
            'type_id' => 'required|int',
            'city_id' => 'required|int',
        ];
    }
}

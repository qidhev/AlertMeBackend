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
            'type_id' => 'nullable|int',
            'type_name' => 'nullable|string|unique:locations_type,name',
            'city_id' => 'required|int',
        ];
    }

    public function getData(): array
    {
        return [
            'name' => $this->validated('name'),
            'email' => $this->validated('email'),
            'phone' => $this->validated('phone'),
            'street' => $this->validated('street'),
            'house' => $this->validated('house'),
            'type_id' => $this->validated('type_id'),
            'type_name' => $this->validated('type_name'),
            'city_id' => $this->validated('city_id'),
        ];
    }
}

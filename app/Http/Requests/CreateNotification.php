<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateNotification extends FormRequest
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
            'message' => 'required|max:120',
            'subtitle' => 'required|max:120',
            'title' => 'required|max:120',
            'main_text' => 'required',
            'date_start_at' => 'required|date',
            'date_end_at' => 'required|date',
            'type_id' => 'nullable|int',
            'type_name' => 'nullable|string',
            'city_id' => 'nullable|int',
            'parent_id' => 'nullable|int',
            'type_location_id' => 'nullable|int',
        ];
    }

    public function getData(): array
    {
        return [
            'message' => $this->validated('message'),
            'subtitle' => $this->validated('subtitle'),
            'title' => $this->validated('title'),
            'main_text' => $this->validated('main_text'),
            'date_start_at' => $this->validated('date_start_at'),
            'date_end_at' => $this->validated('date_end_at'),
            'type_id' => $this->validated('type_id'),
            'type_name' => $this->validated('type_name'),
            'city_id' => $this->validated('city_id'),
            'parent_id' => $this->validated('parent_id'),
            'type_location_id' => $this->validated('type_location_id'),
        ];
    }
}

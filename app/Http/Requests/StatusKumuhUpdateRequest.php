<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StatusKumuhUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nama' => ['required', 'max:255', 'string'],
            'warna' => ['required', 'max:255', 'string'],
            'icon' => ['required', 'max:255', 'string'],
        ];
    }
}

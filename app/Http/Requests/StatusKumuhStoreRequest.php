<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StatusKumuhStoreRequest extends FormRequest
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
            'tahun' => ['required'],
            'nama' => ['required', 'max:255', 'string'],
            'warna' => ['required', 'max:255', 'string'],
            'icon' => ['required', 'max:255', 'string'],
            'nilai_min' => ['required', 'max:11'],
            'nilai_max' => ['required', 'max:11'],
        ];
    }
}

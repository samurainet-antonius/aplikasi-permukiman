<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EvaluasiUpdateRequest extends FormRequest
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
            'province_code' => ['required'],
            'city_code' => ['required'],
            'district_code' => ['required'],
            'village_code' => ['required'],
            'tahun' => ['required'],
            'lingkungan' => ['required'],
            'luas_kawasan' => ['required'],
            'luas_kumuh' => ['required'],
            'jumlah_bangunan' => ['required'],
            'jumlah_penduduk' => ['required'],
            'jumlah_kepala_keluarga' => ['required'],
            'latitude' => ['required'],
            'longitude' => ['required'],
        ];
    }
}

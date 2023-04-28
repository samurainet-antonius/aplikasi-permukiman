<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiteSettingRequest extends FormRequest
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
            'site_name' => ['required', 'max:200'],
            'site_description' => ['required', 'max:160'],
            'site_logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'site_email' => ['required', 'email'],
            'site_phone' => ['required', 'string', 'max:15'],
            'site_address' => ['required', 'string'],
            'site_fax_email' => ['required', 'string'],
        ];
    }
}

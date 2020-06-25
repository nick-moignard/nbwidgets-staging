<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NationRequest extends FormRequest
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
            'name' => 'required|max:250',
            'slug' => 'required|max:100',
            'access_token' => 'required|max:100',
            'logo' => 'image|max:2048',
            'people_count' => 'max:20'
        ];
    }
}

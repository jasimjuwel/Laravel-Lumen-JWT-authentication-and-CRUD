<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'title' => 'required|string|between:2,100',
            'description' => 'required',
            'price' => 'required|numeric',
            'image' => 'required|string|confirmed|min:6',
        ];
    }
}

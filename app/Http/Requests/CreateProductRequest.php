<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
            'name' => 'required|unique:products|string|bail',
            'slug' => 'unique:products|string|bail',
            'sku' => 'required|alpha_num|bail',
            'price' => 'required|integer|bail',
            'description' => 'required|string|bail',
            'quantity' => 'required|integer|bail',
            'status' => 'boolean|required'
        ];
    }
}

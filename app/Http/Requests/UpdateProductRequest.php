<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;


class UpdateProductRequest extends FormRequest
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
            'name' => [
                'required', 'string', 'bail',
                Rule::unique('products')->ignore($this->product->id),
            ],
            'slug' => [
                'required', 'sometimes',
                Rule::unique('products')->ignore($this->product->id),
            ],
            'sku' => 'alpha_num|bail',
            'price' => 'integer|bail',
            'description' => 'string|bail',
            'quantity' => 'integer|bail',
            'status' => 'boolean'
        ];
    }
}

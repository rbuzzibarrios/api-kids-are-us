<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string'],
            'sku' => [
                'sometimes',
                'digits_between:3,50',
                Rule::unique('products', 'sku')
                    ->ignore($this->request->get('id')),
            ],
            'price' => ['sometimes', 'decimal:0,2'],
            'quantity' => ['sometimes', 'numeric', 'min:1', 'max:99999999'],
            'category' => ['sometimes', 'integer', 'exists:product_categories,id'],
            'tags' => ['sometimes', 'array'],
            'tags.*' => ['sometimes', 'string'],
            'description' => ['sometimes', 'string'],
            'additional_information' => ['sometimes', 'string'],
            'rate' => ['bail', 'sometimes', 'int', 'max:5'],
            'images' => ['sometimes', 'array', 'max:4'],
            'images.*' => ['sometimes', 'url'],
        ];
    }
}

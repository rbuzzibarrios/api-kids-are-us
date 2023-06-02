<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'sku' => ['required', 'unique:products', 'digits_between:3,50'],
            'price' => ['required', 'decimal:0,2'],
            'quantity' => ['required', 'numeric', 'min:1', 'max:99999999'],
            'category' => ['required', 'integer', 'exists:product_categories,id'],
            'tags' => ['required', 'array'],
            'tags.*' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'additional_information' => ['nullable', 'string'],
            'rate' => ['bail', 'nullable', 'int', 'max:5'],
            'images' => ['nullable', 'array', 'max:4'],
            'images.*' => ['sometimes', 'url'],
        ];
    }
}

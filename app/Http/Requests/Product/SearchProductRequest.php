<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class SearchProductRequest extends FormRequest
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
            'name' => ['nullable', 'string', 'max:250'],
            'sku' => ['nullable', 'string', 'max:250'],
            'quantity' => ['nullable', 'integer'],
            'category' => ['nullable', 'int', 'max:250'],
            'description' => ['nullable', 'string', 'max:250'],
            'additional_information' => ['nullable', 'string', 'max:250'],
            'rate' => ['nullable', 'int', 'max:5'],
            'query' => ['nullable'],
            'comparison' => ['sometimes'], // strict, contains
        ];
    }
}

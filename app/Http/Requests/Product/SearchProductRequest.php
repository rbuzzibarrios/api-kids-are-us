<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;

class SearchProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function after(): array
    {
        return [
            fn (Validator $validator) => $this->filledAtLeastOneAttributeIfComparisonIsPresent(),
        ];
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
            'price' => ['nullable'],
            'query' => ['sometimes', 'required'],
            'page' => ['sometimes', 'required', 'integer'],
            'skipCache' => ['sometimes', 'required', 'boolean'],
            'comparison' => ['bail', 'nullable', 'in:strict,contains,'], // strict, contains
        ];
    }

    protected function filledAtLeastOneAttributeIfComparisonIsPresent(): bool
    {
        $this->getValidatorInstance()->errors()->addIf(
            $validInput = $this->has('comparison') && empty(Arr::except($this->validated(),
                ['query', 'comparison'])),
            'comparison', __('product.validations.comparison_required', ['attribute' => 'comparison'])
        );

        return ! $validInput;
    }
}

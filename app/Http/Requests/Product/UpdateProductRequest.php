<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateProductRequest extends FormRequest
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
            fn (Validator $validator) => $this->filledAtLeastOneAttribute(),
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string'],
            'price' => ['sometimes', 'required', 'decimal:0,2'],
            'quantity' => ['sometimes', 'required', 'numeric', 'min:1', 'max:99999999'],
            'category' => ['sometimes', 'required', 'integer', 'exists:product_categories,id'],
            'tags' => ['sometimes', 'array'],
            'tags.*' => ['sometimes', 'required', 'string'],
            'description' => ['sometimes', 'string'],
            'additional_information' => ['sometimes', 'string'],
            'rate' => ['sometimes', 'int', 'max:5'],
            'images' => ['sometimes', 'array', 'max:4'],
            'images.*' => ['sometimes', 'url'],
        ];
    }

    protected function filledAtLeastOneAttribute(): bool
    {
        $input = $this->all();
        $inputKeys = array_keys($input);

        $this->getValidatorInstance()->errors()->addIf(
            $validInput = empty($input) ||
                count(array_intersect(array_keys($this->rules()), $inputKeys)) != count($inputKeys),
            'input', __('product.validations.valid_input_update')
        );

        return ! $validInput;
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

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
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
             'product_name' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],

            'description' => ['required', 'string'],

            'highlights' => ['nullable', 'array'],
            'highlights.*' => ['string', 'max:255'],

            'inclusions' => ['nullable', 'array'],
            'inclusions.*' => ['string', 'max:255'],

            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:100'],

            'price' => ['required', 'numeric', 'min:0'],
            'inventory_count' => ['required', 'integer', 'min:0'],

            'valid_from' => ['required', 'date'],
            'valid_until' => ['required', 'date', 'after_or_equal:valid_from'],

            'status' => ['required', 'in:active,inactive'],
        ];
    }
}

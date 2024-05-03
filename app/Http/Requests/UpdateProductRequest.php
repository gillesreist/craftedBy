<?php

namespace App\Http\Requests;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:65535',
            'categories' => 'required|array',
            'categories.*' => 'required|exists:categories,name|distinct',
            'materials' => 'required|array',
            'materials.*' => 'required|exists:materials,name|distinct',
            'customization' => 'nullable|string|exists:customizations,name'
        ];
    }
}

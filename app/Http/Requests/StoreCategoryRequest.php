<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Check user is authenticated
        $user = auth()->user();

        // Return empty data if he is not
        if (!$user) {
            return [];
        }

        return [
            'name' => 'required|string',
        ];
    }
}

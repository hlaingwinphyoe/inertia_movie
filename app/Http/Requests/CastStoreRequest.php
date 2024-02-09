<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CastStoreRequest extends FormRequest
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
            "name" => "required|string|min:3",
            "gender" => "required|string|in:Male,Female,Other",
            "biography" => "nullable|string|min:20",
            "birthday" => "required|date|before:today",
            "profile" => 'nullable|image|mimes:png,jpg,jpeg|max:102400'
        ];
    }
}

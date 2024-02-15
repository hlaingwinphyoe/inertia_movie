<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovieStoreRequest extends FormRequest
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
            "title" => "required|string|min:3",
            "genres" => "required",
            "genres.*" => "numeric|exists:genres,id",
            "country_id" => "required|numeric|exists:countries,id",
            "description" => "required|string|min:10",
            "director" => "required|string",
            "release_date" => "required|date",
            "running_time" => "required|string",
            "video_quality" => "required|string",
            "rating" => "required|numeric|min:0|max:10",
            "cover" => "nullable|image|mimes:png,jpg",
            "trailer_video" => "nullable|string"
        ];
    }
}

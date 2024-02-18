<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsertTracksToPlaylistRequest extends FormRequest
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
            'tracks' =>'array',
            'tracks.*' => 'uuid'
        ];
    }

    public function messages(): array
    {
        return [
          'tracks.array' => 'Must be an array of tracks',
            'tracks.*.uuid' => 'You must provide an UUID'
        ];
    }
}

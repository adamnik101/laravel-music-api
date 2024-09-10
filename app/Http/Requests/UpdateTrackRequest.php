<?php

namespace App\Http\Requests;

use App\Traits\ResponseAPI;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class UpdateTrackRequest extends FormRequest
{
    use ResponseAPI;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::hasUser();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'bail|required|string',
            'owner' => 'bail|required|uuid',
            'cover' => 'nullable',
            'track' => 'nullable|mimes:mp3,wav',
            'genre' => 'bail|required|uuid',
            'album' => 'bail|nullable|exists:albums,id',
            'explicit' => 'nullable',
            'features' => ['bail', 'nullable', 'array' => 'exists:artists,id']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->error("Validation error", 422, $validator->errors()->toArray())
        );
    }
}

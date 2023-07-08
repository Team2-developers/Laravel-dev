<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'img_id' => 'nullable|exists:img,img_id',
            'user_mail' => 'required|email|max:100',
            'user_name' => 'required|max:50',
            'password' => 'required|min:6',
            'life_id' => 'nullable|integer',
            'birth' => 'nullable|date',
            'blood_type' => 'nullable|max:10',
            'height' => 'nullable|integer',
            'hobby' => 'nullable|max:100',
            'episode1' => 'nullable|max:100',
            'episode2' => 'nullable|max:100',
            'episode3' => 'nullable|max:100',
            'episode4' => 'nullable|max:100',
            'episode5' => 'nullable|max:100',
            'abilities' => 'nullable',
        ];
    }
}

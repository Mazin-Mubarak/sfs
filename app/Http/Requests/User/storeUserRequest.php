<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class storeUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3',
            'user_name' => 'required|unique:users,user_name|min:3',
            'password' => 'required|confirmed|min:8',
            'birth_date' => 'required|date'
        ];
    }
}

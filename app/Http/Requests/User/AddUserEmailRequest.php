<?php

namespace App\Http\Requests\User;

use App\Models\Email;
use Illuminate\Foundation\Http\FormRequest;

class AddUserEmailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // every user can add new email to him self
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $validType = \implode(",",Email::getTypes());
        return [
            'email' => 'required|email',
            'type' => "in:$validType",
            'note' => '',
        ];
    }
}

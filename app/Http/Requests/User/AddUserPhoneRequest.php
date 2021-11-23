<?php

namespace App\Http\Requests\User;

use App\Models\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class AddUserPhoneRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //user always is allowed to add new phone number
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $validType = \implode(",",PhoneNumber::getTypes());
        return [
            'number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'type' => "in:$validType",
            'note' => '',
        ];
    }
}

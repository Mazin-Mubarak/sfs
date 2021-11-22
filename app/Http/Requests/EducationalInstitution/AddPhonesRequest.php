<?php

namespace App\Http\Requests\EducationalInstitution;

use App\Models\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class AddPhonesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /** the user can add phone number if
         * # he created the institution
         * # he is an supervisor in the institution
        */
        //TODO : set the authorization rules
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
            'number' => 'required',
            'type' => "in:$validType",
            'note' => '',
        ];
    }
}

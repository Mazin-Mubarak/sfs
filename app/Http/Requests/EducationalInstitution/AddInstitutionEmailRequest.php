<?php

namespace App\Http\Requests\EducationalInstitution;

use App\Models\EducationalInstitution;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class AddInstitutionEmailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /** the user can add email if
         * 
         * # he is an supervisor in the institution
        */
        //TODO : set the authorization rules
        $user = auth()->user();
        $institutionId =  Route::getCurrentRoute()->parameter('id');

        $institution = EducationalInstitution::find($institutionId);
        if(!$institution){
            //if institution does not exist allow it to be handled by the controller
            return true;
        }

        //check if the user created the institution
        //if so allow him to add the email

        if($institution->created_by == $user->id){
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'note' => '',
        ];
    }
}

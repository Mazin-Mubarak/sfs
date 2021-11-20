<?php

namespace App\Http\Requests\EducationalInstitution;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class CreateInstitutionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //every user his age grater than 18 years can create an educational institution
        $user = auth()->user();
        $nowDate = Carbon::now();
        $userBirthDate = $user->birth_date;

        $userAge = $nowDate->diffInYears($userBirthDate);
        if($userAge >= 18){
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
            'name' => 'required|min:3',
            'address' => '',
            'back_image' => 'max:2048',
            'about' => ''
        ];
    }
}

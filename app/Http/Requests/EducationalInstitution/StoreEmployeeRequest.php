<?php

namespace App\Http\Requests\EducationalInstitution;

use App\Models\EducationalInstitution;
use App\Models\InstitutionEmployee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // user can add an employee to institution if
        // - He is the owner of the institution (Created by him)
        // - He is an employee in the institution with administrator role
        $user = auth()->user();
        $institutionId = Route::getCurrentRoute()->parameter('id');
        $institution = EducationalInstitution::find($institutionId);
        if (!$institution) {
            // if the institution is'nt found allow it to be handled from the controller
            return true;
        }

        if($institution->created_by == $user->id){
            // the current user is the creator of the institution
            return true;
        }

        $employeePivot = InstitutionEmployee::where('user_id', $user->id)
            ->where('institution_id', $institution->id)->first();

        if (!$employeePivot) {
            // this user is not an employee in this institution
            return false;
        }

        if($employeePivot->role == InstitutionEmployee::ROLE_ADMIN and $employeePivot->status == InstitutionEmployee::STATUS_APPROVED){
            // this user is an administrator in this institution with approved status
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
        $validRoles = \implode(",", InstitutionEmployee::getValidRoles());
        return [
            'user_name' => 'required',
            'role' => 'in:'.$validRoles
        ];
    }
}

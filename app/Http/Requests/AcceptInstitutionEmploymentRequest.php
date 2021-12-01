<?php

namespace App\Http\Requests;

use App\Models\InstitutionEmployee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class AcceptInstitutionEmploymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $currentUser = auth()->user();
        $requestId = intval(Route::current()->parameter('id'));
        $request = InstitutionEmployee::find($requestId);

        //if request not found allow it to be processed in the controller
        if(!$request) return true;

        return $currentUser->id == $request->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}

<?php

namespace App\Http\Controllers\EducationalInstitution;

use App\Exceptions\PhoneNumber\PhoneDuplicationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\EducationalInstitution\AddInstitutionPhoneRequest;
use App\Models\EducationalInstitution;
use App\Services\PhoneNumbers\PhoneNumbersService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PhonesController extends Controller
{
    /**
     * Add phone numbers for the institution
     */
    public function store(AddInstitutionPhoneRequest $request, int $id)
    {
        //get the institution
        $institution = EducationalInstitution::find($id);

        if(!$institution){
            $messages = [__("institutions.not_found")];
            return $this->sendErrorResponse($messages, null, Response::HTTP_NOT_FOUND);
        }

        $number = $request->input('number');
        $note = $request->input('note');


        try {
            $verificationData = PhoneNumbersService::addInstitutionPhoneNumber($number, $institution->id, $note);
        }catch (PhoneDuplicationException $e) {
            $messages = [$e->getMessage()];
            return $this->sendErrorResponse($messages, null, Response::HTTP_NOT_ACCEPTABLE);
        }catch (\Throwable $th) {
            $messages = [$th->getMessage()];
            return $this->sendErrorResponse($messages, null, Response::HTTP_NOT_ACCEPTABLE);
        }

        return $this->sendSuccessResponse([__("institutions.add_phone_success")] ,$verificationData, Response::HTTP_CREATED);
    }
}

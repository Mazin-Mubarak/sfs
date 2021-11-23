<?php

namespace App\Http\Controllers\EducationalInstitution;

use App\Exceptions\PhoneNumber\PhoneDuplicationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\EducationalInstitution\AddInstitutionPhoneRequest;
use App\Http\Requests\EducationalInstitution\CreateInstitutionRequest;
use App\Models\EducationalInstitution;
use App\Services\Institutions\InstitutionService;
use App\Services\PhoneNumbers\PhoneNumbersService;
use Illuminate\Http\Response;

class EducationalInstitutionsController extends Controller
{
    /**
     * Creates new institution and save it in the database
     */
    public function store(CreateInstitutionRequest $request)
    {
        $name = $request->input('name');
        $address = $request->input('address');
        $about = $request->input('about');

        $user = auth()->user();

        $backImageName = null;

        if($request->hasFile('back_image')){
            //read the file from the request
            $backImage = $request->file('back_image');

            //create new file name
            $backImageName = uniqid("", true).".".$backImage->extension();

            //get the storage directory from configurations
            $directory = config("institutions.images_directory");

            //store the file persistently
            $backImage->storeAs($directory, $backImageName);
        }

        return InstitutionService::create($name, $user->id, $address, $about, $backImageName);
    }

    /**
     * Add phone numbers for the institution
     */
    public function addPhones(AddInstitutionPhoneRequest $request, int $id)
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

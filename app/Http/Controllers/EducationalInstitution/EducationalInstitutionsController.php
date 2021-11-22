<?php

namespace App\Http\Controllers\EducationalInstitution;

use App\Http\Controllers\Controller;
use App\Http\Requests\EducationalInstitution\AddPhonesRequest;
use App\Http\Requests\EducationalInstitution\CreateInstitutionRequest;
use App\Models\EducationalInstitution;
use App\Models\PhoneNumber;
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
    public function addPhones(AddPhonesRequest $request, int $id)
    {
        //get the institution
        $institution = EducationalInstitution::find($id);

        if(!$institution){
            $messages = [__("institutions.not_found")];
            return $this->sendErrorResponse($messages, null, Response::HTTP_NOT_FOUND);
        }

        $number = $request->input('number');
        $note = $request->input('note');


        $verificationData = PhoneNumbersService::addInstitutionPhoneNumber($number, $institution->id, $note);

        return $this->sendSuccessResponse([] ,$verificationData, Response::HTTP_CREATED);
    }
}

<?php

namespace App\Http\Controllers\EducationalInstitution;

use App\Http\Controllers\Controller;
use App\Http\Requests\EducationalInstitution\CreateInstitutionRequest;
use App\Services\Institutions\InstitutionService;

class EducationalInstitutionsController extends Controller
{
    
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
}

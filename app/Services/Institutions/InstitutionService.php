<?php

namespace App\Services\Institutions;

use App\Models\EducationalInstitution;

class InstitutionService
{
    /**
     * creates new educational institution and store it in the database
     * @param string the name of the institution
     * @param int createdBy the user id of the creator
     * @param string address the address of the institution
     * @param string about a text contains information about the institution
     * @param string backImage the institution's profile background image 
     */
    public static function create(string $name,int $createdBy, string $address = null, string $about = null, string $backImage = null)
    {
        $name = mb_strtolower(trim($name));
        
        if($address){
            $address = mb_strtolower(trim($address));
        }

        $institutionData = [
            'name' => $name,
            'created_by' => $createdBy,
            'address' => $address,
            'about' => $about,
            'back_image' => $backImage  
        ];

        $institution = EducationalInstitution::create($institutionData);

        return $institution;
    }
}
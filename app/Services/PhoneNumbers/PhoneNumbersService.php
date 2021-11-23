<?php

namespace App\Services\PhoneNumbers;

use App\Exceptions\PhoneNumber\InvalidPhoneAssociationException;
use App\Exceptions\PhoneNumber\InvalidPhoneTypeException;
use App\Exceptions\PhoneNumber\PhoneDuplicationException;
use App\Models\PhoneNumber;
use Exception;

class PhoneNumbersService
{
    /**
     * generates a verification token from a string
     */
    public static function getVerificationTokenFromOTP(string $otp)
    {
        return hash('sha256', $otp);
    }


    /**
     * Add new phone number to the database
     * @param string number the phone number
     * @param int created by the creator of the phone number
     * @param string type the type of the phone number
     * @param string associatedTo the association relation of the number
     * @param string note notes about the phone number
     * 
     * @throws InvalidPhoneTypeException if the given type is invalid
     * @throws InvalidPhoneAssociationException if the given association is invalid
     * @throws PhoneDuplicationException if the given number is duplicated for the same user
     * 
     */
    public static function addPhoneNumber(string $number, int $createdBy, string $type, string $associatedTo, string $note = null)
    {
        $number = trim($number);


        // check the type 

        $validTypes = PhoneNumber::getTypes();

        if (!in_array($type, $validTypes)) {
            throw new InvalidPhoneTypeException(__('phoneNumbers.invalid_type'));
        }


        // check the association
        $validAssociations = PhoneNumber::getValidAssociations();

        if (!in_array($associatedTo, $validAssociations)) {
            throw new InvalidPhoneAssociationException(__('phoneNumbers.invalid_association'));
        }


        //check phone number duplication for the same association and the same creator
        $phoneNumber = PhoneNumber::where('number', $number)
            ->where('created_by', $createdBy)
            ->where('associated_to', $associatedTo)
            ->first();

        if ($phoneNumber) {

            throw new PhoneDuplicationException(__('phoneNumbers.phone_repeat'));
        }

        // create random one time password for the phone number
        $otp = random_int(100000, 999999);

        $verificationToken = static::getVerificationTokenFromOTP(strval($otp));


        $data = [
            'number' => $number,
            'verification_token' => $verificationToken,
            'verified_at' => null,
            'created_by' => $createdBy,
            'type' => $type,
            'associated_to' => $associatedTo,
            'note' => $note
        ];

        $phone = PhoneNumber::create($data);
        return [
            'secretToPhone' => [
                'otp' => $otp,
                'link' => route('phone.verify.token', ['id' => $phone->id, 'token' => $verificationToken])
            ],
            'phone' => $phone
        ];
    }

    
    /**
     * Add new phone number for the institution and store it in the database
     * @param string number the phone number
     * @param int institutionId the institution id
     * 
     * @param string note notes about the phone number
     * 
     * 
     * @throws PhoneDuplicationException if the given number is duplicated for the same user
     */
    public static function addInstitutionPhoneNumber(string $number, int $institutionId, string $note = null)
    {
        $type = PhoneNumber::TYPE_OFFICE;
        $associatedTo = PhoneNumber::ASSOC_INSTITUTION;
        return static::addPhoneNumber($number, $institutionId, $type, $associatedTo, $note);
    }

    /**
     * Add new phone number for the user and store it in the database
     * @param string number the phone number
     * @param int userId the user id
     * @param string type  the type of the phone number
     * 
     * @param string note notes about the phone number
     * 
     * @throws InvalidPhoneTypeException if the given type is invalid
     * @throws PhoneDuplicationException if the given number is duplicated for the same user
     */
    public static function addUserPhoneNumber(string $number, int $userId, string $type = null, string $note = null)
    {
        if(!$type){
            $type = PhoneNumber::TYPE_PERSONAL;
        }
        $associatedTo = PhoneNumber::ASSOC_USER;
        return static::addPhoneNumber($number, $userId, $type, $associatedTo, $note);
    }
}

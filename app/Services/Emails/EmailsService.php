<?php

namespace App\Services\Emails;

use App\Exceptions\Email\EmailDuplicationException;
use App\Exceptions\Email\InvalidEmailAssociationException;
use App\Exceptions\Email\InvalidEmailTypeException;
use App\Exceptions\PhoneNumber\InvalidPhoneAssociationException;
use App\Exceptions\PhoneNumber\PhoneDuplicationException;
use App\Models\Email;
use App\Models\PhoneNumber;
use Exception;

class EmailsService
{
    /**
     * generates a verification token from a string
     */
    public static function getVerificationTokenFromOTP(string $otp)
    {
        return hash('sha256', $otp);
    }


    /**
     * Add new email to the database
     * @param string email address 
     * @param int created by the creator of the email
     * @param string type the type of the email
     * @param string associatedTo the association relation of email
     * @param string note notes about the email
     * 
     * @throws InvalidEmailTypeException if the given type is invalid
     * @throws InvalidEmailAssociationException if the given association is invalid
     * @throws EmailDuplicationException if the given email is duplicated for the same user
     * 
     */
    public static function addEmail(string $email, int $createdBy, string $type, string $associatedTo, string $note = null)
    {
        $email = trim($email);


        // check the type 

        $validTypes = Email::getTypes();

        if (!in_array($type, $validTypes)) {
            throw new InvalidEmailTypeException(__('emails.invalid_type'));
        }


        // check the association
        $validAssociations = Email::getValidAssociations();

        if (!in_array($associatedTo, $validAssociations)) {
            throw new InvalidEmailAssociationException(__('emails.invalid_association'));
        }


        //check email duplication for the same association and the same creator
        $emailAddress = Email::where('email', $email)
            ->where('created_by', $createdBy)
            ->where('associated_to', $associatedTo)
            ->first();

        if ($emailAddress) {

            throw new EmailDuplicationException(__('emails.email_duplication'));
        }

        // create random one time password 
        $otp = random_int(100000, 999999);

        $verificationToken = static::getVerificationTokenFromOTP(strval($otp));


        $data = [
            'email' => $email,
            'verification_token' => $verificationToken,
            'verified_at' => null,
            'created_by' => $createdBy,
            'type' => $type,
            'associated_to' => $associatedTo,
            'note' => $note
        ];

        $emailAddress = Email::create($data);
        return [
            'secretToPhone' => [
                'otp' => $otp,
                'link' => route('email.verify.token', ['id' => $emailAddress->id, 'token' => $verificationToken])
            ],
            'email' => $emailAddress
        ];
    }

    
    /**
     * Add new email for the institution and store it in the database
     * @param string email the email address
     * @param int institutionId the institution id
     * 
     * @param string note notes about the phone number
     * 
     * 
     * @throws EmailDuplicationException if the given email is duplicated for the same institution
     */
    public static function addInstitutionEmail(string $email, int $institutionId, string $note = null)
    {
        $type = Email::TYPE_OFFICE;
        $associatedTo = Email::ASSOC_INSTITUTION;
        return static::addEmail($email, $institutionId, $type, $associatedTo, $note);
    }

    /**
     * Add new email for the user and store it in the database
     * @param string email the user email
     * @param int userId the user id
     * @param string type  the type of the email
     * 
     * @param string note notes about the email
     * 
     * @throws InvalidEmailTypeException if the given type is invalid
     * @throws EmailDuplicationException if the given email is duplicated for the same user
     */
    public static function addUserEmail(string $email, int $userId, string $type = null, string $note = null)
    {
        if(!$type){
            $type = Email::TYPE_PERSONAL;
        }
        $associatedTo = Email::ASSOC_USER;
        
        return static::addEmail($email, $userId, $type, $associatedTo, $note);
    }
}

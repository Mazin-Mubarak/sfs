<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'number', 
        'verification_token',
        'verified_at',
        'created_by',
        'type',
        'associated_to',
        'note'
    ];

    protected $hidden = [
        'verification_token'
    ];


    # phone number types
    public const TYPE_HOME = 'HOME';
    public const TYPE_OFFICE = 'OFFICE';
    public const TYPE_PERSONAL = 'PERSONAL';

    /**
     * get valid types for phone number
     */
    public static function getTypes()
    {
        return [
            PhoneNumber::TYPE_PERSONAL,
            PhoneNumber::TYPE_HOME,
            PhoneNumber::TYPE_OFFICE,
        ];
    }

    # phone numbers associations

    public const   ASSOC_USER =  'USERS'; // the phone number is a user phone number
    public const   ASSOC_INSTITUTION =  'INSTITUTION'; //phone numbers is an institution phone number

    /**
     * get valid association types for phone number
     */
    public static function getValidAssociations()
    {
        return [
            PhoneNumber::ASSOC_USER,
            PhoneNumber::ASSOC_INSTITUTION,
        ];
    }
}

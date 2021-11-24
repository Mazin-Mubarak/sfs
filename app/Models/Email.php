<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $fillable = [
        'email', 
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
     * get valid types for email
     */
    public static function getTypes()
    {
        return [
            Email::TYPE_PERSONAL,
            Email::TYPE_HOME,
            Email::TYPE_OFFICE,
        ];
    }

    # email associations

    public const   ASSOC_USER =  'USERS'; // the email is a user email
    public const   ASSOC_INSTITUTION =  'INSTITUTION'; //email is an institution email

    /**
     * get valid association types for email
     */
    public static function getValidAssociations()
    {
        return [
            Email::ASSOC_USER,
            Email::ASSOC_INSTITUTION,
        ];
    }
}

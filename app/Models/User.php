<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'user_name',
        'password',
        'birth_date',
        'image'
    ];

    //the attributes that are'nt mass assignable
    protected $guarded = [
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'datetime',
    ];

    /**
     * System users' accounts valid status
     */
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_SUSPENDED = 'SUSPENDED';


    /**
     * @static
     * get all valid statuses for all system users' account
     *  
     * @return array contains all valid status
    */
    public static function getValidStatus(): array
    {
        /**
         * @var array to hold all valid statuses
         */ 

        $validStatuses = [
            User::STATUS_ACTIVE,
            User::STATUS_SUSPENDED,
        ];
        
        //return status
        return $validStatuses;
    }

    /**
     * to define that one user has many educational institutions
     */
    public function educational_institutions()
    {
        return $this->hasMany(EducationalInstitution::class);
    }
}

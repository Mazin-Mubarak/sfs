<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionEmployee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'institution_id',
        'role',
        'status'
    ];

    ############## Relationships ###################

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function institution()
    {
        return $this->belongsTo(EducationalInstitution::class, 'institution_id');
    }


    // employee roles
    public const ROLE_ADMIN = 'ADMIN';
    public const ROLE_SUPERVISOR = 'SUPERVISOR';
    public const ROLE_TEACHER = 'TEACHER';

    public static function getValidRoles()
    {
        return [
            InstitutionEmployee::ROLE_ADMIN,
            InstitutionEmployee::ROLE_SUPERVISOR,
            InstitutionEmployee::ROLE_TEACHER,
        ];
    }

    public static function getDefaultRole()
    {
        return InstitutionEmployee::ROLE_SUPERVISOR;
    }

    // employee statuses
    public const STATUS_PENDING = 'PENDING';
    public const STATUS_DENIED = 'DENIED';
    public const STATUS_APPROVED = 'APPROVED';
 
    public static function getValidStatuses()
    {
        return [
            InstitutionEmployee::STATUS_PENDING,
            InstitutionEmployee::STATUS_DENIED,
            InstitutionEmployee::STATUS_APPROVED,
        ];
    }

    public static function getDefaultStatus()
    {
        return InstitutionEmployee::STATUS_PENDING;
    }
}

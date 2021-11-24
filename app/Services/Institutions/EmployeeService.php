<?php

namespace App\Services\Institutions;

use App\Models\InstitutionEmployee;

class EmployeeService
{
    public static function addEmployee(int $userId, int $institutionId, string $role, string $status)
    {
        $data = [
            'user_id' => $userId,
            'institution_id' => $institutionId,
            'role' => $role,
            'status' => $status
        ];

        $employee = InstitutionEmployee::create($data);

        return $employee;
    }
}
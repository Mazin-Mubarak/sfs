<?php

namespace App\Services\Users;

use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Creates new user and store it in the database
     * @param string name the name of the user
     * @param string userName the unique user name
     * @param string password the plain text password
     * @param DateTime birthDate the birth date of the user
     * 
     * @return User the model of the new created user
     */
    public static function create(string $name, string $userName, string $password, DateTime $birthDate): User
    {
        //trim and str_lower the userName
        // mb_strtolower for supporting multi byte strings
        $userName = mb_strtolower(trim($userName));
        //hash the password 
        $hashedPassword = Hash::make($password);

        //initiate user data
        $userData = [
            'name' => $name,
            'user_name' => $userName,
            'password' => $hashedPassword,
            'birth_date' => $birthDate,
        ];

        // the new user
        $user = User::create($userData);

        //return the user
        return $user;
    }
}
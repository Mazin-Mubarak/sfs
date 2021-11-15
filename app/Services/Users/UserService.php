<?php

namespace App\Services\Users;

use App\Exceptions\User\InActiveUserException;
use App\Exceptions\User\UserNotFoundException;
use App\Exceptions\User\UserWrongPasswordException;
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

    /**
     * Authenticate the user and creates its access token
     * @param string $userName the user name to look for in the database
     * @param string $password the password corresponded to the given userName
     * @param string $ip the ip address of the login device by default empty string
     * @param string $userAgent the agent (software) used by the user by default empty string
     * @param array $tokenAbilities the abilities granted to the token by default : ['*']
     * 
     * @return array contains the access token and the user data 
     * [token => 'token' , user => {user} ]
     * 
     * @throws UserNotFoundException if the given userName is not found in the database
     * @throws UserWrongPasswordException if the given password does not match the password in the database
     * @throws InActiveUserException if the user status in any status rather than active
     */
    public static function login(string $userName, string $password, string $ip = '', string $userAgent = '', array $tokenAbilities = ['*']): array
    {
        //trim and str_lower the userName
        // mb_strtolower for supporting multi byte strings
        $userName = mb_strtolower(trim($userName));

        // load the user from the database
        $user = User::where('user_name' , $userName)->first();

        // check if the user exists
        if(!$user) {
            throw new UserNotFoundException(__("users.user_not_found", ['user_name' => $userName]));
        }

        //check if the given password matches the password in the database
        $passwordsMatched = Hash::check($password, $user->password);

        if(!$passwordsMatched){
            throw new UserWrongPasswordException(__("users.wrong_password", ['user_name' => $userName]));
        }

        //prevent inactive users from creating access tokens
        if($user->status !== User::STATUS_ACTIVE){
            throw new InActiveUserException(__("users.inactive_user"));
        }

        //set the device data
        $userDevice = [
            "ip" => $ip,
            "agent" => $userAgent
        ];

        //set the access token name from login device data
        $tokenName = json_encode($userDevice);

        //create the access token
        $accessToken = $user->createToken($tokenName, $tokenAbilities);


        // return data
        return [
            "token" => $accessToken->plainTextToken,
            "user" => $user
        ];
    }

    /**
     * logout user (revoke access tokens)
     * @param Collection tokens : the tokens to be revoked
     */
    public static function logout($tokens)
    {
        $tokens->delete();
    }
}
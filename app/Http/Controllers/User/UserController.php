<?php

namespace App\Http\Controllers\User;

use App\Exceptions\User\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginUserRequest;
use App\Http\Requests\User\storeUserRequest;
use App\Services\Users\UserService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;

class UserController extends Controller
{
    /**
     * Create and store new user
     */
    public function store(storeUserRequest $request)
    {
        $name = $request->input('name');
        $userName = mb_strtolower($request->input('user_name'));
        $password = $request->input('password');
        $birthDate = Carbon::create($request->input('birth_date'));

        $user = UserService::create($name, $userName, $password, $birthDate);
        return $this->sendSuccessResponse(
            [__("users.addition_success", ["user_name" => $user->user_name])],
            $user,
            Response::HTTP_CREATED
        );
    }

    /**
     * Authenticate the user and grant it an access token
     */

    public function login(LoginUserRequest $request)
    {
        
        $userName = $request->input('user_name');
        $password = $request->input('password');

        $ip = $request->server('REMOTE_ADDR');
        $userAgent = $request->header('USER-AGENT');
        try{
            $loginData = UserService::login($userName, $password, $ip, $userAgent);
        }catch(Exception $e){
            return $this->sendErrorResponse([$e->getMessage()], null, Response::HTTP_UNAUTHORIZED);
        }

        return $this->sendSuccessResponse([__("users.login_successful")], $loginData);
    }
}

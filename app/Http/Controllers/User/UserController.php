<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\storeUserRequest;
use App\Services\Users\UserService;
use Carbon\Carbon;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function store(storeUserRequest $request)
    {
        $name = $request->input('name');
        $userName = mb_strtolower($request->input('user_name'));
        $password = $request->input('password');
        $birthDate = Carbon::create($request->input('birth_date'));

        $user = UserService::create($name, $userName, $password, $birthDate);

        return $this->sendSuccessResponse(
            ["user has been created successfully"],
            $user,
            Response::HTTP_CREATED
        );
    }
}

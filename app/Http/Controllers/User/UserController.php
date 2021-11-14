<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\storeUserRequest;
use App\Services\Users\UserService;
use Carbon\Carbon;

class UserController extends Controller
{
    public function store(storeUserRequest $request)
    {
        $name = $request->input('name');
        $userName = mb_strtolower($request->input('user_name'));
        $password = $request->input('password');
        $birthDate = Carbon::create($request->input('birth_date'));

        $user = UserService::create($name, $userName, $password, $birthDate);

        return [$user];
    }
}

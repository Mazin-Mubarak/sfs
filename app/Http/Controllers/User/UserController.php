<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginUserRequest;
use App\Http\Requests\User\LogoutUserRequest;
use App\Http\Requests\User\storeUserRequest;
use App\Services\Users\UserService;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Throwable;

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
        
        $userAvatarFileName = null;
        if($request->hasFile('image')){
            $userAvatar = $request->file('image');

            // name consist from unique random string + . + image extension
            $uniqueImageName = uniqid("",true);
            $imageExtension = $userAvatar->extension();

            $userAvatarFileName = $uniqueImageName . '.' . $imageExtension ;


            //get the user images directory
            $userImagesDirectory =  config('users.images_directory');

            //store the image persistently
            $userAvatar->storeAs($userImagesDirectory, $userAvatarFileName);

        }

        $user = UserService::create($name, $userName, $password, $birthDate, $userAvatarFileName);
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
        }catch(Throwable $e){
            return $this->sendErrorResponse([$e->getMessage()], null, Response::HTTP_UNAUTHORIZED);
        }

        return $this->sendSuccessResponse([__("users.login_successful")], $loginData);
    }

    
    /**
     * Revoke access tokens for the current user
     */
    public function logout(LogoutUserRequest $request)
    {
        $device = "current";
        if($request->has('device')){
            $device = strtolower(trim($request->input('device')));
        }
 
        //get the authenticated users
        $user = auth()->user();
        $tokensToRevoke = $user->currentAccessToken();

        switch ($device) {
            case 'all':
                $tokensToRevoke = $user->tokens();
                break;

            case 'others':
                $currentAccessTokenId = $user->currentAccessToken()->id;
                $tokensToRevoke = $user->tokens()->where("id", "!=" , $currentAccessTokenId);    
                break;

            case 'current':            
            default:
                //logout from current used device
                $tokensToRevoke = $user->currentAccessToken();
                break;
        }

        //revoke tokens
        UserService::logout($tokensToRevoke);

        return $this->sendSuccessResponse([__("users.logout_successful")], $user);
    }
}

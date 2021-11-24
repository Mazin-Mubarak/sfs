<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Services\Emails\EmailsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmailController extends Controller
{
    //

    public function verify(Request $request, int $id)
    {
        $request->validate(["otp" => 'required']);

        $email = Email::find($id);
        if(!$email){
            return $this->sendErrorResponse([__("emails.not_found")], null, Response::HTTP_NOT_FOUND);
        }

        $otp = $request->input('otp');

        $otpHash = EmailsService::getVerificationTokenFromOTP(strval($otp));

        if($email->verification_token != $otpHash){
            $messages = [__('emails.invalid_otp')];
            return $this->sendErrorResponse($messages, null,Response::HTTP_NOT_ACCEPTABLE);
        }

        $email->verified_at = Carbon::now();
        $email->verification_token = null;

        $email->save();

        return $this->sendSuccessResponse([__('emails.verified_success')], $email);
    }

    public function verifyByToken(int $id, string $token)
    {
        $email = Email::find($id);
        if(!$email){
            return $this->sendErrorResponse([__("emails.not_found")], null, Response::HTTP_NOT_FOUND);
        }

        if($email->verification_token != $token)
        {
            $messages = [__('emails.invalid_token')];
            return $this->sendErrorResponse($messages, null,Response::HTTP_NOT_ACCEPTABLE);
        }

        $email->verified_at = Carbon::now();
        $email->verification_token = null;

        $email->save();

        return $this->sendSuccessResponse([__('emails.verified_success')], $email);
    }
}

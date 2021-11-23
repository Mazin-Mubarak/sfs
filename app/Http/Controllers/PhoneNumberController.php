<?php

namespace App\Http\Controllers;

use App\Models\PhoneNumber;
use App\Services\PhoneNumbers\PhoneNumbersService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PhoneNumberController extends Controller
{
    public function verify(Request $request, int $id)
    {
        $request->validate(["otp" => 'required']);

        $phone = PhoneNumber::find($id);
        if(!$phone){
            return $this->sendErrorResponse([__("phoneNumbers.not_found")], null, Response::HTTP_NOT_FOUND);
        }

        $otp = $request->input('otp');

        $otpHash = PhoneNumbersService::getVerificationTokenFromOTP(strval($otp));

        if($phone->verification_token != $otpHash){
            $messages = [__('phoneNumbers.invalid_otp')];
            return $this->sendErrorResponse($messages, null,Response::HTTP_NOT_ACCEPTABLE);
        }

        $phone->verified_at = Carbon::now();
        $phone->verification_token = null;

        $phone->save();

        return $this->sendSuccessResponse([__('phoneNumbers.verified_success')], $phone);
    }

    public function verifyByToken(int $id, string $token)
    {
        $phone = PhoneNumber::find($id);
        if(!$phone){
            return $this->sendErrorResponse([__("phoneNumbers.not_found")], null, Response::HTTP_NOT_FOUND);
        }

        if($phone->verification_token != $token)
        {
            $messages = [__('phoneNumbers.invalid_token')];
            return $this->sendErrorResponse($messages, null,Response::HTTP_NOT_ACCEPTABLE);
        }

        $phone->verified_at = Carbon::now();
        $phone->verification_token = null;

        $phone->save();

        return $this->sendSuccessResponse([__('phoneNumbers.verified_success')], $phone);
    }
}

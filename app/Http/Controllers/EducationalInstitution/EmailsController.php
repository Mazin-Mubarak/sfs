<?php

namespace App\Http\Controllers\EducationalInstitution;

use App\Exceptions\Email\EmailDuplicationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\EducationalInstitution\AddInstitutionEmailRequest;
use App\Models\EducationalInstitution;
use App\Services\Emails\EmailsService;

use Illuminate\Http\Response;

class EmailsController extends Controller
{

    /**
     * Add email for the institution
     */
    public function store(AddInstitutionEmailRequest $request, int $id)
    {
        //get the institution
        $institution = EducationalInstitution::find($id);

        if(!$institution){
            $messages = [__("institutions.not_found")];
            return $this->sendErrorResponse($messages, null, Response::HTTP_NOT_FOUND);
        }

        $email = $request->input('email');
        $note = $request->input('note');


        try {
            $verificationData = EmailsService::addInstitutionEmail($email, $institution->id, $note);
        }catch (EmailDuplicationException $e) {
            $messages = [$e->getMessage()];
            return $this->sendErrorResponse($messages, null, Response::HTTP_NOT_ACCEPTABLE);
        }catch (\Throwable $th) {
            $messages = [$th->getMessage()];
            return $this->sendErrorResponse($messages, null, Response::HTTP_NOT_ACCEPTABLE);
        }

        return $this->sendSuccessResponse([__("institutions.add_email_success")] ,$verificationData, Response::HTTP_CREATED);
    }
}

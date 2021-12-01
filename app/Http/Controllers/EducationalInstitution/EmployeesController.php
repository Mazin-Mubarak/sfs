<?php

namespace App\Http\Controllers\EducationalInstitution;

use App\Http\Controllers\Controller;
use App\Http\Requests\AcceptInstitutionEmploymentRequest;
use App\Http\Requests\DeclineInstitutionEmploymentRequest;
use App\Http\Requests\EducationalInstitution\StoreEmployeeRequest;
use App\Models\EducationalInstitution;
use App\Models\InstitutionEmployee;
use App\Models\User;
use App\Services\Institutions\EmployeeService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmployeesController extends Controller
{
    /**
     * Adds new employee and store it in the database
     */
    public function store(StoreEmployeeRequest $request, int $id)
    {
        //check if the institution exists
        $institution = EducationalInstitution::find($id);
        if (!$institution) {
            $messages = [__('institutions.not_found')];
            return $this->sendErrorResponse($messages, null, Response::HTTP_NOT_FOUND);
        }

        //check if the user exists
        $userName = mb_strtolower(trim($request->input('user_name')));
        $user = User::where('user_name', $userName)->first();

        if (!$user) {
            $messages = [__('users.user_not_found', ['user_name' => $userName])];
            return $this->sendErrorResponse($messages, null, Response::HTTP_NOT_FOUND);
        }

        $role = InstitutionEmployee::getDefaultRole();
        if ($request->has('role')) {
            $role = $request->input('role');
        }

        // check user duplication for the same institution of the same same
        $employee = InstitutionEmployee::where('user_id', $user->id)
            ->where('institution_id', $institution->id)
            ->where('role', $role)->first();
        if($employee){
            $messages = [__('institutions.duplicated_employee')];
            return $this->sendErrorResponse($messages, null, Response::HTTP_NOT_ACCEPTABLE);
        }

        
        $status = InstitutionEmployee::getDefaultStatus();

        $employee = EmployeeService::addEmployee($user->id, $institution->id, $role, $status);

        return $employee;
    }

    /**
     *  Accept a request for joining the institution as employee
     */
    public function accept(AcceptInstitutionEmploymentRequest $httpRequest ,int $id)
    {
        $employmentRequest = InstitutionEmployee::find($id);
        if(!$employmentRequest){
            $messages = [__("employmentRequests.not_found")];
            return $this->sendErrorResponse($messages, null, Response::HTTP_NOT_FOUND);
        }

        $data = ['status' => InstitutionEmployee::STATUS_APPROVED];
        $employmentRequest->update($data);
        return $this->sendSuccessResponse([__("employmentRequests.approved")], $employmentRequest);
    }

    /**
     *  Decline a request for joining the institution as employee
     */
    public function decline(DeclineInstitutionEmploymentRequest $httpRequest ,int $id)
    {
        $employmentRequest = InstitutionEmployee::find($id);
        if(!$employmentRequest){
            $messages = [__("employmentRequests.not_found")];
            return $this->sendErrorResponse($messages, null, Response::HTTP_NOT_FOUND);
        }

        $employmentRequest->delete();
        return $this->sendSuccessResponse([__("employmentRequests.declined")], $employmentRequest);
    }
}

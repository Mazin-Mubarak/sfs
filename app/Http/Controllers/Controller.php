<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    
    /**
     * Sends the response if it has a successful status
     * @param array set of messages to send with the response
     * @param $data the data to send in the response
     * @param array headers http headers to be sent with response
     * 
     * @return ResponseFactory
     */
    public function sendSuccessResponse(array $messages,$data, int $responseCode = 200, array $headers = [])
    {
        $response = [
            "hasError" => false,
            "messages" => $messages,
            "data" => $data
        ];

        return response($response, $responseCode, $headers);
    }

    /**
     * Send the response if there was an error
     * @param array set of messages to send with the response
     * @param $data the data to send in the response
     * @param array headers http headers to be sent with response
     * 
     * @return ResponseFactory
     */
    public function sendErrorResponse(array $messages, $data = [], int $responseCode = 200, array $headers = [] )
    {
        $response = [
            "hasError" => true,
            "messages" => $messages,
            "data" => $data
        ];

        return response($response, $responseCode, $headers);
    }
}

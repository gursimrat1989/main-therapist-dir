<?php

/*
 * API success message response
 */
function sendResponse($result, $message)
{
$response = [
        'success' => true,
        'data'    => $result,
        'message' => ucwords($message),
    ];

    return response()->json($response, 200);
}

/*
 * API error message response
 */
function sendError($error, $errorMessages = [], $code = 400)
{
$response = [
        'success' => false,
        'message' => ucwords($error),
    ];

    if(!empty($errorMessages)){
        $response['data'] = $errorMessages;
    }

    return response()->json($response, $code);
}
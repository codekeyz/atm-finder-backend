<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function sendErrorMessage($errorCode, $success, $errorMessage) {
        return response()->json([
            'success' => $success,
            'errorMessage' => $errorMessage,
            'errorCode' => $errorCode
        ], $errorCode);
    }
}

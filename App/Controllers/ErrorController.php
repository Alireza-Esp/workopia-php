<?php 
namespace App\Controllers;

class ErrorController
{
    public static function notFound(string $message = "resource not found") {
        http_response_code(404);
        loadView(
            "error",
            [
                'statusCode' => '404',
                'message' => $message
            ]
        );
        exit();
    }
    
}
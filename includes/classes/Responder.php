<?php

    require_once 'includes/interfaces/ResponderInterface.php';
    
    class Responder implements ResponderInterface
    {
        public function sendResponse($statusCode, $message, $contentType='application/json') {
            http_response_code($statusCode);
            header('content-Type: '.$contentType);
            echo $message;
        }
    }
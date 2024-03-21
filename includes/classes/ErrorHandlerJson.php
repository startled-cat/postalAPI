<?php
    
    require_once 'includes/classes/ErrorJsonMessage.php';
    
    require_once 'includes/interfaces/ErrorHandlerInterface.php';

    class ErrorHandlerJson implements ErrorHandlerInterface
    {
        private $responder;
        
        public function __construct(ResponderInterface $responder) {
            $this->responder = $responder;
        }
        
        public function throwError($code, $errorMsg) {
            $errorObj = new ErrorJsonMessage($errorMsg);
            $this->responder->sendResponse($code, json_encode($errorObj));
        }
    }
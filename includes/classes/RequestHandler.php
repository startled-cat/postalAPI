<?php

    class RequestHandler
    {
        private $requestData;
        private $headerData;
        private $jsonParser;
        private $errorHandler;
        private $responder;
        
        public function __construct(
            string $requestData, 
            array $headerData, 
            ParserInterface $parser, 
            ErrorHandlerInterface $errorHandler, 
            DataHandlerInterface $dataHandler,
            ResponderInterface $responder
        ) {
            $this->requestData = $requestData;
            $this->headerData = $headerData;
            $this->dataHandler = $dataHandler;
            $this->jsonParser = $parser;
            $this->errorHandler = $errorHandler;
            $this->responder = $responder;
        }
        
        public function getContentType() {
            if (!isset($this->headerData['Content-Type'])) {
                return null;
            }
            
            return $this->headerData['Content-Type'];
        }
        
        public function checkHeader() {
            return ($this->getContentType() === 'application/json');
        }
        
        private function checkRequest() {
            return ($this->requestData != '');
        }
        
        public function handleRequest() {
            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                $this->errorHandler->throwError(400, 'Invalid request type');
            }
            if ($this->checkHeader() == 0) {
                $this->errorHandler->throwError(400, 'Invalid request header');
            }
            if ($this->checkRequest() == 0) {
                $this->errorHandler->throwError(400, 'Request body is empty');
            }
            
            $this->handleMessageBody();
        }
        
        private function handleMessageBody() {
            $jsonDecoded = $this->jsonParser->parse($this->requestData);
            
            if ($jsonDecoded === null) {
                $this->errorHandler->throwError(400, 'Invalid request body');
            }
            
            if (!isset($jsonDecoded->request)) {
                $this->errorHandler->throwError(400, 'No request given in json');
            }
            
            if($jsonDecoded->request != "getDataByPostalCode") {
                $this->errorHandler->throwError(400, 'Request of type '.$jsonDecoded->request.' cannot be handled');
            }
            
            if($jsonDecoded->postal_code == "" || !isset($jsonDecoded->request)) {
                $this->errorHandler->throwError(400, 'Postal code cannot be empty');
            }
            
            $messageToSend = $this->dataHandler->getDataToSendByPostalCode($jsonDecoded->postal_code);
            $this->responder->sendResponse(200, $messageToSend);
        }
    }
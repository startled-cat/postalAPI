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
            if (isset($this->headerData['Content-Type'])) {
                return $this->headerData['Content-Type'];
            } else {
                return null;
            }
        }
        
        public function checkHeader() {
            return ($this->getContentType() === 'application/json');
        }
        
        public function getRequestData() {
            return $this->requestData;
        }
        
        private function checkRequest() {
            return ($this->requestData != '');
        }
        
        public function handleRequest() {
            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                $this->errorHandler->throwError(400, 'Invalid request type');
                return;
            }
            if ($this->checkHeader() == 0) {
                $this->errorHandler->throwError(400, 'Invalid request header');
                return;
            }
            if ($this->checkRequest() == 0) {
                $this->errorHandler->throwError(400, 'Request body is empty');
                return;
            }
            
            $this->handleMessageBody();
        }
        
        private function handleMessageBody() {
            $jsonDecoded = $this->jsonParser->parse($this->requestData);
            
            if ($jsonDecoded === null) {
                $this->errorHandler->throwError(400, 'Invalid request body');
                return;
            }
            
            if (isset($jsonDecoded->request)) {
                if($jsonDecoded->request === "getDataByPostalCode") {
                    if($jsonDecoded->postal_code != "" && isset($jsonDecoded->request)) {
                       $messageToSend = $this->dataHandler->getDataToSendByPostalCode($jsonDecoded->postal_code);
                       $this->responder->sendResponse(200, $messageToSend);
                    } else {
                        $this->errorHandler->throwError(400, 'Postal code cannot be empty');
                    }
                } else {
                    $this->errorHandler->throwError(400, 'Request of type '.$jsonDecoded->request.' cannot be handled');
                }
            } else {
                $this->errorHandler->throwError(400, 'No request given in json');
                return;
            }
        }
    }
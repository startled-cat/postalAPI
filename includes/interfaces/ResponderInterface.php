<?php

    interface ResponderInterface
    {
        public function sendResponse($statusCode, $message, $contentType);
    }
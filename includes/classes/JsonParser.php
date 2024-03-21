<?php

    require_once 'includes/interfaces/ParserInterface.php';

    class JsonParser implements ParserInterface
    {     
        public function parse($json) {
            $jsonDecoded = json_decode($json);
            
            if ($jsonDecoded === null) { // w sumie to robi coś ten if wgle? czy nei zwróci tego samego bez tego ifa?
                return null;
            }
            
            return $jsonDecoded;
        }
    }
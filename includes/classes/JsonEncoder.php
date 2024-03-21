<?php

    require_once 'includes/interfaces/EncoderInterface.php';

    class JsonEncoder implements EncoderInterface
    {     
        public function encode($json) {
            $jsonEncoded = json_encode($json);
            
            if ($jsonEncoded === null) {
                return null;
            }
            
            return $jsonEncoded;
        }
    }
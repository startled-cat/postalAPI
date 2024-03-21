<?php
    class ErrorJsonMessage {
        public $error;
        
        public function __construct($error) {
            $this->error = $error;
        }
    }
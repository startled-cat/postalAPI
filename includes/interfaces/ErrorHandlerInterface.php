<?php

    interface ErrorHandlerInterface
    {
        public function throwError($code, $message);
    }
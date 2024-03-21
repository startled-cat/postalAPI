<?php
    
    require_once 'includes/interfaces/DataHandlerInterface.php';

    class DataHandler implements DataHandlerInterface
    {
        private $dataGetter;
        private $encoder;
        
        public function __construct(DataGetterInterface $dataGetter, EncoderInterface $encoder) {
            $this->dataGetter = $dataGetter;
            $this->encoder = $encoder;
        }
        public function getDataToSendByPostalCode($postalCode) {
            $dataArray = $this->dataGetter->getDataByPostalCode($postalCode);
            
            $sendArray = Array();
            
            foreach($dataArray as $key=>$value) {
                if ($key == 'id' || $key == 'date_added' || $key == 'code') continue;
                $sendArray[$key] = $value;
            }
            
            return $this->encoder->encode($sendArray);
        }
    }
<?php
    
    require_once 'includes/interfaces/DataGetterInterface.php';

    class MySQLDataGetter implements DataGetterInterface
    {
        private $host;
        private $user;
        private $password;
        private $database;
        private $connection;
        private $errorHandler;
        
        public function __construct($host, $user, $password, $database, ErrorHandlerInterface $errorHandler) {
            $this->host = $host;
            $this->user = $user;
            $this->password = $password;
            $this->database = $database;
            $this->errorHandler = $errorHandler;
            $this->openConnection();
        }
        
        public function __destruct() {
            $this->closeConnection();
        }
        
        private function openConnection() {
            try {
                $this->connection = new mysqli($this->host, $this->user, $this->password, $this->database);
                $this->connection->set_charset('utf8');
            } catch (Exception $ex) {
                $this->errorHandler->throwError(500, 'Database connection error: '.$ex);
            }
            
        }
        
        private function closeConnection() {
            if (isset($this->connection)) {
                try {
                    $this->connection->close();
                } catch (Exception $ex) {
                    //go to void //nwm co to here xd
                }
            }
        }
        
        private function makeDataSafe($data) { // może sanitizeData bym nazwał
            $safeData = $this->connection->real_escape_string($data);
            return $safeData;
        }
        
        private function getAllByField($field, $value, $table) {
            $safeField = $this->makeDataSafe($field);
            $safeValue = $this->makeDataSafe($value);
            $safeValue = $this->makeDataSafe($table);
            $query = "SELECT * FROM `".$table."` WHERE `".$field."` = '".$value."';";
            return $this->connection->query($query);
        }
        
        private function getRawDataByPostalCode($postalCode) {
            return $this->getAllByField('code', $postalCode, 'postal_codes');
        }
        
        public function getDataByPostalCode($postalCode) {
            $response = $this->getRawDataByPostalCode($postalCode)->fetch_assoc();
            
            if($response == NULL) {
                // nie mieszałbym żeczy związanych z db i http, w sensie klasa ogarniająca baze danych nie powinna ogarniać odpowiedzi http
                $this->errorHandler->throwError(400, 'Could not find any data for code: '.$postalCode);
            }
            
            return $response;
        }
    }

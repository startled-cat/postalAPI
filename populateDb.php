<?php

    require_once 'config.php';
    
    if(!isset($_FILES['csvFile'])) {
        echo '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Upload CSV File</title>
            </head>
            <body>
                <h2>Upload CSV File</h2>
                <form action="populateDb.php" method="post" enctype="multipart/form-data">
                    <label for="csvFile">Choose a CSV file:</label><br>
                    <input type="file" id="csvFile" name="csvFile"><br><br>
                    <input type="submit" value="Send">
                </form>
            </body>
            </html>';
    } else {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $file = $_FILES["csvFile"];
        $handle = fopen($file["tmp_name"], "r");
        $flag = 0;
        
        while (($row = fgetcsv($handle)) !== false) {
            if($flag == 0) {
                //dump the first row with names
                $flag = 1;
                continue;
            }
            $row = explode(';', $row[0]);
            $code = $row[0];
            $city = $row[1];
            $community = $row[2];
            $county = $row[3];
            $voivodeship = $row[4];
            $sql = "INSERT INTO postal_codes VALUES ('null', '".$code."', '".$city."', '".$community."', '".$county."', '".$voivodeship."', CURRENT_TIMESTAMP)";

            if ($conn->query($sql) === TRUE) {
                echo "Query done created successfully";
            } else {
                echo "Error creating table: " . $conn->error;
            }
        }
        
        fclose($handle);
        $conn->close();
    }
?>
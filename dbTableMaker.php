<?php

    require_once 'config.php';
    
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "CREATE TABLE IF NOT EXISTS postal_codes (
        id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        code VARCHAR(255) NOT NULL,
        city VARCHAR(255) NOT NULL,
        community VARCHAR(255) NOT NULL,
        county VARCHAR(255) NOT NULL,
        voivodeship VARCHAR(255) NOT NULL,
        date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "Table postal_codes created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }

    // Close connection
    $conn->close();
?>
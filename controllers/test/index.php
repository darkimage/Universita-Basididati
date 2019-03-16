<?php 
    if(!defined('ROOT')){
        define('ROOT', $_SERVER['DOCUMENT_ROOT']);
    }
    require_once(ROOT."/private/dbConnection.php");

    $test = Orders::findAll("SELECT * FROM @this",null,['max'=>5,'offset'=>0]);
    print_r($test);
    $t = 2;
    // $database->connect();
    // $sql = "CREATE TABLE MyGuests (
    //     id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    //     firstname VARCHAR(30) NOT NULL,
    //     lastname VARCHAR(30) NOT NULL,
    //     email VARCHAR(50),
    //     reg_date TIMESTAMP
    //     )";
        
    // if ($database->query($sql) === TRUE) {
    //     echo "Table MyGuests created successfully";
    // } else {
    //     echo "Error creating table: " . $database->error;
    // }
    // $database->close();
    ?>
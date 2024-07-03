<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "data_base_2";

    //create connections
    $conn = mysqli_connect($servername,$username,$password,$dbname);

    //check connection
    if(!$conn)
    {
        die("Connection failed".mysqli_connect_error()); // . : concat
    }
    else
    {
        /*if (!$conn->set_charset("utf8")) {
            printf("Error loading character set utf8: %s\n", $conn->error);
        } else {
            printf("Current character set: %s\n", $conn->character_set_name());
        }*/
        echo "Connect successfully";
        //set timeout
        ini_set('max_execution_time', 150);
    }
?>
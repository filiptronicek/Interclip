<?php

$GLOBALS['servername'] = $servername;
$GLOBALS['username'] = $username;
$GLOBALS['password'] = $password;
$GLOBALS['dbname'] = $DBName;

function noteLimit($action) {
    include_once "./db.php";
    
    //whether ip is from share internet
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    //whether ip is from proxy
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    //whether ip is from remote address
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    $cryptIP = hash("sha512", $ip);

    // Create connection
    $conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $rateLimitCheck = "SELECT COUNT(*) FROM `hits` where `date` > (CURRENT_TIMESTAMP - 3600) AND `iphash` = '$cryptIP'";
    $rateLimitCheckResult = $conn->query($rateLimitCheck);
    while ($row = $rateLimitCheckResult->fetch_assoc()) {
        $count = $row["COUNT(*)"];
        break;
    }
    
    if($count > 15) {
        die("Rate Limited");
    }

    $sqlquery = "INSERT INTO hits (id, iphash, date, operation) VALUES (NULL, '$cryptIP', NOW(), '$action') ";
    $result = $conn->query($sqlquery);
    if ($result === FALSE) {
        echo "Error: " . $sqlquery . "<br>" . $conn->error;
    }
}
<?php

include_once "redis.php";

function noteLimit()
{

    if ($GLOBALS["redisAvailable"]) {

    // The IP is from the shared internet
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        // The IP is from a proxy
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        // The IP is from a remote address
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $cryptIP = hash("sha512", $_ENV['SALT'] . "-" . $ip);

        $count = ipHit($cryptIP);

        if ($count > 15) {
            http_response_code(429);
            die("Rate Limited");
        }

        return true;
    } else {
        return false;
    }
}

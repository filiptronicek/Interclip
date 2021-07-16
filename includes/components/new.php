<?php

include_once "includes/components/rate.php";
include_once "includes/verify-domain.php";
include_once "includes/components/redis.php";

/**
 * Creates a new clip in the database
 *
 * @param  mixed $url
 * @return void
 */
function createClip($url)
{
    noteLimit();

    $err = "";

    // Create connection
    $conn = new mysqli($_ENV['DB_SERVER'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DB_NAME']);

    $url = htmlspecialchars($url);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    /**
     * Creates a random alpha-numberic ID.
     *
     * @param  mixed $len
     * @return string
     */
    function gen_uid($len = 10)
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $len);
    }

    $usr = gen_uid(5);

    /* Expiry of clips */

    $startdate = strtotime("Today");
    $expires = strtotime("+1 month", $startdate);
    $expiryDate = date("Y-m-d", $expires);

    $stmt = $conn->prepare('SELECT * FROM userurl WHERE url = ?');

    $stmt->bind_param('s', $url);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $usr = $row['usr'];
            break;
        }
    } else {
        $stmt = $conn->prepare('SELECT * FROM userurl WHERE usr = ?');

        $stmt->bind_param('s', $usr);
        $stmt->execute();
        $stmt->store_result();

        while ($stmt->num_rows > 0) {
            $usr = gen_uid(5);

            $stmt->bind_param('s', $usr);
            $stmt->execute();
            $stmt->store_result();
        }

        if (verify($url)) {
            $stmt = $conn->prepare('INSERT INTO userurl (usr, url, date, expires) VALUES (?, ?, NOW(), ?)');

            $stmt->bind_param('sss', $usr, $url, $expiryDate);
            storeRedis($usr, $url);

            if ($stmt->execute() === FALSE) {
                $err = "Error inserting clip: <br>" . $conn->error;
            }
        } else {
            $err = "Error: this domain is not accesible nor registered.";
        }
    }

    //$conn->close();
    return [$usr, $err];
}

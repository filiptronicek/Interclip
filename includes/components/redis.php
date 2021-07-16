<?php

$GLOBALS["redisAvailable"] = true;

try {
    $GLOBALS["redis"] = new Redis();
} catch (Error $e) {
    $GLOBALS["redisAvailable"] = false; // Failed connecting to the server
}

try {
    if ($GLOBALS["redisAvailable"]) {
        // Connecting to Redis
        $GLOBALS["redis"]->connect('localhost', 6379);
    }
} catch (Exception $e) {
    $GLOBALS["redisAvailable"] = false; // Failed connecting to the server
}

function ipHit($hashedIP)
{
    if ($GLOBALS["redisAvailable"] && $GLOBALS["redis"]->ping()) {

        $redisKey = "ip-" . substr($hashedIP, 0, 7);

        $GLOBALS["redis"]->incr($redisKey);
        $GLOBALS["redis"]->expire($redisKey, 30);
        $count = $GLOBALS["redis"]->get($redisKey);

        return $count;
    } else {
        return 0;
    }
}

function getTotal()
{
    if ($GLOBALS["redisAvailable"] && $GLOBALS["redis"]->ping()) {
        return $GLOBALS["redis"]->dbSize();
    } else {
        return "n/a";
    }
}

function storeRedis($key, $value)
{

    if ($GLOBALS["redisAvailable"] && $GLOBALS["redis"]->ping()) {
        $redisCached = $GLOBALS["redis"]->get($key);

        if ($redisCached) {
            return $redisCached;
        } else {
            $GLOBALS["redis"]->setEx($key, 60 * 60 * 24 * 7, $value); // Expire the key in one week
            return false;
        }
    } else {
        return false;
    }
}

function getRedis($key)
{

    if ($GLOBALS["redisAvailable"] && $GLOBALS["redis"]->ping()) {
        $redisCached = $GLOBALS["redis"]->get($key);

        if ($redisCached) {
            return $redisCached;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

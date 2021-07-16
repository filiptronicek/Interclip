<?php

/**
 * Formats a value in bytes to its appropriate prefix
 *
 * @param  mixed $bytes
 * @return string
 */
function formatBytes($bytes)
{
    if ($bytes > 0) {
        $i = floor(log($bytes) / log(1024));
        $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        return sprintf('%.02F', round($bytes / pow(1024, $i), 1)) * 1 . ' ' . @$sizes[$i];
    } else {
        return '0 B';
    }
}

/* str_starts_with pollyfill */
if (!function_exists('str_starts_with')) {
    function str_starts_with($haystack, $needle)
    {
        return (string)$needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}

/**
 * Gets all the local git branches and returns an arrray of them.
 *
 * @return array
 */
function getBranches()
{
    exec("git branch", $gitOutput);

    $branches = [
        "all" => [],
        "current" => ''
    ];

    foreach ($gitOutput as $branchString) {
        if (str_starts_with($branchString, "*")) {
            $current = substr($branchString, 2);
            $branches["current"] = $current;
        } else {
            $currentBranchClean = str_replace("remotes/", "", $branchString);
            array_push($branches["all"], $currentBranchClean);
        }
    }
    return $branches;
}

/**
 * Redirects the user to a specified URL
 *
 * @param mixed $url
 * @return void
 */
function reDir($url)
{
    header("Location: " . $url . "");
    die();
}

/**
 * Returns the OS from the user agent string
 *
 * @return string
 */
function getOS()
{

    global $user_agent;

    $os_platform = "Unknown OS Platform";

    $os_array = array(
        '/windows nt 10/i' => 'Windows', // Windows 10
        '/windows nt 6.3/i' => 'Windows', // Windows 8.1
        '/windows nt 6.2/i' => 'Windows', // Windows 8.0
        '/windows nt 6.1/i' => 'Windows', // Windows 7
        '/windows nt 6.0/i' => 'Windows', // Windows Vista
        '/windows nt 5.2/i' => 'Windows', // Windows Server 2003/XP x64
        '/windows nt 5.1/i' => 'Windows', // Windows XP
        '/windows xp/i' => 'Windows', // Windows XP
        '/windows nt 5.0/i' => 'Windows', // Windows 2000
        '/windows me/i' => 'Windows', // Windows ME
        '/win98/i' => 'Windows', // Windows 98
        '/win95/i' => 'Windows', // Windows 95
        '/win16/i' => 'Windows', // Windows 3.11
        '/macintosh|mac os x/i' => 'Macos', // Mac OS X
        '/mac_powerpc/i' => 'Macos', // Mac OS 9
        '/linux/i' => 'Linux', // Linux
        '/ubuntu/i' => 'Ubuntu', // Ubuntu
        '/iphone/i' => 'iPhone', // iPhone
        '/ipod/i' => 'iPod', // iPod
        '/ipad/i' => 'iPad', // iPad
        '/android/i' => 'Android', // Android
        '/blackberry/i' => 'BlackBerry', // Blackberry
        '/webos/i' => 'Mobile' // Mobile
    );

    foreach ($os_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $os_platform = $value;

    return $os_platform;
}

/**
 * Get an array of various OS info
 *
 * @return array|null
 */
function getOSInformation()
{
    if (false == function_exists("shell_exec") || false == is_readable("/etc/os-release")) {
        return null;
    }

    $osInfo = shell_exec('cat /etc/os-release');
    $listIds = preg_match_all('/.*=/', $osInfo, $matchListIds);
    $listIds    = $matchListIds[0];

    $listVal = preg_match_all('/=.*/', $osInfo, $matchListVal);
    $listVal = $matchListVal[0];

    array_walk($listIds, function (&$v) {
        $v = strtolower(str_replace('=', '', $v));
    });

    array_walk($listVal, function (&$v) {
        $v = preg_replace('/=|"/', '', $v);
    });

    return array_combine($listIds, $listVal);
}

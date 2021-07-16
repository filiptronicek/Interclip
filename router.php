<?php

include_once "includes/lib/init.php";
include_once "includes/lib/sentry.php";

use Pecee\SimpleRouter\SimpleRouter;

/* Dynamic pages */

SimpleRouter::group(['prefix' => ROOT], function () {

    SimpleRouter::get('/', function () {
        include_once "public/index.php";
    });

    SimpleRouter::get('/receive/', function () {
        include_once "public/receive.php";
    });

    SimpleRouter::get('/file/', function () {
        include_once "public/file.php";
    });

    SimpleRouter::form('/upload/', function () {
        include_once "public/upload/index.php";
    });

    SimpleRouter::get('/admin/', function () {
        include_once "public/admin.php";
    });

    /* Clip manipulation */

    SimpleRouter::form('/get', function () {
        include_once "public/core/get.php";
    });

    SimpleRouter::form('/set', function () {
        include_once "public/core/set.php";
    });

    /* Static pages */

    SimpleRouter::get('/desktop/', function () {
        include_once "public/desktop.php";
    });

    SimpleRouter::get('/about/', function () {
        include_once "public/about.php";
    });

    /* Auth */
    SimpleRouter::get('/login/', function () {
        include_once "public/login.php";
    });

    SimpleRouter::get('/logout/', function () {
        include_once "public/logout.php";
    });

    SimpleRouter::get('/privacy/', function () {
        include_once "public/privacy.php";
    });

    /* API */
    SimpleRouter::match(['get', 'post'], '/api/set', function () {
        include_once "public/api/set.php";
    });

    SimpleRouter::match(['get', 'post'], '/api/get', function () {
        include_once "public/api/get.php";
    });

    /* Internal behavior */

    SimpleRouter::get('/staging/change-branch', function () {
        include_once "public/change-branch.php";
    });
});

use Pecee\Http\Request;

SimpleRouter::error(function (Request $request, \Exception $exception) {
    include_once "includes/lib/functions.php";

    $currURL = $request->getUrl();
    $unRootedURL = str_replace(ROOT, "", $currURL);
    $user_code = str_replace("/", "", $unRootedURL);

    include_once "includes/components/get.php";

    if (isset($url)) {
        header("Location: $url");
    } else {
        $statusCode = $exception->getCode();
        http_response_code($statusCode);
        include_once "includes/error.php";
    }
});
// Start the routing
SimpleRouter::start();

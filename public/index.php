<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include_once "includes/header.php";
    ?>
    <title>Interclip - easy peasy clipboard sharing</title>

    <link rel="stylesheet" type="text/css" href="<?php echo ROOT ?>/css/index.css">
    <link rel="stylesheet" type="text/css" href="<?php echo ROOT ?>/css/mobile-style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo ROOT ?>/css/file.css">
    <link rel="stylesheet" type="text/css" href="<?php echo ROOT ?>/css/progressbar.css">
</head>

<body>

    <a class="skip-link" href="#maincontent">Skip to main</a>

    <?php

    /**
     * Returns a boolean value indicating whether the user is using mobile (according to their user agent)
     *
     * @return bool
     */
    function isMobile()
    {
        if (!empty($_SERVER["HTTP_USER_AGENT"])) {
            return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
        } else {
            return false;
        }
    }

    include "includes/anti-csrf.php";
    store();
    include "includes/menu.php";
    ?>
    <main id="maincontent">
        <form name="urlform" id="content" onsubmit="return validateForm()" action="<?php echo ROOT ?>/set" method="POST">
            <h2>Paste your link here!</h2>
            <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>" />
            <span class="input-wrapper">
                <input type="url" name="input" class="input square" placeholder="https://youtu.be/dQw4w9WgXcQ" id="search-input" autofocus />
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="hidden" viewBox="0 0 24 24" width="24" height="24">
                    <path d="M11.998 2.5A9.503 9.503 0 003.378 8H5.75a.75.75 0 010 1.5H2a1 1 0 01-1-1V4.75a.75.75 0 011.5 0v1.697A10.997 10.997 0 0111.998 1C18.074 1 23 5.925 23 12s-4.926 11-11.002 11C6.014 23 1.146 18.223 1 12.275a.75.75 0 011.5-.037 9.5 9.5 0 009.498 9.262c5.248 0 9.502-4.253 9.502-9.5s-4.254-9.5-9.502-9.5z"></path>
                    <path d="M12.5 7.25a.75.75 0 00-1.5 0v5.5c0 .27.144.518.378.651l3.5 2a.75.75 0 00.744-1.302L12.5 12.315V7.25z"></path>
                </svg>
                <select class="hidden history-select">
                    <option value="" selected disabled>Choose a clip</option>
                </select>
            </span>
        </form>
        <div id="modal" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
                <progress id="progressBar" value="0" max="100"></progress>
                <br>
                <span id="progressPercent">
                    0%
                </span>
                <div id="fact">
                    Inter-clippin' good!
                </div>
            </div>
        </div>
        <div class="output"></div>
        </div>
    </main>
    <script>
        const csrfToken = "<?= $_SESSION['token'] ?>";
        const isMobile = <?php echo isMobile() ? "true" : "false" ?>;
    </script>
    <script src="<?php echo ROOT ?>/js/validate.js"> </script>
    <script src="<?php echo ROOT ?>/js/index.js"> </script>
    <script src="<?php echo ROOT ?>/js/file.js"></script>
</body>

</html>
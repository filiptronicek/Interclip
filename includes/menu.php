<?php
if(!isset($prodvar)) {
    include('prod.php');
  }

$relative_path = $_SERVER['PHP_SELF'];
$index = 0;
list($scriptPath) = get_included_files();
$pages = array(
    'clip' => ['', 'Clip'],
    'file' => ['file', 'Send file'],
    'recieve' => ['recieve', 'Recieve clip'],
    'about' => ['about', 'About'],
);
if (!isProd()) {
    $scriptNameArray = explode("\\", $scriptPath);
} else {
    $scriptNameArray = explode("/", $scriptPath);
    include_once('analytics.php');
}
$currFile = end($scriptNameArray);

if ($currFile == "get.php" || $currFile == "new.php") {
    $urlPrefix = "../";
} else {
    $urlPrefix = "./";
}

// Render the menu
echo '<ul>';

//echo sizeof($pages);
foreach ($pages as $page) {

    $index++;
    //  echo $page;
    if ($page[0] == $currFile) {
        echo '<li><a class="active" href="#">' . $page[1] . '</a></li>';
    } else {
        if ($page[0] == "about") {
            echo '<li style="float:right"><a href="' . $urlPrefix . 'about">About</a></li></ul>';
        } else {
            echo '<li><a href="' . $urlPrefix . $page[0] . '">' . $page[1] . '</a></li> ';
        }
    }
}
echo '<div id="endora" style="display: none"><endora></div>';
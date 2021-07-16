<?php

include_once "lib/functions.php";

$index = 0;

$pages = array(
  'clip' => ['', 'Clip'],
  'file' => ['file', 'File <span class="beta">beta</span>'],
  'receive' => ['receive', 'Receive clip'],
  'privacy' => ['privacy', 'Privacy policy'],
);

if ($isStaff && $_ENV['ENVIRONMENT'] !== 'staging') {
  $pages += ['admin' => ['admin', 'Admin']];
}

$pages += ['about' => ['about', 'About']];

include_once "components/html/adminbar.php";

// Render the menu
echo '<ul id="menu">';

foreach ($pages as $page) {

  $index++;

  if ($page[0] !== "") {
    $page[0] = "$page[0]/";
  }

  if (strpos($page[1], '<span class="beta">') === false) {
    if ($page[0] == "about/") {
      echo '<li><a href="' . ROOT . '/about/">About</a></li><li class="triggerItem" id="triggerModal"><svg class="settingsIcon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg></li></ul>';
    } else {
      echo '<li><a href="' . ROOT . "/" . $page[0] . '">' . $page[1] . '</a></li> ';
    }
  } else {
    echo '<li style="display: none"><a href="' . ROOT . "/" . $page[0] . '">' . $page[1] . '</a></li> ';
  }
}

include_once "components/html/settings.php";

?>

<script>
  const loggedIn = <?php echo $user ? "true" : "false" ?>;
  const isAdmin = <?php echo $isStaff ? "true" : "false" ?>;
  const version = "<?php echo $release[0] ?>";

  const root = "<?php echo ROOT ?>";
</script>

<script src="<?php echo ROOT ?>/js/formatter.js"></script>
<script src="<?php echo ROOT ?>/js/menu.js"></script>
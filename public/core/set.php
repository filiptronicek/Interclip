<html lang="en">

<head>
    <?php
        include_once "includes/header.php";
    ?>
    <title>New clip | Interclip</title>

    <link rel="stylesheet" type="text/css" href="<?php echo ROOT ?>/css/new.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono&display=swap" rel="stylesheet">
</head>

<body>
  <?php
  include "includes/anti-csrf.php";
  validate();
  include_once "includes/menu.php";
  ?>

  <?php

  if (isset($_POST['input'])) {
    $url = $_POST['input'];
    $url = htmlspecialchars($url);

    include_once "includes/components/new.php";
  }
  ?>

  <script src="https://cdn.jsdelivr.net/gh/englishextra/qrjs2@latest/js/qrjs2.min.js"> </script>

  <div id="fullscreen">
    <div class="fullscreen-content">

      <div class="title">
        <?php
        if (isset($_POST['input']))
          include_once "includes/components/html/new/ok-msg.php";
        else
          include_once "includes/components/html/new/ok-msg.php";
        ?>

        <!--
        <script type="module">
          import {
            embed
          } from "https://cdn.jsdelivr.net/gh/aperta-principium/embed.js/embed.min.js";

          embed(document.getElementById("url").innerText);
        </script>
        -->
      </div>
    </div>
</body>
<html lang="en">

<head>
    <?php
    include_once "includes/header.php";
    ?>
    <title>About | Interclip</title>

    <link rel="stylesheet" type="text/css" href="<?php echo ROOT ?>/css/about.css">
</head>

<body>
    <a class="skip-link" href="#maincontent">Skip to main</a>
    <div class="nav">
        <?php
        include "includes/menu.php";

        exec('git rev-parse --verify HEAD', $output);
        $hash = $output[0];
        $hashShort = substr($hash, 0, 7);
        $commit = "https://github.com/aperta-principium/Interclip/commit/" . $hash;

        exec('git describe --abbrev=0 --tags', $release);
        ?>
    </div>
    <br>
    <main id="maincontent">
        <div class="center">
            <h1>About Interclip</h1>

            <div id="repoInfo">
                Read the <a href="https://github.com/aperta-principium/Interclip#readme">Documentation</a>
                <br />
                Take a look at Interclip's <a href="https://github.com/aperta-principium/Interclip/wiki/Legal-notices">legal notices</a>
                <br />
                <span id="release">Release: <span id="version"><?php echo $release[0]; ?> - <a href="https://github.com/aperta-principium/Interclip/releases/tag/<?php echo $release[0]; ?>">what's new?</a></span><br /></span>
                Total clips:
                <?php
                $conn = new mysqli($_ENV['DB_SERVER'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DB_NAME']);

                $sqlquery = "SELECT id FROM userurl ORDER BY ID DESC LIMIT 1";
                $result = $conn->query($sqlquery);

                $count = 0;
                while ($row = $result->fetch_assoc()) {
                    $count = $row['id'];
                    break;
                }

                echo $count;
                ?>
            </div>
        </div>
    </main>
    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

    <div class='madeBy center'>
        made with ❤️ <i class="icon ion-heart"></i> and a little bit of code by &nbsp;
        <span>
            <a href="https://github.com/filiptronicek">
                <img src="https://github.com/filiptronicek.png">
            </a>
        </span>
    </div>


</body>

</html>
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
        include_once "includes/components/rate.php";

        noteLimit();
        
        exec('git rev-parse --verify HEAD', $output);
        $hash = $output[0];
        $hashShort = substr($hash, 0, 7);
        $commit = "https://github.com/aperta-principium/Interclip/commit/" . $hash;

        exec('git describe --abbrev=0 --tags', $release);

        $conn = new mysqli($_ENV['DB_SERVER'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DB_NAME']);

        $sqlquery = "SELECT id FROM userurl ORDER BY ID DESC LIMIT 1";
        $result = $conn->query($sqlquery);

        $count = 0;
        while ($row = $result->fetch_assoc()) {
            $count = $row['id'];
            break;
        }

        ?>
    </div>
    <br>
    <main id="maincontent">
        <h1>About Interclip</h1>
        <section id="about">
            <h2>
                What is Interclip?
            </h2>
            <p>
                Interclip is a handy-dandy clipboard sharing tool to share URLs between devices and users.
                You can read on in my article
                <a href="https://docs.interclip.app/#what-is-interclip" target="_blank" rel="noopener noreferrer">
                    What even is Interclip
                </a>
                or visit
                <a href="https://docs.interclip.app/" target="_blank" rel="noopener noreferrer">
                    Interclip's docs
                </a>
                for usage guides and many other docs.
            </p>
        </section>
        <section id="libs">
            <h2>
                Interclip's code
            </h2>
            <p>
                Interclip's code is in its entirety published on
                <a href="https://github.com/aperta-principium/Interclip" target="_blank" rel="noopener noreferrer">
                    GitHub
                </a>
                . The project is mostly written in pure PHP and JS, but there are some
                <a href="https://interclip-docs.vercel.app/Legal%20notices#libraries" target="_blank" rel="noopener noreferrer">
                    libraries and designs
                </a>
                we use to make it easier upon ourselves.
            </p>
        </section>
        <section id="facts">
                <h2>
                    Facts about Interclip
                </h2>
                <ul class="facts">
                    <li>
                        Latest release: <?php echo $release[0]; ?> 
                        <a target="_blank" rel="noopener noreferrer" href="https://github.com/aperta-principium/Interclip/releases/tag/<?php echo $release[0]; ?>">
                            (changelog)
                        </a>
                    </li>
                    <li>
                        Total clips made:
                        <?php
                        echo $count;
                        ?>
                    </li>
                </ul>

                <br />
        </section>
    </main>

    <div class="madeBy">
        <p> made with ❤️ by &nbsp;</p>
        <a href="https://github.com/filiptronicek">
            <img src="https://github.com/filiptronicek.png">
        </a>
    </div>


</body>

</html>
<!DOCTYPE html>
<html lang="en">

<?php

include_once "includes/lib/init.php";
include_once "includes/lib/auth.php";

include_once "includes/lib/sentry.php";
include_once "includes/lib/functions.php";
include_once "includes/components/redis.php";

global $isWindows;

if (!$isWindows) {
    $osinfo = getOSInformation() ?? ['version' => 'Unknown'];
    exec("uname -srm", $kernel);
    exec("mysql -V", $mysqlVerOut);
    $mysqlVer = explode(" ", $mysqlVerOut[0])[3];
    $systemLoad = sys_getloadavg()[0];
    $uptime = explode(',', explode(' up ', shell_exec('uptime'))[1])[0];
} else {
    $mysqlVer = "n/a";
    $kernel = "n/a";
    $systemLoad = "n/a";
    $uptime = "n/a";
}

if ($isStaff) {

    $sqlquery = "SELECT id FROM userurl ORDER BY ID DESC LIMIT 1";
    $result = $conn->query($sqlquery);
    $count = 0;

    while ($row = $result->fetch_assoc()) {
        $count = $row['id'];
        break;
    }

    $totalLinesQuery = "SELECT SUM(TABLE_ROWS) FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'iclip'";
    $totalLinesResult = $conn->query($totalLinesQuery);
    while ($row = $totalLinesResult->fetch_assoc()) {
        $totalLines = $row['SUM(TABLE_ROWS)'];
        break;
    }

    if (!$totalLines) {
        $totalLines = 0;
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Interclip</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mvp.css@1.7.3/mvp.min.css">
    <link rel="stylesheet" href="<?php echo ROOT ?>/css/admin.css">
</head>

<body>
    <?php if ($isStaff) : ?>
        <?php
        include_once "includes/header.php";
        include_once "includes/menu.php";
        ?>
    <?php endif; ?>
    <main>
        <?php if ($_ENV['ENVIRONMENT'] === 'staging') : ?>
            <section id="intro">
                <header>
                    <h1>Sorry</h1>
                    <p>The admin page is not accesible while in staging.</p>
                </header>
            </section>
        <?php elseif ($isStaff) : ?>
            <section id="intro">
                <header>
                    <h1>Hi, <?php echo $user["name"] ?? $user["nickname"] ?></h1>
                    <p>Welcome to the Interclip admin dashboard!<sup>BETA</sup></p>
                </header>
                <aside>
                    <img alt="A lady sitting on a table" src="<?php echo ROOT ?>/img/graphics/fbdwm0.svg" height="150" />
                    <h3>Interclip files</h3>
                    <p>Total files: <strong id="files">-</strong></p>
                    <p>Total size: <strong id="filesize">-</strong></p>
                </aside>
                <aside>
                    <img alt="A server cluster" src="<?php echo ROOT ?>/img/graphics/1ahd5p.svg" height="150" />
                    <h3>Server metrics</h3>
                    <p>Server load: <strong><?php echo $systemLoad ?></strong></p>
                    <p>Storage: <strong><?php echo (formatBytes(disk_total_space('/') - disk_free_space('/'))) . "/" . (formatBytes(disk_total_space('/'))) ?></strong></p>
                    <p>Uptime: <strong><?php echo $uptime ?></strong></p>
                </aside>
                <aside>
                    <img alt="A dashboard" src="<?php echo ROOT ?>/img/graphics/hggz12.svg" height="150" />
                    <h3>Runtime details</h3>
                    <p>Used memory: <strong><?php echo formatBytes(memory_get_usage()) ?></strong></p>
                    <p>PHP version: <strong><?php echo phpversion(); ?></strong></p>
                    <p>MySQL version: <strong><?php echo $mysqlVer ?></strong></p>
                    <p>Running as: <strong><?php echo exec("whoami") ?></strong></p>
                </aside>
                <aside>
                    <img alt="A person using a laptop" src="<?php echo ROOT ?>/img/graphics/szaqt9.svg" height="150" />
                    <h3>Service stats</h3>
                    <p>Clips: <strong><?php echo $count ?></strong></p>
                    <p>Database rows: <strong><?php echo $totalLines ?></strong></p>
                    <p>Redis items: <strong><?php echo getTotal() ?></strong></p>
                </aside>
                <aside>
                    <img alt="A lady looking at a database symbol" src="<?php echo ROOT ?>/img/graphics/agmx1d.svg" height="150" />
                    <h3>Service status</h3>
                    <p>OS: <strong><?php echo PHP_OS ?></strong></p>
                    <p>OS version: <strong><?php print_r($osinfo['version']) ?></strong></p>
                    <p>Kernel: <strong><?php echo $kernel[0] ?></strong></p>
                </aside>
            </section>
        <?php elseif ($user !== false) : ?>
            <?php http_response_code(403); ?>
            <section id="intro">
                <header>
                    <h1>Yikes ¯\_(ツ)_/¯</h1>
                    <p>Sorry, but you don't have the permissions to access this resource.</p>
                </header>
            </section>
        <?php else : ?>
            <?php
            http_response_code(401);
            header("Location: " . ROOT . "/login");
            ?>
        <?php endif; ?>
    </main>
</body>
<script src="<?php echo ROOT; ?>/js/formatter.js"></script>
<script src="<?php echo ROOT; ?>/js/admin.js"></script>

</html>

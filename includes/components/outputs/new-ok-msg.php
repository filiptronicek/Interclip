<?php
    $createArray = createClip($url);
    $usr = $createArray[0];
    $err = $createArray[1];
?>

<?php if ($err === ""): ?>
    <button style="display: none;" onclick="share()" id="shareBtn">
        Share this!
    </button>

    <p><span id="url" class="url"><?php echo $url ?> </span><br><br> was saved as</p>
    <h1 class="usrCode"><?php echo $usr ?></h1>
    <div id="embed"> </div>
    <div id="qrcode"></div>
<?php else: ?>
    <p><span id="url" class="url"><?php echo $url ?> </span><br></p>
    <h1 class="usrCode"><?php echo $err ?></h1>
<?php endif; ?>

<script>
    const url = "<?php echo $url ?>";
</script>

<script src="<?php echo ROOT; ?>/js/new.js"></script>
<script type="module">
    /* MIT  Copyright (c) Feross Aboukhadijeh */

    function colorSchemeChange (onChange) {
        const media = window.matchMedia('(prefers-color-scheme: dark)')

        handleChange()

        if ('addEventListener' in media) {
            media.addEventListener('change', handleChange)
        } else if ('addListener' in media) {
            media.addListener(handleChange)
        }

        function handleChange () {
            const scheme = media.matches
            ? 'dark'
            : 'light'
            onChange(scheme)
        }
    }

    /* End of copyrighted code, code from https://github.com/feross/color-scheme-change */

    const update = (scheme) => {
        options = {
            text: "https://interclip.app/<?php echo $usr ?>",
            background: scheme === 'dark' ? "#444444" : "#ff9800",
            foreground: scheme === 'dark' ? "#e4e4e4" : "#000000",
        }
        document.getElementById('qrcode').innerHTML = "";
        $('#qrcode').qrcode(options);
    }


    const style = window
        .getComputedStyle(document.documentElement)
        .getPropertyValue('content')
        .replace(/"/g, '')

    const computedStyle = localStorage.getItem("dark-mode-toggle");
    
    let options;

    if (computedStyle === null || computedStyle === "light") {
        options = {
            text: "https://https://interclip.app/<?php echo $usr ?>",
            background: "#ff9800",
            foreground: "#000000",
        }
    } else if (computedStyle == "dark") {
        options = {
            text: "https://interclip.app/<?php echo $usr ?>",
            background: "#444444",
            foreground: "#e4e4e4",
        }
    }
    $('#qrcode').qrcode(options);

    colorSchemeChange(colorScheme => {
        const switcherScheme = localStorage.getItem("dark-mode-toggle");
        update(switcherScheme ? colorScheme : switcherScheme);
    })

    document.querySelector("#dark-mode-toggle-1").addEventListener('click', function() {
        setTimeout(() => {
            update(localStorage.getItem("dark-mode-toggle"));
        }, 20);
    });

    update(localStorage.getItem("dark-mode-toggle")) || style;

</script>
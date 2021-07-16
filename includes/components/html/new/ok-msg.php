<?php
$createArray = createClip($url);
$usr = $createArray[0];
$err = $createArray[1];
?>

<?php if ($err === "") : ?>
    <p><span id="url" class="url"><?php echo $url ?> </span><br><br> was saved as</p>
    <h1 class="usrCode"><?php echo $usr ?></h1>
    <div id="embed"> </div>
    <div id="qrcode"></div>
<?php else : ?>
    <p><span id="url" class="url"><?php echo $url ?> </span><br></p>
    <h1 class="usrCode"><?php echo $err ?></h1>
<?php endif; ?>


<script type="module">
    /* MIT  Copyright (c) Feross Aboukhadijeh */

    function colorSchemeChange(onChange) {
        const media = window.matchMedia('(prefers-color-scheme: dark)')

        handleChange()

        if ('addEventListener' in media) {
            media.addEventListener('change', handleChange)
        } else if ('addListener' in media) {
            media.addListener(handleChange)
        }

        function handleChange() {
            const scheme = media.matches ?
                'dark' :
                'light'
            onChange(scheme)
        }
    }

    /* End of copyrighted code, code from https://github.com/feross/color-scheme-change */

    const update = (scheme) => {

        const style = window
            .getComputedStyle(document.documentElement)
            .getPropertyValue('content')
            .replace(/"/g, '')

        if (scheme === null || scheme === "system") {
            scheme = style;
        }

        document.getElementById("qrcode").innerHTML = "";
        const qr = QRCode.generateSVG("https://interclip.app/<?php echo $usr ?>", {
            ecclevel: "M",
            fillcolor: scheme === 'dark' ? "#151515" : "#157EFB",
            textcolor: "#e4e4e4",
            margin: 0,
            modulesize: 4
        });
        document.getElementById("qrcode").appendChild(qr);
    }

    const computedStyle = localStorage.getItem("dark-mode-toggle");

    let options;

    update(computedStyle);

    colorSchemeChange(colorScheme => {
        const switcherScheme = localStorage.getItem("dark-mode-toggle");
        update(switcherScheme ? colorScheme : switcherScheme);
    });

    const toggle = document.getElementById("slct");

    toggle.addEventListener('change', () => {
        update(toggle.value);
    });

    const initialValue = localStorage.getItem("recentClips");
    const url = "<?php echo $url ?>";

    if (initialValue) {
        const recentlyMadeArray = JSON.parse(initialValue);
        if (!recentlyMadeArray.includes(url)) {
            recentlyMadeArray.push(url);
        }
        if (recentlyMadeArray.length > 6) {
            const reversedRecents = recentlyMadeArray.reverse();
            reversedRecents.pop();
            localStorage.setItem("recentClips", JSON.stringify(reversedRecents.reverse()));
        } else {
            localStorage.setItem("recentClips", JSON.stringify(recentlyMadeArray));
        }
    } else {
        localStorage.setItem("recentClips", JSON.stringify([url]))
    }
</script>
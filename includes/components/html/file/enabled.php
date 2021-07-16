<span id="content"> </span>
<div class="title">
    <h1>Upload a file to Interclip</h1>
    <p class="note">Upload any file type that's under 100MB.</p>
    <div class="demo-droppable" id="dropzone">
        <p>Drag files here or click to upload</p>
    </div>

    <div id="modal" class="modal">

        <!-- Modal content -->
        <div class="modal-content">
            <p>
                <progress id="progressBar" value="0" max="100"></progress>
                <br>
                <span id="progressPercent">
                    0%
                </span>
                <div id="fact">
                    Inter-clippin' good!
                </div>
            </p>
        </div>

    </div>
    <div class="output"></div>
</div>

<script>
    const csrfToken = "<?= $_SESSION['token'] ?>";
</script>
<script src="<?php echo ROOT ?>/js/file.js"></script>
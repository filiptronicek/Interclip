const shareButton = document.getElementById("shareBtn");

if (navigator.share) {
    shareButton.style.display = "block";
}

function share() {
    if (navigator.share) {
        navigator.share({
            url,
            title: "Interclip",
            text: "A URL shared with Interclip",
        })
            .catch((error) => console.log("Sharing failed", error));
    } else {
        Swal.fire(
            "Yikes!",
            "Your system doesn't support sharing files.",
            "error"
        );
    }
}

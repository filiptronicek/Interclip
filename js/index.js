const historyBtn = document.querySelector("#content > .input-wrapper > svg");
const selectDropdown = document.querySelector(".history-select")
const linkInput = document.getElementById("search-input");
const form = document.getElementById("content");

historyBtn.onclick = () => {
    selectDropdown.classList.toggle("hidden");
}

selectDropdown.onchange = (e) => {
    linkInput.value = e.target.value;
    form.submit();
}

const getEntries = () => {
    const initialValue = localStorage.getItem("recentClips");
    return initialValue ? JSON.parse(initialValue) : [];
}

const recentlyMade = getEntries();

if (recentlyMade.length > 0) {
    historyBtn.classList.remove("hidden")
    for (const clip of recentlyMade) {
        const option = document.createElement("option");
        option.value = clip;
        option.innerText = clip;
        selectDropdown.append(option);
    }
}

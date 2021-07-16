function placeHolder() {
  const hash = Math.random()
    .toString(36)
    .substr(2, 5);

  document.querySelector("#code").setAttribute("placeholder", hash);
}

function printOutText(text) {
  document.querySelector("#result").innerText = text;
}

if (!localStorage.getItem("hideHashAnimation")) {
  setInterval(() => {
    placeHolder();
  }, 500);
  placeHolder();
}

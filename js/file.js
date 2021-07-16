const modal = document.getElementById("modal");
const output = document.querySelector(".output");
const fact = document.getElementById("fact");
const dropzone = document.getElementById("dropzone");

const fileSizeLimitInMegabytes = 100;
const fileSizeLimitInBytes = fileSizeLimitInMegabytes * 1048576;

function encodeHTML(s) {
  return s.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/"/g, "&quot;");
}

const submitClip = (url) => {
  output.innerHTML += `
  <form id="clip" action="/set" method="POST">
    <input type="hidden" name="token" value="${csrfToken}"/>
    <input type="url" name="input" value="${url}">
    <input type="submit">
  </form>`;
  document.getElementById("clip").submit();
}

function showCode(data) {
  data = encodeHTML(data);

  modal.style.display = "none";
  submitClip(data);
}

const progressBar = document.getElementById("progressBar");
const progressValue = document.getElementById("progressPercent");

function uploadRe($files) {
  // Begin file upload
  const request = new XMLHttpRequest();
  request.upload.onprogress = (event) => {
    progressValue.innerText = `${Math.round(
      (event.loaded / event.total) * 100
    )}%`;
    progressBar.value = (event.loaded / event.total) * 100;
  };

  request.onreadystatechange = () => {
    if (request.readyState == XMLHttpRequest.DONE) {
      const data = request.responseText;
      const jsonData = JSON.parse(data);
      if (jsonData.status === "error") {
        Swal.fire("Something's went wrong", jsonData.result, "error").then(
          () => {
            location.reload();
          }
        );
      } else {
        const link = jsonData.result;
        showCode(link);
      }
    }
  };
  // API Endpoint
  const apiUrl = `${root || ""}/upload/?api`;

  const formData = new FormData();
  formData.append("uploaded_file", $files);

  request.open("POST", apiUrl);
  request.send(formData);

  modal.style.display = "block";
  document.querySelector(".demo-droppable").style.display = "none";
}

((window) => {
  function triggerCallback(e, callback) {
    if (!callback || typeof callback !== "function") {
      return;
    }

    const urls = new Set();

    // "Borrowed" from https://github.com/thinkverse/draggable/blob/ddb6d6ff23ef80fb60f80d4119586f4b0902e8f5/src/draggable.ts#L40-L46
    for (const item of e.dataTransfer.items) {
      if (["text/uri-list", "text/plain"].includes(item.type)) {
        urls.add(e.dataTransfer.getData("URL"));
        continue;
      }
    }

    const firstURL = urls.values().next().value;
    if (urls.length !== 0 && firstURL && firstURL !== "") {
      submitClip(firstURL);
      return;
    }

    let files;
    if (e.dataTransfer) {
      files = e.dataTransfer.files;
    } else if (e.target) {
      files = e.target.files;
    }
    if (files.length > 0) {
      callback.call(null, files);
    }
  }

  window.fileOver = false;

  function makeDroppable(ele, callback) {
    const input = document.createElement("input");
    input.setAttribute("type", "file");
    input.setAttribute("multiple", false);
    input.style.display = "none";
    input.addEventListener("change", (e) => {
      triggerCallback(e, callback);
    });
    ele.appendChild(input);

    ele.addEventListener("dragover", (e) => {
      e.preventDefault();
      e.stopPropagation();
      fileOver = true;
      if (dropzone) {
        dropzone.classList.add("dragover");
      }
      ele.classList.add("dragover");
    });

    ele.addEventListener("dragleave", (e) => {
      e.preventDefault();
      e.stopPropagation();
      fileOver = false;
      setInterval(() => {
        if (!fileOver) {
          ele.classList.remove("dragover");
          if (dropzone) {
            dropzone.classList.remove("dragover");
          }
        }
      }, 100);
    });

    ele.addEventListener("drop", (e) => {
      e.preventDefault();
      e.stopPropagation();
      fileOver = false;
      ele.classList.remove("dragover");
      triggerCallback(e, callback);
    });

    if (dropzone) {
      dropzone.onclick = () => {
        input.value = null;
        input.click();
      };
    }
  }
  window.makeDroppable = makeDroppable;
})(this);

(() => {
  makeDroppable(document.body, (files) => {
    console.log(files);
    document.getElementById("content").style.display = "none";
    output.innerHTML = "";

    const file = files[0];

    if (file.type.indexOf("image/") === 0) {
      output.innerHTML += `<img width="200" src="${URL.createObjectURL(
        file
      )}" />`;
    }
    output.innerHTML += `<p>${file.name}</p>`;

    if (file.size > fileSizeLimitInBytes) {
      Swal.fire(
        "Something's went wrong",
        `Your file is ${formatBytes(
          file.size
        )}, which is over the limit of ${fileSizeLimitInMegabytes}MB`,
        "error"
      ).then(() => {
        location.reload();
      });
    }
    uploadRe(file);
  });

  document.onpaste = function (event) {
    const items = (event.clipboardData || event.originalEvent.clipboardData)
      .items;
    for (const item of items) {
      if (item.kind === "file") {
        const blob = item.getAsFile();
        uploadRe(blob);
      }
    }
  };
})(this);

window.onload = () => {
  fetch("https://interclips.filiptronicek.workers.dev/")
    .then((res) => res.text())
    .then((res) => {
      fact.innerText = res;
    });
};

// Get the modal
const settingsModal = document.getElementById("settingsModal");
const darkModeToggle = document.querySelector("dark-mode-toggle");

// Get the button that opens the modal
const btn = document.getElementById("triggerModal");

// The delete data button
const removeBtn = document.getElementById("removeData");

// Get the <span> element that closes the modal
const span = document.getElementsByClassName("closeBtn")[0];

// Get the toggle checkbox
const colorSchemePreference = document.querySelector("#slct");
const toggle = document.querySelector("#hashanimation");
const betaToggle = document.querySelector("#betafeatures");

// Get the system value
const systemOpt = document.getElementById("systemOption");

const userAgent = navigator.userAgent.toLowerCase();

const isTablet =
  /(ipad|tablet|(android(?!.*mobile))|(windows(?!.*phone)(.*touch))|kindle|playbook|silk|(puffin(?!.*(IP|AP|WP))))/.test(
    userAgent
  );

if (loggedIn && isAdmin) {
  const adminbar = document.querySelector("#adminbar");
  adminbar.style.display = localStorage.getItem("adminbarVisible") || "flex";
}

const isPhone = !isTablet
  ? "ontouchstart" in document.documentElement &&
    /mobi/i.test(navigator.userAgent)
  : false;

function showPaintTimings() {
  if (!loggedIn) {
    return null;
  }
  if (window.performance) {
    const performance = window.performance;
    const performanceEntries = performance.getEntriesByType("paint");
    performanceEntries.forEach((performanceEntry) => {
      if (performanceEntry.name === "first-contentful-paint") {
        const paintTime = performanceEntry.startTime;
        document.getElementById("load").innerText = `Paint: ${Math.round(
          paintTime
        )}ms`;
      }
    });
  } else {
    console.log("Performance timing isn't supported.");
  }
}

// When the user clicks the button, open the modal
btn.onclick = () => {
  settingsModal.style.display = "block";
};

colorSchemePreference.addEventListener("change", function () {
  if (this.value === "system") {
    const isDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
    darkModeToggle.mode = isDark ? "dark" : "light";
    localStorage.removeItem("dark-mode-toggle");
  } else {
    darkModeToggle.mode = this.value;
    localStorage.setItem("dark-mode-toggle", this.value);
  }
});

toggle.addEventListener("change", function () {
  if (this.checked) {
    localStorage.removeItem("hideHashAnimation");
  } else {
    localStorage.setItem("hideHashAnimation", "true");
  }
});

betaToggle.addEventListener("change", function () {
  if (this.checked) {
    localStorage.removeItem("hideBetaMenu");
  } else {
    localStorage.setItem("hideBetaMenu", "true");
  }
  updateMenu();
});

/* Initialization */

systemOpt.innerText = systemOpt.innerText += ` ${
  isTablet ? "📱" : isPhone ? "📱" : "💻"
}`;

const updateOptions = () => {
  colorSchemePreference.value =
    localStorage.getItem("dark-mode-toggle") || "system";

  toggle.checked = !localStorage.getItem("hideHashAnimation");

  betaToggle.checked = !localStorage.getItem("hideBetaMenu");
};

// When the user clicks on <span> (x), close the modal
span.onclick = () => {
  settingsModal.style.display = "none";
};

// When the user clicks anywhere outside of the modal, close it
document.onclick = (event) => {
  if (event.target == settingsModal) {
    settingsModal.style.display = "none";
  }
};

const updateMenu = () => {
  for (const li of document.querySelectorAll("#menu li")) {
    if (li?.children[0]?.children[0]?.classList.contains("beta")) {
      li.style.display = localStorage.getItem("hideBetaMenu")
        ? "none"
        : "block";
    }
  }
};

console.log(
  `%c
    ▄▄▄ ▄▄    ▄ ▄▄▄▄▄▄▄ ▄▄▄▄▄▄▄ ▄▄▄▄▄▄   ▄▄▄▄▄▄▄ ▄▄▄     ▄▄▄ ▄▄▄▄▄▄▄ 
    █   █  █  █ █       █       █   ▄  █ █       █   █   █   █       █
    █   █   █▄█ █▄     ▄█    ▄▄▄█  █ █ █ █       █   █   █   █    ▄  █
    █   █       █ █   █ █   █▄▄▄█   █▄▄█▄█     ▄▄█   █   █   █   █▄█ █
    █   █  ▄    █ █   █ █    ▄▄▄█    ▄▄  █    █  █   █▄▄▄█   █    ▄▄▄█
    █   █ █ █   █ █   █ █   █▄▄▄█   █  █ █    █▄▄█       █   █   █    
    █▄▄▄█▄█  █▄▄█ █▄▄▄█ █▄▄▄▄▄▄▄█▄▄▄█  █▄█▄▄▄▄▄▄▄█▄▄▄▄▄▄▄█▄▄▄█▄▄▄█    %c ${version}`,
  "color:#FF9800",
  "color:green;font-weight:bold"
);

if (loggedIn && isAdmin) {
  if (document.getElementById("adminbar").classList.contains("staging")) {
    document.getElementById("branch-select").addEventListener("change", (e) => {
      const targetBranch = e.target.value.replace(/\s/g, "");
      if (targetBranch !== "-") {
        fetch(`${root}/staging/change-branch?branch=${targetBranch}`)
          .then((res) => res.json())
          .then(() => {
            location.reload();
          })
          .catch((err) => {
            Swal.fire("Something's went wrong", err.toString(), "error");
          });
      }
    });
  }

  document.addEventListener("keydown", (e) => {
    if (e.shiftKey && e.code === "KeyB") {
      e.preventDefault();
      const displayStatus = adminbar.style.display === "flex" ? "none" : "flex";

      let newColor = "#262626";
      if (displayStatus === "flex") {
        if (document.getElementById("adminbar").classList.contains("staging")) {
          newColor = "#F15922";
        } else {
          newColor = "#588D6A";
        }
      }
      document
        .querySelector("meta[name=theme-color]")
        .setAttribute("content", newColor);
      adminbar.style.display = displayStatus;
      localStorage.setItem("adminbarVisible", displayStatus);
    }
  });
} else if (loggedIn && !isAdmin) {
  document.addEventListener("keydown", (e) => {
    e.preventDefault();
    if (e.shiftKey && e.code === "KeyB") {
      Swal.fire(
        "Permission error",
        "Yikes! It seems you have to be an admin to view the admin bar. Want to be an admin? Tweet me @filiptronicek",
        "error"
      );
    }
  });
}

removeBtn.onclick = () => {
  const isDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
  localStorage.clear();
  updateOptions();
  darkModeToggle.mode = isDark ? "dark" : "light";

  Swal.fire({
    icon: "success",
    title: "All local data has been deleted",
    showConfirmButton: false,
    timer: 1700,
  });
};

updateMenu();

window.addEventListener("load", () => {
  showPaintTimings();
  updateOptions(); // Rerender the option values
});

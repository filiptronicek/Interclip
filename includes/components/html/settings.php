<dark-mode-toggle id="dark-mode-toggle-1"></dark-mode-toggle>

<!-- The Modal -->
<div id="settingsModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="closeBtn">&times;</span>
    <h2>Settings</h2>
    <p>Here you can change some of Interclip's settings.</p>
    <h3>Color scheme</h3>
    <!-- Rounded switch -->
    <div class="select">
      <select name="slct" id="slct">
        <option value="dark">Dark 🌑</option>
        <option value="light">Light ☀️</option>
        <option id="systemOption" value="system">System</option>
      </select>
    </div>
    <h3>Hash animations</h3>
    <p>Toggle the animation of the random hash on the receive page.</p>
    <div class="flex">
      <span class="toggleLabel">Off</span>
      <!-- Rounded switch -->
      <label class="switch">
        <input type="checkbox" id="hashanimation">
        <span class="slider round"></span>
      </label>
      <span class="toggleLabel">On</span>
    </div>
    <h3>Beta menu</h3>
    <p>Hide or show Interclip's beta features in the menu.</p>
    <div class="flex">
      <span class="toggleLabel">Hide</span>
      <!-- Rounded switch -->
      <label class="switch">
        <input type="checkbox" id="betafeatures">
        <span class="slider round"></span>
      </label>
      <span class="toggleLabel">Show</span>
    </div>
    <h3>Danger zone</h3>
    <p>Clear all the local data including settings and recently made clips.</p>
    <button class="btn-remove" id="removeData">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
      </svg>
      <span>
        Remove all data
      </span>
    </button>
  </div>

</div>
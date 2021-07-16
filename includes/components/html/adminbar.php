<?php
if (empty($user)) {
  if ($_ENV['AUTH_TYPE'] === "account") {
    if ($auth0->getUser()) {
      $user = $auth0->getUser();
    } else {
      $user = false;
      $isStaff = false;
    }
  }
}

exec('git describe --abbrev=0 --tags', $release);
if ($user !== false) {
  if ($isStaff) {
    exec('git rev-parse --verify HEAD', $output);
    $hash = $output[0];
    $hashShort = substr($hash, 0, 7);
    $commit = "https://github.com/aperta-principium/Interclip/commit/" . $hash;
  }
}

$renderTimeMicro = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
$renderTime = number_format($renderTimeMicro * 1000, 2);

$branches = getBranches();
$currBranch = $branches["current"];

?>
<?php if (!is_bool($user) && $isStaff) : ?>
  <div id="adminbar" <?php echo $_ENV['ENVIRONMENT'] === "staging" ? "class='staging'" : "" ?>>
    <span title="The total time it took the client to render the DOM and fetch all the necessary resources" id="load">Client: TBD</span>
    <span title="The total time it took the server to process the request">Server: <?php echo $renderTime ?>ms</span>
    <span class="lg" title="The current response status code">HTTP <?php echo http_response_code() ?></span>
    <?php if ($_ENV['ENVIRONMENT'] === "staging") : ?>
      <?php $branches = getBranches(); ?>
      <span>Current branch:
        <select id="branch-select">
          <?php
          echo "<option value='-'>$currBranch</option>";
          foreach ($branches["all"] as $branch) {
            echo "<option value='$branch'>$branch</option>";
          }
          ?>
        </select>
      </span>
    <?php endif; ?>
    <span>
      <a title="View branch on GitHub" href="https://github.com/aperta-principium/Interclip/tree/<?php echo $currBranch ?>">
        <?php echo $currBranch ?>
      </a>
      @
      <a title="View commit on GitHub" href="https://github.com/aperta-principium/Interclip/commit/<?php echo $hash ?>">
        <?php echo $hashShort ?>
      </a>
    </span>
    <span class="lg">PHP <?php echo phpversion(); ?></span>
    <span class="lg">Memory: <?php echo formatBytes(memory_get_usage()) ?></span>
    <span class="ending lg">
      Hi, <?php echo $user["name"] ?? $user["nickname"]  ?>
      <a class="subitem" href="<?php echo ROOT ?>/logout">Log out</a>
    </span>
  </div>
<?php endif; ?>

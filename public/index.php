<?php
include '../init.php';

$lh = new LoginHelper($config);

// Application logic
if (devEnv() && !empty($_GET['slack_id'])) {
    if ($lh->validateLogin($_GET['slack_id'])) {
        header("Location: dashboard.php");
    } else {
        $_SESSION['error'][0] = "Incorrect credentials";
    }
} elseif ($_GET['action'] == 'logout') {
    $lh->logout();
} elseif (!empty($_COOKIE['broskies_portal'])) {
    if ($lh->validateLogin($_COOKIE['broskies_portal'])) {
        header("Location: dashboard.php");
    }
}

if (!empty($_SESSION['token'])) {
    header("Location: dashboard.php");
    die();
}

// Site/page boilerplate
$site = new site('GTDU');
init_site($site);

$page = new page();
$site->setPage($page);

// Start rendering the content
ob_start();

?>
<div class="container mt-3">
    <h1>GTDU Broskies Portal</h1>
    <a href="https://slack.com/oauth/authorize?scope=identity.basic&client_id=<?php echo $config['slack_id']; ?>"><img src="https://api.slack.com/img/sign_in_with_slack.png"  alt=""/></a>
</div>
<?php

// End rendering the content
$content = ob_get_clean();
$page->setContent($content);

$site->render();
?>

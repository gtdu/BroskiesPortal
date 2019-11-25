<?php
include '../init.php';

$lh = new LoginHelper($config);

// Application logic
if (!empty($_POST)) {
    if ($lh->validateLogin($_POST)) {
        header("Location: dashboard.php");
    } else {
        $errors[] = "Incorrect credentials";
    }
} elseif ($_GET['action'] == 'logout') {
    $lh->logout();
}

if (!empty($_SESSION['token'])) {
    header("Location: dashboard.php");
    die();
}

// Site/page boilerplate
$site = new site('GTDU', $errors);
init_site($site);

$page = new page();
$site->setPage($page);

// Start rendering the content
ob_start();

?>
<div class="container mt-3">
    <form method="post">
        <div class="form-group">
            <label for="loginEmail">Email</label>
            <input name="email" type="email" class="form-control" id="loginEmail" aria-describedby="emailHelp" placeholder="dude@email.org" required>
        </div>
        <div class="form-group">
            <label for="loginPassword">Password</label>
            <input name="password" type="password" class="form-control" id="loginPassword" aria-describedby="emailHelp" placeholder="DikiaCunt" required>
        </div>
        <input type="hidden" name="action" value="resetPassword">
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
    <div class="mt-3"><a onclick="alert('Please email the Member at Large (webmaster@rybel-llc.com) for assistance!');">Forgot Password?</a></div>
</div>
<?php

// End rendering the content
$content = ob_get_clean();
$page->setContent($content);

$site->render();
?>

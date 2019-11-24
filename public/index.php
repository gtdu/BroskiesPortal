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

// Site/page boilerplate
$site = new site('GTDU', $errors);
init_site($site);

$page = new page();
$site->setPage($page);

// $site->addHeader("../includes/navbar.php");

// Start rendering the content
ob_start();

?>
<form method="post">
    <div>
        <div>Username:</div>
        <input name="email" type="email" class="textinp" value="<?php echo $_SESSION['username']; ?>">
    </div>
    <br />
    <div>
        <div>Password:</div>
        <input name="password" type="password" >
    </div>
    <input name="submit" type="submit" value="Login" class="confirm">
    <p><a onclick="alert('Please email the Member at Large (webmaster@rybel-llc.com) for assistance!');">Forgot Password?</a></p>
</form>
<?php

// End rendering the content
$content = ob_get_clean();
$page->setContent($content);

$site->render();
?>

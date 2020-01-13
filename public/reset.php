<?php
include '../init.php';

$helper = new LoginHelper($config);

// Application logic
if ($_POST['action'] == "sendCode") {
    $helper->sendPasswordReset($_POST['email']);
    header("Location: index.php");
    die();
} else if ($_POST['action'] == 'resetPassword') {
    $helper->resetUserPassword($_POST['code'], $_POST['password']);
    header("Location: index.php");
    die();
}

// Site/page boilerplate
$site = new site('GTDU', $errors);
init_site($site);

$page = new page();
$site->setPage($page);

// Start rendering the content
ob_start();

echo '<div class="container mt-3">';

if (isset($_GET['code'])):

?>
<form method="post">
    <div class="form-group">
        <label for="loginEmail">Code</label>
        <input name="code" type="text" class="form-control" id="loginEmail" aria-describedby="emailHelp" placeholder="123456789" required value="<?php echo $_GET['code']; ?>">
    </div>
    <div class="form-group">
        <label for="loginPassword">Password</label>
        <input name="password" type="password" class="form-control" id="loginPassword" aria-describedby="emailHelp" placeholder="DikiaBrother" required>
    </div>
    <input type="hidden" name="action" value="resetPassword">
    <button type="submit" class="btn btn-primary">Reset Password</button>
</form>
<?php else: ?>
<form method="post">
    <div class="form-group">
        <label for="loginEmail">Email</label>
        <input name="email" type="email" class="form-control" id="loginEmail" aria-describedby="emailHelp" placeholder="dude@email.org" required value="<?php echo $_GET['email']; ?>">
    </div>
    <input type="hidden" name="action" value="sendCode">
    <button type="submit" class="btn btn-primary">Send Reset Email</button>
</form>
<?php
endif;

echo "</div>";

// End rendering the content
$content = ob_get_clean();
$page->setContent($content);

$site->render();
?>

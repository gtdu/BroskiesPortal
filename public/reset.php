<?php
include '../init.php';

$helper = new LoginHelper($config);

// Application logic
if ($_POST['action'] == "sendCode") {
    if ($helper->sendPasswordReset($_POST['email'])) {
        $_SESSION['success'] = true;
        header("Location: ?");
        die();
    } else {
        $_SESSION['error'][0] = $helper->getErrorMessage();
        header("Location: ?");
        die();
    }
} elseif ($_POST['action'] == 'resetPassword') {
    if ($helper->resetUserPassword($_POST['code'], $_POST['password'])) {
        $_SESSION['success'] = true;
        header("Location: index.php");
        die();
    else {
        $_SESSION['error'][0] = $helper->getErrorMessage();
        header("Location: ?");
        die();
    }
}

// Site/page boilerplate
$site = new site('GTDU', $errors);
init_site($site);

$page = new page();
$site->setPage($page);

// Start rendering the content
ob_start();

echo '<div class="container mt-3">';

if (isset($_GET['code'])) {
    include_once("../components/useResetCodeForm.php");
} else {
    include_once("../components/requestResetCodeForm.php");
}

echo "</div>";

// End rendering the content
$content = ob_get_clean();
$page->setContent($content);

$site->render();

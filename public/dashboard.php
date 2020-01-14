<?php
include '../init.php';

// Site/page boilerplate
$site = new site('Broskies Portal');
init_site($site);

$page = new page(true);
$site->setPage($page);

$helper = new AdminHelper($config);

if ($_POST['action'] == 'resetPassword') {
    if ($helper->resetUserPassword($_POST['user'], $_POST['password'])) {
        $_SESSION['success'] = true;
    } else {
        $_SESSION['error'][0] = $site->getSQLError();
    }
    header("Location: ?");
    die();
} else if ($_POST['action'] == 'updateConfig') {
    if ($helper->updateDynamicConfig($_POST['key'], $_POST['value'])) {
        $_SESSION['success'] = true;
    } else {
        $_SESSION['error'][0] = $site->getSQLError();
    }
    header("Location: ?");
    die();
}

// Start rendering the content
ob_start();

include_once("../includes/navbar.php");

if (isset($_GET['page'])): ?>
<div class="d-flex content container">
    <iframe src="<?php echo $helper->getModule($_GET['page'])['root_url'] . "?session_token=" . $_SESSION['token']; ?>" class="flex-fill"></iframe>
</div>
<?php else: ?>
    <div class="content container">
        <?php
        // Render dynamic login message
        $loginMessage = $helper->getDynamicConfig()['HOME_MESSAGE'];
        if ($loginMessage != NULL) {
            echo '<h3 class="mt-5 text-center">' . $loginMessage . '</h3>';
        }
        ?>
        <h2 class="mt-5 text-center" style="width: 100%">Select a module from the above menu to get started</h2></br>
        <h5 class="text-center" style="width: 100%">or</h5>
        <div class="pl-4 pr-4 mb-4">
            <form method="post">
                <div class="form-group">
                    <label for="newUserPassword">Change Your Password</label>
                    <input name="password" type="text" class="form-control" id="newUserPassword" aria-describedby="emailHelp" placeholder="DikaiaBrother69" required>
                </div>
                <input type="hidden" name="user" value="<?php echo $site->userID; ?>">
                <input type="hidden" name="action" value="resetPassword">
                <button type="submit" class="btn btn-primary">Update Password</button>
            </form>
        </div>
        <?php
        if ($site->userCorePem > 1) {
            ?>
            <hr/>
            <div class="pl-4 pr-4 mb-4">
                <form method="post">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Set Home Message</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="value" placeholder="HTML is supported"><?php echo $helper->getDynamicConfig()['HOME_MESSAGE']; ?></textarea>
                    </div>
                    <input type="hidden" name="action" value="updateConfig">
                    <input type="hidden" name="key" value="HOME_MESSAGE">
                    <button type="submit" class="btn btn-primary">Update Message</button>
                </form>
            </div>
            <?php
        }
        ?>
    </div>
    <?php

endif;

// End rendering the content
$content = ob_get_clean();
$page->setContent($content);

$site->render();
?>

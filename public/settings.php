<?php
include '../init.php';

// Site/page boilerplate
$site = new site('Broskies Portal');
init_site($site);

$page = new page(true);
$site->setPage($page);

$helper = new AdminHelper($config);

if ($_POST['action'] == 'changePhone') {
    if ($helper->setUserPhone($_POST['user'], $_POST['phone'])) {
        $_SESSION['success'] = true;
        header("Location: ?");
        die();
    } else {
        $_SESSION['error'][0] = $helper->getErrorMessage();
        header("Location: ?");
        die();
    }
} else if ($_POST['action'] == 'updateConfig') {
    if ($helper->updateDynamicConfig($_POST['key'], $_POST['value'])) {
        $_SESSION['success'] = true;
        header("Location: ?");
        die();
    } else {
        $_SESSION['error'][0] = $helper->getErrorMessage();
        header("Location: ?");
        die();
    }
}

// Start rendering the content
ob_start();

include_once("../includes/navbar.php");

?>
    <div class="content container">
        <div class="pl-4 pr-4 mb-4 mt-4">
            <form method="post">
                <div class="form-group">
                    <label for="newUserPassword">Change Your Phone Number <i>(digits only)</i></label>
                    <input name="phone" type="tel" class="form-control" id="newUserPassword" aria-describedby="aria" placeholder="6784206969" value="<?php echo $helper->getUserByID($site->userID)['phone']; ?>" required pattern='\d{10}'>
                </div>
                <input type="hidden" name="user" value="<?php echo $site->userID; ?>">
                <input type="hidden" name="action" value="changePhone">
                <button type="submit" class="btn btn-primary">Update Phone Number</button>
            </form>
        </div>
        <?php
        // Check if they are core admins
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
            </div><hr/>
            <div class="pl-4 pr-4 mb-4">
                <form method="post">
                    <div class="form-group">
                        <label for="deleteModuleModule">Set Default Module</label>
                        <select name="value" required id="deleteModuleModule" class="form-control">
                            <option value="-1">Resources</option>
                            <?php
                            $modules = $helper->getModules();
                            $current = $helper->getDynamicConfig()['DEFAULT_MODULE'];
                            foreach ($modules as $m) {
                                if ($m['id'] != 1) {
                                    echo "<option value='" . $m['id'] . "'";
                                    if ($m['id'] == $current) {
                                        echo " selected";
                                    }
                                    echo ">" . $m['name'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <input type="hidden" name="action" value="updateConfig">
                    <input type="hidden" name="key" value="DEFAULT_MODULE">
                    <button type="submit" class="btn btn-primary">Update Default</button>
                </form>
            </div>
            <?php
        }
        ?>
    </div>
    <?php

// End rendering the content
$content = ob_get_clean();
$page->setContent($content);

$site->render();
?>

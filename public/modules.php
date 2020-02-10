<?php
include '../init.php';

// Site/page boilerplate
$site = new site('Broskies Portal');
init_site($site);

$page = new page(true);
$site->setPage($page);

$helper = new AdminHelper($config);
$modules = $helper->getModules();

if ($_POST['action'] == 'newModule') {
    if ($helper->createNewModule($_POST['name'], $_POST['root_url'], $_POST['external'], $_POST['icon_url'], $_POST['levelNames'])) {
        $_SESSION['success'] = true;
        header("Location: ?");
        die();
    } else {
        $_SESSION['error'][0] = $helper->getErrorMessage();
        header("Location: ?");
        die();
    }
} elseif ($_POST['action'] == 'deleteModule') {
    if ($helper->deleteModule($_POST['module_id'])) {
        $_SESSION['success'] = true;
        header("Location: ?");
        die();
    } else {
        $_SESSION['error'][0] = $helper->getErrorMessage();
        header("Location: ?");
        die();
    }
} elseif ($_POST['action'] == 'editModule') {
    if ($helper->editModule($_POST['module_id'], $_POST['root_url'], $_POST['external'], $_POST['icon_url'], $_POST['levelNames'])) {
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
<div class="container">
    <h1 class="mt-2">Manage Modules</h1>
    <?php

    // Show the correct form
    if ($_GET['action'] == 'delete') {
        $item = $helper->getModuleByID($_GET['id']);
        include_once("../components/deleteModuleForm.php");
    } elseif ($_GET['action'] == 'create') {
        include_once("../components/newModuleForm.php");
    } elseif ($_GET['action'] == 'edit') {
        $item = $helper->getModuleByID($_GET['id']);
        include_once("../components/changeModuleForm.php");
    } else {
        echo '<a href="?action=create" role="button" class="btn btn-success mb-3">Create Resource</a>';
    }
    ?>

    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th>Name</th>
                <th>API Token</th>
                <th>Root URL</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <?php
        // Render the modules
        foreach ($modules as $m) {
            if ($m['id'] != 1) {
                echo "<tr>";
                echo "<td>" . $m['name'] . '</td>';
                echo "<td>" . $m['api_token'] . '</td>';
                echo "<td>" . $m['root_url'] . '</td>';
                echo '<td><a href="modules.php?action=delete&id=' . $m['id'] . '"><img src="../resources/delete.png" class="icon"></a><a href="modules.php?action=edit&id=' . $m['id'] . '"><img src="../resources/edit.png" class="icon"></a></td>';
                echo "</tr>";
            }
        }
        ?>
    </table>

<?php
// End rendering the content
$content = ob_get_clean();
$page->setContent($content);

$site->render();
?>

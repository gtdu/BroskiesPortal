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
    if ($helper->createNewModule($_POST['name'], $_POST['url'], $_POST['external'], $_POST['defaultAccess'])) {
        $_SESSION['success'] = true;
    } else {
        $_SESSION['error'][0] = getSQLError();
    }
    header("Location: ?");
    die();
} elseif ($_POST['action'] == 'deleteModule') {
    if ($helper->deleteModule($_POST['module'])) {
        $_SESSION['success'] = true;
    } else {
        $_SESSION['error'][0] = getSQLError();
    }
    header("Location: ?");
    die();
} elseif ($_POST['action'] == 'editModule') {
    if ($helper->editModule($_POST['module'], $_POST['url'])) {
        $_SESSION['success'] = true;
    } else {
        $_SESSION['error'][0] = getSQLError();
    }
    header("Location: ?");
    die();
}

// Start rendering the content
ob_start();

include_once("../includes/navbar.php");

?>
<div class="container">
    <h1 class="mt-2">Manage Users</h1>
    <div class="d-flex mt-3 mb-3">
        <div class="btn-group flex-fill" role="group" aria-label="Basic example">
            <a href='?action=newModule' class="btn btn-warning">Create New Module</a>
            <a href='?action=editModule' class="btn btn-warning">Edit Module</a>
            <a href="?action=deleteModule" class="btn btn-warning">Delete Module</a>
        </div>
    </div>
    <?php

    // Show the correct form
    if ($_GET['action'] == 'deleteModule') {
        include_once("../components/deleteModuleForm.php");
    } elseif ($_GET['action'] == 'newModule') {
        include_once("../components/newModuleForm.php");
    } elseif ($_GET['action'] == 'editModule') {
        include_once("../components/editModuleForm.php");
    }
    ?>

    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th>Name</th>
                <th>API Token</th>
                <th>Root URL</th>
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

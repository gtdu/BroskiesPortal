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
    $helper->createNewModule($_POST['name'], $_POST['url'], $_POST['external'], $_POST['defaultAccess']);
    header("Location: ?");
    die();
} else if ($_POST['action'] == 'deleteModule') {
   $helper->deleteModule($_POST['module']);
   header("Location: ?");
   die();
} else if ($_POST['action'] == 'editModule') {
   $helper->editModule($_POST['module'], $_POST['url']);
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

if ($_GET['action'] == 'deleteModule') {
    ?>
    <div class="pl-4 pr-4 mb-4">
        <form method="post">
            <div class="form-group">
                <label for="deleteModuleModule">Module</label>
                <select name="module" required id="deleteModuleModule" class="form-control">
                    <?php
                    foreach ($modules as $m) {
                        if ($m['id'] != 1) {
                            echo "<option value='" . $m['id'] . "'>" . $m['name'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <input type="hidden" name="action" value="deleteModule">
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </div>
    <?php
} else if ($_GET['action'] == 'newModule') {
    ?>
    <div class="pl-4 pr-4 mb-4">
        <form method="post">
            <div class="form-group">
                <label for="newUserName">Name</label>
                <input name="name" type="text" class="form-control" id="newUserName" aria-describedby="emailHelp" placeholder="Doohickey" required>
            </div>
            <div class="form-group">
                <label for="newUserEmail">Root URL</label>
                <input name="url" type="text" class="form-control" id="newUserEmail" aria-describedby="emailHelp" placeholder="/modules/doohickey" required>
            </div>
            <div class="form-group">
                <label for="newUserName2">Default Access Level</label>
                <input name="defaultAccess" type="number" step="1" min="0" value="1" class="form-control" id="newUserName2" aria-describedby="emailHelp" placeholder="1" required>
            </div>
            <div class="form-group">
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" id="customSwitch1" name="external">
                  <label class="custom-control-label" for="customSwitch1">Open In New Tab</label>
                </div>
            </div>
            <input type="hidden" name="action" value="newModule">
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>
    <?php
} else if ($_GET['action'] == 'editModule') {
    ?>
    <div class="pl-4 pr-4 mb-4">
        <form method="post">
            <div class="form-group">
                <label for="editModuleName">Name</label>
                <select name="module" required id="editModuleName" class="form-control">
                    <?php
                    foreach ($modules as $m) {
                        if ($m['id'] != 1) {
                            echo "<option value='" . $m['id'] . "'>" . $m['name'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="newUserEmail">Root URL</label>
                <input name="url" type="text" class="form-control" id="newUserEmail" aria-describedby="emailHelp" placeholder="/modules/doohickey" required>
            </div>
            <input type="hidden" name="action" value="editModule">
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
    <?php
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

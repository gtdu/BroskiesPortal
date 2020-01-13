<?php
include '../init.php';

// Site/page boilerplate
$site = new site('Broskies Portal');
init_site($site);

$page = new page(true);
$site->setPage($page);

$helper = new AdminHelper($config);
$users = $helper->getUsers();

if ($_POST['action'] == 'resetPassword') {
    $helper->resetUserPassword($_POST['user'], $_POST['password']);
    header("Location: ?");
    die();
} else if ($_POST['action'] == 'newUser') {
    $helper->newUser($_POST['name'], $_POST['email'], $_POST['password']);
    header("Location: ?");
    die();
} else if ($_POST['action'] == 'deleteUser') {
    $helper->deleteUser($_POST['user']);
    header("Location: ?");
    die();
} else if ($_POST['action'] == 'changeCore') {
    $helper->setUserPerm($_POST['user'], 'core', $_POST['level']);
    header("Location: ?");
    die();
} else if ($_POST['action'] == 'changePermission') {
    $helper->setUserPerm($_POST['user'], $_POST['module'], $_POST['level']);
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
        <a href="?action=newUser" class="btn btn-warning">Create New User</a>
        <a href="?action=deleteUser" class="btn btn-warning">Delete User</a>
        <a href='?action=resetPassword' class="btn btn-warning">Reset User Password</a>
        <a href='?action=changeCore' class="btn btn-warning">Change Core Permission Level</a>
        <a href='?action=changePermission' class="btn btn-warning">Change Module Permission Level</a>
    </div>
</div>
<?php

if ($_GET['action'] == 'resetPassword') {
    ?>
    <div class="pl-4 pr-4 mb-4">
        <form method="post">
            <div class="form-group">
                <label for="resetPasswordUser">User</label>
                <select name="user" required id="resetPasswordUser">
                    <?php
                    foreach ($users as $user) {
                        echo "<option value='" . $user['id'] . "'>" . $user['name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="resetPasswordPassword">New Password</label>
                <input name="password" type="text" class="form-control" id="resetPasswordPassword" aria-describedby="emailHelp" placeholder="DikiaCunt" required>
            </div>
            <input type="hidden" name="action" value="resetPassword">
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <?php
} else if ($_GET['action'] == 'newUser') {
    ?>
    <div class="pl-4 pr-4 mb-4">
        <script>
        function generatePassword() {
            var words = ["Fuck", "Shit", "Bitch", "Ass", "Cunt", "Bastard"];
            var randomWord = words[Math.floor(Math.random() * words.length)];
            var randomNumber = Math.floor(Math.random() * 100);
            document.getElementById("newUserPassword").value = "Dikaia" + randomWord + randomNumber
        }
        </script>
        <form method="post">
            <div class="form-group">
                <label for="newUserName">Name</label>
                <input name="name" type="text" class="form-control" id="newUserName" aria-describedby="emailHelp" placeholder="Jernandez, Facinto" required>
            </div>
            <div class="form-group">
                <label for="newUserEmail">Email</label>
                <input name="email" type="email" class="form-control" id="newUserEmail" aria-describedby="emailHelp" placeholder="dude@gtdu.org" required>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-outline-secondary float-right btn-sm" onclick="generatePassword()">Generate Password</button>
                <label for="newUserPassword">Password</label>
                <input name="password" type="text" class="form-control" id="newUserPassword" aria-describedby="emailHelp" placeholder="DikaiaCunt" required>
            </div>
            <input type="hidden" name="action" value="newUser">
            <button type="submit" class="btn btn-success">Create User</button>
        </form>
    </div>
    <?php
} else if ($_GET['action'] == 'deleteUser') {
    ?>
    <div class="pl-4 pr-4 mb-4">
        <form method="post">
            <div class="form-group">
                <label for="deleteUserUser">User</label>
                <select name="user" required id="deleteUserUser">
                    <?php
                    foreach ($users as $user) {
                        echo "<option value='" . $user['id'] . "'>" . $user['name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <input type="hidden" name="action" value="deleteUser">
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </div>
    <?php
} else if ($_GET['action'] == 'changeCore') {
    ?>
    <div class="pl-4 pr-4 mb-4">
        <form method="post">
            <div class="form-group">
                <label for="changeCoreUser">User</label>
                <select name="user" required id="changeCoreUser" class="form-control">
                    <?php
                    foreach ($users as $user) {
                        echo "<option value='" . $user['id'] . "'>" . $user['name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="changeCoreLevel">User</label>
                <select name="level" required id="changeCoreLevel" class="form-control">
                    <option value="0">No Access</option>
                    <option value="1">Standard Brother</option>
                    <option value="2">Administrator</option>
                </select>
            </div>
            <input type="hidden" name="action" value="changeCore">
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <?php
}
else if ($_GET['action'] == 'changePermission') {
    ?>
    <div class="pl-4 pr-4 mb-4">
        <form method="post">
            <div class="form-group">
                <label for="changePermissionUser">User</label>
                <select name="user" required id="changePermissionUser" class="form-control">
                    <?php
                    foreach ($users as $user) {
                        echo "<option value='" . $user['id'] . "'>" . $user['name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="changePermissionModule">Module</label>
                <select name="module" required id="changePermissionModule" class="form-control">
                    <?php
                    $modules = $helper->getModules();
                    foreach ($modules as $m) {
                        if ($m['id'] != 1) {
                            echo "<option value='" . $m['pem_name'] . "'>" . $m['name'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="changePermissionLevel">Level</label>
                <input name="level" type="number" min="0" step="1" class="form-control" id="changePermissionLevel" aria-describedby="emailHelp" value="1" required>
            </div>
            <input type="hidden" name="action" value="changePermission">
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <?php
}
?>

<table class="table">
    <thead class="thead-dark">
        <?php
        $keys = array_keys($users[0]);
        ?>
        <tr>
            <th rowspan="2">Name</th>
            <th colspan="<?php echo (count($keys) - 5); ?>">Permissions</th>
        </tr>
        <tr>
            <th>CORE</th>
            <?php
            for ($i=6; $i < count($keys); $i++) {
                echo "<th>" . strtoupper($keys[$i]) . "</th>";
            }
            ?>
        </tr>
    </thead>
    <?php
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['name'] . '</td>';
        echo "<td>" . $user['core'] . '</td>';
        for ($i=6; $i < count($keys); $i++) {
            echo "<td>" . $user[$keys[$i]] . "</td>";
        }
        echo "</tr>";
    }

    ?>
</table>
</div>


<?php
// End rendering the content
$content = ob_get_clean();
$page->setContent($content);

$site->render();
?>

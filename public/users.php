<?php
include '../init.php';

// Site/page boilerplate
$site = new site('Broskies Portal');
init_site($site);

$page = new page(true);
$site->setPage($page);

$helper = new AdminHelper($config);
$users = $helper->getUsers();

if ($_POST['action'] == 'newUser') {
    if ($helper->newUser($_POST['name'], $_POST['email'], $_POST['password'], $_POST['phone'])) {
        $_SESSION['success'] = true;
    } else {
        $_SESSION['error'][0] = getSQLError();
    }
    header("Location: ?");
    die();
} elseif ($_POST['action'] == 'deleteUser') {
    if ($helper->deleteUser($_POST['user'])) {
        $_SESSION['success'] = true;
    } else {
        $_SESSION['error'][0] = getSQLError();
    }
    header("Location: ?");
    die();
} elseif ($_POST['action'] == 'changeCore') {
    if ($helper->setUserPerm($_POST['user'], 'core', $_POST['level'])) {
        $_SESSION['success'] = true;
    } else {
        $_SESSION['error'][0] = getSQLError();
    }
    header("Location: ?");
    die();
} elseif ($_POST['action'] == 'changePermission') {
    if ($helper->setUserPerm($_POST['user'], $_POST['module'], $_POST['level'])) {
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
            <a href="?action=newUser" class="btn btn-warning">Create New User</a>
            <a href="?action=deleteUser" class="btn btn-warning">Delete User</a>
            <a href='?action=changeCore' class="btn btn-warning">Change Core Permission Level</a>
            <a href='?action=changePermission' class="btn btn-warning">Change Module Permission Level</a>
        </div>
    </div>
    <script>
    // Random password generator
    function generatePassword() {
        var randomNumber = Math.floor(Math.random() * 100);
        document.getElementById("newUserPassword").value = "DikaiaBrother" + randomNumber
    }
</script>
<?php
// Render the correct form
if ($_GET['action'] == 'newUser') {
    include_once("../components/newUserForm.php");
} elseif ($_GET['action'] == 'deleteUser') {
    include_once("../components/deleteUserForm.php");
} elseif ($_GET['action'] == 'changeCore') {
    include_once("../components/changeCorePemForm.php");
} elseif ($_GET['action'] == 'changePermission') {
    include_once("../components/changePemForm.php");
}
?>

<table class="table">
    <thead class="thead-dark">
        <?php
        $keys = array_keys($users[0]);
        ?>
        <tr>
            <th rowspan="2">Name</th>
            <th colspan="<?php echo(count($keys) - 5); ?>">Permissions</th>
        </tr>
        <tr>
            <th>CORE</th>
            <?php
            for ($i=8; $i < count($keys); $i++) {
                echo "<th>" . strtoupper($keys[$i]) . "</th>";
            }
            ?>
        </tr>
    </thead>
    <?php
    // Render users
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['name'] . '</td>';
        echo "<td>";
        if ($user['core'] == 0) {
            echo "<span style='color: red'>No Access</span>";
        } elseif ($user['core'] == 1) {
            echo "Standard User";
        } elseif ($user['core'] == 2) {
            echo "<span style='color: orange'>Administrator</span>";
        } else {
            echo "ERROR";
        }
        echo '</td>';
        for ($i=8; $i < count($keys); $i++) {
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

<?php
include '../init.php';

// Site/page boilerplate
$site = new site('Broskies Portal');
init_site($site);

$page = new page(true);
$site->setPage($page);

$helper = new AdminHelper($config);
$users = $helper->getUsers();

if ($_POST['action'] == 'deleteUser') {
    if ($helper->deleteUser($_POST['user_id'])) {
        $_SESSION['success'] = true;
        header("Location: ?");
        die();
    } else {
        $_SESSION['error'][0] = $helper->getErrorMessage();
        header("Location: ?");
        die();
    }
} elseif ($_POST['action'] == 'changePermission') {
    if ($helper->setUserPerm($_POST['user_id'], $_POST['module'], $_POST['level'])) {
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
    <h1 class="mt-2">Manage Users</h1>
<?php
// Render the correct form
if ($_GET['action'] == 'delete') {
    $item = $helper->getUserByID($_GET['id']);
    include_once("../components/deleteUserForm.php");
} elseif ($_GET['action'] == 'edit') {
    $item = $helper->getUserByID($_GET['id']);
    include_once("../components/changePemForm.php");
}
?>
<div style="overflow-x: scroll;">
<table class="table">
    <thead class="thead-dark">
        <?php
        $keys = array_keys($users[0]);
        ?>
        <tr>
            <th rowspan="2" style='min-width: 175px;'>Name</th>
            <th rowspan="2">&nbsp;</th>
            <th colspan="<?php echo(count($keys) - 4); ?>">Permissions</th>
        </tr>
        <tr>
            <th style='min-width: 125px;'>CORE</th>
            <?php
            for ($i=6; $i < count($keys); $i++) {
                echo "<th style='min-width: 145px;'>" . strtoupper($keys[$i]) . "</th>";
            }
            ?>
        </tr>
    </thead>
    <?php
    // Render users
    $modules = $helper->getModules();

    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['name'] . '</td>';
        echo '<td><a href="users.php?action=delete&id=' . $user['id'] . '"><img src="../resources/delete.png" class="icon" alt=""></a><a href="users.php?action=edit&id=' . $user['id'] . '"><img src="../resources/edit.png" class="icon" alt=""></a></td>';
        echo "<td>";
        if ($user['core'] == 0) {
            echo "<span style='color: red'>No Access</span>";
        } elseif ($user['core'] == 1) {
            echo "Pledge";
        } elseif ($user['core'] == 2) {
            echo "Brother";
        } elseif ($user['core'] == 3) {
            echo "<span style='color: blue'>MEC Officer</span>";
        } elseif ($user['core'] == 4) {
            echo "<span style='color: orange'>EC Officer</span>";
        } elseif ($user['core'] == 5) {
            echo "<span style='color: gold'>Owner</span>";
        } else {
            echo "ERROR";
        }
        echo '</td>';
        for ($i=6; $i < count($keys); $i++) {
            $levels = explode(",", $modules[$i - 4]['levelNames']);

            echo "<td>";
            if ($user[$keys[$i]] == 0) {
                echo "<span style='color: red'>";
            } elseif ($user[$keys[$i]] == 1) {
                echo "<span>";
            } elseif ($user[$keys[$i]] == 2) {
                echo "<span style='color: blue'>";
            } elseif ($user[$keys[$i]] >= 3) {
                echo "<span style='color: orange'>";
            }

            if (count($levels) < $user[$keys[$i]]) {
                echo "<b>" . $user[$keys[$i]] . "</b>";
            } else {
                echo $levels[$user[$keys[$i]]];
            }

            echo "</span></td>";
        }
        echo "</tr>";
    }

    ?>
</table>
</div>
</div>


<?php
// End rendering the content
$content = ob_get_clean();
$page->setContent($content);

$site->render();
?>

<?php

/**
* This is the navigation bar that is shown to authenticated users within the portal
*
* @author Ryan Cobelli <ryan.cobelli@gmail.com>
*/

// Use the standard lib to get all the users current permissions
$result = getCurrentPermissions($config);

// Check that the session is valid and that they have permission to use the portal
if (empty($result) || $result['core'] == 0) {
    // Invalidate session info and return them to the login page
    session_destroy();
    header("Location: index.php");
    die();
}

?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand"><img src="../resources/logo.png" width="30" height="30" alt=""></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="dashboard.php"><img src="../resources/home.png" title="Home" class="icon"></a>
                </li>
                <?php
                // Pull all permission data
                $data = getCurrentPermissions($config);

                // Remove unnecessary info
                unset($data['id']);
                unset($data['email']);
                unset($data['phone']);
                unset($data['core']);
                unset($data['name']);
                unset($data['password']);
                unset($data['session_token']);
                unset($data['password_reset']);

                // Loop through every module
                foreach ($data as $key => $value) {
                    // Can they access it?
                    if ($value > 0) {
                        $handle = $config['dbo']->prepare('SELECT id, name, root_url, external FROM modules WHERE pem_name = ? LIMIT 1');
                        $handle->bindValue(1, $key);
                        $handle->execute();
                        $pemResult = $handle->fetchAll(\PDO::FETCH_ASSOC)[0];

                        // Check if the module actually exists
                        if (!empty($pemResult)) {
                            echo '<li class="nav-item active">';
                            // Check if it should open in a new tab or not
                            if ($pemResult['external']) {
                                echo '<a class="nav-link" href="' . $pemResult['root_url'] . '?session_token=' . $_SESSION['token'] . '" target="_blank">' . $pemResult['name'] . '</a>';
                            } else {
                                echo '<a class="nav-link" href="dashboard.php?page=' . $pemResult['id'] . '">' . $pemResult['name'] . '</a>';
                            }
                            echo '</li>';
                        }
                    }
                }
                ?>
                <?php
                // Check if the user is an admin
                // Admins are able to manage users and modules
                if ($result['core'] == 2) {
                    ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="users.php">Manage Users</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="modules.php">Manage Modules</a>
                    </li>
                    <?php
                }
                ?>
                <li class="nav-item active">
                    <a class="nav-link" href="settings.php"><img src="../resources/settings.png" title="Settings" class="icon"></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=logout"><img src="../resources/logout.png" title="Logout" class="icon"></a>
                </li>
            </ul>
        </div>
    </div>
</nav>

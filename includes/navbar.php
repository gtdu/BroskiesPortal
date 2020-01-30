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
                    <a class="nav-link" href="dashboard.php">Home</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="settings.php">Settings</a>
                </li>
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
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=logout">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

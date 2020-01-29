<?php

$result = getCurrentPermissions($config);

if (empty($result) || $result['core'] == 0) {
    $_SESSION['token'] = NULL;
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
                if ($data['core'] == 2) {
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

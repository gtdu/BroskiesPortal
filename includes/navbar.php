<?php

$result = getCurrentPermissions($config);

if (!empty($result)) {
    $data = $result;
    unset($data['id']);
    unset($data['email']);
    unset($data['name']);
    unset($data['password']);
    unset($data['session_token']);
} else if ($data['core'] == 0) {
    $_SESSION['token'] = NULL;
    header("Location: index.php");
    die();
} else {
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
unset($data['core']);

foreach ($data as $key => $value) {
    if ($value > 0) {
        $handle = $config['dbo']->prepare('SELECT id, name, root_url FROM modules WHERE pem_name = ? LIMIT 1');
        $handle->bindValue(1, $key);
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);

        if (!empty($result)) {
            ?>
            <li class="nav-item active">
                <a class="nav-link" href="dashboard.php?page=<?php echo $result[0]['id']; ?>"><?php echo $result[0]['name']; ?></a>
            </li>
            <?php
        }
    }
}
?>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=logout">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<?php
include '../init.php';

// Site/page boilerplate
$site = new site('Broskies Portal');
init_site($site);

$page = new page(true);
$site->setPage($page);

// Start rendering the content
ob_start();

$handle = $config['dbo']->prepare('SELECT * FROM users WHERE session_token = ? LIMIT 1');
$handle->bindValue(1, $_SESSION['token']);
$handle->execute();
$result = $handle->fetchAll(\PDO::FETCH_ASSOC);

if (!empty($result)) {
    $data = $result[0];
    unset($data['id']);
    unset($data['email']);
    unset($data['name']);
    unset($data['password']);
    unset($data['session_token']);
} else {
    $_SESSION['token'] = NULL;
    die();
}

echo "</br></br>";

foreach ($data as $key => $value) {
    if ($value > 0) {
        $handle = $config['dbo']->prepare('SELECT name, root_url FROM modules WHERE pem_name = ? LIMIT 1');
        $handle->bindValue(1, $key);
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);

        if (!empty($result)) {
            echo "<a href='" . $result[0]['root_url'] . "?session_token=" . $_SESSION['token'] . "' target='_blank'>" . $result[0]['name'] . "</a></br>";
        }
    }
}

// End rendering the content
$content = ob_get_clean();
$page->setContent($content);

$site->render();
?>

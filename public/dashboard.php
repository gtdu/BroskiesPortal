<?php
include '../init.php';

// Site/page boilerplate
$site = new site('Broskies Portal');
init_site($site);

$page = new page(true);
$site->setPage($page);

$helper = new AdminHelper($config);

// Start rendering the content
ob_start();

include_once("../includes/navbar.php");

if (isset($_GET['page'])): ?>
<div class="d-flex content container">
    <iframe src="<?php echo $helper->getModule($_GET['page'])['root_url'] . "?session_token=" . $_SESSION['token']; ?>" class="flex-fill"></iframe>
</div>
<?php else: ?>
    <div class="content container">
        <?php
        // Render dynamic login message
        $loginMessage = $helper->getDynamicConfig()['HOME_MESSAGE'];
        if ($loginMessage != NULL) {
            echo '<h3 class="mt-5 text-center">' . $loginMessage . '</h3>';
        }
        ?>
        <h2 class="mt-5 text-center" style="width: 100%">Select a module to get started:</h2></br>
        <?php
        $data = getCurrentPermissions($config);

        unset($data['id']);
        unset($data['email']);
        unset($data['core']);
        unset($data['name']);
        unset($data['password']);
        unset($data['session_token']);
        unset($data['password_reset']);


        foreach ($data as $key => $value) {
            if ($value > 0) {
                $handle = $config['dbo']->prepare('SELECT id, name, root_url, external FROM modules WHERE pem_name = ? LIMIT 1');
                $handle->bindValue(1, $key);
                $handle->execute();
                $result = $handle->fetchAll(\PDO::FETCH_ASSOC)[0];

                if (!empty($result)) {
                    echo '<li>';
                    if ($result['external']) {
                        echo '<a class="nav-link" href="' . $result['root_url'] . '?session_token=' . $_SESSION['token'] . '" target="_blank">' . $result['name'] . '</a>';
                    } else {
                        echo '<a class="nav-link" href="dashboard.php?page=' . $result['id'] . '">' . $result['name'] . '</a>';
                    }
                    echo '</li>';
                }
            }
        }
        ?>
    </div>
    <?php

endif;

// End rendering the content
$content = ob_get_clean();
$page->setContent($content);

$site->render();
?>

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

?>
<div class="d-flex content container">
    <?php if (isset($_GET['page'])): ?>
        <iframe src="<?php echo $helper->getModule($_GET['page'])['root_url'] . "?session_token=" . $_SESSION['token']; ?>" class="flex-fill"></iframe>
    <?php endif; ?>
</div>

<?php

// End rendering the content
$content = ob_get_clean();
$page->setContent($content);

$site->render();
?>

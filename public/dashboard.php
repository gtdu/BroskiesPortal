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

// Check if we are rendering a module or the home page
if (isset($_GET['page'])):
?>
<div class="d-flex content container">
    <iframe src="<?php echo $helper->getModule($_GET['page'])['root_url'] . "?session_token=" . $_SESSION['token']; ?>" class="flex-fill"></iframe>
</div>
<?php else: ?>
<div class="content container">
    <?php include_once("../includes/homePage.php"); ?>
</div>
<?php
endif;

// End rendering the content
$content = ob_get_clean();
$page->setContent($content);

$site->render();
?>

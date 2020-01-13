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
    <h2 class="mt-5 text-center" style="width: 100%">Select a module from the above menu to get started</h2></br>
    <h5 class="text-center" style="width: 100%">or</h5>
    <ul>
        <li><a href="reset.php?email=<?php echo $_SESSION['email']; ?>">Change Password</a></li>
    </ul>
</div>
<?php

endif;

// End rendering the content
$content = ob_get_clean();
$page->setContent($content);

$site->render();
?>

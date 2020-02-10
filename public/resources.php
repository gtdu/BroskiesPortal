<?php
include '../init.php';

// Site/page boilerplate
$site = new site('Broskies Portal');
init_site($site);

$page = new page(true);
$site->setPage($page);

$helper = new ResourcesHelper($config);

if ($site->userCorePem > 1) {
    if ($_POST['action'] == 'deleteResource') {
        if ($helper->deleteResource($_POST['resource_id'])) {
            $_SESSION['success'] = true;
            header("Location: ?");
            die();
        } else {
            $_SESSION['error'][0] = $helper->getErrorMessage();
            header("Location: ?");
            die();
        }
    } elseif ($_POST['action'] == 'newResource') {
        if ($helper->createResource($_POST['title'], $_POST['link'], $_POST['description'], $_POST['position'], $_POST['visibility'])) {
            $_SESSION['success'] = true;
            header("Location: ?");
            die();
        } else {
            $_SESSION['error'][0] = $helper->getErrorMessage();
            header("Location: ?");
            die();
        }
    } elseif ($_POST['action'] == 'editResource') {
        if ($helper->updateResource($_POST['resource_id'], $_POST['title'], $_POST['link'], $_POST['description'], $_POST['position'], $_POST['visibility'])) {
            $_SESSION['success'] = true;
            header("Location: ?");
            die();
        } else {
            $_SESSION['error'][0] = $helper->getErrorMessage();
            header("Location: ?");
            die();
        }
    }
}

// Start rendering the content
ob_start();

include_once("../includes/navbar.php");

?>
<div class="container pt-3">
<h1 class="mt-2">Resources</h1>
<?php

if ($site->userCorePem > 1) {
    // Render the correct form
    if ($_GET['action'] == 'create') {
        include_once("../components/newResourceForm.php");
    } elseif ($_GET['action'] == 'delete') {
        $item = $helper->getResourceByID($_GET['id']);
        include_once("../components/deleteResourceForm.php");
    } elseif ($_GET['action'] == 'edit') {
        $item = $helper->getResourceByID($_GET['id']);
        include_once("../components/changeResourceForm.php");
    } else {
        echo '<a href="?action=create" role="button" class="btn btn-success mb-3">Create Resource</a>';
    }
}

?>
<?php

$helper->renderResources($site->userCorePem);

echo "</div>";

// End rendering the content
$content = ob_get_clean();
$page->setContent($content);

$site->render();
?>

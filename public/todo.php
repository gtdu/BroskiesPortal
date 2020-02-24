<?php
include '../init.php';

// Site/page boilerplate
$site = new site('Broskies Portal');
init_site($site);

$page = new page(true);
$site->setPage($page);

$helper = new TodoHelper($config);

if ($site->userCorePem > 1) {
    if ($_POST['action'] == 'deleteTodo') {
        if ($helper->deleteTodo($_POST['todo_id'])) {
            $_SESSION['success'] = true;
            header("Location: ?");
            die();
        } else {
            $_SESSION['error'][0] = $helper->getErrorMessage();
            header("Location: ?");
            die();
        }
    } elseif ($_POST['action'] == 'newTodo') {
        if ($helper->createTodo($_POST['title'], $_POST['link'], $_POST['description'])) {
            $_SESSION['success'] = true;
            header("Location: ?");
            die();
        } else {
            $_SESSION['error'][0] = $helper->getErrorMessage();
            header("Location: ?");
            die();
        }
    } elseif ($_POST['action'] == 'editTodo') {
        if ($helper->updateTodo($_POST['todo_id'], $_POST['title'], $_POST['link'], $_POST['description'])) {
            $_SESSION['success'] = true;
            header("Location: ?");
            die();
        } else {
            $_SESSION['error'][0] = $helper->getErrorMessage();
            header("Location: ?");
            die();
        }
    } elseif ($_GET['action'] == 'completed') {
        if ($helper->markTodoAsCompleted($_GET['id'], $site->userID)) {
            // $_SESSION['success'] = true;
            if ($_GET['home'] == true) {
                header("Location: dashboard.php");
            } else {
                header("Location: ?");
            }
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
    <h1 class="mt-2">To-Do</h1>
<?php

if ($site->userCorePem > 1) {
    // Render the correct form
    if ($_GET['action'] == 'create') {
        include_once("../components/newTodoForm.php");
    } elseif ($_GET['action'] == 'delete') {
        $item = $helper->getTodoByID($_GET['id']);
        include_once("../components/deleteTodoForm.php");
    } elseif ($_GET['action'] == 'edit') {
        $item = $helper->getTodoByID($_GET['id']);
        include_once("../components/changeTodoForm.php");
    } else {
        echo '<a href="?action=create" role="button" class="btn btn-success mb-3 mr-3">Create To-Do</a>';
        if ($_GET['viewAll'] == "true") {
            echo '<a href="?viewAll=false" role="button" class="btn btn-outline-secondary mb-3">View Pending</a>';
        } else {
            echo '<a href="?viewAll=true" role="button" class="btn btn-outline-secondary mb-3">View All</a>';
        }
    }
}

$helper->renderTodos($site->userID, $site->userCorePem, $_GET['viewAll'] == "true");

echo "</div>";

// End rendering the content
$content = ob_get_clean();
$page->setContent($content);

$site->render();
?>

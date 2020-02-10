<?php
// Render dynamic login message
$loginMessage = $helper->getDynamicConfig()['HOME_MESSAGE'];
if ($loginMessage != null) {
    echo '<h3 class="mt-5 text-center">' . $loginMessage . '</h3><hr/>';
}

// Display events in the next 7 days
echo '<h2 class="mt-2">Next 7 Days</h2>';
$calHelper = new CalendarHelper($config);
$calHelper->renderCalendar();
?>
<hr/>

<h2 class="mt-2">Todos</h2>
<?php
$todoHelper = new TodoHelper($config);
$todoHelper->renderTodos($site->userID, $site->userCorePem);
?>
<hr/>

<?php

$default_id = $helper->getDynamicConfig()['DEFAULT_MODULE'];
if ($default_id != -1) {
    ?>
    <div class="d-flex content">
        <iframe src="<?php echo $helper->getModule($default_id)['root_url'] . "?session_token=" . $_SESSION['token']; ?>" class="flex-fill"></iframe>
    </div>
    <?php
} else {
    echo '<h2 class="mt-2">Resources</h2>';
    $resourcesHelper = new ResourcesHelper($config);
    $resourcesHelper->renderResources($site->userCorePem);
}
?>

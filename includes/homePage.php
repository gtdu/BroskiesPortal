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

<h2 class="mt-2">To-Do</h2>
<?php
$todoHelper = new TodoHelper($config);
$todoHelper->renderTodos($site->userID, $site->userCorePem, false, true);
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

// If they want to cause chaos and its lucky, it tell's somebody to drink
if ($_GET['chaos'] == 'true' && rand(0,5) == 1) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // UCQ6ZGDNZ = Nilabh
    // C30NXEX7U = Random
    curl_setopt($ch, CURLOPT_URL, "https://slack.com/api/chat.postMessage?token=" . $ini['slack_token'] . "&channel=C30NXEX7U&text=Drink%20One%20<@UCQ6ZGDNZ>");
    curl_exec($ch);
    curl_close($ch);
}

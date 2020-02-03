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
if (isset($_GET['page'])): ?>
<div class="d-flex content container">
    <iframe src="<?php echo $helper->getModule($_GET['page'])['root_url'] . "?session_token=" . $_SESSION['token']; ?>" class="flex-fill"></iframe>
</div>
<?php else: ?>
    <div class="content container">
        <?php
        // Render dynamic login message
        $loginMessage = $helper->getDynamicConfig()['HOME_MESSAGE'];
        if ($loginMessage != null) {
            echo '<h3 class="mt-5 text-center">' . $loginMessage . '</h3>';
        }
        ?>
        <h2 class="mt-5 text-center" style="width: 100%">Select a module to get started:</h2></br>
        <?php
        // Pull all permission data
        $data = getCurrentPermissions($config);

        // Remove unnecessary info
        unset($data['id']);
        unset($data['email']);
        unset($data['core']);
        unset($data['name']);
        unset($data['password']);
        unset($data['session_token']);
        unset($data['password_reset']);

        //set modCount for styling purposes
        $modCount = 0;

        //initialize our module table as not visible aka no modules
            echo '<table id="modulesTable">';

        // Loop through every module
        foreach ($data as $key => $value) {
            //get module count
            $modCount += 1;

            // Can they access it?
            if ($value > 0) {
                $handle = $config['dbo']->prepare('SELECT id, name, root_url, external FROM modules WHERE pem_name = ? LIMIT 1');
                $handle->bindValue(1, $key);
                $handle->execute();
                $result = $handle->fetchAll(\PDO::FETCH_ASSOC)[0];

                // Check if the module actually exists
                if (!empty($result)) {

                    //if it's % 3 we want a new row as we're doing 3 across
                    if ($modCount % 3 == 0) {
                        echo '<tr>';
                    }

                    //create new table entry
                    echo '<td>';

                    // Check if it should open in a new tab or not
                    if ($result['external']) {
                        echo '<a class="nav-link" href="' . $result['root_url'] . '?session_token=' . $_SESSION['token'] . '" target="_blank">' . $result['name'] . '</a>';
                    } else {
                        echo '<a class="nav-link" href="dashboard.php?page=' . $result['id'] . '">' . $result['name'] . '</a>';
                    }

                    //close table entry
                    echo '</td>';

                    //closing check, it's a fast calculation so this is okay
                    if ($modCount % 3 == 0) {
                        echo '</tr>';
                    }
                }
            }
        }
        echo '</table>';
        ?>
    </div>
    <?php

endif;

// End rendering the content
$content = ob_get_clean();
$page->setContent($content);

$site->render();
?>

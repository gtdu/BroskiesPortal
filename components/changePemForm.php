<div class="pl-4 pr-4 mb-4">
    <form method="post">
        <div class="form-group">
            <label for="changePermissionModule">Module</label>
            <select name="module" required id="changePermissionModule" class="form-control" onchange="window.location.href = '?action=edit&id=<?php echo $_GET['id'];?>&module=' + this.value" <?php if (!empty($_GET['module'])) { echo "disabled";}?>>
                <option value="" disabled>Select Module</option>
                <?php
                $modules = $helper->getModules();
                foreach ($modules as $m) {
                    echo "<option value='" . $m['pem_name'] . "'";
                    if ($_GET['module'] == $m['pem_name']) {
                        echo " selected";
                        $levelNames = $m['levelNames'];
                    }
                    echo ">" . $m['name'] . "</option>";
                }
                ?>
            </select>
        </div>
        <?php if (!empty($_GET['module'])): ?>
            <input type="hidden" name="module" value="<?php echo $_GET['module']; ?>">
            <div class="form-group">
                <label for="changePermissionModule">Level</label>
                <select name="level" required id="changePermissionModule" class="form-control">
                    <option value="" disabled>Select Level</option>
                    <?php
                    $levels = explode(",", $levelNames);
                    $val = 0;
                    foreach ($levels as $l) {
                        echo "<option value='" . $val++ . "'>" . $l . "</option>";
                    }
                    ?>
                </select>
            </div>
        <?php endif;?>
        <input type="hidden" name="user_id" value="<?php echo $item['id']; ?>">
        <input type="hidden" name="action" value="changePermission">
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

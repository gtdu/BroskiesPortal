<div class="pl-4 pr-4 mb-4">
    <form method="post">
        <div class="form-group">
            <label for="changePermissionUser">User</label>
            <select name="user" required id="changePermissionUser" class="form-control">
                <?php
                foreach ($users as $user) {
                    echo "<option value='" . $user['id'] . "'>" . $user['name'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="changePermissionModule">Module</label>
            <select name="module" required id="changePermissionModule" class="form-control">
                <?php
                $modules = $helper->getModules();
                foreach ($modules as $m) {
                    if ($m['id'] != 1) {
                        echo "<option value='" . $m['pem_name'] . "'>" . $m['name'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="changePermissionLevel">Level</label>
            <input name="level" type="number" min="0" step="1" class="form-control" id="changePermissionLevel" aria-describedby="emailHelp" value="1" required>
        </div>
        <input type="hidden" name="action" value="changePermission">
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

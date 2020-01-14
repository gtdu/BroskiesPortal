<div class="pl-4 pr-4 mb-4">
    <form method="post">
        <div class="form-group">
            <label for="editModuleName">Name</label>
            <select name="module" required id="editModuleName" class="form-control">
                <?php
                foreach ($modules as $m) {
                    if ($m['id'] != 1) {
                        echo "<option value='" . $m['id'] . "'>" . $m['name'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="newUserEmail">Root URL</label>
            <input name="url" type="text" class="form-control" id="newUserEmail" aria-describedby="emailHelp" placeholder="/modules/doohickey" required>
        </div>
        <input type="hidden" name="action" value="editModule">
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

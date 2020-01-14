<div class="pl-4 pr-4 mb-4">
    <form method="post">
        <div class="form-group">
            <label for="deleteModuleModule">Module</label>
            <select name="module" required id="deleteModuleModule" class="form-control">
                <?php
                foreach ($modules as $m) {
                    if ($m['id'] != 1) {
                        echo "<option value='" . $m['id'] . "'>" . $m['name'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <input type="hidden" name="action" value="deleteModule">
        <button type="submit" class="btn btn-danger">Delete</button>
    </form>
</div>

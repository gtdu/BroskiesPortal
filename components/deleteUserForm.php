<div class="pl-4 pr-4 mb-4">
    <form method="post">
        <div class="form-group">
            <label for="deleteUserUser">User</label>
            <select name="user" required id="deleteUserUser">
                <?php
                foreach ($users as $user) {
                    echo "<option value='" . $user['id'] . "'>" . $user['name'] . "</option>";
                }
                ?>
            </select>
        </div>
        <input type="hidden" name="action" value="deleteUser">
        <button type="submit" class="btn btn-danger">Delete</button>
    </form>
</div>

<div class="pl-4 pr-4 mb-4">
    <form method="post">
        <div class="form-group">
            <label for="changeCoreUser">User</label>
            <select name="user" required id="changeCoreUser" class="form-control">
                <?php
                foreach ($users as $user) {
                    echo "<option value='" . $user['id'] . "'>" . $user['name'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="changeCoreLevel">User</label>
            <select name="level" required id="changeCoreLevel" class="form-control">
                <option value="1">Standard Brother</option>
                <option value="2">MEC Officer</option>
                <option value="3">EC Officer</option>
            </select>
        </div>
        <input type="hidden" name="action" value="changeCore">
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

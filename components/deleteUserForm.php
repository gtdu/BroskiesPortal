<?php
/** @var $item array */
?>
<div class="pl-4 pr-4 mb-4">
    <form method="post">
        <h2 class="mt-4">Delete Todo</h2>
        <div class="form-group">
            <label for="input1">Are you sure you want to delete this item?</label>
        </div>
        <input type="hidden" name="user_id" value="<?php echo $item['id']; ?>">
        <input type="hidden" name="action" value="deleteUser">
        <button type="submit" class="btn btn-danger">Yes</button>
        <a href="?" class="btn btn-secondary active" role="button" aria-pressed="true">No</a>
    </form>
</div>

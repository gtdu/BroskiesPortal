<?php
/** @var $item array */
?>
<div class="pl-4 pr-4 mb-4">
    <h2>Update To-Do</h2>
    <form method="post">
        <div class="form-group">
            <label for="newUserName">Title</label>
            <input name="title" type="text" class="form-control" id="newUserName" aria-describedby="aria" placeholder="Survey" required value="<?php echo $item['title']; ?>">
        </div>
        <div class="form-group">
            <label for="newResourceLink">Link</label>
            <input name="link" type="url" class="form-control" id="newResourceLink" aria-describedby="aria" placeholder="http://drive.google.com" value="<?php echo $item['link']; ?>">
        </div>
        <div class="form-group">
            <label for="newResourceDescription">Description</label>
            <input name="description" type="text" class="form-control" id="newResourceDescription" aria-describedby="aria" placeholder="Mountain Weekend Survey" value="<?php echo $item['description']; ?>">
        </div>
        <input type="hidden" name="action" value="editTodo">
        <input type="hidden" name="todo_id" value="<?php echo $item['id']; ?>">
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

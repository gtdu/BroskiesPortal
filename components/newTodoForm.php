<div class="pl-4 pr-4 mb-4">
    <h2>Create Todo</h2>
    <form method="post">
        <div class="form-group">
            <label for="newResourceTitle">Title</label>
            <input name="title" type="text" class="form-control" id="newUserName" aria-describedby="emailHelp" placeholder="Survey" required>
        </div>
        <div class="form-group">
            <label for="newResourceLink">Link</label>
            <input name="link" type="url" class="form-control" id="newResourceLink" aria-describedby="emailHelp" placeholder="http://drive.google.com">
        </div>
        <div class="form-group">
            <label for="newResourceDescription">Description</label>
            <input name="description" type="text" class="form-control" id="newResourceDescription" aria-describedby="emailHelp" placeholder="Mountain Weekend Survey">
        </div>
        <input type="hidden" name="action" value="newTodo">
        <button type="submit" class="btn btn-success">Submit</button>
    </form>
</div>
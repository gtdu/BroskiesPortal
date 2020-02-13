<div class="pl-4 pr-4 mb-4">
    <h2>Create Resource</h2>
    <form method="post">
        <div class="form-group">
            <label for="newResourceTitle">Title</label>
            <input name="title" type="text" class="form-control" id="newUserName" aria-describedby="aria" placeholder="Roll Book" required>
        </div>
        <div class="form-group">
            <label for="newResourceLink">Link</label>
            <input name="link" type="url" class="form-control" id="newResourceLink" aria-describedby="aria" placeholder="http://drive.google.com" required>
        </div>
        <div class="form-group">
            <label for="newResourcePosition">Position</label>
            <input name="position" type="text" class="form-control" id="newResourcePosition" aria-describedby="aria" placeholder="Secretary" required>
        </div>
        <div class="form-group">
            <label for="newResourceDescription">Description</label>
            <input name="description" type="text" class="form-control" id="newResourceDescription" aria-describedby="aria" placeholder="2019-2020 Roll Book">
        </div>
        <div class="form-group">
            <label for="changeCoreLevel">Visibility</label>
            <select name="visibility" required id="changeCoreLevel" class="form-control">
                <option value="1">Pledge</option>
                <option value="2">Brother</option>
                <option value="2">MEC Officer</option>
                <option value="3">EC Officer</option>
            </select>
        </div>
        <input type="hidden" name="action" value="newResource">
        <button type="submit" class="btn btn-success">Submit</button>
    </form>
</div>

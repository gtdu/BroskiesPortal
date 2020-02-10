<div class="pl-4 pr-4 mb-4">
    <h2>Update Resource</h2>
    <form method="post">
        <div class="form-group">
            <label for="newResourceTitle">Title</label>
            <input name="title" type="text" class="form-control" id="newUserName" aria-describedby="emailHelp" placeholder="Roll Book" required value="<?php echo $item['title']; ?>">
        </div>
        <div class="form-group">
            <label for="newResourceLink">Link</label>
            <input name="link" type="url" class="form-control" id="newResourceLink" aria-describedby="emailHelp" placeholder="http://drive.google.com" required value="<?php echo $item['link']; ?>">
        </div>
        <div class="form-group">
            <label for="newResourcePosition">Position</label>
            <input name="position" type="text" class="form-control" id="newResourcePosition" aria-describedby="emailHelp" placeholder="Secretary" required value="<?php echo $item['position']; ?>">
        </div>
        <div class="form-group">
            <label for="newResourceDescription">Description</label>
            <input name="description" type="text" class="form-control" id="newResourceDescription" aria-describedby="emailHelp" placeholder="2019-2020 Roll Book" value="<?php echo $item['description']; ?>">
        </div>
        <div class="form-group">
            <label for="changeCoreLevel">Visibility</label>
            <select name="visibility" required id="changeCoreLevel" class="form-control" required>
                <option value="1" <?php if ($item['visibility'] == 1){ echo "selected"; }?>>Standard Brother</option>
                <option value="2" <?php if ($item['visibility'] == 2){ echo "selected"; }?>>MEC Officer</option>
                <option value="3" <?php if ($item['visibility'] == 3){ echo "selected"; }?>>EC Officer</option>
            </select>
        </div>
        <input type="hidden" name="action" value="editResource">
        <input type="hidden" name="resource_id" value="<?php echo $item['id']; ?>">
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

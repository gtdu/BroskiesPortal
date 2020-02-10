<div class="pl-4 pr-4 mb-4">
    <form method="post">
        <div class="form-group">
            <label for="randomID">Root URL</label>
            <input name="root_url" type="text" class="form-control" id="randomID" aria-describedby="aria" placeholder="/modules/doohickey" required value="<?php echo $item['root_url']; ?>">
        </div>
        <div class="form-group">
            <label for="randomID">Icon URL</label>
            <input name="icon_url" type="text" class="form-control" id="randomID" aria-describedby="aria" placeholder="http://google.com/png.jpg" value="<?php echo $item['icon_url']; ?>">
        </div>
        <div class="form-group">
            <label for="newUserName2">Access Level Names</label>
            <small>Make a comma seperated list of the permission level names with the first value corresponding to 0 and increasing</small>
            <input name="levelNames" type="text" class="form-control" id="newUserName2" aria-describedby="aria" placeholder="No Access, Pledge, Brother, Officer, Admin" value="<?php echo $item['levelNames']; ?>">
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" id="customSwitch1" name="external" <?php if ($item['external'] == 1) { echo "checked";}?>>
              <label class="custom-control-label" for="customSwitch1">Open In New Tab</label>
            </div>
        </div>
        <input type="hidden" name="action" value="editModule">
        <input type="hidden" name="module_id" value="<?php echo $item['id']; ?>">
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

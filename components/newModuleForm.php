<div class="pl-4 pr-4 mb-4">
    <form method="post">
        <div class="form-group">
            <label for="newUserName">Name</label>
            <input name="name" type="text" class="form-control" id="newUserName" aria-describedby="aria" placeholder="Doohickey" required>
        </div>
        <div class="form-group">
            <label for="randomID">Root URL</label>
            <input name="root_url" type="text" class="form-control" id="randomID" aria-describedby="aria" placeholder="/modules/doohickey" required>
        </div>
        <div class="form-group">
            <label for="randomID">Icon URL</label>
            <input name="icon_url" type="text" class="form-control" id="randomID" aria-describedby="aria" placeholder="http://google.com/png.jpg">
        </div>
        <div class="form-group">
            <label for="newUserName2">Default Access Level</label>
            <input name="defaultAccess" type="number" step="1" min="0" value="1" class="form-control" id="newUserName2" aria-describedby="aria" placeholder="1" required>
        </div>
        <div class="form-group">
            <label for="newUserName2">Access Level Names</label>
            <small>Make a comma separated list of the permission level names with the first value corresponding to 0 and increasing</small>
            <input name="levelNames" type="text" class="form-control" id="newUserName2" aria-describedby="aria" placeholder="No Access, Pledge, Brother, Officer, Admin">
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" id="customSwitch1" name="external">
              <label class="custom-control-label" for="customSwitch1">Open In New Tab</label>
            </div>
        </div>
        <input type="hidden" name="action" value="newModule">
        <button type="submit" class="btn btn-success">Create</button>
    </form>
</div>

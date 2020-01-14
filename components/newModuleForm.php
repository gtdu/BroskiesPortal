<div class="pl-4 pr-4 mb-4">
    <form method="post">
        <div class="form-group">
            <label for="newUserName">Name</label>
            <input name="name" type="text" class="form-control" id="newUserName" aria-describedby="emailHelp" placeholder="Doohickey" required>
        </div>
        <div class="form-group">
            <label for="newUserEmail">Root URL</label>
            <input name="url" type="text" class="form-control" id="newUserEmail" aria-describedby="emailHelp" placeholder="/modules/doohickey" required>
        </div>
        <div class="form-group">
            <label for="newUserName2">Default Access Level</label>
            <input name="defaultAccess" type="number" step="1" min="0" value="1" class="form-control" id="newUserName2" aria-describedby="emailHelp" placeholder="1" required>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" id="customSwitch1" name="external">
              <label class="custom-control-label" for="customSwitch1">Open In New Tab</label>
            </div>
        </div>
        <input type="hidden" name="action" value="newModule">
        <button type="submit" class="btn btn-success">Submit</button>
    </form>
</div>

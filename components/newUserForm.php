<div class="pl-4 pr-4 mb-4">
    <form method="post">
        <div class="form-group">
            <label for="newUserName">Name</label>
            <input name="name" type="text" class="form-control" id="newUserName" aria-describedby="aria" placeholder="Jernandez, Facinto" required>
        </div>
        <div class="form-group">
            <label for="randomID">Slack User ID</label>
            <input name="slack_id" type="text" class="form-control" id="randomID" aria-describedby="aria" placeholder="U12345" required>
        </div>
        <div class="form-group">
            <label for="randomID">Phone Number</label>
            <input name="phone" type="tel" class="form-control" id="randomID" aria-describedby="aria" placeholder="6784206969" required>
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-outline-secondary float-right btn-sm" onclick="generatePassword()">Generate Password</button>
            <label for="newUserPassword">Password</label>
            <input name="password" type="text" class="form-control" id="newUserPassword" aria-describedby="aria" placeholder="DikaiaBrother69" required>
        </div>
        <input type="hidden" name="action" value="newUser">
        <button type="submit" class="btn btn-success">Create User</button>
    </form>
</div>

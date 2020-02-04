<div class="pl-4 pr-4 mb-4">
    <form method="post">
        <div class="form-group">
            <label for="newUserName">Name</label>
            <input name="name" type="text" class="form-control" id="newUserName" aria-describedby="emailHelp" placeholder="Jernandez, Facinto" required>
        </div>
        <div class="form-group">
            <label for="newUserEmail">Email</label>
            <input name="email" type="email" class="form-control" id="newUserEmail" aria-describedby="emailHelp" placeholder="dude@gtdu.org" required>
        </div>
        <div class="form-group">
            <label for="newUserEmail">Phone Number</label>
            <input name="phone" type="tel" class="form-control" id="newUserEmail" aria-describedby="emailHelp" placeholder="6784206969" required>
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-outline-secondary float-right btn-sm" onclick="generatePassword()">Generate Password</button>
            <label for="newUserPassword">Password</label>
            <input name="password" type="text" class="form-control" id="newUserPassword" aria-describedby="emailHelp" placeholder="DikaiaBrother69" required>
        </div>
        <input type="hidden" name="action" value="newUser">
        <button type="submit" class="btn btn-success">Create User</button>
    </form>
</div>

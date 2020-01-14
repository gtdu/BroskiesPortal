<form method="post">
    <div class="form-group">
        <label for="loginEmail">Email</label>
        <input name="email" type="email" class="form-control" id="loginEmail" aria-describedby="emailHelp" placeholder="dude@email.org" required value="<?php echo $_GET['email']; ?>">
    </div>
    <input type="hidden" name="action" value="sendCode">
    <button type="submit" class="btn btn-primary">Send Reset Email</button>
</form>

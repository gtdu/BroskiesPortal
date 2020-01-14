<form method="post">
    <div class="form-group">
        <label for="loginEmail">Code</label>
        <input name="code" type="text" class="form-control" id="loginEmail" aria-describedby="emailHelp" placeholder="123456789" required value="<?php echo $_GET['code']; ?>">
    </div>
    <div class="form-group">
        <label for="loginPassword">Password</label>
        <input name="password" type="password" class="form-control" id="loginPassword" aria-describedby="emailHelp" placeholder="DikiaBrother69" required>
    </div>
    <input type="hidden" name="action" value="resetPassword">
    <button type="submit" class="btn btn-primary">Reset Password</button>
</form>

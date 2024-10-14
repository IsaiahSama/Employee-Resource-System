<?php

$current_user = SessionManager::get(SessionValues::USER_INFO->value);

if (is_null($current_user)) { 
    header("HTTP/1.0 401 Unauthorized");
    content_render('errors/401');
    die();
}

?>

<h2 class='subtitle'> Add New Employee </h2>
<span class="has-text-danger"><?php if (isset($_GET['error'])) echo htmlspecialchars($_GET['error']); ?></span>

<form action="/users/create" method="post">

    <div class="field">
        <label for="username" class="label">Employee's Username <span class="has-text-danger">*</span></label>
        <div class="control">
            <input type="text" name="username" id="username" class="input" required>
        </div>
    </div>

    <div class="field">
        <label for="email" class="label">Employee's Email <span class="has-text-danger">*</span></label>
        <div class="control">
            <input type="email" name="email" id="email" class="input" required>
        </div>
    </div>

    <div class="field">
        <label for="password" class="label">Password <span class="has-text-danger">*</span></label>
        <div class="control">
            <input type="password" name="password" id="password" class="input" required>
        </div>
    </div>    

    <div class="field">
        <label for="role" class="label">Role <span class="has-text-danger">*</span></label>

        <div class="select">
            <select name="role" id="role" required>
                <option value="1">Employee</option>
                <option value="2">Manager</option>
                <option value="3">Admin</option>
            </select>
        </div>
    </div>

    <div class="field">
        <div class="control">
            <input type="submit" value="Submit" class="button is-primary"/>
        </div>
    </div>

</form>

<h1 class="title">Register Page</h1>
<span class="has-text-danger has-text-weight-bold"><?php if (isset($_GET['error'])) echo $_GET['error']; ?></span>
<form action="/register" method="POST">
    <div class="field">
        <div class="label">Username</div>
        <div class="control">
            <input type="text" name="username" class="input" id="username" required>
        </div>
    </div>
    <div class="field">
        <div class="label">Email</div>
        <div class="control">
            <input type="email" name="email" id="email" class="input" required>
        </div>
    </div>
    <div class="field">
        <div class="label">Password</div>
        <div class="control">
            <input type="password" name="password" id="password" class="input" required>
        </div>
    </div>
    <div class="field">
        <div class="control">
            <button class="button is-success">Register</button>
        </div>
    </div>
</form>
<small>Already have an account? <a href="/login">Login here</a></small>
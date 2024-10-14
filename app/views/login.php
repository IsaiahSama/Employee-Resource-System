<h1 class="title">Login Page</h1>
<span class="has-text-success has-text-weight-bold"><?php if (isset($_GET['success'])) echo htmlspecialchars($_GET['success']) ?></span>
<span class="has-text-danger has-text-weight-bold"><?php if (isset($_GET['error'])) echo htmlspecialchars($_GET['error']); ?></span>
<form action="/login" method="post">
    <div class="field">
        <div class="label">Email</div>
        <div class="control">
            <input type="email" name="email" id="email" class="input">
        </div>
    </div>
    <div class="field">
        <div class="label">Password</div>
        <div class="control">
            <input type="password" name="password" id="password" class="input">
        </div>
    </div>
    <div class="field">
        <div class="control">
            <button class="button is-primary">Login</button>
        </div>
    </div>
</form>
<small>Don't have an account? <a href="/register">Register here</a></small>
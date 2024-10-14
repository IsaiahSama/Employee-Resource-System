<div class="is-flex is-justify-content-space-around is-flex-direction-column has-text-centered">
    <h1 class="title has-text-danger">UNAUTHORIZED!</h1>
    <h2 class="subtitle">Now now, you're not supposed to be here. Go back!</h2>
    <?php echo $error ?? '' ?>
    <p>(401 Authorized. Little birdie far from home)</p>
    <a href="/dashboard">Return to last valid save point</a>
</div>
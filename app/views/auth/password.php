<section class="panel form-panel">
    <div class="panel-head">
        <div>
            <span class="eyebrow">ACCOUNT SECURITY</span>
            <h2>Change your password</h2>
        </div>
        <a class="text-link" href="index.php?route=dashboard">Back to dashboard</a>
    </div>
    <form method="post" action="index.php?route=account/password" class="form-grid">
        <?= csrf_field() ?>
        <label class="wide">Current password
            <input type="password" name="current_password" required autocomplete="current-password">
        </label>
        <label>New password
            <input type="password" name="new_password" required minlength="12" maxlength="72" autocomplete="new-password">
        </label>
        <label>Confirm new password
            <input type="password" name="confirm_password" required minlength="12" maxlength="72" autocomplete="new-password">
        </label>
        <div class="wide"><button class="btn">Update password</button></div>
    </form>
</section>

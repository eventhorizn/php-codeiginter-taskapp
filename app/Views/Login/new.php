<?= $this->extend("layouts/default") ?>

<?= $this->section("title") ?>Login<?= $this->endSection() ?>

<?= $this->section("content") ?>

<h1>Login</h1>

<?= form_open("/login/create") ?>

    <div>
        <label for="email">Email</label>
        <input type="text" name="email" id="email" value="<?= old('email') ?>">
    </div>

    <div>
        <label for="password">Password</label>
        <input type="password" name="password">
    </div>

    <div>
        <label for="remember_me">
            <input type="checkbox" id="remember_me" name="remember_me"
            <?php if (old('remember_me')): ?>checked<?php endif; ?>> Remember Me
        </label>
    </div>

    <button>Log in</button>

    <a href="<?= site_url("/password/forgot") ?>">Forgot Password?</a>
</form>

<?= $this->endSection() ?>
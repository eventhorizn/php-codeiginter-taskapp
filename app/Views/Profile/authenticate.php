<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>Edit Profile<?= $this->endSection() ?>

<?= $this->section('content') ?>

<h1>Edit Profile</h1>

<p>Please enter your password to continue</p>

<?= form_open("/profile/processauthenticate") ?>

    <div>
        <label for="password">Password</label>
        <input type="password" name="password">
    </div>

    <button>Send</button>
    <a href="<?= site_url('/profile/show') ?>">Cancel</a>
</form>

<?= $this->endSection() ?>
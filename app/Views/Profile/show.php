<?= $this->extend("layouts/default") ?>

<?= $this->section("title") ?>Signup<?= $this->endSection() ?>

<?= $this->section("content") ?>

<h1 class="title">Profile</h1>

<?php if ($user->profile_image): ?>

    <img src="<?= site_url('/profile/image') ?>" 
          width="200" height="200" alt="profile_image">

    <div>
        <a class="button is-danger is-light" href="<?= site_url("/profileimage/delete") ?>">Delete Profile Image</a>
    </div>

<?php else: ?>

    <img src="<?= site_url('/images/blank_profile.png') ?>" 
        width="200" height="200" alt="profile_image">

<?php endif; ?>

<div class="content">
    <dl>
        <dt class="has-text-weight-bold">Name</dt>
        <dd><?= esc($user->name) ?></dd>

        <dt class="has-text-weight-bold">Email</dt>
        <dd><?= esc($user->email) ?></dd>
    </dl>
</div>

<a class="button is-link" href="<?= site_url("/profile/edit") ?>">Edit</a>

<a class="button is-link" href="<?= site_url("/profile/editpassword") ?>">Change Password</a>

<a class="button is-link" href="<?= site_url("/profileimage/edit") ?>">Change Profile Image</a>

<?= $this->endSection() ?>
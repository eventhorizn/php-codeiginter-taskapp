<?= $this->extend("layouts/default") ?>

<?= $this->section("title") ?>Signup<?= $this->endSection() ?>

<?= $this->section("content") ?>

<h1>Profile</h1>

<?php if ($user->profile_image): ?>

    <img src="<?= site_url('/profile/image') ?>" 
          width="200" height="200" alt="profile_image">

    <a href="<?= site_url("/profileimage/delete") ?>">Delete Profile Image</a>

<?php else: ?>

    <img src="<?= site_url('/images/blank_profile.png') ?>" 
        width="200" height="200" alt="profile_image">

<?php endif; ?>

<dl>
    <dt>Name</dt>
    <dd><?= esc($user->name) ?></dd>

    <dt>Email</dt>
    <dd><?= esc($user->email) ?></dd>
</dl>

<a href="<?= site_url("/profile/edit") ?>">Edit</a>

<a href="<?= site_url("/profile/editpassword") ?>">Change Password</a>

<a href="<?= site_url("/profileimage/edit") ?>">Change Profile Image</a>

<?= $this->endSection() ?>
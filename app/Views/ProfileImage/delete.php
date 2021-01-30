<?= $this->extend("layouts/default") ?>

<?= $this->section("title") ?>Delete Profile Image<?= $this->endSection() ?>

<?= $this->section("content") ?>

<h1>Delete Profile Image</h1>

<p>Are you sure?</p>

<?= form_open("/profileimage/delete") ?>

    <button>Yes</button>
    <a href="<?= site_url('/profile/show/') ?>">Cancel</a>

</form>

<?= $this->endSection() ?>
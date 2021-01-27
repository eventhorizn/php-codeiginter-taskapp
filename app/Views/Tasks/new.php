<?= $this->extend("layouts/default") ?>

<?= $this->section("title") ?>Tasks<?= $this->endSection() ?>

<?= $this->section("content") ?>

<h1>New Task</h1>

<?= form_open("/tasks/create") ?>
    <div>
        <label for="description">Description</label>
        <input type="text" name="description" id="description" value="">
    </div>

    <button>Save</button>
    <a href="<?= site_url("/tasks") ?>">Cancel</a>
</form>

<?= $this->endSection() ?>
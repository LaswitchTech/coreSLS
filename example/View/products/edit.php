<div class="py-5">
    <div class="container">
        <div class="d-flex flex-columns justify-content-start align-items-center mb-3">
            <div class="flex-shrink-1">
                <a href="<?= $this->getParent() ?>" class="btn btn-link text-decoration-none px-2"><i class="bi bi-chevron-left" style="font-size: 2em;"></i></a>
            </div>
            <div class="flex-grow-1">
                <a href="<?= $this->getRoute() ?>?product=<?= $this->Return['product']['name'] ?>" class="nav-link px-2">
                    <h1 class="display-5 fw-lighter m-0">
                        Product <span style="font-size: 0.5em;">Edit</span>
                    </h1>
                </a>
            </div>
        </div>
        <?php if(isset($this->Return['status']) && $this->Return['status'] === 'invalid'): ?>
            <div class="alert alert-danger m-3 mt-0 d-flex flex-columns" role="alert">
                <div class="flex-shrink-1 pe-3">
                    <i class="bi bi-exclamation-triangle fs-2"></i>
                </div>
                <div class="flex-grow-1">
                    <p class="m-0 mb-1"><strong>An error occured!</strong></p>
                    <p class="m-0"><?= $this->Return['message'] ?></p>
                </div>
            </div>
        <?php endif; ?>
        <?php if(isset($this->Return['status']) && $this->Return['status'] === 'valid'): ?>
            <div class="alert alert-success m-3 mt-0 d-flex flex-columns" role="alert">
                <div class="flex-shrink-1 pe-3">
                    <i class="bi bi-check2-circle fs-2"></i>
                </div>
                <div class="flex-grow-1">
                    <p class="m-0 mb-1"><strong>Success!</strong></p>
                    <p class="m-0"><?= $this->Return['message'] ?></p>
                </div>
            </div>
        <?php endif; ?>
        <form action="?product=<?= $this->Return['product']['name'] ?>" method="post">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="name" name="name" placeholder="name" value="<?= isset($_POST['name']) ? $_POST['name'] : $this->Return['product']['name'] ?>">
                <label for="name">Name</label>
            </div>
            <div class="form-floating mb-3">
                <textarea class="form-control" placeholder="description" id="description" name="description"><?= isset($_POST['description']) ? $_POST['description'] : $this->Return['product']['description'] ?></textarea>
                <label for="description">Description</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="term" aria-label="term" name="term">
                    <option value="" <?= isset($_POST['term']) ? '' : 'selected' ?>>Select Licensing Terms</option>
                    <?php foreach($this->Return['terms'] as $term): ?>
                        <option value="<?= $term ?>" <?= (isset($_POST['term']) && $_POST['term'] === $term) || ($this->Return['product']['term'] === $term) ? 'selected' : '' ?>><?= ucfirst($term) ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="term">Licensing Terms</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="duration" aria-label="duration" name="duration">
                    <option value="" <?= isset($_POST['duration']) ? '' : 'selected' ?>>Select Terms Duration</option>
                    <?php foreach($this->Return['durations'] as $duration): ?>
                        <option value="<?= $duration ?>" <?= (isset($_POST['duration']) && $_POST['duration'] === $duration) || ($this->Return['product']['duration'] === $duration) ? 'selected' : '' ?>><?= ucfirst($duration) ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="duration">Duration</label>
            </div>
            <button type="submit" class="btn btn-lg btn-success w-100">Save</button>
        </form>
    </div>
</div>

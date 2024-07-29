<div class="py-5">
    <div class="container">
        <div class="d-flex flex-columns justify-content-start align-items-center mb-3">
            <div class="flex-shrink-1">
                <a href="<?= $this->getParent() ?>" class="btn btn-link text-decoration-none px-2"><i class="bi bi-chevron-left" style="font-size: 2em;"></i></a>
            </div>
            <div class="flex-grow-1">
                <a href="<?= $this->getRoute() ?>" class="nav-link px-2">
                    <h1 class="display-5 fw-lighter m-0">
                        License <span style="font-size: 0.5em;">Create</span>
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
                    <p class="m-0"><?= $this->Return['message'] ?>.</p>
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
                    <p class="m-0"><?= $this->Return['message'] ?>.</p>
                </div>
            </div>
        <?php endif; ?>
        <form action="?type=license" method="post">
            <div class="form-floating mb-3">
                <select class="form-select" id="product" aria-label="product" name="product">
                    <option value="" <?= isset($_POST['product']) ? '' : 'selected' ?>>Select Product</option>
                    <?php foreach($this->Return['products'] as $product): ?>
                        <option value="<?= $product['id'] ?>" <?= isset($_POST['product']) && $_POST['product'] === $product['name'] ? 'selected' : '' ?>><?= ucfirst($product['name']) ?> - <?= ucfirst($product['description']) ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="product">Product</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="user" name="user" placeholder="user" value="<?= isset($_POST['user']) ? $_POST['user'] : '' ?>">
                <label for="user">User</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="term" aria-label="term" name="term">
                    <option value="" <?= isset($_POST['term']) ? '' : 'selected' ?>>Select Licensing Terms</option>
                    <?php foreach($this->Return['terms'] as $term): ?>
                        <option value="<?= $term ?>" <?= isset($_POST['term']) && $_POST['term'] === $term ? 'selected' : '' ?>><?= ucfirst($term) ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="term">Licensing Terms</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="duration" aria-label="duration" name="duration">
                    <option value="" <?= isset($_POST['duration']) ? '' : 'selected' ?>>Select Terms Duration</option>
                    <?php foreach($this->Return['durations'] as $duration): ?>
                        <option value="<?= $duration ?>" <?= isset($_POST['duration']) && $_POST['duration'] === $duration ? 'selected' : '' ?>><?= ucfirst($duration) ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="duration">Duration</label>
            </div>
            <button type="submit" class="btn btn-lg btn-success w-100">Create</button>
        </form>
    </div>
</div>
<pre><?php var_dump($this->Return); ?></pre>

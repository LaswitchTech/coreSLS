<div class="py-5">
    <div class="container">
        <div class="d-flex flex-columns justify-content-start align-items-center mb-3">
            <div class="flex-shrink-1">
                <a href="<?= $this->getParent() ?>" class="btn btn-link text-decoration-none px-2"><i class="bi bi-chevron-left" style="font-size: 2em;"></i></a>
            </div>
            <div class="flex-grow-1">
                <a href="<?= $this->getRoute() ?>?product=<?= $this->Return['product']['name'] ?>" class="nav-link px-2">
                    <h1 class="display-5 fw-lighter m-0">
                        Product <span style="font-size: 0.5em;">Delete</span>
                    </h1>
                </a>
            </div>
        </div>
        <?php if(isset($this->Return['status']) && $this->Return['status'] === 'valid'): ?>
            <div class="alert alert-success m-3 mt-0 d-flex" role="alert">
                <div class="flex-shrink-1 pe-3">
                    <i class="bi bi-check2-circle fs-2"></i>
                </div>
                <div class="flex-grow-1">
                    <p class="m-0 mb-1"><strong>Success!</strong></p>
                    <p class="m-0"><?= $this->Return['message'] ?></p>
                </div>
            </div>
        <?php else: ?>
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
            <form action="?product=<?= $this->Return['product']['name'] ?>" method="post">
                <div class="px-3">
                    <div class="card card-body text-bg-light my-4 d-block" style="border-left-width: 4px;">
                        <div class="d-flex">
                            <div class="flex-shrink-1 pe-3">
                                <i class="bi bi-question-circle fs-2"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="m-0 mb-1"><strong>Are you sure?</strong></p>
                                <p class="m-0">You are about to delete the product <strong><?= $this->Return['product']['name'] ?></strong>. Are you sure you want to delete this product?</p>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-lg btn-danger w-100">Delete</button>
            </form>
        <?php endif; ?>
    </div>
</div>

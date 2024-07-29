<div class="py-5">
    <div class="container">
        <div class="d-flex flex-columns justify-content-start align-items-center mb-3">
            <div class="flex-shrink-1">
                <a href="<?= $this->getParent() ?>" class="btn btn-link text-decoration-none px-2"><i class="bi bi-chevron-left" style="font-size: 2em;"></i></a>
            </div>
            <div class="flex-grow-1">
                <a href="<?= $this->getRoute() ?>" class="nav-link px-2">
                    <h1 class="display-5 fw-lighter m-0">
                        Licenses <span style="font-size: 0.5em;">Index</span>
                    </h1>
                </a>
            </div>
            <div class="flex-shrink-1">
                <a href="<?= $this->getRoute() ?>/create" class="btn btn-link text-decoration-none px-2"><i class="bi bi-plus-lg" style="font-size: 2em;"></i></a>
            </div>
        </div>
        <table class="table table-striped table-hover rounded">
            <thead class="table-dark">
                <tr>
                    <th class="rounded-top px-3" style="border-top-right-radius:0px!important;">License</th>
                    <th class="px-3">Product</th>
                    <th class="px-3">User</th>
                    <th class="px-3">Term</th>
                    <th class="px-3">Duration</th>
                    <th class="px-3">Expire</th>
                    <th class="rounded-top px-3" style="border-top-left-radius:0px!important;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->Return as $record): ?>
                    <tr>
                        <td class="border-end"><?= $record['license'] ?></td>
                        <td class="border-end"><?= $record['product'] ?></td>
                        <td class="border-end"><?= $record['user'] ?></td>
                        <td class="border-end"><?= $record['term'] ?></td>
                        <td class="border-end"><?= $record['duration'] ?></td>
                        <td class="border-end"><?= $record['expire'] ?></td>
                        <td>
                            <a href="<?= $this->getRoute() ?>/renew?license=<?= $record['license'] ?>" class="btn btn-sm btn-primary"><i class="bi bi-arrow-repeat"></i></a>
                            <a href="<?= $this->getRoute() ?>/edit?license=<?= $record['license'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                            <a href="<?= $this->getRoute() ?>/delete?license=<?= $record['license'] ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

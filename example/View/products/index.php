<div class="py-5">
    <div class="container">
        <h1 class="display-5 fw-lighter m-0">Products <span style="font-size: 0.5em;">Index</span></h1>
        <div class="w-100 btn-group m-3">
            <a href="/" class="btn btn-primary">Back</a>
            <a href="/products/create" class="btn btn-success">Create</a>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Term</th>
                    <th>Duration</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->Return as $license): ?>
                    <tr>
                        <td><?= $license['name'] ?></td>
                        <td><?= $license['description'] ?></td>
                        <td><?= $license['term'] ?></td>
                        <td><?= $license['duration'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

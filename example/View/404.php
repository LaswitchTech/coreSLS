<!doctype html>
<html lang="en" class="h-100 w-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <title><?= $this->getLabel(); ?></title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  </head>
  <body class="h-100 w-100">
    <div class="row h-100 w-100 m-0 p-0">
      <div class="col h-100 m-0 p-0">
        <div class="container h-100">
          <div class="d-flex h-100 row align-items-center justify-content-center">
            <div class="col">
                <h3 class="mt-5 mb-3">404: <strong>Not Found</strong></h3>
                <p>The requested URL <?= $this->getNamespace() ?> was not found on this server.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>

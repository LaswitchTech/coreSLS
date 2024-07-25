<h1><?= $this->getLabel(); ?></h1>
<ul>
  <?php foreach($this->getRoutes() as $route => $param){ ?>
    <?php if(str_starts_with($route,'/')){ ?>
      <li><a href="<?= $route ?>"><?= $param['label'] ?></a></li>
    <?php } ?>
  <?php } ?>
</ul>
<?= phpinfo(); ?>

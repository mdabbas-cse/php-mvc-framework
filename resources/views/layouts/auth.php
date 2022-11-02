<!doctype html>
<html lang="en">
<?php

use MVC\Framework\Helpers\FlashMessages;
?>

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?= $this->siteTitle(); ?> | MVC Framework</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <!-- custom CSS -->
  <link rel="stylesheet" href="<?= css('/style.css') ?>">
  <?= $this->content('head'); ?>

</head>

<body>

  <?php include('navbar.php') ?>
  <div class="container" style="width: 50%;">
    <?php (new FlashMessages)->getFlash('success'); ?>
  </div>
  <?= $this->content('body'); ?>


  <!-- Optional JavaScript; choose one of the two! -->
  <?= $this->content('script'); ?>

</body>

</html>
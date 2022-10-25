<!doctype html>
<html lang="en">
<?php

$proot = MVC\Framework\App::get('config')['app']['web-root'];



?>

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?= $this->siteTitle(); ?> | MVC Framework</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <!-- custom CSS -->
  <link rel="stylesheet" href="<?= $proot ?>resources/assets/css/style.css">
  <?= $this->content('head'); ?>

</head>

<body>
  <h1>Auth layouts</h1>
  <?= $this->content('body'); ?>

</body>

</html>
<?php $this->setSiteTile('Home'); ?>

<?php $this->start('head'); ?>

<meta name="autor" content="test">

<?php $this->end(); ?>

<?php $this->start('body'); ?>

<h1 class="text-center red">Welcome to LaraCore Framework <?= $title ?? '' ?> </h1>

<script>
  $(function() {
    alert('hello');
  })
</script>

<?php $this->end(); ?>
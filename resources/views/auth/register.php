<?php

use Lora\Core\Framework\Components\Form;

$this->setSiteTile('Home'); ?>

<?php $this->start('head'); ?>

<!-- include any style sheet and script sheet -->

<?php $this->end(); ?>

<?php $this->start('body'); ?>

<?php //if ($model) dd($model);

?>
<div class="container mt-4">
  <div class="row">
    <div class="col-md-6 offset-md-3 bg-white p-4 rounded">
      <h1 class="text-center">Registration</h1>
      <?php $form = Form::begin(app_url('auth-register'), 'post') ?>
      <div class="row mb-4">
        <div class="col">
          <?= $form->field('text', 'firstname', 'First name') ?>
        </div>
        <div class="col">
          <?= $form->field('text', 'lastname', 'Last name') ?>
        </div>
      </div>

      <!-- Email input -->
      <?= $form->field('email', 'email', 'Email address', 'form-outline mb-4') ?>

      <!-- Password input -->
      <div class="row">
        <div class="col">
          <?= $form->field('password', 'password', 'Password', 'form-outline mb-4') ?>
        </div>
        <div class="col">
          <?= $form->field('password', 'confirmPassword', 'Confirm Password', 'form-outline mb-4') ?>
        </div>
      </div>

      <!-- Submit button -->
      <button type="submit" class="btn btn-primary btn-block mb-4">Sign up</button>
      <?php Form::end() ?>
    </div>
  </div>
</div>

<?php $this->end(); ?>
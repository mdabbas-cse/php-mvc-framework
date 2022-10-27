<?php $this->setSiteTile('Home'); ?>

<?php $this->start('head'); ?>

<!-- include any style sheet and script sheet -->

<?php $this->end(); ?>

<?php $this->start('body'); ?>

<?php if ($model) var_dump($model) ?>
<div class="container mt-4">
  <div class="row">
    <div class="col-md-6 offset-md-3 bg-white p-4 rounded">
      <h1 class="text-center">Registration</h1>
      <form action="<?= app_url('auth-register') ?>" method="POST">
        <!-- 2 column grid layout with text inputs for the first and last names -->
        <div class="row mb-4">
          <div class="col">
            <div class="form-outline">
              <input type="text" id="name" name="firstname" class="form-control" />
              <label class="form-label" for="name" name="firstname">First name</label>
            </div>
          </div>
          <div class="col">
            <div class="form-outline">
              <input type="text" id="lastname" name="lastname" class="form-control" />
              <label class="form-label" for="lastname" name="lastname">Last name</label>
            </div>
          </div>
        </div>

        <!-- Email input -->
        <div class="form-outline mb-4">
          <input type="email" id="email" name="email" class="form-control" />
          <label class="form-label" for="email" name="email">Email address</label>
        </div>

        <!-- Password input -->
        <div class="row">
          <div class="col">
            <div class="form-outline mb-4">
              <input type="password" id="password" name="password" class="form-control" />
              <label class="form-label" for="password" name="password">Password</label>
            </div>
          </div>
          <div class="col">
            <div class="form-outline mb-4">
              <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" />
              <label class="form-label" for="confirmPassword" name="confirmPassword">Confirm Password</label>
            </div>
          </div>
        </div>

        <!-- Submit button -->
        <button type="submit" class="btn btn-primary btn-block mb-4">Sign up</button>
      </form>
    </div>
  </div>
</div>

<?php $this->end(); ?>
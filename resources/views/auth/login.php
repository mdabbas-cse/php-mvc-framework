<?php $this->setSiteTile('Home'); ?>

<?php $this->start('head'); ?>

<!-- include any style sheet and script sheet -->

<?php $this->end(); ?>

<?php $this->start('body'); ?>

<!-- include any body content -->
<!-- login form from using bootstrap 5 -->
<div class="container mt-4">
  <div class="row">
    <div class="col-md-6 offset-md-3 bg-white p-4 rounded">
      <h1 class="text-center">Login</h1>
      <form action="http://pro.af/register" method="post">
        <div class="mb-3">
          <?=
          input([
            'label' => 'Email address',
            'type' => 'text',
            'name' => 'email',
            'id' => 'email',
            'placeholder' => 'Enter your name',
            'required' => true,
            'error' => $this->errors['name'] ?? false
          ])
          ?>
        </div>
        <div class="mb-3">
          <?=
          input([
            'label' => 'Password',
            'type' => 'password',
            'name' => 'password',
            'id' => 'password',
            'placeholder' => 'Enter your password',
            'required' => true,
            'error' => $this->errors['password'] ?? false
          ])
          ?>
        </div>
        <!-- <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" id="remember" name="remember">
          <label class="form-check-label" for="remember">Remember Me</label>
        </div> -->
        <?= button([
          'type' => 'submit',
          'label' => 'Submit',
        ]) ?>
      </form>
    </div>
  </div>

  <?php $this->end(); ?>

  <?php $this->start('script'); ?>
  <!-- include any script -->
  <script>
    // your script
  </script>

  <?php $this->end(); ?>
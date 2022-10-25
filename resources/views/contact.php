<?php $this->setSiteTile('Home'); ?>

<?php $this->start('head'); ?>

<!-- include any style sheet and script sheet -->

<style>
  .form {
    width: 50%;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #fff;
    margin-top: 50px;
  }
</style>

<?php $this->end(); ?>

<?php $this->start('body'); ?>
<div class="form">

  <form method="POST" action="http://pro.af/contact">
    <div class="mb-3">
      <label for="exampleInputEmail1" class="form-label">Email address</label>
      <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
    </div>
    <div class="mb-3">
      <label for="exampleInputPassword1" class="form-label">Password</label>
      <input type="password" name="password" class="form-control" id="exampleInputPassword1">
    </div>
    <!-- <div class="mb-3 form-check">
      <input type="checkbox" class="form-check-input" id="exampleCheck1">
      <label class="form-check-label" for="exampleCheck1">Check me out</label>
    </div> -->
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</div>

<?php $this->end(); ?>
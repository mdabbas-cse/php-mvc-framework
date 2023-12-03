<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <!-- simple contact form -->
  <form action="<?= app_url('/contact') ?>" method="post">

    <div class="mb-3">
      <label for="name" class="form-label">Name</label>
      <input type="text" name="name" id="name" class="form-control" placeholder="Enter your name" required>
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Email address</label>
      <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
    </div>
    <div class="mb-3">
      <label for="message" class="form-label">Message</label>
      <textarea name="message" id="message" cols="30" rows="10" class="form-control" placeholder="Enter your message"
        required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</body>

</html>
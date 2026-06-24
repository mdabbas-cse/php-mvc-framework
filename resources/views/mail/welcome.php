<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
    .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 8px; overflow: hidden; }
    .header { background: #4f46e5; padding: 32px; text-align: center; }
    .header h1 { color: #ffffff; margin: 0; font-size: 24px; }
    .body { padding: 32px; color: #374151; line-height: 1.6; }
    .body h2 { color: #111827; }
    .button { display: inline-block; margin-top: 20px; padding: 12px 28px; background: #4f46e5; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; }
    .footer { padding: 20px 32px; background: #f9fafb; text-align: center; font-size: 12px; color: #9ca3af; }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="header">
      <h1><?= htmlspecialchars($_ENV['APP_NAME'] ?? 'LaraCore') ?></h1>
    </div>
    <div class="body">
      <h2>Welcome, <?= htmlspecialchars($user['name'] ?? 'there') ?>!</h2>
      <p>Thank you for joining us. Your account has been created successfully.</p>
      <p>You can now log in and start exploring the platform.</p>
      <a href="<?= htmlspecialchars($_ENV['APP_URL'] ?? '/') ?>" class="button">Get Started</a>
    </div>
    <div class="footer">
      <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($_ENV['APP_NAME'] ?? 'LaraCore') ?>. All rights reserved.</p>
    </div>
  </div>
</body>
</html>

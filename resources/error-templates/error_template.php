<!-- error_template.php -->

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Error Page</title>
  <style>
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    background-color: #f4f4f4;
  }

  .error-container {
    text-align: center;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

  h1 {
    color: #e44d26;
  }

  p {
    color: #333;
    margin: 20px 0;
  }

  a {
    color: #007bff;
    text-decoration: none;
  }

  a:hover {
    text-decoration: underline;
  }
  </style>
</head>


<body>
  <div class="error-container">
    <h1>Error</h1>
    <p>Oops! Something went wrong.</p>
    <p>Return <a href="/">home</a>.</p>
    <p>Error Details:</p>
    <p><?php echo "Severity: $severity, Message: $message, File: $file, Line: $line"; ?></p>
  </div>
</body>

</html>
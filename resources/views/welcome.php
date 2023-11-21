<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
  <title>Welcome to Laracore</title>

  <style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(to right, #3498db, #2c3e50);
    color: #fff;
  }

  header {
    background: #2c3e50;
    color: #fff;
    text-align: center;
    padding: 2em 0;
  }

  h1 {
    font-size: 2em;
  }

  .highlight {
    color: #3498db;
  }

  main {
    max-width: 800px;
    margin: 40px auto;
    text-align: center;
  }

  section {
    margin-bottom: 40px;
  }

  h2 {
    font-size: 1.8em;
    color: #ecf0f1;
  }

  ul {
    list-style: none;
    padding: 0;
  }

  li {
    font-size: 1.2em;
    margin-bottom: 10px;
  }

  #downloadButton {
    display: inline-block;
    background: #3498db;
    color: #fff;
    padding: 15px 30px;
    font-size: 1.2em;
    text-decoration: none;
    border-radius: 5px;
    transition: background 0.3s ease;
  }

  #downloadButton:hover {
    background: #2980b9;
  }

  footer {
    background: #2c3e50;
    color: #ecf0f1;
    text-align: center;
    padding: 1em 0;
    position: fixed;
    bottom: 0;
    width: 100%;
  }
  </style>
</head>

<body>

  <header>
    <h1>Welcome to <span class="highlight">Laracore</span> Framework</h1>
  </header>

  <main>
    <section id="features">
      <h2>Features</h2>
      <ul>
        <li>Easy to use and customize</li>
        <li>Powerful MVC architecture</li>
        <li>Integrated ORM for database operations</li>
        <li>Responsive design out of the box</li>
      </ul>
    </section>

    <section id="download">
      <h2>Download Laracore</h2>
      <p>Get started with Laracore today. Download the latest version below:</p>
      <a href="#" id="downloadButton">Download Now</a>
    </section>
  </main>

  <footer>
    <p>&copy; 2023 Laracore Framework. All rights reserved.</p>
  </footer>

  <script src="script.js"></script>
</body>

</html>
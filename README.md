# LaraCore Framework

A lightweight PHP MVC framework built for fast, enjoyable web development. LaraCore provides routing, query building, form validation, migrations, seeders, CLI tooling, mail service, and REST API support — all without heavy dependencies.

> **Note:** This framework is actively under development.

---

## Table of Contents

- [Requirements](#requirements)
- [Features](#features)
- [Installation](#installation)
  - [Standard Setup](#standard-setup)
  - [Docker Setup](#docker-setup)
- [Directory Structure](#directory-structure)
- [Configuration](#configuration)
- [Routing](#routing)
- [Controllers](#controllers)
- [Models](#models)
- [Views & Layouts](#views--layouts)
- [Validation](#validation)
- [Middleware](#middleware)
- [Database Migrations](#database-migrations)
- [Seeders & Factories](#seeders--factories)
- [Authentication & Sessions](#authentication--sessions)
- [Mail Service](#mail-service)
- [REST API](#rest-api)
- [Helper Functions](#helper-functions)
- [CLI Commands](#cli-commands)
- [Roadmap](#roadmap)
- [License](#license)

---

## Requirements

- PHP >= 7.4 (8.0+ recommended)
- Composer
- MySQL 5.7+
- Apache (mod_rewrite enabled) or Nginx

---

## Features

- [x] Simple and fast routing with named routes and parameter binding
- [x] Custom query builder with PDO prepared statements
- [x] MVC architecture with base Controller, Model, and View classes
- [x] Multi-layout view template system
- [x] Form validation with built-in rules
- [x] CSRF token support
- [x] Middleware pipeline (auth, guest, admin, API auth)
- [x] Mail service with SMTP support (PHPMailer) and Mailable classes
- [x] REST API support with optional bearer token authentication
- [x] Environment variables via custom `.env` loader
- [x] Database migrations with fluent Blueprint API
- [x] Database seeders and factories
- [x] Session management and password hashing
- [x] Flash messages and input sanitization helpers
- [x] CLI tool (`laracore`) for code generation and migrations
- [x] Docker support (PHP 8.2 + Apache + MySQL 8.0 + Mailpit)
- [ ] Full ORM with relationships
- [ ] User management system
- [ ] Multi-authentication system
- [ ] Blade-style templating

---

## Installation

### Standard Setup

**1. Clone the repository**
```bash
git clone https://github.com/mdabbas-cse/php-mvc-framework.git
cd php-mvc-framework
```

**2. Install dependencies**
```bash
composer install
```

**3. Set up environment**
```bash
cp .env.example .env
```

Edit `.env` with your settings:
```env
APP_NAME=Laracore
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000/
APP_DEFAULT_LAYOUT=default
APP_TIME_ZONE=Asia/Dhaka

DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=laracore
DB_USERNAME=root
DB_PASSWORD=

MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS=your@gmail.com
MAIL_FROM_NAME=Laracore
```

**4. Run migrations**
```bash
php laracore migrate
```

**5. Start the development server**
```bash
php laracore serve
# or
php -S localhost:8000
```

Visit `http://localhost:8000`

---

### Docker Setup

The project ships with a fully configured Docker environment — PHP 8.2 + Apache, MySQL 8.0, and Mailpit for local email testing.

**1. Copy the environment file**
```bash
cp .env.example .env
```

**2. Build and start all containers**
```bash
docker compose up -d
```

**3. Run migrations**
```bash
docker compose exec app php laracore migrate
```

**Services:**

| Service | URL / Connection |
|---|---|
| App (PHP/Apache) | http://localhost:8000 |
| Mailpit web UI | http://localhost:8025 |
| MySQL (from host) | `localhost:3307` |

**Useful Docker commands:**
```bash
docker compose up -d            # Start all services
docker compose down             # Stop all services
docker compose logs -f app      # Stream app logs
docker compose exec app bash    # Shell into the app container
docker compose exec app php laracore migrate
```

**Connect to MySQL from a GUI tool (TablePlus, DBeaver, etc.):**
- Host: `localhost`, Port: `3307`
- Database: `laracore`, Username: `laracore`, Password: `secret`

> The app container talks to MySQL over the internal Docker network at `db:3306` — no config changes needed.

---

## Directory Structure

```
php-mvc-framework/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── HomeController.php
│   │   │   ├── UserController.php
│   │   │   ├── Api/
│   │   │   │   └── UserApiController.php
│   │   │   └── Auth/
│   │   │       └── AuthController.php
│   │   ├── Middlewares/
│   │   │   ├── AuthMiddleware.php
│   │   │   ├── GuestMiddleware.php
│   │   │   ├── AdminMiddleware.php
│   │   │   └── AuthApiMiddleware.php
│   │   └── Kernel.php
│   ├── Mail/
│   │   └── WelcomeMail.php
│   ├── Models/
│   │   └── Users.php
│   └── Providers/
│       ├── AppServiceProvider.php
│       └── RouteServiceProvider.php
├── config/
│   └── Config.php
├── database/
│   ├── migrations/
│   ├── Seeders/
│   └── Factories/
├── docker/
│   └── apache/
│       └── 000-default.conf
├── framework/
│   ├── Application.php
│   ├── Bootstrap.php
│   ├── Controller.php
│   ├── Request.php
│   ├── Response.php
│   ├── View.php
│   ├── Sessions.php
│   ├── Validation.php
│   ├── Helpers.php
│   ├── Mail/
│   │   ├── Mailable.php
│   │   └── Mailer.php
│   ├── Routers/
│   ├── Db/
│   ├── Console/
│   └── Helpers/
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   ├── auth/
│   │   ├── mail/
│   │   │   └── welcome.php
│   │   └── *.php
│   └── assets/
│       ├── css/
│       └── js/
├── routes/
│   ├── web.php
│   └── api.php
├── storage/
│   ├── logs/
│   ├── cache/
│   └── sessions/
├── .env
├── .env.example
├── .dockerignore
├── .htaccess
├── Dockerfile
├── docker-compose.yml
├── index.php
├── laracore
└── composer.json
```

---

## Configuration

All configuration is in `config/Config.php`, driven by `.env` values.

**Key `.env` variables:**

```env
# Application
APP_NAME=Laracore
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000/
APP_DEFAULT_LAYOUT=default
APP_TIME_ZONE=Asia/Dhaka

# Database
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=laracore
DB_USERNAME=root
DB_PASSWORD=

# Mail
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_ENCRYPTION=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=hello@example.com
MAIL_FROM_NAME=Laracore

# API Token (optional)
API_TOKEN_CHECK=false
API_TOKEN_KEY=your-secret-key
```

Access config values in code:
```php
use LaraCore\Framework\Configuration;

$mailConfig = Configuration::get('mail');
$allConfig  = Configuration::all();
```

---

## Routing

Define routes in `routes/web.php` for web and `routes/api.php` for API endpoints.

### HTTP Methods

```php
Router::get($uri, $action);
Router::post($uri, $action);
Router::put($uri, $action);
Router::delete($uri, $action);
```

### Route Actions

```php
// Closure
Router::get('/', function (Request $request, Response $response) {
    return view('welcome');
});

// Controller method
Router::get('/users/{id}', [UserController::class, 'show']);

// View shorthand (renders view directly, no controller)
Router::get('/about', 'about');
```

### Route Parameters

```php
// Basic
Router::get('/user/{id}', [UserController::class, 'show']);

// Regex-constrained
Router::get('/post/{id:\d+}', [PostController::class, 'show']);

// Access inside controller
$id = $request->getParam('id');
```

### Named Routes & Middleware

```php
Router::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Generate URL
$url = Router::route('dashboard');

// Redirect by name
return $response->route('dashboard');
```

---

## Controllers

```bash
php laracore make:controller UserController
```

```php
namespace LaraCore\App\Http\Controllers;

use LaraCore\Framework\Controller;
use LaraCore\Framework\Request;
use LaraCore\Framework\Response;
use LaraCore\App\Models\Users;

class UserController extends Controller
{
    public function index(Request $request, Response $response)
    {
        $users = (new Users())->getAll();
        return $this->view('users.index', compact('users'));
    }

    public function store(Request $request, Response $response)
    {
        $this->validation($request, [
            'name'  => ['required'],
            'email' => ['required', 'email'],
        ]);

        if ($this->isValidate()) {
            $user = new Users();
            $user->loadData($request->all());
            $user->save();
            return $response->redirect('/users');
        }

        return $this->view('users.create');
    }
}
```

| Method | Description |
|---|---|
| `$this->view($name, $data)` | Render a view |
| `$this->setLayout($layout)` | Override default layout |
| `$this->validation($request, $rules)` | Run validation |
| `$this->isValidate()` | Check if validation passed |
| `$this->errors()` | Get validation errors array |

---

## Models

```bash
php laracore make:model User
```

```php
namespace LaraCore\App\Models;

use LaraCore\Framework\Db\DataModel;

class Users extends DataModel
{
    protected $table    = 'users';
    protected $fillable = ['name', 'email', 'password'];

    public function tableName(): string { return $this->table; }
    public function attributes(): array { return $this->fillable; }
}
```

**CRUD:**

```php
$model = new Users();

// Create
$model->name     = 'John';
$model->email    = 'john@example.com';
$model->password = Hash::make('secret');
$model->save();

// Read
$user  = $model->find(1);
$all   = $model->getAll();
$one   = $model->findOne(['email' => 'a@b.com']);
$rows  = $model->selectWhere(['name', 'email'], ['active' => 1]);

// Update
$user = $model->find(1);
$user->name = 'Jane';
$user->update(1);

// Delete
$model->delete(1);

// Load array into model properties
$model->loadData($request->all());
```

---

## Views & Layouts

Views live in `resources/views/`, layouts in `resources/views/layouts/`.

```php
// Render with data
return view('home', ['title' => 'Home Page']);
return $this->view('home', ['title' => 'Home Page']);
```

**Layout** (`resources/views/layouts/default.php`):
```php
<!DOCTYPE html>
<html>
<head>
    <title><?= $this->siteTitle() ?></title>
    <?= $this->content('head') ?>
</head>
<body>
    <?= $this->content('body') ?>
    <?= $this->content('script') ?>
</body>
</html>
```

**View** (`resources/views/home.php`):
```php
<?php $this->setSiteTile('Home'); ?>

<?php $this->start('head'); ?>
    <link rel="stylesheet" href="<?= css('style.css') ?>">
<?php $this->end(); ?>

<?php $this->start('body'); ?>
    <h1>Hello, <?= $title ?>!</h1>

    <?php if ($this->hasError('email')): ?>
        <span class="error"><?= $this->error('email') ?></span>
    <?php endif; ?>

    <input type="email" name="email" value="<?= old('email') ?>">
<?php $this->end(); ?>
```

---

## Validation

```php
$this->validation($request, [
    'firstname'       => ['required'],
    'email'           => ['required', 'email', ['unique', 'class' => Users::class]],
    'password'        => ['required', ['min', 'min' => 8], ['max', 'max' => 64]],
    'confirmPassword' => ['required', ['match', 'match' => 'password']],
]);

if ($this->isValidate()) {
    // proceed
}
```

| Rule | Usage |
|---|---|
| `required` | Field must not be empty |
| `email` | Must be a valid email address |
| `min` | `['min', 'min' => 8]` — minimum length |
| `max` | `['max', 'max' => 64]` — maximum length |
| `match` | `['match', 'match' => 'fieldName']` — must equal another field |
| `unique` | `['unique', 'class' => Model::class]` — must not exist in DB |

---

## Middleware

Register aliases in `app/Http/Kernel.php`:

```php
public static $middlewareAliases = [
    'auth'    => \LaraCore\App\Http\Middlewares\AuthMiddleware::class,
    'guest'   => \LaraCore\App\Http\Middlewares\GuestMiddleware::class,
    'admin'   => \LaraCore\App\Http\Middlewares\AdminMiddleware::class,
    'authApi' => \LaraCore\App\Http\Middlewares\AuthApiMiddleware::class,
];
```

**Create a middleware:**
```php
namespace LaraCore\App\Http\Middlewares;

use LaraCore\Framework\Request;

class AuthMiddleware
{
    public function handle(Request $request, $next)
    {
        if (!isset($_SESSION['user'])) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}
```

**Attach to a route:**
```php
Router::get('/dashboard', [DashController::class, 'index'])->middleware('auth');
```

---

## Database Migrations

```bash
php laracore make:migration create_posts_table
```

```php
namespace LaraCore\Database\Migrations;

use LaraCore\Framework\Db\Migrations\Blueprint;
use LaraCore\Framework\Db\Migrations\Migration;

class Post_2024_01_01_000000 extends Migration
{
    public function up()
    {
        $this->create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content')->nullable();
            $table->integer('user_id');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->drop('posts');
    }
}
```

**Blueprint column types:**

| Method | SQL Type |
|---|---|
| `$table->id()` | INT PRIMARY KEY AUTO_INCREMENT |
| `$table->string($name, $length)` | VARCHAR |
| `$table->integer($name)` | INT |
| `$table->bigInteger($name)` | BIGINT |
| `$table->text($name)` | TEXT |
| `$table->boolean($name)` | TINYINT(1) |
| `$table->timestamps()` | `created_at`, `updated_at` |

**Modifiers:** `.nullable()` `.default($value)` `.unique()` `.comment($text)`

```bash
php laracore migrate               # Run all pending migrations
php laracore migration:rollback    # Roll back all migrations
```

---

## Seeders & Factories

```php
// database/Factories/UserFactory.php
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'     => 'John Doe',
            'email'    => 'john@example.com',
            'password' => 'secret',
        ];
    }

    public function create(): void
    {
        $this->make(Users::class, $this->definition());
    }

    public static function new(): static { return new static(); }
}
```

```php
// database/Seeders/UserSeeder.php
class UserSeeder extends Seeder
{
    public function run(): void
    {
        UserFactory::new()->create();
    }
}
```

```bash
php laracore db:seed                   # Run DatabaseSeeder
php laracore db:seeder UserSeeder      # Run a specific seeder
```

---

## Authentication & Sessions

```php
use LaraCore\Framework\Sessions;
use LaraCore\Framework\Helpers\Hash;

// Store user in session
Session::set('user', $user);
$user = Session::get('user');

// Password hashing
$hash = Hash::make('plaintext');
if (Hash::verify('plaintext', $hash)) { /* authenticated */ }
```

```php
// Flash messages
$flash = new FlashMessages();
$flash->setFlash('success', 'Profile updated.');
echo $flash->getFlash('success');
```

---

## Mail Service

The mail service is built on [PHPMailer](https://github.com/PHPMailer/PHPMailer). Create a Mailable class, point it at a view, and call `send()`.

### Create a Mailable

```bash
# Manually create in app/Mail/
```

```php
// app/Mail/WelcomeMail.php
namespace LaraCore\App\Mail;

use LaraCore\Framework\Mail\Mailable;

class WelcomeMail extends Mailable
{
    public function __construct(private array $user) {}

    public function build(): self
    {
        return $this
            ->subject('Welcome to ' . $_ENV['APP_NAME'])
            ->view('mail.welcome', ['user' => $this->user]);
    }
}
```

### Send Mail

```php
// In a controller
use LaraCore\App\Mail\WelcomeMail;

// Fluent API
(new WelcomeMail($user))->to($user['email'], $user['name'])->send();

// Using the helper
send_mail((new WelcomeMail($user))->to($user['email']));
```

### Mailable API

| Method | Description |
|---|---|
| `->to($email, $name)` | Set recipient |
| `->from($email, $name)` | Override sender (falls back to config) |
| `->subject($text)` | Set email subject |
| `->view($name, $data)` | Render a PHP view as the HTML body |
| `->html($html)` | Set raw HTML body directly |
| `->text($text)` | Set plain-text fallback body |
| `->attach($path, $name)` | Attach a file |
| `->send()` | Build and send the email |

### Mail Views

Mail views live in `resources/views/mail/` and are plain PHP files (no layout wrapping):

```php
// resources/views/mail/welcome.php
<!DOCTYPE html>
<html>
<body>
  <h1>Welcome, <?= htmlspecialchars($user['name']) ?>!</h1>
  <p>Your account has been created.</p>
</body>
</html>
```

### SMTP Configuration (`.env`)

```env
# Local development (Docker — Mailpit, no auth needed)
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_ENCRYPTION=
MAIL_USERNAME=
MAIL_PASSWORD=

# Production (e.g. Gmail)
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=you@gmail.com
MAIL_PASSWORD=your-app-password

MAIL_FROM_ADDRESS=hello@example.com
MAIL_FROM_NAME=Laracore
```

> In Docker, all sent emails are captured by **Mailpit** at `http://localhost:8025` — nothing is actually delivered.

---

## REST API

Define API routes in `routes/api.php`. All routes are automatically prefixed with `/api` and return JSON headers.

```php
Router::get('/users', [UserApiController::class, 'index']);
Router::get('/users/{id}', [UserApiController::class, 'show']);
Router::post('/users', [UserApiController::class, 'store']);
```

```php
class UserApiController extends Controller
{
    public function index(Request $request, Response $response)
    {
        return $response->json(['success' => true, 'data' => (new Users())->getAll()]);
    }
}
```

**Optional token auth** — enable in `.env`:
```env
API_TOKEN_CHECK=true
API_TOKEN_KEY=your-secret-key
```

Client must send: `Authorization: Bearer your-secret-key`

---

## Helper Functions

```php
// Views & assets
view('home', $data)
assets('img/logo.png')     // /resources/assets/img/logo.png
css('style.css')           // /resources/assets/css/style.css
js('app.js')               // /resources/assets/js/app.js
app_url('/about')          // full URL from APP_URL

// Redirection
redirect()->redirect('/login')
redirect()->route('user.show', ['id' => 1])

// Forms
old('email')               // previously submitted value
errors('email')            // validation error for field

// Mail
send_mail($mailable)       // send a Mailable instance

// Paths
base_path('storage/logs')  // absolute path from project root

// Debug
dd($var1, $var2)           // styled dump and die

// Passwords
Hash::make($password)
Hash::verify($plain, $hash)

// Input sanitization
Input::sanitize($value)    // trim + stripslashes + htmlspecialchars
```

---

## CLI Commands

```bash
# Development
php laracore serve

# Code generation
php laracore make:controller ControllerName
php laracore make:model ModelName
php laracore make:migration create_table_name

# Migrations
php laracore migrate
php laracore migration:rollback

# Seeders
php laracore db:seed
php laracore db:seeder SeederClassName

# API
php laracore generate:api-key
```

---

## Roadmap

- [ ] Full ORM with relationships (hasOne, hasMany, belongsTo)
- [ ] User management system
- [ ] Multi-authentication system
- [ ] Blade-style templating
- [ ] Route groups and prefixes
- [ ] Query caching

---

## License

LaraCore is open-sourced software licensed under the [MIT license](LICENSE).

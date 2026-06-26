# LaraCore Framework

A lightweight PHP MVC framework built for fast, enjoyable web development. LaraCore provides routing, an Eloquent-style Active Record ORM with relationships, form validation, migrations, seeders, CLI tooling, mail service, and REST API support — all without heavy dependencies.

> **Note:** This framework is actively under development.

---

## Table of Contents

- [LaraCore Framework](#laracore-framework)
  - [Table of Contents](#table-of-contents)
  - [Requirements](#requirements)
  - [Features](#features)
  - [Installation](#installation)
    - [Standard Setup](#standard-setup)
    - [Docker Setup](#docker-setup)
  - [Directory Structure](#directory-structure)
  - [Configuration](#configuration)
  - [Routing](#routing)
    - [HTTP Methods](#http-methods)
    - [Route Actions](#route-actions)
    - [Route Parameters](#route-parameters)
    - [Named Routes \& Middleware](#named-routes--middleware)
  - [Controllers](#controllers)
  - [ORM / Models](#orm--models)
    - [Defining a Model](#defining-a-model)
    - [CRUD](#crud)
    - [Query Builder](#query-builder)
    - [Relationships](#relationships)
    - [Scopes](#scopes)
    - [Lifecycle Hooks](#lifecycle-hooks)
    - [Soft Deletes](#soft-deletes)
    - [Collections](#collections)
  - [Views \& Layouts](#views--layouts)
  - [Validation](#validation)
  - [Middleware](#middleware)
  - [Database Migrations](#database-migrations)
  - [Seeders \& Factories](#seeders--factories)
  - [Authentication \& Sessions](#authentication--sessions)
  - [Mail Service](#mail-service)
    - [Create a Mailable](#create-a-mailable)
    - [Send Mail](#send-mail)
    - [Mailable API](#mailable-api)
    - [Mail Views](#mail-views)
    - [SMTP Configuration (`.env`)](#smtp-configuration-env)
  - [REST API](#rest-api)
  - [Helper Functions](#helper-functions)
  - [CLI Commands](#cli-commands)
  - [Testing](#testing)
    - [Running Tests Locally](#running-tests-locally)
    - [Running Tests with Docker](#running-tests-with-docker)
    - [Test Structure](#test-structure)
  - [Roadmap](#roadmap)
  - [License](#license)

---

## Requirements

- PHP >= 7.4 (8.0+ recommended)
- Composer
- MySQL 5.7+ / SQLite (tests only)
- Apache (mod_rewrite enabled) or Nginx

---

## Features

- [x] Simple and fast routing with named routes and parameter binding
- [x] **Eloquent-style Active Record ORM** — chainable query builder, relationships, casts, scopes, lifecycle hooks
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
- [x] **PHPUnit test suite** — unit, integration (SQLite in-memory), and feature tests
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

**2. Build and start all services**
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
# Lifecycle
docker compose up -d                        # Start all services in background
docker compose down                         # Stop and remove containers
docker compose down -v                      # Stop and remove containers + volumes
docker compose restart app                  # Restart the app container

# Logs
docker compose logs -f                      # Stream all service logs
docker compose logs -f app                  # Stream app logs only

# Shell access
docker compose exec app bash                # Open bash shell in app container
docker compose exec db mysql -u laracore -psecret laracore   # MySQL shell

# Migrations & seeds (inside app container)
docker compose exec app php laracore migrate
docker compose exec app php laracore migration:rollback
docker compose exec app php laracore db:seed

# Code generation (inside app container)
docker compose exec app php laracore make:controller UserController
docker compose exec app php laracore make:model User
docker compose exec app php laracore make:migration create_posts_table
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
│   │   ├── Model.php           ← Active Record base class
│   │   ├── QueryBuilder.php    ← Fluent query builder
│   │   ├── Collection.php      ← Iterable result set
│   │   ├── Connection.php      ← PDO singleton
│   │   ├── DataModel.php       ← Legacy compatibility shim
│   │   ├── Concerns/
│   │   │   └── SoftDeletes.php
│   │   ├── Relations/
│   │   │   ├── HasOne.php
│   │   │   ├── HasMany.php
│   │   │   ├── BelongsTo.php
│   │   │   └── BelongsToMany.php
│   │   └── Migrations/
│   │       ├── Blueprint.php
│   │       └── Migration.php
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
├── tests/
│   ├── TestCase.php
│   ├── DatabaseTestCase.php    ← SQLite in-memory base
│   ├── Fixtures/
│   │   └── TestUser.php
│   ├── Unit/
│   │   ├── Db/
│   │   │   ├── CollectionTest.php
│   │   │   ├── ModelTest.php
│   │   │   ├── QueryBuilderSqlTest.php
│   │   │   └── Migrations/
│   │   │       └── BlueprintTest.php
│   │   ├── Helpers/
│   │   │   └── HashTest.php
│   │   ├── Routers/
│   │   │   └── RouterTest.php
│   │   ├── RequestTest.php
│   │   └── ValidationTest.php
│   ├── Integration/
│   │   └── Db/
│   │       ├── ConnectionTest.php
│   │       ├── QueryBuilderIntegrationTest.php
│   │       └── ModelCrudTest.php
│   └── Feature/
│       └── Http/
│           └── Controllers/
│               └── HomeControllerTest.php
├── .env
├── .env.example
├── .dockerignore
├── .htaccess
├── Dockerfile
├── docker-compose.yml
├── phpunit.xml
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
        $users = Users::all();
        return $this->view('users.index', compact('users'));
    }

    public function store(Request $request, Response $response)
    {
        $this->validation($request, [
            'name'  => ['required'],
            'email' => ['required', 'email'],
        ]);

        if ($this->isValidate()) {
            Users::creating($request->all());
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

## ORM / Models

LaraCore ships an Eloquent-inspired Active Record ORM. Every model instance represents a database row; static methods and the fluent query builder make querying clean and chainable.

### Defining a Model

```bash
php laracore make:model Post
```

```php
namespace LaraCore\App\Models;

use LaraCore\Framework\Db\Model;

class Post extends Model
{
    protected static $table      = 'posts';
    protected static $primaryKey = 'id';
    protected static $timestamps = true;   // manages created_at / updated_at

    protected $fillable = ['title', 'body', 'user_id', 'status'];
    protected $hidden   = [];              // excluded from toArray() / toJson()
    protected $casts    = [
        'user_id' => 'int',
        'status'  => 'bool',
    ];
}
```

### CRUD

```php
// Create — fires onCreating / onCreated hooks
$post = Post::creating([
    'title'   => 'Hello World',
    'body'    => 'Content here',
    'user_id' => 1,
    'status'  => true,
]);

echo $post->id;      // auto-incremented primary key
echo $post->exists;  // true

// Read — single record
$post = Post::find(1);          // returns Post|null
$post = Post::findOrFail(1);    // throws RuntimeException if not found

// Read — all records
$posts = Post::all();           // returns Collection

// Save — insert or update based on $model->exists
$post = new Post();
$post->title = 'New post';
$post->saving();                // INSERT (exists = false)

$post->title = 'Updated';
$post->saving();                // UPDATE (exists = true)

// Update — mass-update attributes on an existing record
$post->updated(['title' => 'Revised title', 'status' => false]);

// Delete
$post->delete();

// firstOrCreate — find or insert
$post = Post::firstOrCreate(
    ['title' => 'Hello World'],  // search criteria
    ['body'  => 'Body text']     // extra attributes on create
);

// updateOrCreate — find and update or insert
$post = Post::updateOrCreate(
    ['title' => 'Hello World'],
    ['body'  => 'Updated body']
);
```

### Query Builder

Every static call that doesn't resolve to a method on `Model` is forwarded to a `QueryBuilder` instance, giving you a fully chainable fluent API:

```php
// WHERE clauses
Post::where('status', true)->get();
Post::where('user_id', '>', 0)->orWhere('featured', 1)->get();
Post::whereIn('id', [1, 2, 3])->get();
Post::whereNotIn('id', [4, 5])->get();
Post::whereNull('deleted_at')->get();
Post::whereNotNull('published_at')->get();
Post::whereBetween('created_at', ['2024-01-01', '2024-12-31'])->get();
Post::whereLike('title', '%hello%')->get();
Post::whereRaw('YEAR(created_at) = ?', [2024])->get();

// Nested WHERE
Post::where('status', 1)
    ->whereNested(fn($q) => $q->where('views', '>', 100)->orWhere('featured', 1))
    ->get();

// SELECT
Post::select('id', 'title')->get();
Post::distinct()->select('user_id')->get();

// JOINs
Post::join('users', 'posts.user_id', '=', 'users.id')
    ->select('posts.*', 'users.name as author')
    ->get();

Post::leftJoin('comments', 'posts.id', '=', 'comments.post_id')->get();
Post::rightJoin('categories', 'posts.category_id', '=', 'categories.id')->get();

// ORDER, GROUP, LIMIT
Post::orderBy('created_at', 'DESC')->limit(10)->get();
Post::latest()->get();                   // ORDER BY created_at DESC
Post::oldest()->get();                   // ORDER BY created_at ASC
Post::groupBy('user_id')->get();
Post::having('total', '>', 5)->get();

// Pagination
$page = Post::where('status', 1)->paginate(15, 1);
// Returns: ['data' => Collection, 'total' => int, 'per_page' => 15, 'current_page' => 1, 'last_page' => int]

Post::forPage(2, 15)->get();             // LIMIT 15 OFFSET 15

// Single results
$post  = Post::where('slug', 'hello')->first();
$post  = Post::where('slug', 'hello')->firstOrFail();  // throws if not found
$post  = Post::find(1);

// Aggregates
$count = Post::where('status', 1)->count();
$max   = Post::where('status', 1)->max('views');
$min   = Post::min('price');
$sum   = Post::sum('views');
$avg   = Post::avg('rating');
$bool  = Post::where('slug', 'x')->exists();
$bool  = Post::where('slug', 'x')->doesntExist();

// Raw SQL inspection (no DB hit)
$sql = Post::where('status', 1)->orderBy('id')->toSql();
```

### Relationships

```php
class User extends Model
{
    protected static $table = 'users';

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }
}

class Post extends Model
{
    protected static $table = 'posts';

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
```

**Accessing relationships** (lazy-loaded, results cached on first access):

```php
$user = User::find(1);
$profile = $user->profile;       // HasOne  → User|null
$posts   = $user->posts;         // HasMany → Collection
$roles   = $user->roles;         // BelongsToMany → Collection
$author  = $post->author;        // BelongsTo → User|null
```

**Many-to-many pivot helpers:**

```php
$roles = $user->roles();
$roles->attach([1, 2]);           // insert pivot rows
$roles->detach([2]);              // delete pivot rows
$roles->sync([1, 3]);             // replace all pivot rows
```

### Scopes

```php
class Post extends Model
{
    public function scopePublished(QueryBuilder $query): QueryBuilder
    {
        return $query->where('status', 1);
    }

    public function scopeByUser(QueryBuilder $query, int $userId): QueryBuilder
    {
        return $query->where('user_id', $userId);
    }
}

// Usage — scope name without "scope" prefix, camelCase
Post::published()->get();
Post::published()->byUser(5)->orderBy('created_at')->get();
```

### Lifecycle Hooks

Override any of these protected methods in your model to react to persistence events:

```php
class Users extends Model
{
    protected function onCreating(): void
    {
        // Fires before INSERT — hash password, set defaults, etc.
        $this->attributes['password'] = Hash::make($this->attributes['password']);
        $this->attributes['status']   = 1;
    }

    protected function onCreated(): void  {}  // after INSERT
    protected function onUpdating(): void {}  // before UPDATE
    protected function onUpdated(): void  {}  // after UPDATE
    protected function onSaving(): void   {}  // before INSERT or UPDATE
    protected function onSaved(): void    {}  // after INSERT or UPDATE
    protected function onDeleting(): void {}  // before DELETE
    protected function onDeleted(): void  {}  // after DELETE
}
```

### Soft Deletes

```php
use LaraCore\Framework\Db\Concerns\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    // Requires a `deleted_at TIMESTAMP NULL` column in your table
}

$post->delete();              // sets deleted_at, row stays in DB
$post->forceDelete();         // permanent delete
$post->restore();             // clears deleted_at
$post->trashed();             // bool — is soft-deleted?

Post::withTrashed()->get();   // include soft-deleted rows
Post::onlyTrashed()->get();   // only soft-deleted rows
```

### Collections

`get()` and `all()` return a `Collection` — a rich wrapper around a plain array:

```php
$posts = Post::where('status', 1)->get();

$posts->count();
$posts->first();
$posts->last();
$posts->filter(fn($p) => $p->views > 100);
$posts->map(fn($p) => $p->title);
$posts->pluck('title');
$posts->sortBy('created_at');
$posts->sortByDesc('views');
$posts->take(5);
$posts->skip(10);
$posts->chunk(20);               // returns array of Collection
$posts->unique('user_id');
$posts->sum('views');            // float
$posts->avg('rating');           // float
$posts->max('views');
$posts->min('price');
$posts->contains($post);
$posts->isEmpty();
$posts->isNotEmpty();
$posts->each(fn($p) => /* side-effect */);
$posts->merge($otherCollection);
$posts->reverse();
$posts->toArray();
$posts->toJson();
foreach ($posts as $post) { /* iterable */ }
echo count($posts);              // Countable
echo $posts[0]->title;           // ArrayAccess
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
        return $response->json(['success' => true, 'data' => Users::all()->toArray()]);
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
# Development server
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

## Testing

The framework ships with a full PHPUnit 9.6 test suite organised into three layers.

| Suite | Location | DB | What it tests |
|---|---|---|---|
| Unit | `tests/Unit/` | None | Pure logic — collection methods, model attributes, query SQL, router, request, validation, hash |
| Integration | `tests/Integration/` | SQLite in-memory | Real INSERT/SELECT/UPDATE/DELETE via `Connection::setInstance()` injection |
| Feature | `tests/Feature/` | None | Controller instantiation and callability |

### Running Tests Locally

```bash
# Install dev dependencies (first time)
composer install

# All suites
./vendor/bin/phpunit --testdox --colors=always

# Individual suites
./vendor/bin/phpunit --testsuite Unit        --testdox --colors=always
./vendor/bin/phpunit --testsuite Integration --testdox --colors=always
./vendor/bin/phpunit --testsuite Feature     --testdox --colors=always

# Composer script shortcuts
composer test              # Unit suite
composer test:unit         # Unit suite
composer test:integration  # Integration suite
composer test:feature      # Feature suite
composer test:all          # All suites

# Single test file
./vendor/bin/phpunit tests/Unit/Db/CollectionTest.php --testdox

# Single test method
./vendor/bin/phpunit --filter testSumReturnsFloat tests/Unit/Db/CollectionTest.php

# Only tests tagged @group db
./vendor/bin/phpunit --group db
```

### Running Tests with Docker

The `test` service runs in an isolated container using SQLite for integration tests — no MySQL required.

**First-time build:**
```bash
docker compose build test
```

**Run all tests:**
```bash
docker compose run --rm test
```

**Run a specific suite:**
```bash
docker compose run --rm test php vendor/bin/phpunit --testsuite Unit        --testdox --colors=always
docker compose run --rm test php vendor/bin/phpunit --testsuite Integration --testdox --colors=always
docker compose run --rm test php vendor/bin/phpunit --testsuite Feature     --testdox --colors=always
```

**Run a single test file:**
```bash
docker compose run --rm test php vendor/bin/phpunit tests/Unit/Db/CollectionTest.php --testdox
```

**Run a single test method:**
```bash
docker compose run --rm test php vendor/bin/phpunit --filter testSumReturnsFloat tests/Unit/Db/CollectionTest.php
```

**Run only DB-tagged tests:**
```bash
docker compose run --rm test php vendor/bin/phpunit --group db
```

**Reset the vendor volume** (required after `docker compose build test` when dependencies change):
```bash
docker volume rm php-mvc-framework_vendor_data
docker compose build test
docker compose run --rm test
```

### Test Structure

```
tests/
├── TestCase.php               ← Base class (extends PHPUnit TestCase)
├── DatabaseTestCase.php       ← SQLite in-memory: creates PDO, injects via Connection::setInstance()
├── Fixtures/
│   └── TestUser.php           ← Concrete Model used across DB tests
├── Unit/
│   ├── Db/
│   │   ├── CollectionTest.php          ← all(), first(), filter(), map(), pluck(), sort, aggregate…
│   │   ├── ModelTest.php               ← fill, setAttribute, cast, isDirty, toArray, hidden, hydration
│   │   ├── QueryBuilderSqlTest.php     ← toSql() output for every clause type (no real DB)
│   │   └── Migrations/
│   │       └── BlueprintTest.php       ← column SQL string generation
│   ├── Helpers/
│   │   └── HashTest.php               ← make(), verify() pass/fail
│   ├── Routers/
│   │   └── RouterTest.php             ← route registration, named routes, unsupported method
│   ├── RequestTest.php                ← uri(), method(), isGet/isPost, route params
│   └── ValidationTest.php             ← required, email, min, max, match rules
├── Integration/
│   └── Db/
│       ├── ConnectionTest.php                  ← singleton behaviour, reset
│       ├── QueryBuilderIntegrationTest.php     ← INSERT/SELECT/UPDATE/DELETE, paginate, exists
│       └── ModelCrudTest.php                   ← creating(), find(), saving(), updated(), delete()
└── Feature/
    └── Http/
        └── Controllers/
            └── HomeControllerTest.php          ← controller callable, methods exist
```

---

## Roadmap

- [ ] User management system
- [ ] Multi-authentication system
- [ ] Blade-style templating
- [ ] Route groups and prefixes
- [ ] Query caching
- [ ] Eager loading (with / load)

---

Documentation update with [claude](https://claude.ai)

## License


LaraCore is open-sourced software licensed under the [MIT license](LICENSE).

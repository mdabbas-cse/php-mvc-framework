# php-mvc-framework
## About LaraCore Framework
This is a web application framework built with PHP programming language. I believe development must be an enjoyable and creative experience to be truly fulfilling. we develop any project easily by using this framework.

NOTE: This framework is still under development in dev-mode branch.

## Features and Road map
- [x] Simple and fast routing system.
- [x] Custom routing system.
- [x] Custom query builder.
- [x] Simple view template.
- [x] Multi layouts system.
- [x] HTTP helper.
- [x] CSRF token.
- [x] Develop API with Bearer token.
- [x] Component for form and input field.
- [x] Form validations.
- [x] Environment variables (Custom Dotenv).
- [ ] Database migrations.
- [ ] Database factories.
- [ ] Database seeders.
- [ ] Object relational mapping(ORM).
- [ ] User management system.
- [ ] Multi authentication system.
- [ ] E-mail and SMS system.
- [ ] Blade template (like laravel).

### Requirements
- PHP >= 7.2
- Composer
- MySQL
- Apache or Nginx

### Folder Structure
```bash
├── app
│   ├── Http
│   │   ├── Controllers
│   │   │   └── UserController.php
│   │   ├── Middleware
│   │   │    └── AuthMiddleware.php
│   │   └── Kernel.php
│   ├── Models
│   │   └── User.php
│   └── Providers
│       ├── AppServiceProvider.php
│       └── RouteServiceProvider.php
├── config
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   ├── mail.php
│   ├── session.php
├── database
│   ├── factories
│   │   └── UserFactory.php
│   ├── migrations
│   │   └── 2021_01_01_000000_create_users_table.php
│   └── seeders
│       └── UserSeeder.php
├── resources
│   ├── assets
│   │   ├── css
│   │   │   └── style.css
│   │   └── js
│   │       └── script.js
│   ├── lang
│   │   └── en
│   │       └── validation.php
│   ├── views
│   │   ├── auth
│   │   │   ├── login.php
│   │   │   ├── register.php
│   │   │   └── reset.php
│   │   ├── layouts
│   │   │   ├── auth.php
│   │   │   └── default.php
│   │   ├── partials
│   │   │   ├── footer.php
│   │   │   ├── head.php
│   │   │   └── header.php
│   │   ├── 404.php
│   │   ├── about.php
│   │   ├── blue_print_file_view.php
│   │   └── welcome.php
├── routes
│   ├── api.php
│   └── web.php
├── storage
│   ├── cache
│   ├── logs
│   │   └── error.log
│   └── sessions
├── tests
│   └── ExampleTest.php
├── .env
├── .gitignore
├── .htaccess
├── .php-cs-fixer.php
├── composer.json
├── composer.lock
├── database.sql
├── index.php
├── laracore
├── LICENSE
├── README.md
```

### Installation
1. Clone the repository
```bash 
git clone https://github.com/mdabbas-cse/php-mvc-framework.git
```
2. Install dependencies
```bash
composer install
```
3. Create a database and import the `database.sql` file.
4. Configure the database connection in `.env` file.
```bash
DB_HOST=localhost # your database host
DB_PORT=3306 # your database port
DB_DATABASE=laracore # your database name
DB_USERNAME=root # your database username
DB_PASSWORD= # your database password
```
5. Application configuration in `.env` file.
```bash
APP_NAME=Laracore # your application name
APP_DEBUG=true # your application debug mode
APP_URL=http://php-mvc-framework.test/ # your application url
APP_DEFAULT_LAYOUT=default # your application default layout
APP_TIME_ZONE=Asia/Dhaka # your application time zone
```
6. migrate the database
```bash
php laracore migrate
```
7. Run the application
```bash
php -S localhost:8000
```
or 
Your application root url

8. Open the application in your browser `http://localhost:8000`
9. Create Route in `routes/web.php` file
```bash
// route for home page 
Route::get('/', function () {
  return view('welcome');
});

// route for render only view any file without call back function
Route::get('/about', 'about');

// route with parameter controller and method
Route::get('/user/{id}', [UserController::class , 'show'])->middleware('auth')->name('user.show');
```
10. Create User Migration or any migration in `database/migrations` directory or
```bash
php laracore make:migration create_users_table
```
11. Edit the migration file in `database/migrations` directory
```bash
database/migrations/2021_01_01_000000_create_users_table.php
```
```bash
<?php

namespace LaraCore\Database\Migrations;

use LaraCore\Framework\Db\Migrations\Blueprint;
use LaraCore\Framework\Db\Migrations\Migration;

class User_2023_11_21_204052 extends Migration
{
  public function up()
  {
    $this->create('users', function (Blueprint $table) {
      $table->id();
      $table->string('name', 50)->nullable()->default('laracore')->comment('user name');
      $table->string('email', 25)->unique()->comment('user email');
      $table->string('password')->comment('user password');
      $table->timestamps();
    });
  }

  public function down()
  {
    $this->drop('users');
  }
}
```
12. Run the migration
```bash
php laracore migrate
```
13. Create User Model or any model in `app/Models` directory or
```bash
php laracore make:model User
```
14. Edit the model file in `app/Models` directory
```bash
app/Models/User.php
```
15.  Create layout in `resources/Views/layouts` directory
```bash
resources/Views/layouts/default.php
```

16.  Create view in `resources/Views` directory
```bash
resources/Views/welcome.php
```
```bash
<?php $this->setSiteTile('Home'); ?> <!-- set site title -->

<?php $this->start('head'); ?> <!-- start head section -->

<!-- include any style sheet and script sheet -->

<?php $this->end(); ?> <!-- end head section -->

<?php $this->start('body'); ?> <!-- start body section -->

<h1>Welcome to LaraCore Framework <?= dirname(__FILE__); ?></h1>

<script>
  (function() {
    alert('hello');
  }())
</script>

<?php $this->end(); ?> <!-- end body section -->
```
17.   Create controller in `app/Http/Controllers` directory or
```bash
php laracore make:controller UserController
```
18.   Create model in `app/Models` directory or
```bash
php laracore make:model User
```
 
## License
This framework is open-sourced software licensed under the MIT license.

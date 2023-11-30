# php-mvc-framework
## About LaraCore Framework
This is a web application framework built with PHP programming language. I believe development must be an enjoyable and creative experience to be truly fulfilling. we develop any project easily by using this framework.

## Features and Road map
- [x] Simple and fast routing system.
- [x] Custom routing system.
- [x] Custom query builder.
- [x] Simple view templating.
- [x] Multi layouts system.
- [x] HTTP helper.
- [x] CSRF token.
- [x] Component for form and input field.
- [x] Form validations.
- [ ] Database migrations.
- [ ] Object relational mapping(ORM).
- [ ] User management system.
- [ ] Multi authentication system.
- [ ] E-mail and SMS system.

### Requirements
- PHP >= 7.2
- Composer
- MySQL
- Apache or Nginx

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
4. Configure the database connection in `config/Config.php` file.
```bash
'database' => [
    'dbname' => 'your database name',
    'username' => 'your database username',
    'password' => 'your database password',
    'connection' => 'your database connection',
    'port' => 3306,
    'options' => [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
  ],
```
5. Application configuration
```bash
'app' => [
  'title' => 'Your application title',
  'defaultLayout' => 'default', // default layout name "default" 
  'root' => 'http://localhost:8000', // your application root url
  'debug' => false, // set true for debug mode
  'timezone' => 'Asia/Dhaka' // your local timezone
],
```
6. migrate the database
```bash
php laracore migrate
```
7. Run the application
```bash
php -S localhost:8000
```
8. Open the application in your browser `http://localhost:8000`
9. Create Route in `routes/web.php` file
```bash
Route::get('/', function () {
  return view('welcome');
});
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
      $table->string('name');
      $table->string('email');
      $table->string('password');
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

15.  Create view in `views` directory
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
  $(function() {
    alert('hello');
  })
</script>

<?php $this->end(); ?> <!-- end body section -->
```
16.  Create layout in `resources/Views/layouts` directory
```bash
resources/Views/layouts/default.php
```
17.  Create partials in `resources/Views/partials` directory
```bash
resources/Views/partials/header.php
resources/Views/partials/footer.php
```
18.  Create component in `resources/Views/components` directory
```bash
resources/Views/components/form.php
```
19.  Create controller in `app/Http/Controllers` directory or
```bash
php laracore make:controller UserController
```
20.  Create model in `app/Models` directory or
```bash
php laracore make:model User
```
 
## License
This framework is open-sourced software licensed under the MIT license.

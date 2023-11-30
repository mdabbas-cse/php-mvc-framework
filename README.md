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
git clone 
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
1. Run the application
```bash
php -S localhost:8000
```
1. Open the application in your browser `http://localhost:8000`
 
## License
This framework is open-sourced software licensed under the MIT license.

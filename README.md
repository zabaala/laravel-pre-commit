# Laravel pre-commit

A Laravel package to analyze your PHP code and check your PHPUnit tests before commit your code.

## Requirements

 - PHP >= 7.0.0
 - phpunit/phpunit ~6.0
 - squizlabs/php_codesniffer ~6.0

## How to install

### 1. Include package dependencie into composer.json:

```
composer require zabaala/laravel-pre-commit
```
### 2. Discover the package Service Provider

If you're using Laravel 5.5 version, this package will be auto-discovered. But if you're using any other Laravel 5 
version, you will need add the PreCommitServiceProvider inside of your `config/app.php` file:

```php
// ...
\Zabaala\PreCommit\PreCommitServiceProvider::class,
```

### 3. Create the pre-commit file

Create a pre-commit file in: `.git/hooks/pre-commit` with the content below:

```
#!/bin/bash

./artisan git:pre-commit
```

## Usage

Modify and commit any file.

## License
MIT

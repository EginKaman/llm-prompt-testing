## 📝 Table of Contents

1. [Requirements](#requirements)
2. [Installation](#installation)
3. [Vite (Asset Bundling)](#vite)
4. [Quality Tools](#quality-tools)
5. [Testing](#testing)

## <a name="requirements"></a> ☑️ Requirements

- Docker
- PHP 8.4+ is required
- Composer 2
- Node.js 22
- Redis server
- MySQL 8.0+

## <a name="installation"></a> 🖥️ Installation

Install composer packages:

```shell
composer install
```

### Docker ([Laravel Sail](https://laravel.com/docs/10.x/sail))

For local development, need to configure a shell alias for Laravel
Sail - [How to configure a shell alias](https://laravel.com/docs/12.x/sail#configuring-a-shell-alias)

Build Docker Image:

```shell
sail build --no-cache
```

Run Docker Images:

```shell
sail up -d
```

Generate application key:

```shell
sail artisan key:generate
```

Run migrations with seeders:

```shell
sail artisan migrate --seed
```

Install node.js packages:

```shell
sail npm install
```

Build the assets:

```shell
sail npm run build
```

Open the application in your browser:

[Home page](http://localhost/)

## <a name="#quality-tools"></a> Quality Tools

### Laravel Pint

[Laravel Pint](https://laravel.com/docs/10.x/pint) is an opinionated PHP code style fixer for minimalists. Pint is built
on top of PHP-CS-Fixer and makes it simple to ensure that your code style stays clean and consistent.

#### Running Pint

You can instruct Pint to fix code style issues by invoking the pint binary that is available in your project's
vendor/bin directory:

```shell
sail bin pint
```

If you would like Pint to only modify the files that have uncommitted changes according to Git, you may use
the `--dirty` option:

```shell
sail bin pint --dirty
```

## Larastan (PHPStan)

[Larastan](https://github.com/larastan/larastan) adds code analysis to Laravel improving developer productivity and code
quality.

#### Running Larastan

```shell
sail bin phpstan analyse
```

If you are getting the error `Allowed memory size exhausted`, then you can use the `--memory-limit` option fix the
problem:

```shell
sail bin phpstan analyse --memory-limit=2G
```

### Ignoring errors

Ignoring a specific error can be done either with a php comment or in the configuration file:

```php
// @phpstan-ignore-next-line
$test->badMethod();

$test->badMethod(); // @phpstan-ignore-line
```

When ignoring errors in PHPStan's configuration file, they are ignored by writing a regex based on error messages:

```yaml
parameters:
    ignoreErrors:
        - '#Call to an undefined method .*badMethod\(\)#'
```

## <a name="testing"></a> Testing

[Testing documentation](https://laravel.com/docs/10.x/testing)

<a name="running-tests"></a>

## Running Tests

```shell
sail artisan test
```

Any arguments that can be passed to the `phpunit` command may also be passed to the Artisan `test` command:

```shell
sail artisan test --testsuite=Feature --stop-on-failure
```

<a name="running-tests-in-parallel"></a>

### Running Tests in Parallel

```shell
sail artisan test --parallel
```

By default, Laravel will create as many processes as there are available CPU cores on your machine. However, you may
adjust the number of processes using the `--processes` option:

```shell
sail artisan test --parallel --processes=4
```

> [!WARNING]  
> When running tests in parallel, some PHPUnit options (such as `--do-not-cache-result`) may not be available.

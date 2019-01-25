# Installation Swann Xerri site

## Après chaque création de branche ou clonage

### Install Composer vendor

```sh
composer install
```

### Installer ckeditor assets

```sh
php bin/console ckeditor:install
php bin/console assets:install public
```

### Create Database

```sh
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

### Download CaptchaBundle using composer

<https://captcha.com/doc/php/symfony-captcha-bundle.html>

```sh
composer require captcha-com/symfony-captcha-bundle:"4.*"
```

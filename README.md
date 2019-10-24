# laravel6-boilerplate
laravel6-boilerplate

## About

Getting started laravel6 with full configuration for production and development.
With laradock. 

### Installation and Running

```php
git clone https://github.com/santutu/laravel6-boilerplate.git
composer install --ignore-platform-reqs
npm install
php artisan app:init
npm run watch
```
__Running laravel-echo-server on local__
```php
npm install -g laravel-echo-server
laravel-echo-server start
```

### Running with docker

If select "local" env in app:init but want to run with docker, follow below.

```bash
php artisan env:copy docker
cd laradock-{your-project-name}
docker-compose up -d nginx mysql redis php-worker laravel-echo-server phpmyadmin // phpmyadmin, laravel-echo-server is optional
docker-compose run workspace php artisan migrate
docker-compose run workspace php artisan db:seed
npm run watch // on project root dir 
docker-compose exec workspace bash // if want to connect docker container bash
```

### Useful commands

```php
php artisan generate:model-factory //https://github.com/mpociot/laravel-test-factory-helper
php artisan api:doc //https://github.com/mpociot/laravel-apidoc-generator
php artisan env:copy {.env file name} // ex)docker or local or prod. https://github.com/santutu/laravel-dotenv
```

### Useful installed packages and etc.

- https://github.com/glorand/laravel-model-settings
- https://github.com/illuminatech/validation-composite
- https://github.com/Askedio/laravel-soft-cascade
- https://github.com/webmozart/path-util
- http://localhost:{your-port}/horizon
- http://localhost:{your-port}/telescope


## Useful not installed packages
 
- proengsoft/laravel-jsvalidation
- staudenmeir/eloquent-has-many-deep
- spatie/once
- spatie/async
- imangazaliev/didom
- hyungju/laravel-sens
- guzzlehttp/guzzle
- awobaz/compoships
- intervention/image
- jenssegers/mongodb
- spatie/laravel-medialibrary
- google/apiclient
- nova
- laravel/nova
- numaxlab/nova-ckeditor5-classic
- beyondcode/nova-tinker-tool
- ebess/advanced-nova-media-library




#### composer usage on windows (Horizon require PHP extension pcntl, but window can't install it.)

```bash
composer require {package} --ignore-platform-reqs
```




### TDL

-[x] laravel echo server
-[ ] lang copy artisan console. vendor/caouecs/laravel-lang https://github.com/caouecs/Laravel-lang
-[ ] laravel/passport
-[ ] describe about configging log_slack_webhook_url.


## License

The Laravel framework is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).

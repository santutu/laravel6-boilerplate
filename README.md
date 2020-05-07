# laravel6-boilerplate
laravel6-boilerplate

## About

Getting started laravel6 with full configuration for production and development.
With laradock. 

## Installation and Running

```bash
git clone https://github.com/santutu/laravel6-boilerplate.git
composer install // if got "require PHP extension pcntl" error, try "composer install --ignore-platform-reqs"
npm install
php artisan app:init
npm run watch
```
__Running laravel-echo-server in local__
```bash
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

## Useful commands

```bash
php artisan generate:model-factory //https://github.com/mpociot/laravel-test-factory-helper
php artisan api:doc //https://github.com/mpociot/laravel-apidoc-generator
php artisan env:copy {.env file name} // ex- docker or local or prod. https://github.com/santutu/laravel-dotenv
```

## Useful installed packages and etc.
- https://github.com/spatie/laravel-permission
- https://github.com/glorand/laravel-model-settings
- https://github.com/illuminatech/validation-composite
- https://github.com/Askedio/laravel-soft-cascade
- https://github.com/webmozart/path-util
- http://localhost:{your-port}/horizon
- http://localhost:{your-port}/telescope
- https://github.com/thepinecode/blade-filters
- https://github.com/LaravelCollective/annotations
- https://github.com/creativeorange/gravatar
- https://cartalyst.com/manual/tags/9.x#introduction
- https://github.com/Torann/laravel-geoip // require redis cache
- https://github.com/laravelista/comments
- https://github.com/vkovic/laravel-custom-casts
- https://symfony.com/doc/current/components/serializer.html
- https://github.com/glorand/laravel-model-settings 
- https://github.com/qcod/laravel-app-settings, http://localhost:{your-port}/settings
## Useful not installed packages
 
- https://github.com/proengsoft/laravel-jsvalidation
- https://github.com/staudenmeir/eloquent-has-many-deep
- https://github.com/spatie/once
- https://github.com/spatie/async
- https://github.com/Imangazaliev/DiDOM
- https://github.com/HyungJu/laravel-sens
- https://github.com/guzzle/guzzle
- https://github.com/topclaudy/compoships
- https://github.com/Intervention/image
- https://github.com/jenssegers/laravel-mongodb
- https://github.com/spatie/laravel-medialibrary
- https://nova.laravel.com/
- https://github.com/numaxlab/nova-ckeditor5-classic/releases
- https://github.com/beyondcode/nova-tinker-tool
- https://github.com/ebess/advanced-nova-media-library
- https://github.com/beyondcode/laravel-er-diagram-generator
- https://github.com/Astrotomic/laravel-translatable
- https://github.com/mcamara/laravel-localization
- https://github.com/barryvdh/laravel-translation-manager


## composer usage on windows (Horizon require PHP extension pcntl, but window can't install it.)

```bash
composer require {package} --ignore-platform-reqs
```


## TDL

-[x] laravel echo server

-[ ] lang copy artisan console. vendor/caouecs/laravel-lang https://github.com/caouecs/Laravel-lang

-[ ] laravel/passport

-[ ] api with jwt token(laravel guard)

-[ ] laravel/cashier

-[ ] elasticsearch

-[ ] envoy for queue, cron, laravel-echo-server

-[ ] easy testing package.

-[ ] Board Package

-[ ] Infinity scroll package on backend

-[ ] describe about configging log_slack_webhook_url.

-[ ] https

-[ ] soft delete with changing column

-[ ] deployment method.

-[x] tagging

-[ ] chat boilerplate?

-[ ] https://github.com/langleyfoxall/laravel-nist-password-rules

-[ ] ban user for pried

//

kirschbaum-development/laravel-translations-loader

andywer/laravel-js-localization

rmariuzzo/Laravel-JS-Localization

### Commands before deploying
```bash
composer install --optimize-autoloader --no-dev
php artisan cache:clear
php artisan config:cache
php artisan event:scan
php artisan route:scan
php artisan model:scan
php artisan route:cache
``` 

## License

The Laravel framework is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).

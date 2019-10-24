# laravel6-boilerplate
laravel6-boilerplate


```php
php artisan app:init
php artisan generate:model-factory //https://github.com/mpociot/laravel-test-factory-helper
php artisan api:doc //https://github.com/mpociot/laravel-apidoc-generator
```

https://github.com/glorand/laravel-model-settings

https://github.com/illuminatech/validation-composite

https://github.com/Askedio/laravel-soft-cascade

https://laravel.com/docs/6.x/horizon

todo
laravel echo server

lang copy artisan console
vendor/caouecs/laravel-lang 
https://github.com/caouecs/Laravel-lang


laravel
laravel/passport
laravel/socialite

proengsoft/laravel-jsvalidation
staudenmeir/eloquent-has-many-deep
spatie/once
spatie/async
imangazaliev/didom
hyungju/laravel-sens
guzzlehttp/guzzle
awobaz/compoships
intervention/image
jenssegers/mongodb
spatie/laravel-medialibrary
google/apiclient

nova
laravel/nova
numaxlab/nova-ckeditor5-classic
beyondcode/nova-tinker-tool
ebess/advanced-nova-media-library


https://github.com/webmozart/path-util
```php
use Webmozart\PathUtil\Path;

Path::join('dirPath','filePath');

```

```bash

docker-compose up -d nginx mysql redis php-worker phpmyadmin laravel-echo-server
docker-compose exec workspace bash
```

composer usage on windows (Horizon require PHP extension pcntl, but window can't install it.)
```php

composer require {package} --ignore-platform-reqs

```

Change .env
```bash
php artisan env:copy docker
php artisan env:copy local
php artisan env:copy prod
```


todo 
echoserver
authunticate

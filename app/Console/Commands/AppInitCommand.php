<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Santutu\LaravelDotEnv\DotEnv;
use Symfony\Component\Console\Output\ConsoleOutput;
use Webmozart\PathUtil\Path;

class AppInitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init {--name=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    protected $outputOption;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->outputOption = new ConsoleOutput();
    }

    protected $defaultPhpVersion = '7.2';
    protected $defaultMysqlVersion = '5.7.26';

    public function handle()
    {
        if (\App::isProduction()) {
            $this->error("cant't execute command in production mode.");
            return false;
        }
        [$appName, $phpVersion, $mysqlVersion, $mysqlDatabaseName] = $this->getOptions();

        if (!$this->confirm('Do you wish to continue?', true)) {
            return false;
        }

        $tempDotEnv = $this->envTemp($appName, $mysqlDatabaseName);
        try {
            $this->envLocal($tempDotEnv);

            $newLaradockDirName = $this->envLaradock($appName, $phpVersion, $mysqlVersion, $mysqlDatabaseName);

            $this->envDocker($tempDotEnv);
            $this->envProd($tempDotEnv);

            //init app
            $env = $this->choice('Select Environment', ['local', 'docker'], 0);

            \DotEnv::copy($env);
            \DotEnv::set('APP_KEY', $this->generateRandomKey());
            $this->artisanCall('storage:link');
        } finally {
            unlink($tempDotEnv->getDotEnvFilePath());
        }
        switch ($env) {
            case 'docker' :
            {
                $this->line('Install docker container and run.');
                $containers = ['nginx', 'mysql', 'redis', 'php-worker', 'laravel-echo-server', 'phpmyadmin'];
                $this->info("Type this - cd \"{$newLaradockDirName}\"");
                $this->info('Type this - "docker-compose up -d ' . implode(' ', $containers) . '"');
                $this->info('Type "docker-compose run workspace php artisan migrate"');
                $this->info('Type "docker-compose run workspace php artisan db:seed"');
                $this->info('Type "npm run watch" for hotreload on project root dir.');
                $this->info('Can enter container by "docker-compose exec workspace bash"');
                break;
            }
            case 'local' :
            {
                $this->reloadDotEnvToApp();
                try {
                    $this->artisanCall('migrate');
                    $this->artisanCall('db:seed');
                } catch (\Exception $e) {
                    $this->error('Error while migrating. check mysql server is running.');
                    $this->error($e->getMessage());
                }
                $this->info('Type "npm run watch" for hotreload.');
                $this->artisanCall('serve');
                break;
            }
        }


        return true;
    }


    /**
     * @return array
     */
    public function getOptions(): array
    {
        $appName = $this->ask('What is your application name', 'laravel-boilerplate');
        $phpVersion = $this->anticipate('What is your php version?', [$this->defaultPhpVersion], $this->defaultPhpVersion);
        $mysqlVersion = $this->anticipate('What is mysql version?', [$this->defaultMysqlVersion], $this->defaultMysqlVersion);
        $mysqlDatabaseName = $this->ask('What is mysql database name?', Str::snake($appName));
        return [$appName, $phpVersion, $mysqlVersion, $mysqlDatabaseName];
    }


    protected function artisanCall(string $command, array $arguments = [])
    {
        Artisan::call($command, $arguments, $this->outputOption);
    }

    protected function setEnv(DotEnv $dotEnv, string $key, $value)
    {
        $value = \DotEnv::convertValue($value);
        return $this->artisanCall("env:set {$key} {$value} --env {$dotEnv->dotEnvFilePath}");
    }

    protected function generateRandomKey()
    {
        return 'base64:' . base64_encode(
                Encrypter::generateKey($this->laravel['config']['app.cipher'])
            );
    }


    public function envTemp($appName, $mysqlDatabaseName)
    {
        $tempDotEnv = (new DotEnv('app-init-temp'))->copy('example');
        $this->setEnv($tempDotEnv, 'APP_NAME', $appName);
        $this->setEnv($tempDotEnv, 'APP_ENV', 'local');
        $this->setEnv($tempDotEnv, 'APP_DEBUG', true);
        $this->setEnv($tempDotEnv, 'APP_KEY', $this->generateRandomKey());
        $this->setEnv($tempDotEnv, 'DB_DATABASE', $mysqlDatabaseName);
        $this->setEnv($tempDotEnv, 'REDIS_CLIENT', 'predis');
        $this->setEnv($tempDotEnv, 'REDIS_PREFIX', '');

        return $tempDotEnv;
    }

    /**
     * @param DotEnv $tempDotEnv
     * @throws \Exception
     */
    public function envLocal(DotEnv $tempDotEnv): void
    {
        $localDotEnv = (new DotEnv('local'))->copyByIns($tempDotEnv);
        $this->setEnv($localDotEnv, 'APP_URL', 'http://127.0.0.1:8000');
        $this->setEnv($localDotEnv, 'DB_HOST', '127.0.0.1');
        $this->setEnv($localDotEnv, 'DB_USERNAME', 'root');
        $this->setEnv($localDotEnv, 'DB_PASSWORD', '');

        $this->setEnv($localDotEnv, 'REDIS_HOST', '127.0.0.1');

        $this->setEnv($localDotEnv, 'BROADCAST_DRIVER', 'redis');
        $this->setEnv($localDotEnv, 'CACHE_DRIVER', 'file');
        $this->setEnv($localDotEnv, 'QUEUE_CONNECTION', 'sync');
        $this->setEnv($localDotEnv, 'SESSION_DRIVER', 'file');

        $this->setEnv($localDotEnv, 'LARAVEL_ECHO_SERVER_DEBUG', true);
        $this->setEnv($localDotEnv, 'LARAVEL_ECHO_SERVER_AUTH_HOST', 'http://127.0.0.1:8000');
        $this->setEnv($localDotEnv, 'LARAVEL_ECHO_SERVER_REDIS_HOST', '127.0.0.1');
        $this->setEnv($localDotEnv, 'LARAVEL_ECHO_SERVER_REDIS_PORT', '6379');
        $this->setEnv($localDotEnv, 'LARAVEL_ECHO_SERVER_REDIS_PASSWORD', null);
    }


    public function envDocker(DotEnv $tempDotEnv): void
    {
        $dockerEnv = (new DotEnv('docker'))->copyByIns($tempDotEnv);
        $this->setEnv($dockerEnv, 'APP_URL', 'http://localhost');

        $this->setEnv($dockerEnv, 'DB_HOST', 'mysql');
        $this->setEnv($dockerEnv, 'DB_USERNAME', 'default');
        $this->setEnv($dockerEnv, 'DB_PASSWORD', 'secret');

        $this->setEnv($dockerEnv, 'REDIS_HOST', 'redis');

        $this->setEnv($dockerEnv, 'BROADCAST_DRIVER', 'redis');
        $this->setEnv($dockerEnv, 'CACHE_DRIVER', 'redis');
        $this->setEnv($dockerEnv, 'QUEUE_CONNECTION', 'redis');
        $this->setEnv($dockerEnv, 'SESSION_DRIVER', 'redis');

        $this->setEnv($dockerEnv, 'LARAVEL_ECHO_SERVER_DEBUG', true);
        $this->setEnv($dockerEnv, 'LARAVEL_ECHO_SERVER_AUTH_HOST', 'http://nginx');
        $this->setEnv($dockerEnv, 'LARAVEL_ECHO_SERVER_REDIS_HOST', 'redis');
        $this->setEnv($dockerEnv, 'LARAVEL_ECHO_SERVER_REDIS_PORT', '6379');
        $this->setEnv($dockerEnv, 'LARAVEL_ECHO_SERVER_REDIS_PASSWORD', null);
    }

    public function envProd(DotEnv $tempDotEnv): void
    {
        $prodDotEnv = (new DotEnv('prod'))->copyByIns($tempDotEnv);
        $this->setEnv($prodDotEnv, 'APP_ENV', 'production');
        $this->setEnv($prodDotEnv, 'APP_DEBUG', false);
        $this->setEnv($prodDotEnv, 'APP_URL', '');

        $this->setEnv($prodDotEnv, 'BROADCAST_DRIVER', 'redis');
        $this->setEnv($prodDotEnv, 'CACHE_DRIVER', 'redis');
        $this->setEnv($prodDotEnv, 'QUEUE_CONNECTION', 'redis');
        $this->setEnv($prodDotEnv, 'SESSION_DRIVER', 'redis');

        $this->setEnv($prodDotEnv, 'LARAVEL_ECHO_SERVER_DEBUG', false);
        $this->setEnv($prodDotEnv, 'LARAVEL_ECHO_SERVER_AUTH_HOST', 'http://nginx');
        $this->setEnv($prodDotEnv, 'LARAVEL_ECHO_SERVER_REDIS_HOST', 'redis');
        $this->setEnv($prodDotEnv, 'LARAVEL_ECHO_SERVER_REDIS_PORT', '6379');
        $this->setEnv($prodDotEnv, 'LARAVEL_ECHO_SERVER_REDIS_PASSWORD', null);
    }

    /**
     * @param $appName
     * @param $phpVersion
     * @param $mysqlVersion
     * @param $mysqlDatabaseName
     * @return string
     */
    public function envLaradock($appName, $phpVersion, $mysqlVersion, $mysqlDatabaseName): string
    {
        $oriLaradockDirPath = collect(File::directories('./'))->first(function (string $dirname) {
            return mb_strpos($dirname, 'laradock-') !== false;
        });
        $newLaradockDirName = "laradock-{$appName}";
        if (Path::getFilename($oriLaradockDirPath) !== Path::getFilename($newLaradockDirName)) {
            File::move($oriLaradockDirPath, $newLaradockDirName);
        }
        $laradockEnvFilePath = "{$newLaradockDirName}/.env";

        if (!file_exists("{$newLaradockDirName}/.env-example")) {
            copy("{$newLaradockDirName}/env-example", "{$newLaradockDirName}/.env-example");
        }
        $laradockDotEnv = (new DotEnv)->copy("{$newLaradockDirName}/.env-example", $laradockEnvFilePath);

        $this->setEnv($laradockDotEnv, 'COMPOSE_PROJECT_NAME', $newLaradockDirName);
        $this->setEnv($laradockDotEnv, 'PHP_VERSION', $phpVersion);
        $this->setEnv($laradockDotEnv, 'PHP_WORKER_INSTALL_BCMATH', true);
        $this->setEnv($laradockDotEnv, 'PHP_FPM_INSTALL_EXIF', true);

        $this->setEnv($laradockDotEnv, 'MYSQL_VERSION', $mysqlVersion);
        $this->setEnv($laradockDotEnv, 'MYSQL_DATABASE', $mysqlDatabaseName);
        $this->setEnv($laradockDotEnv, 'MYSQL_USER', 'default');
        $this->setEnv($laradockDotEnv, 'MYSQL_PASSWORD', 'secret');
        $this->setEnv($laradockDotEnv, 'MYSQL_ROOT_PASSWORD', 'root');
        return $newLaradockDirName;
    }

    public function reloadDotEnvToApp(): void
    {
        app()->loadEnvironmentFrom('.env.local');
        app()->loadEnvironmentFrom('.env');
    }

}

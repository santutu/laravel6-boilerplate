<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Santutu\LaravelDotEnv\DotEnv;
use Symfony\Component\Console\Output\ConsoleOutput;

class InitAppCommand extends Command
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

        [$appName, $phpVersion, $mysqlVersion, $mysqlDatabaseName] = $this->getOptions();

        if (!$this->confirm('Do you wish to continue?', true)) {
            return false;
        }

        $exampleDotEnv = $this->envExample($appName, $mysqlDatabaseName);
        $this->envLocal($exampleDotEnv);

        $newLaradockDirName = $this->envLaradock($appName, $phpVersion, $mysqlVersion, $mysqlDatabaseName);
        $this->envDocker();
        $this->envProd();

        //init app
        $env = $this->choice('Select Environment', ['local', 'docker'], 0);

        \DotEnv::copy($env);
        \DotEnv::set('APP_KEY', $this->generateRandomKey());
        $this->reloadDotEnvToApp();

        $this->artisanCall('storage:link');
        $this->artisanCall('migrate');

        switch ($env) {
            case 'docker' :
            {
                $this->info('Do you want to install docker container and run?');
                $containers = ['nginx', 'mysql', 'redis', 'php-worker', 'laravel-echo-server', 'phpmyadmin'];
                $this->line("type this - cd \"{$newLaradockDirName}\"");
                $this->line('type this - "docker-compose up -d ' . implode(' ', $containers) . '"');
                break;
            }
            case 'local' :
            {
                $this->artisanCall('serve');
                break;
            }
        }


        return true;
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


    public function envExample($appName, $mysqlDatabaseName)
    {
        $exampleDotEnv = new DotEnv('example');
        $this->setEnv($exampleDotEnv, 'APP_NAME', $appName);
        $this->setEnv($exampleDotEnv, 'APP_ENV', 'local');
        $this->setEnv($exampleDotEnv, 'APP_DEBUG', true);
        $this->setEnv($exampleDotEnv, 'APP_KEY', $this->generateRandomKey());
        $this->setEnv($exampleDotEnv, 'DB_DATABASE', $mysqlDatabaseName);
        $this->setEnv($exampleDotEnv, 'TELESCOPE_ENABLED', true);
        $this->setEnv($exampleDotEnv, 'REDIS_CLIENT', 'predis');
        $this->setEnv($exampleDotEnv, 'REDIS_PREFIX', '');

        return $exampleDotEnv;
    }

    /**
     * @param DotEnv $exampleDotEnv
     * @throws \Exception
     */
    public function envLocal(DotEnv $exampleDotEnv): void
    {
        $localDotEnv = (new DotEnv('local'))->copyByIns($exampleDotEnv);
        $this->setEnv($localDotEnv, 'APP_URL', 'http://127.0.0.1:8000');
        $this->setEnv($localDotEnv, 'DB_HOST', '127.0.0.1');
        $this->setEnv($localDotEnv, 'DB_USERNAME', 'root');
        $this->setEnv($localDotEnv, 'DB_PASSWORD', '');

        $this->setEnv($localDotEnv, 'REDIS_HOST', '127.0.0.1');

        $this->setEnv($localDotEnv, 'BROADCAST_DRIVER', 'log');
        $this->setEnv($localDotEnv, 'CACHE_DRIVER', 'file');
        $this->setEnv($localDotEnv, 'QUEUE_CONNECTION', 'sync');
        $this->setEnv($localDotEnv, 'SESSION_DRIVER', 'file');
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
        File::move($oriLaradockDirPath, $newLaradockDirName);
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

    public function envDocker(): void
    {
        $dockerEnv = (new DotEnv)->copy('example', 'docker');
        $this->setEnv($dockerEnv, 'APP_URL', 'http://localhost');

        $this->setEnv($dockerEnv, 'DB_HOST', 'mysql');
        $this->setEnv($dockerEnv, 'DB_USERNAME', 'default');
        $this->setEnv($dockerEnv, 'DB_PASSWORD', 'secret');

        $this->setEnv($dockerEnv, 'REDIS_HOST', 'redis');

        $this->setEnv($dockerEnv, 'BROADCAST_DRIVER', 'redis');
        $this->setEnv($dockerEnv, 'CACHE_DRIVER', 'redis');
        $this->setEnv($dockerEnv, 'QUEUE_CONNECTION', 'redis');
        $this->setEnv($dockerEnv, 'SESSION_DRIVER', 'redis');
    }

    public function envProd(): void
    {
        $prodDotEnv = (new DotEnv)->copy('example', 'prod');
        $this->setEnv($prodDotEnv, 'APP_ENV', 'production');
        $this->setEnv($prodDotEnv, 'APP_DEBUG', false);
        $this->setEnv($prodDotEnv, 'APP_URL', '');
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

    public function reloadDotEnvToApp(): void
    {
        app()->loadEnvironmentFrom('.env.local');
        app()->loadEnvironmentFrom('.env');
    }

}

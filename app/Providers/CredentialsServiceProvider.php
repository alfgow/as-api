<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CredentialsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $path = config_path('credentials/credentials.local.php');

        if (! file_exists($path)) {
            return;
        }

        $c = require $path;

        // Sobreescribe configuración base
        config([
            'app.url' => $c['app']['url'] ?? config('app.url'),
            'app.key' => $c['app']['key'] ?? config('app.key'),

            'database.connections.mysql.host'     => $c['database']['host'],
            'database.connections.mysql.port'     => $c['database']['port'],
            'database.connections.mysql.database' => $c['database']['name'],
            'database.connections.mysql.username' => $c['database']['user'],
            'database.connections.mysql.password' => $c['database']['password'],
            'database.connections.mysql.charset'  => $c['database']['charset'] ?? 'utf8mb4',

            'as-api.aws' => $c['aws'],
        ]);

        // Configuración dinámica de buckets individuales
        config([
            'filesystems.disks.inquilinos' => [
                'driver' => 's3',
                'key'    => $c['aws']['access_key'],
                'secret' => $c['aws']['secret_key'],
                'region' => $c['aws']['s3']['inquilinos']['region'],
                'bucket' => $c['aws']['s3']['inquilinos']['bucket'],
            ],
            'filesystems.disks.arrendadores' => [
                'driver' => 's3',
                'key'    => $c['aws']['access_key'],
                'secret' => $c['aws']['secret_key'],
                'region' => $c['aws']['s3']['arrendadores']['region'],
                'bucket' => $c['aws']['s3']['arrendadores']['bucket'],
            ],
            'filesystems.disks.blog' => [
                'driver' => 's3',
                'key'    => $c['aws']['access_key'],
                'secret' => $c['aws']['secret_key'],
                'region' => $c['aws']['s3']['blog']['region'],
                'bucket' => $c['aws']['s3']['blog']['bucket'],
            ],
        ]);
    }

    public function boot(): void
    {
        //
    }
}

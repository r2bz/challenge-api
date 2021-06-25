<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0683bb6cf080b1dee0e3de344ff008bc
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'Api\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Api\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Api\\App\\Models\\Alert' => __DIR__ . '/../..' . '/src/App/Models/Alert.php',
        'Api\\App\\Models\\Incident' => __DIR__ . '/../..' . '/src/App/Models/Incident.php',
        'Api\\App\\Models\\Metric' => __DIR__ . '/../..' . '/src/App/Models/Metric.php',
        'Api\\App\\Models\\MetricsReport' => __DIR__ . '/../..' . '/src/App/Models/MetricsReport.php',
        'Api\\App\\Services\\AlertService' => __DIR__ . '/../..' . '/src/App/Services/AlertService.php',
        'Api\\App\\Services\\HealthService' => __DIR__ . '/../..' . '/src/App/Services/HealthService.php',
        'Api\\App\\Services\\MetricsService' => __DIR__ . '/../..' . '/src/App/Services/MetricsService.php',
        'Api\\App\\Services\\ReceiveService' => __DIR__ . '/../..' . '/src/App/Services/ReceiveService.php',
        'Api\\Config\\Database' => __DIR__ . '/../..' . '/src/Config/Database.php',
        'Api\\Config\\Log' => __DIR__ . '/../..' . '/src/Config/Log.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0683bb6cf080b1dee0e3de344ff008bc::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0683bb6cf080b1dee0e3de344ff008bc::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit0683bb6cf080b1dee0e3de344ff008bc::$classMap;

        }, null, ClassLoader::class);
    }
}
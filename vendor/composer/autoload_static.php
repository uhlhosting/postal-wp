<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit252b57ff20caea505865470914bfae40
{
    public static $files = array (
        '941748b3c8cae4466c827dfb5ca9602a' => __DIR__ . '/..' . '/rmccue/requests/library/Deprecated.php',
    );

    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WpOrg\\Requests\\' => 15,
        ),
        'A' => 
        array (
            'AtelliTech\\Postal\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WpOrg\\Requests\\' => 
        array (
            0 => __DIR__ . '/..' . '/rmccue/requests/src',
        ),
        'AtelliTech\\Postal\\' => 
        array (
            0 => __DIR__ . '/..' . '/atellitech/postal/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Requests' => __DIR__ . '/..' . '/rmccue/requests/library/Requests.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit252b57ff20caea505865470914bfae40::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit252b57ff20caea505865470914bfae40::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit252b57ff20caea505865470914bfae40::$classMap;

        }, null, ClassLoader::class);
    }
}
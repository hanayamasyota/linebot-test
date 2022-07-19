<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitedc1fe89040b5aacd72b8652b6a3f6ca
{
    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'LINE\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'LINE\\' => 
        array (
            0 => __DIR__ . '/..' . '/linecorp/line-bot-sdk/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitedc1fe89040b5aacd72b8652b6a3f6ca::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitedc1fe89040b5aacd72b8652b6a3f6ca::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitedc1fe89040b5aacd72b8652b6a3f6ca::$classMap;

        }, null, ClassLoader::class);
    }
}
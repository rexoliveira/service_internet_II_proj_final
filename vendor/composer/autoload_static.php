<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4fd517c9685ae08498464e7aea3ab3f5
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4fd517c9685ae08498464e7aea3ab3f5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4fd517c9685ae08498464e7aea3ab3f5::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4fd517c9685ae08498464e7aea3ab3f5::$classMap;

        }, null, ClassLoader::class);
    }
}

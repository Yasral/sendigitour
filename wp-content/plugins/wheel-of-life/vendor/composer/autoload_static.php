<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4e0dc4be192f0368fb9aaa0c7b387dd1
{
    public static $files = array (
        '6c41697b31a6dbbb45ab0240329e0c82' => __DIR__ . '/../..' . '/includes/functions/HelperFunctions.php',
        'd59b526359b0ef622b26309a2d7293d2' => __DIR__ . '/../..' . '/includes/functions/AjaxFunctions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WheelOfLife\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WheelOfLife\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4e0dc4be192f0368fb9aaa0c7b387dd1::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4e0dc4be192f0368fb9aaa0c7b387dd1::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4e0dc4be192f0368fb9aaa0c7b387dd1::$classMap;

        }, null, ClassLoader::class);
    }
}

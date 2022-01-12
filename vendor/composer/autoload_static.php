<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit59cd42fa8c407ff3a30639f257ef376b
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
            $loader->prefixLengthsPsr4 = ComposerStaticInit59cd42fa8c407ff3a30639f257ef376b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit59cd42fa8c407ff3a30639f257ef376b::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit59cd42fa8c407ff3a30639f257ef376b::$classMap;

        }, null, ClassLoader::class);
    }
}

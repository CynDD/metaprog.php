<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita842e8b92657e3e5e5dac5326eab139e
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PhpParser\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PhpParser\\' => 
        array (
            0 => __DIR__ . '/..' . '/nikic/php-parser/lib/PhpParser',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita842e8b92657e3e5e5dac5326eab139e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita842e8b92657e3e5e5dac5326eab139e::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}

<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitbed2006dafe15c12e4c8ea29e3e52f01
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitbed2006dafe15c12e4c8ea29e3e52f01', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitbed2006dafe15c12e4c8ea29e3e52f01', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitbed2006dafe15c12e4c8ea29e3e52f01::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}

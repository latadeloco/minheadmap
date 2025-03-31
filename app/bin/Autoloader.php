<?php

namespace MinHeadmap\App\Bin;

class Autoloader
{
    private static $namespaces = [];

    public static function register(): void
    {
        spl_autoload_register([__CLASS__, 'loadClass']);
    }

    public static function addNamespace(string $prefix, string $baseDir, bool $prepend = false): void
    {
        $prefix = trim($prefix, '\\') . '\\';
        $baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR) . '/';

        if (!isset(self::$namespaces[$prefix])) {
            self::$namespaces[$prefix] = [];
        }

        if ($prepend) {
            array_unshift(self::$namespaces[$prefix], $baseDir);
        } else {
            array_push(self::$namespaces[$prefix], $baseDir);
        }
    }

    public static function loadClass(string $class): ?string
    {
        $prefix = $class;

        while (false !== $pos = strrpos($prefix, '\\')) {
            $prefix = substr($class, 0, $pos + 1);
            $relativeClass = substr($class, $pos + 1);

            if ($file = self::loadFile($prefix, $relativeClass)) {
                return $file;
            }

            $prefix = rtrim($prefix, '\\');
        }

        return null;
    }

    protected static function loadFile(string $prefix, string $relativeClass): ?string
    {
        if (!isset(self::$namespaces[$prefix])) {
            return null;
        }

        foreach (self::$namespaces[$prefix] as $baseDir) {
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

            if (file_exists($file)) {
                require $file;
                return $file;
            }
        }

        return null;
    }
}
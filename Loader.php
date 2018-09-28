<?php
namespace Guanjia;

class Loader{
    // Guanjia\EPP\verisignEppConnection
    public static function autoloadRegistry($className) {
        $fileName = str_replace('Guanjia\\EPP\\', '', $className);
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $fileName = __DIR__ . '\\Registries\\' . $fileName . '\\eppConnection.php';
        } else {
            $fileName = __DIR__ . '/Registries/' . $fileName . '/eppConnection.php';
        }
        //echo "Test autoload registry epp $fileName\n";
        if (is_readable($fileName)) {
            //echo "Autoloaded registry epp $fileName\n";
            require($fileName);
        }
        $fileName = str_replace('Guanjia\\TMCH\\', '', $className);
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $fileName = __DIR__ . '\\Registries\\' . $fileName . '\\tmchConnection.php';
        } else {
            $fileName = __DIR__ . '/Registries/' . $fileName . '/tmchConnection.php';
        }
        //echo "Test autoload registry tmch $fileName\n";
        if (is_readable($fileName)) {
            //echo "Autoloaded registry tmch $fileName\n";
            require($fileName);
        }
    }

    public static function autoloadEPP($className) {
        // First load data elements
        $fileName = str_replace('Guanjia\\EPP\\', '', $className);
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $fileName = __DIR__ . '\\Protocols\\EPP\\eppData\\' . $fileName . '.php';
        } else {
            $fileName = __DIR__ . '/Protocols/EPP/eppData/' . $fileName . '.php';
        }
        //echo "Test autoload data $fileName\n";
        if (is_readable($fileName)) {
            //echo "Autoloaded data $fileName\n";
            require($fileName);
        }
        // Then load protocol files
        $fileName = str_replace('Guanjia\\', '', $className);
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $fileName = __DIR__ . '\\Protocols\\' . $fileName . '.php';
            // Support for EPP Request file structure
            if (strpos($className, 'Request')) {
                $fileName = str_replace('Protocols\\EPP\\', 'Protocols\\EPP\\eppRequests\\', $fileName);
            }
            // Support for EPP Response file structure
            if (strpos($className, 'Response')) {
                $fileName = str_replace('Protocols\\EPP\\', 'Protocols\\EPP\\eppResponses\\', $fileName);
            }
        } else {
            $fileName = __DIR__ . '/Protocols/' . str_replace('\\', '/', $fileName) . '.php';
            // Support for EPP Request file structure
            if (strpos($className, 'Request')) {
                $fileName = str_replace('Protocols/EPP/', 'Protocols/EPP/eppRequests/', $fileName);
            }
            // Support for EPP Response file structure
            if (strpos($className, 'Response')) {
                $fileName = str_replace('Protocols/EPP/', 'Protocols/EPP/eppResponses/', $fileName);
            }
        }

        //echo "Test autoload EPP $fileName\n";
        if (is_readable($fileName)) {
            //echo "Autoloaded EPP $fileName\n";
            require($fileName);
        }
    }

    public static function autoloadTMCH($className) {
        // First load data elements
        $fileName = str_replace('Guanjia\\TMCH\\', '', $className);
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $fileName = __DIR__ . '\\Protocols\\TMCH\\tmchData\\' . $fileName . '.php';
        } else {
            $fileName = __DIR__ . '/Protocols/TMCH/tmchData/' . $fileName . '.php';
        }
        //echo "Test autoload tmch data $fileName\n";
        if (is_readable($fileName)) {
            // echo "Autoloaded tmch data $fileName\n";
            require($fileName);
        }
        // Then load protocol files
        $fileName = str_replace('Guanjia\\TMCH\\', '', $className);
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $fileName = __DIR__ . '\\Protocols\\TMCH\\' . $fileName . '.php';
            // Support for EPP Request file structure
        } else {
            $fileName = __DIR__ . '/Protocols/TMCH/' . str_replace('\\', '/', $fileName) . '.php';
            // Support for EPP Request file structure
        }

        //echo "Test autoload TMCH $fileName\n";
        if (is_readable($fileName)) {
            //echo "Autoloaded TMCH $fileName\n";
            require($fileName);
        }
    }

    public static function load()
    {
        spl_autoload_register(Loader::class . '::autoloadEPP');
        spl_autoload_register(Loader::class . '::autoloadTMCH');
        spl_autoload_register(Loader::class . '::autoloadRegistry');
    }
}
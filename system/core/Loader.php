<?php
/**
 * Loader
 * php version 7.3.5
 *
 * @category Loader
 * @package  Core
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace System\Core;

use System\Core\Utility;
use System\Core\FrameworkException;

defined('VALID_REQ') or exit('Invalid request');
/**
 * Loader Class autoloads the files
 *
 * @category Loader
 * @package  Core
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
class Loader
{
    /**
     * Loader class instance
     *
     * @var Loader|null $_instance
     */
    private static $_instance = null;

    private $_prefixes = [];
    /**
     * Controller object
     *
     * @var Loader
     */
    private static $_ctrl;

    /**
     * Instantiate the the Loader instance
     */
    private function __construct()
    {
        global $config;
        $this->_prefixes = [
            "App\Controller\\" => $config['controller'],
            "App\Model\\" => $config['model'],
            "App\DataModel\\" => $config['model'] ."DataModel/",
            "App\Service\\" => $config['service'],
            "System\Helper\\" => $config['helper'],
            "System\Core\\" => "system/core/",
            "System\Library\\" => $config['library'],
            "System\Database\\" => "system/database/"
        ];
        spl_autoload_register([$this, "autoLoader"]);
        $this->loadAll('system/core');
        $this->loadAll('system/database');
        $this->loadAll('app/config/routes');
    }

    /**
     * Loads the all classes from autoload class list
     * and creates the instance for them
     *
     * @param SysController $ctrl Controller object
     *
     * @return Loader
     * @throws FrameworkException
     */
    public static function autoLoadClass(SysController $ctrl): Loader
    {
        global $autoload, $config;
        $loads = ['model', 'service', 'library', 'helper'];
        if (isset(static::$_instance)) {
            static::$_ctrl = $ctrl;
            foreach ($loads as $load) {
                $files = $autoload[$load];
                is_array($files) or $files = array($files);
                static::$_instance->$load(...$files);
            }
            return static::$_instance;
        }
        throw new FrameworkException("Loader class is not Initialized");
    }

    /**
     * Loads models
     *
     * @param string ...$models Model list
     *
     * @return void
     * @throws FrameworkException
     */
    public function model(...$models)
    {
        global $config;
        foreach ($models as $model) {
            $file =  $config['model'] . '/' . (Utility::endswith($model, "Model")
                ? $model
                : $model . 'Model') . '.php';
            $class = "App\\Model\\" . $model . 'Model';
            if (file_exists($file)) {
                include_once $file;
                static::$_ctrl->{lcfirst($model)} = new $class();
            } else {
                throw new FrameworkException(
                    "Unable to locate the model class '$model'"
                );
            }
        }
    }

    /**
     * Loads Services
     *
     * @param string ...$services Service list
     *
     * @return void
     * @throws FrameworkException
     */
    public function service(...$services)
    {
        global $config;
        foreach ($services as $service) {
            $file =  $config['service'] . '/'
                . (Utility::endswith($service, "Service")
                    ? $service
                    : $service . 'Service')
                . '.php';
            $class = "App\\Service\\" . $service . 'Service';
            if (file_exists($file)) {
                include_once $file;
                static::$_ctrl->{lcfirst($service)} = new $class();
            } else {
                throw new FrameworkException(
                    "Unable to loacate the '$service' class"
                );
            }
        }
    }

    /**
     * Loads Libraries
     *
     * @param string ...$libraries Library list
     *
     * @return void
     * @throws FrameworkException
     */
    public function library(...$libraries)
    {
        global $config;
        foreach ($libraries as $library) {
            if (file_exists($config['library'] . $library . '.php')) {
                include_once $config['library'] . $library . '.php';
                $class = "App\\Library\\".$library;
                static::$_ctrl->{lcfirst($library)} = new $class();
            } elseif (file_exists("system/library/" . $library . '.php')) {
                include_once "system/library/" . $library . '.php';
                $class = "System\Library\\".$library;
                static::$_ctrl->{lcfirst($library)} = new $class();
            } else {
                throw new FrameworkException("Library class '$library' not found");
            }
        }
    }


    /**
     * Loads helpers
     *
     * @param string ...$helpers Helper list
     *
     * @return void
     * @throws FrameworkException
     */
    public function helper(...$helpers)
    {
        global $config;
        foreach ($helpers as $helper) {
            $helper = (Utility::endswith($helper, "helper")
                ? $helper
                : $helper . '.php');
            if (file_exists($config['helper'] . '/' . $helper)) {
                include_once $config['helper'] . '/' . $helper;
            } elseif (file_exists('system/helper/' . $helper)) {
                include_once 'system/helper/' . $helper;
            } else {
                throw new FrameworkException("Helper class '$helper' not found");
            }
        }
    }

    /**
     * Loads all php files from the specified directory
     *
     * @param string $dir Directory Name
     *
     * @return void
     */
    public function loadAll(string $dir)
    {
        foreach (glob("$dir/*.php") as $filename) {
            include_once $filename;
        }
    }

    /**
     * Function autoloader
     *
     * @param string $class classname
     *
     * @return void
     */
    public function autoLoader(string $class)
    {
        global $config;
        foreach ($this->_prefixes as $prefix => $dir) {
            if (strpos($class, $prefix) == 0) {
                if ($this->loadFile($class, $prefix)) {
                    break;
                }
            }
        }
    }

    /**
     * Includes the file if it exists
     *
     * @param string      $file   file name
     * @param string|null $prefix namesapace prefix if any
     *
     * @return bool
     */
    public function loadFile(string $file, ?string $prefix = null): bool
    {
        $file = rtrim($file, '.php') . '.php';
        if ($prefix != null) {
            if (isset($this->_prefixes[$prefix])) {
                $path = rtrim($this->_prefixes[$prefix], "/");
                $prefix = str_replace("\\", "/", $prefix);
                $file = str_replace("\\", "/", $file);
                $prefix = '#'. rtrim($prefix, "/") .'#';
                $file = preg_replace($prefix, $path, $file, 1);
            } else {
                return false;
            }
        }
        if (file_exists($file)) {
            include_once $file;
            return true;
        }
        return false;
    }

    /**
     * Intialize the Loader class and returns load class object
     *
     * @return Loader
     */
    public static function intialize(): Loader
    {
        if (self::$_instance == null) {
            self::$_instance = new static();
        }
        return self::$_instance;
    }
}

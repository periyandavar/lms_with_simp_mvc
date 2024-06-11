<?php
/**
 * SysController
 * php version 7.3.5
 *
 * @category Controller
 * @package  Core
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace System\Core;

defined('VALID_REQ') or exit('Invalid request');
use System\Core\Utility;
use System\Core\Log;

/**
 * Super class for all controller. All controllers should extend this controller
 * SysController class consists of basic level functions for various purposes
 *
 * @category Controller
 * @package  Core
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
class SysController
{
    /**
     * Model class object that will has the link to the Model Class
     * using this variable we can acces the model class functions within this
     * controller Ex : $this->model->getData();
     *
     * @var Model $model
     */
    protected $model;

    /**
     * Autoload class objects
     *
     * @var array
     */
    private $_obj=[];

    /**
     * Input allows us to access the get, post, session, files values
     *
     * @var InputData $input
     */
    protected $input;

    /**
     * Service class object that will offers the services(bussiness logics)
     *
     * @var Service $service
     */
    protected $service;

    /**
     * Loader class object
     *
     * @var Loader
     */
    protected $load;

    /**
     * Log class instance
     *
     * @var Log
     */
    protected $log;

    /**
     * Instantiate the SysController instance
     *
     * @param Model   $model   model class object to intialize $this->model
     * @param Service $service service class object to intialize $this->service
     */
    public function __construct($model = null, $service = null)
    {
        $this->model = $model;
        $this->service = $service;
        $this->input = new InputData();
        $this->load = Loader::autoLoadClass($this);
        $this->log = Log::getInstance();
        $this->log->info(
            "The " . static::class . " class is initalized successfully"
        );
    }

    /**
     * This function will call when the undefined function is called
     *
     * @param string $name function name
     * @param array  $args arguments
     *
     * @return void
     */
    public function __call(string $name, array $args)
    {
        $this->log->error("Undefined method call in $name " . get_called_class());
    }

    /**
     * This function will call when the undefined static function is called
     *
     * @param string $name function name
     * @param array  $args arguments
     *
     * @return void
     */
    public static function __callStatic($name, $args)
    {
        $this->log->error("Undefined static method call in " . get_called_class());
    }

    // /**
    //  * Making clone as deep copy instead of shallow
    //  *
    //  * @return void
    //  */
    // public function __clone()
    // {
    //     $this->model = clone $this->model;
    //     $this->service = clone $this->service;
    // }

    /**
     * Add new object to $_obj array
     *
     * @param string $name  name
     * @param mixed  $value object
     *
     * @return void
     */
    final public function __set(string $name, $value)
    {
        $this->_obj[$name] = $value;
    }

    /**
     * Get the object
     *
     * @param string $name object name
     *
     * @return mixed
     */
    final public function __get($name)
    {
        if (array_key_exists($name, $this->_obj)) {
            return $this->_obj[$name];
        }
        return null;
    }

    /**
     * Check the object is present or not
     *
     * @param string $name object name
     *
     * @return boolean
     */
    final public function __isset(string $name): bool
    {
        return array_key_exists($name, $this->_obj);
    }
}

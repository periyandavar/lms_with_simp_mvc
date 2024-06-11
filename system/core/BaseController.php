<?php
/**
 * BaseController
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
use System\Core\SysController;

/**
 * Super class for all controller. All controllers should extend this controller
 * BaseController class consists of basic level functions for various purposes
 *
 * @category Controller
 * @package  Core
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
class BaseController extends SysController
{
    /**
     * This function will load the required View(php) file without error on failure
     * only files with .php extension are allowed and those files should
     * store on View Folder
     *
     * @param string     $file filename without extension
     * @param array|null $data varaibles to passed to the view
     *
     * @return void
     */
    final protected function loadView(string $file, ?array $data = null)
    {
        global $config;
        $path = $config['view'] . '' . $file . ".php";
        if (file_exists($path)) {
            if ($data != null) {
                foreach ($data as $key => $value) {
                    $$key = $value;
                }
            }
            include_once $path;
        } else {
            $this->log->debug("Unable to load the $file view");
        }
    }

    /**
     * This function will redirect the page
     *
     * @param string $url       page to redirect
     * @param bool   $permanent optional default:false indicates
     *                          whether the redirect is permanent or not
     *
     * @return void
     */
    final protected function redirect(string $url, bool $permanent = false)
    {
        Utility::redirectURL($url, $permanent);
    }

    /**
     * This function loads html layout files
     *
     * @param string $file html filename with extension
     *
     * @return void
     */
    final protected function loadLayout(string $file)
    {
        global $config;
        $path = $config['layout'] . '/' . $file;
        file_exists($path)
            ? readfile($path)
            : $this->log->warning("Unable to load the $file layout");
    }

    /**
     * This functions include the script file
     *
     * @param string      $script filename with extension
     * @param string|null $path   optional default:true if its true
     *                            this function will include
     *                            JS from static directory
     *
     * @return void
     */
    final public function includeScript(string $script, ?string $path= null)
    {
        global $config;
        $script = ($path ?? ($config['static'] . '/static' . '/js'))
             . "/" . $script;
        echo "<script src='$script'></script>";
    }

    /**
     * This functions include the style sheet
     *
     * @param string      $sheet filename with extension
     * @param string|null $path  optional default:true if its true
     *                           this function will include
     *                           css from static directory
     *
     * @return void
     */
    final public function includeSheet($sheet, ?string $path= null)
    {
        global $config;
        $sheet = ($path ?? ($config['static'] . '/static/css'))
            . "/" . $sheet;
        echo "<link rel='stylesheet' type='text/css' href='$sheet'>";
    }


    /**
     * Adds the Js script on the view
     *
     * @param string $script Script
     *
     * @return void
     */
    final public function addScript(string $script)
    {
        echo "<script>" . $script . "</script>";
    }

    /**
     * Adds the CSS style on the view
     *
     * @param string $style Style
     *
     * @return void
     */
    final public function addStyle(string $style)
    {
        echo "<style>" . $style . "</style>";
    }
}

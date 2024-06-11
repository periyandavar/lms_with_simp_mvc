<?php
/**
 * Router
 * php version 7.3.5
 *
 * @category Router
 * @package  Core
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace System\Core;

defined('VALID_REQ') or exit('Invalid request');
/**
 * Router Class handles routing
 *
 * @category Router
 * @package  Core
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
class Router
{
    /**
     * GET method Routes
     *
     * @var array
     */
    private static $_getMethodRoutes = [];

    /**
     * POST method routes
     *
     * @var array
     */
    private static $_postMethodRoutes = [];

    /**
     * PUT method routes
     *
     * @var array
     */
    private static $_putMethodRoutes = [];

    /**
     * PATCH method routes
     *
     * @var array
     */
    private static $_patchMethodRoutes = [];

    /**
     * DELETE method routes
     *
     * @var array
     */
    private static $_deleteMethodRoutes = [];

    /**
     * Other method routes
     *
     * @var array
     */
    private static $_otherRoutes = [];

    private static $_methodNotAllowed = null;

    private static $_pathNotFound = null;

    private static $_onError = null;

    /**
     * Adds new Route
     *
     * @param string        $route      route
     * @param string|null   $expression execution value (controller/method)
     * @param string        $method     method Name
     * @param callable|null $filter     filter function
     * @param string|null   $name       Route alias name
     *
     * @return void
     */
    public static function add(
        string $route,
        ?string $expression = null,
        string $method = Constants::METHOD_GET,
        ?callable $filter = null,
        ?string $name = null
    ) {
        $method = strtolower($method);
        switch ($method) {
        case Constants::METHOD_GET:
            $name != null
                ? self::$_getMethodRoutes[$name] = [
                    'route' => $route,
                    'expression' => $expression,
                    'rule' => $filter
                    ]
                : self::$_getMethodRoutes[] = [
                    'route' => $route,
                    'expression' => $expression,
                    'rule' => $filter
                    ];
            break;

        case Constants::METHOD_POST:
            $name != null
                ? self::$_postMethodRoutes[$name] = [
                    'route' => $route,
                    'expression' => $expression,
                    'rule' => $filter
                    ]
                : self::$_postMethodRoutes[] = [
                    'route' => $route,
                    'expression' => $expression,
                    'rule' => $filter
                    ];
            break;
        case Constants::METHOD_PUT:
            $name != null
                ? self::$_putMethodRoutes[$name] = [
                    'route' => $route,
                    'expression' => $expression,
                    'rule' => $filter
                    ]
                : self::$_putMethodRoutes[] = [
                    'route' => $route,
                    'expression' => $expression,
                    'rule' => $filter
                    ];
            break;
        case Constants::METHOD_PATCH:
                $name != null
                    ? self::$_patchMethodRoutes[$name] = [
                        'route' => $route,
                        'expression' => $expression,
                        'rule' => $filter
                        ]
                    : self::$_patchMethodRoutes[] = [
                        'route' => $route,
                        'expression' => $expression,
                        'rule' => $filter
                        ];
            break;
        case Constants::METHOD_DELETE:
            $name != null
                ? self::$_deleteMethodRoutes[$name] = [
                    'route' => $route,
                    'expression' => $expression,
                    'rule' => $filter
                    ]
                : self::$_deleteMethodRoutes[] = [
                    'route' => $route,
                    'expression' => $expression,
                    'rule' => $filter
                    ];
            break;
        default:
            $name != null
                ? self::$_otherRoutes[$name] = [
                    'route' => $route,
                    'expression' => $expression,
                    'rule' => $filter
                    ]
                : self::$_otherRoutes[] = [
                    'route' => $route,
                    'expression' => $expression,
                    'rule' => $filter
                    ];
            break;
        }
    }

    /**
     * Returns the URL
     *
     * @param string     $name   URL alias name
     * @param string     $method URL method
     * @param array|null $data   URL data
     *
     * @return string|null
     */
    public static function getURL(
        string $name,
        string $method = 'get',
        array $data = null
    ): ?string {
        $route = null;
        switch ($method) {
        case Constants::METHOD_GET:
            $route = isset(self::$_getMethodRoutes[$name])
                ? self::$_getMethodRoutes[$name]['route']
                : null;
            break;
        case Constants::METHOD_POST:
            $route = isset(self::$_postMethodRoutes[$name])
                ? self::$_postMethodRoutes[$name]['route']
                : null;
            break;
        case Constants::METHOD_PUT:
            $route = isset(self::$_putMethodRoutes[$name])
                ? self::$_putMethodRoutes[$name]['route']
                : null;
            break;
        case Constants::METHOD_PATCH:
            $route = isset(self::$_patchMethodRoutes[$name])
                ? self::$_patchMethodRoutes[$name]['route']
                : null;
            break;
        case Constants::METHOD_DELETE:
            $route = isset(self::$_deleteMethodRoutes[$name])
                ? self::$_deleteMethodRoutes[$name]['route']
                : null;
            break;
        default:
            $route = isset(self::$_otherRoutes[$name])
                ? self::$_otherRoutes[$name]['route']
                : null;
            break;
        }
        if ($route != null) {
            foreach ($data as $value) {
                $route .= "/" . $value;
            }
        }
        return $route;
    }

    /**
     * Sets not allowed method
     *
     * @param callable $callback method
     *
     * @return void
     */
    public static function setMethodNotAllowed(callable $callback)
    {
        self::$_methodNotAllowed = $callback;
    }

    /**
     * Sets path not found method
     *
     * @param callable $callback method
     *
     * @return void
     */
    public static function setPathNotFound(callable $callback)
    {
        self::$_pathNotFound = $callback;
    }

    /**
     * Sets on error method
     *
     * @param callable $callback method
     *
     * @return void
     */
    public static function setOnError(callable $callback)
    {
        self::$_onError = $callback;
    }

    /**
     * Runs the current route
     *
     * @param boolean $caseSensitive does the URL is case sensitive or not
     *
     * @return void
     */
    public static function run(bool $caseSensitive = false)
    {
        $parsedUrl = parse_url($_SERVER['REQUEST_URI']);
        $path = $parsedUrl['path'] ?? '/';
        $path = urldecode($path);
        $reqMethod = strtolower($_SERVER['REQUEST_METHOD']);
        switch ($reqMethod) {
        case Constants::METHOD_GET:
            self::handleRequest($path, self::$_getMethodRoutes, $caseSensitive);
            break;
        case Constants::METHOD_POST:
            self::handleRequest($path, self::$_postMethodRoutes, $caseSensitive);
            break;
        case Constants::METHOD_PUT:
            self::handleRequest($path, self::$_putMethodRoutes, $caseSensitive);
            break;
        case Constants::METHOD_PATCH:
            self::handleRequest($path, self::$_patchMethodRoutes, $caseSensitive);
            break;
        case Constants::METHOD_DELETE:
            self::handleRequest($path, self::$_deleteMethodRoutes, $caseSensitive);
            break;
        default:
            self::handleRequest($path, self::$_otherMethodRoutes, $caseSensitive);
            break;
        }
    }

    /**
     * Runs the current api route
     *
     * @param boolean $caseSensitive does the URL is case sensitive or not
     *
     * @return void
     */
    public static function runApi(bool $caseSensitive = false)
    {
        $parsedUrl = parse_url($_SERVER['REQUEST_URI']);
        $path = $parsedUrl['path'] ?? '/';
        $path = explode("/", ltrim(urldecode($path), "/"));
        $reqMethod = strtolower($_SERVER['REQUEST_METHOD']);
        $controllerName = "App\Controller\\" . ucfirst($path[1]) . "Controller";
        $controllerObj = new $controllerName();
        unset($path[0]);
        unset($path[1]);
        switch ($reqMethod) {
        case Constants::METHOD_GET:
            $controllerObj->get(...$path);
            break;
        case Constants::METHOD_POST:
            $controllerObj->create(...$path);
            break;
        case Constants::METHOD_PUT:
            $controllerObj->update(...$path);
            break;
        case Constants::METHOD_PATCH:
            $controllerObj->patch(...$path);
            break;
        case Constants::METHOD_DELETE:
            $controllerObj->delete(...$path);
            break;
        default:
            echo "Invalid Request";
            exit();
        }
    }

    /**
     * Handles the URL request
     *
     * @param string  $path          Requested URL path
     * @param array   $routes        Routes
     * @param boolean $caseSensitive Does the URL is case sensitive or not
     *
     * @return void
     */
    public static function handleRequest(
        string $path,
        array $routes,
        bool $caseSensitive = false
    ) {
        $pathMatch = false;
        $methodMatch = false;
        global $config;
        foreach ($routes as $route) {
            $routeUrl = '#^' . $route['route'] . '$#';

            !$caseSensitive and
                $routeUrl = $routeUrl . 'i';
            if (preg_match($routeUrl, $path, $matches)) {
                $pathMatch = true;
                $rule = $route['rule'];
                if ($rule != null) {
                    if ($rule($matches) != true) {
                        return;
                    }
                }
                array_shift($matches);
                $requestCtrl = $route['expression'] ?? $path;
                $requestCtrl = explode('/', trim($requestCtrl, "/"));
                $ctrl = $requestCtrl[0];
                $method = $requestCtrl[1] ?? '';
                $controllerName = "App\Controller\\" . ucfirst($ctrl) . "Controller";
                $controllerObj = new $controllerName();
                if (method_exists($controllerName, $method)) {
                    $controllerObj->$method(...$matches);
                    $methodMatch = true;
                }
                break;
            }
        }
        if (!$pathMatch) {
            if (self::$_pathNotFound) {
                self::$pathNotAllowed();
                return;
            } elseif (isset($config['error_ctrl'])) {
                $controllerName = "App\Controller\\" . $config['error_ctrl'];
                $file = $config['controller'] . "/" . $config['error_ctrl'].".php";
                if (file_exists($file)) {
                    if (method_exists($controllerName, 'pageNotFound')) {
                        (new $controllerName())->pageNotFound();
                        $methodMatch = true;
                        return;
                    }
                }
            }
            !headers_sent() and header('HTTP/1.1 404 Not Found');
            die('404 - The file not found');
        }
        if (!$methodMatch) {
            if (self::$_methodNotAllowed) {
                self::$_methodNotAllowed();
                return;
            } elseif (isset($config['error_ctrl'])) {
                $controllerName = "App\Controller\\" . $config['error_ctrl'];
                $file = $config['controller'] . "/" . $config['error_ctrl'] . ".php";
                if (file_exists($file)) {
                    if (method_exists($controllerName, 'invalidRequest')) {
                        (new $controllerName())->invalidRequest();
                        return;
                    }
                }
            }
            !headers_sent() and header('HTTP/1.1 400 Bad Request');
            die('404 - The method not allowed');
        }
    }

    /**
     * Calls when an error occured
     *
     * @param string|null $data Error data
     *
     * @return void
     */
    public static function error(?string $data = null)
    {
        global $config;
        if (self::$_onError) {
            ob_start();
            self::$_onError($data);
            $content = ob_get_clean();
            echo $content;
            exit();
        } elseif (isset($config['error_ctrl'])) {
            $controllerName = "App\Controller\\" . $config['error_ctrl'];
            $file = $config['controller'] . "/" . $config['error_ctrl'] . ".php";
            if (file_exists($file)) {
                if (method_exists($controllerName, 'serverError')) {
                    ob_start();
                    (new $controllerName())->serverError($data);
                    $content = ob_get_clean();
                    echo $content;
                    exit();
                }
            }
        }
        !headers_sent() and header('HTTP/1.1 500 Internal Server Error');
        die('500 - Server Error');
        exit();
    }

    /**
     * Performs Dispatch
     *
     * @param string $url URL
     *
     * @return void
     */
    public static function dispatch(string $url)
    {
        global $config;
        $url = ltrim($url, "/");
        $url = explode("/", $url);
        $controller = $url[0] . "Controller";
        $method = $url[1];
        if (file_exists($config['controller']) . "/" . $controller . ".php") {
            $controller = "App\Controller\\" . ucfirst($controller);
            if (method_exists($controller, $method)) {
                (new $controller())->$method();
                exit();
            }
        }
        Router::error();
    }
}

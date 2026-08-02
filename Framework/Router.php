<?php
namespace Framework;

use App\Controllers\ErrorController;
use Framework\Middleware\Authorize;

class Router {
    protected $routes = [];

    /**
     * Registers a method with uri and controller to routes array
     *
     * @param $method method
     * @param $uri uri
     * @param $action action; equals to "Controller@method" that we want to be executed
     *
     * @return void
     */
    protected function registerRoute($method, $uri, $action, $middlewares): void {
        list($controller, $controllerMethod) = explode("@", $action);

        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'controllerMethod' => $controllerMethod,
            'middlewares' => $middlewares,
        ];
    }

    /**
     * Add a GET route
     *
     * @param string $uri uri
     * @param string $action action; equals to "Controller@method" that we want to be executed
     *
     * @return void
     */
    public function get(string $uri, string $action, $middlewares = []): void {
        $this->registerRoute('GET', $uri, $action, $middlewares);
    }

    /**
     * Add a POST route
     *
     * @param string $uri uri
     * @param string $action action; equals to "Controller@method" that we want to be executed
     *
     * @return void
     */
    public function post(string $uri, string $action, $middlewares = []): void {
        $this->registerRoute('POST', $uri, $action, $middlewares);
    }

    /**
     * Add a PUT route
     *
     * @param string $uri uri
     * @param string $action action; equals to "Controller@method" that we want to be executed
     *
     * @return void
     */
    public function put(string $uri, string $action, $middlewares = []): void {
        $this->registerRoute('PUT', $uri, $action, $middlewares);
    }

    /**
     * Add a DELETE route
     *
     * @param string $uri uri
     * @param string $action action; equals to "Controller@method" that we want to be executed
     *
     * @return void
     */
    public function delete(string $uri, string $action, $middlewares = []): void {
        $this->registerRoute('DELETE', $uri, $action, $middlewares);
    }

    /**
     * Renders error view with code
     *
     * @param int $httpCode code
     *
     * @return void
     */

    /**
     * Method route
     *
     * @param string $uri uri
     *
     * @return void
     */
    public function route(string $uri): void {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if ($requestMethod == "POST" && isset($_POST["_method"])) {
            $requestMethod = strtoupper($_POST["_method"]);
        }

        $uriSegments = explode('/', trim($uri, '/'));


        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            $routeSegments = explode('/', trim($route['uri'], '/'));

            if (count($routeSegments) !== count($uriSegments)) {
                continue;
            }

            $params = [];
            $match = true;

            foreach ($routeSegments as $i => $routeSegment) { 
                $uriSegment = $uriSegments[$i];

                $segmentsAreEqual = $routeSegment === $uriSegment;
                
                $hasParameter = preg_match('/\{(.+?)\}/', $routeSegment, $matches);

                if (!$segmentsAreEqual && !$hasParameter) {
                    $match = false;
                    break;
                }
                if ($hasParameter) {
                    $params[$matches[1]] = $uriSegment;
                }
            }

            if ($match) {
                // Run middlewares
                foreach ($route["middlewares"] as $middleware) {
                    Authorize::handle($middleware);
                }

                // Extract controller and controller method
                $controller = 'App\\Controllers\\' . $route['controller'];
                $controllerMethod = $route['controllerMethod'];

                // Initiate controller class
                $controllerInstance = new $controller();
                $controllerInstance->$controllerMethod($params);
                return;
            }
        }

        ErrorController::notFound();

    }
}
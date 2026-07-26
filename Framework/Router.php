<?php


namespace Framework;

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
    protected function registerRoute($method, $uri, $action): void {
        list($controller, $controllerMethod) = explode("@", $action);

        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'controllerMethod' => $controllerMethod,
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
    public function get(string $uri, string $action): void {
        $this->registerRoute('GET', $uri, $action);
    }

    /**
     * Add a POST route
     *
     * @param string $uri uri
     * @param string $action action; equals to "Controller@method" that we want to be executed
     *
     * @return void
     */
    public function post(string $uri, string $action): void {
        $this->registerRoute('POST', $uri, $action);
    }

    /**
     * Add a PUT route
     *
     * @param string $uri uri
     * @param string $action action; equals to "Controller@method" that we want to be executed
     *
     * @return void
     */
    public function put(string $uri, string $action): void {
        $this->registerRoute('PUT', $uri, $action);
    }

    /**
     * Add a DELETE route
     *
     * @param string $uri uri
     * @param string $action action; equals to "Controller@method" that we want to be executed
     *
     * @return void
     */
    public function delete(string $uri, string $action): void {
        $this->registerRoute('DELETE', $uri, $action);
    }

    /**
     * Renders error view with code
     *
     * @param int $httpCode code
     *
     * @return void
     */
    public function error(int $httpCode = 404): void {
        http_response_code($httpCode);
        loadView("errors/{$httpCode}");
        exit();
    }

    /**
     * Method route
     *
     * @param string $uri uri
     * @param string $method uri
     *
     * @return void
     */
    public function route(string $uri, string $method): void {
        foreach ($this->routes as $route) {
            if ($uri === $route['uri'] && $method === $route['method']) {
                // Extract controller and controller method
                $controller = 'App\\Controllers\\' . $route['controller'];
                $controllerMethod = $route['controllerMethod'];
                // Initiate controller class
                $controllerInstance = new $controller();
                $controllerInstance->$controllerMethod();
                return;
            }
        }

        $this->error();

    }
}
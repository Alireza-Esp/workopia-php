<?php

class Router {
    protected $routes = [];

    /**
     * Registers a method with uri and controller to routes array
     *
     * @param $method method
     * @param $uri uri
     * @param $controller controller
     *
     * @return void
     */
    protected function registerRoute($method, $uri, $controller): void {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller
        ];
    }

    /**
     * Add a GET route
     *
     * @param string $uri uri
     * @param string $controller controller
     *
     * @return void
     */
    public function get(string $uri, string $controller): void {
        $this->registerRoute('GET', $uri, $controller);
    }

    /**
     * Add a POST route
     *
     * @param string $uri uri
     * @param string $controller controller
     *
     * @return void
     */
    public function post(string $uri, string $controller): void {
        $this->registerRoute('POST', $uri, $controller);
    }

    /**
     * Add a PUT route
     *
     * @param string $uri uri
     * @param string $controller controller
     *
     * @return void
     */
    public function put(string $uri, string $controller): void {
        $this->registerRoute('PUT', $uri, $controller);
    }

    /**
     * Add a DELETE route
     *
     * @param string $uri uri
     * @param string $controller controller
     *
     * @return void
     */
    public function delete(string $uri, string $controller): void {
        $this->registerRoute('DELETE', $uri, $controller);
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
                require basePath("App/" . $route['controller']);
                return;
            }
        }

        $this->error();

    }
}
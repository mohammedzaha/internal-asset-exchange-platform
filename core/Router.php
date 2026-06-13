<?php
class Router {
    private array $routes = [];

    public function add(string $method, string $path, array $action): void {
        $this->routes[] = compact('method','path','action');
    }

    public function dispatch(string $uri, string $method): void {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/');
        if ($uri === '') $uri = '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;

            $pattern = preg_replace('#\{id\}#', '(\d+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                [$controllerName, $actionName] = $route['action'];
                $controller = new $controllerName();
                call_user_func_array([$controller, $actionName], $matches);
                return;
            }
        }
        http_response_code(404);
        echo "404 Not Found";
    }
}
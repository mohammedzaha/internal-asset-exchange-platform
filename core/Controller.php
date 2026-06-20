<?php
abstract class Controller {
    protected function view(string $path, array $data = []): void {
        extract($data);
        require __DIR__ . "/../views/{$path}.php";
    }
    protected function redirect(string $url): void {
        header("Location: " . BASE_URL . $url);
        exit();
    }
}
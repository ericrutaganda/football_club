<?php
declare(strict_types=1);
class Controller {
    protected function view(string $name, array $data = []): void {
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/' . $name . '.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }
    protected function json(array $data, int $status = 200): never {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }
}

<?php
namespace Core;

abstract class Controller {
    /**
     * Render view với layout
     */
    protected function view(string $viewPath, array $data = [], ?string $layout = 'main'): void {
        extract($data);

        $viewFile = ROOT_PATH . '/Views/' . str_replace('.', '/', $viewPath) . '.php';

        if (!file_exists($viewFile)) {
            die("Không tìm thấy view file: " . htmlspecialchars($viewFile));
        }

        // Bắt đầu buffer để lấy content của view
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Nạp layout tương ứng
        if ($layout !== null) {
            $layoutFile = ROOT_PATH . '/Views/layout/' . $layout . '.php';
            if (file_exists($layoutFile)) {
                require $layoutFile;
            } else {
                echo $content;
            }
        } else {
            echo $content;
        }
    }

    /**
     * Khởi tạo Model
     */
    protected function model(string $modelName): object {
        $className = "\\Models\\" . $modelName;
        if (class_exists($className)) {
            return new $className();
        }
        die("Không tìm thấy model: " . htmlspecialchars($className));
    }

    /**
     * Xuất dữ liệu JSON
     */
    protected function json(mixed $data, int $statusCode = 200): void {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Chuyển hướng URL
     */
    protected function redirect(string $url): void {
        redirect($url);
    }
}

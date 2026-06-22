<?php
class HealthController {
    public function index(): void {
        header('Content-Type: application/json');
        try {
            $config = require __DIR__ . '/../../config/database.php';
            (new Database($config))->getConnection()->query('SELECT 1');
            echo json_encode(['status' => 'ok', 'database' => 'connected']);
            } catch (Exception $e) { 
            http_response_code(500); 
            // Thêm $e->getMessage() vào để nó in thẳng lý do chết ra màn hình
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]); 
        }
    }
}
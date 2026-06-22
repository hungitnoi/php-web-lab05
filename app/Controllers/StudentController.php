<?php
class StudentController {
    // Hàm hỗ trợ gọi nhanh StudentRepository
    private function repo(): StudentRepository {
        $config = require __DIR__ . '/../../config/database.php';
        return new StudentRepository((new Database($config))->getConnection());
    }

    // Hiển thị danh sách, phân trang và tìm kiếm
    public function index(): void {
        $q = trim($_GET['q'] ?? ''); 
        $page = max(1, (int)($_GET['page'] ?? 1)); 
        $perPage = 10;
        $sort = $_GET['sort'] ?? 'created_at'; 
        $direction = $_GET['direction'] ?? 'desc';
        
        $repo = $this->repo(); 
        $total = $repo->countAll($q);
        $totalPages = max(1, (int) ceil($total / $perPage));
        if ($page > $totalPages) $page = $totalPages;
        
        $students = $repo->getPaginated($q, $perPage, ($page - 1) * $perPage, $sort, $direction);
        view('students/index', compact('students', 'q', 'page', 'totalPages', 'sort', 'direction'));
    }

    // Hiển thị form thêm mới
    public function create(): void { 
        view('students/form', ['student' => [], 'errors' => [], 'action' => '/students/store']); 
    }

    // Nhận dữ liệu từ form thêm mới và lưu
    public function store(): void { 
        $this->save(); 
    }
    
    // Hiển thị form sửa (đổ dữ liệu cũ vào form)
    public function edit(): void {
        $student = $this->repo()->findById((int)$_GET['id']);
        if (!$student) { 
            http_response_code(404); 
            view('errors/404'); 
            return; 
        }
        view('students/form', ['student' => $student, 'errors' => [], 'action' => '/students/update?id='.$student['id']]);
    }

    // Nhận dữ liệu từ form sửa và cập nhật
    public function update(): void { 
        $this->save((int)$_GET['id']); 
    }

    // Nhận ID từ form ẩn và xóa học viên (Delete bằng POST để bảo mật)
    public function delete(): void {
        $this->repo()->delete((int)$_POST['id']);
        flash_set('success', 'Đã xóa học viên!'); 
        redirect('/students');
    }

    // Hàm dùng chung cho cả Store (thêm) và Update (sửa) để tái sử dụng code
    private function save(?int $id = null): void {
        $data = [
            'name' => trim($_POST['name'] ?? ''), 
            'email' => trim($_POST['email'] ?? ''), 
            'phone' => trim($_POST['phone'] ?? ''), 
            'status' => $_POST['status'] ?? 'new', 
            'note' => trim($_POST['note'] ?? '')
        ];
        
        // Validation (Bắt lỗi người dùng nhập sai)
        $errors = [];
        if (!$data['name']) $errors['name'] = 'Vui lòng nhập tên.';
        if (!$data['email'] || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ.';
        }

        // Nếu có lỗi, trả lại form nhập liệu kèm thông báo
        if ($errors) { 
            view('students/form', ['student' => $data, 'errors' => $errors, 'action' => $id ? "/students/update?id=$id" : "/students/store"]); 
            return; 
        }

        // Thử lưu vào Database
        try {
            $id ? $this->repo()->update($id, $data) : $this->repo()->create($data);
            flash_set('success', 'Lưu thành công!'); 
            redirect('/students');
        } catch (DuplicateRecordException $e) {
            // Bắt lỗi trùng Email từ Repository
            $errors['email'] = $e->getMessage();
            view('students/form', ['student' => $data, 'errors' => $errors, 'action' => $id ? "/students/update?id=$id" : "/students/store"]);
        }
    }
}
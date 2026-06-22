<?php
class EnrollmentController {
    private function repo(): EnrollmentRepository {
        $config = require __DIR__ . '/../../config/database.php';
        return new EnrollmentRepository((new Database($config))->getConnection());
    }

    public function index(): void {
        $q = trim($_GET['q'] ?? ''); $page = max(1, (int)($_GET['page'] ?? 1)); $perPage = 10;
        $sort = $_GET['sort'] ?? 'created_at'; $direction = $_GET['direction'] ?? 'desc';
        
        $repo = $this->repo(); $total = $repo->countAll($q);
        $totalPages = max(1, (int) ceil($total / $perPage));
        if ($page > $totalPages) $page = $totalPages;
        
        $enrollments = $repo->getPaginated($q, $perPage, ($page - 1) * $perPage, $sort, $direction);
        view('enrollments/index', compact('enrollments', 'q', 'page', 'totalPages', 'sort', 'direction'));
    }

    public function create(): void { view('enrollments/form', ['enrollment' => [], 'errors' => [], 'action' => '/enrollments/store']); }

    public function store(): void { $this->save(); }
    
    public function edit(): void {
        $enrollment = $this->repo()->findById((int)$_GET['id']);
        if (!$enrollment) { http_response_code(404); view('errors/404'); return; }
        view('enrollments/form', ['enrollment' => $enrollment, 'errors' => [], 'action' => '/enrollments/update?id='.$enrollment['id']]);
    }

    public function update(): void { $this->save((int)$_GET['id']); }

    public function delete(): void {
        $this->repo()->delete((int)$_POST['id']);
        flash_set('success', 'Đã xóa đơn đăng ký!'); redirect('/enrollments');
    }

    private function save(?int $id = null): void {
        $data = [
            'enrollment_code' => trim($_POST['enrollment_code'] ?? ''), 
            'student_email' => trim($_POST['student_email'] ?? ''), 
            'course_name' => trim($_POST['course_name'] ?? ''), 
            'total_fee' => (float)($_POST['total_fee'] ?? 0),
            'status' => $_POST['status'] ?? 'pending'
        ];
        
        $errors = [];
        if (!$data['enrollment_code']) $errors['enrollment_code'] = 'Vui lòng nhập mã đăng ký.';
        if (!$data['student_email'] || !filter_var($data['student_email'], FILTER_VALIDATE_EMAIL)) $errors['student_email'] = 'Email học viên không hợp lệ.';
        if (!$data['course_name']) $errors['course_name'] = 'Vui lòng nhập tên khóa học.';

        if ($errors) { view('enrollments/form', ['enrollment' => $data, 'errors' => $errors, 'action' => $id ? "/enrollments/update?id=$id" : "/enrollments/store"]); return; }

        try {
            $id ? $this->repo()->update($id, $data) : $this->repo()->create($data);
            flash_set('success', 'Lưu đơn thành công!'); redirect('/enrollments');
        } catch (DuplicateRecordException $e) {
            $errors['enrollment_code'] = $e->getMessage();
            view('enrollments/form', ['enrollment' => $data, 'errors' => $errors, 'action' => $id ? "/enrollments/update?id=$id" : "/enrollments/store"]);
        }
    }
}
<?php ob_start(); ?>
<h1><?= isset($enrollment['id']) ? 'Sửa Đơn' : 'Thêm Đơn Đăng Ký' ?></h1>

<form method="post" action="<?= e($action) ?>" class="card form-card">
    <label>Mã Đơn (Enrollment Code)</label>
    <input type="text" name="enrollment_code" value="<?= e($enrollment['enrollment_code'] ?? '') ?>">
    <p class="error"><?= e($errors['enrollment_code'] ?? '') ?></p>
    
    <label>Email Học viên (Đã có trong hệ thống)</label>
    <input type="email" name="student_email" value="<?= e($enrollment['student_email'] ?? '') ?>">
    <p class="error"><?= e($errors['student_email'] ?? '') ?></p>

    <label>Tên Khóa Học</label>
    <input type="text" name="course_name" value="<?= e($enrollment['course_name'] ?? '') ?>">
    <p class="error"><?= e($errors['course_name'] ?? '') ?></p>

    <label>Tổng Học Phí (VNĐ)</label>
    <input type="number" name="total_fee" value="<?= e($enrollment['total_fee'] ?? 0) ?>">

    <label>Trạng thái</label>
    <select name="status">
        <option value="pending" <?= ($enrollment['status'] ?? '') == 'pending' ? 'selected' : '' ?>>Chờ thanh toán</option>
        <option value="paid" <?= ($enrollment['status'] ?? '') == 'paid' ? 'selected' : '' ?>>Đã thanh toán</option>
        <option value="cancelled" <?= ($enrollment['status'] ?? '') == 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
    </select>
    
    <button class="btn primary" type="submit">Lưu</button> <a class="btn" href="/enrollments">Hủy</a>
</form>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php'; ?>
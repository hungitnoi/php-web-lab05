<?php ob_start(); ?>
<h1><?= isset($student['id']) ? 'Sửa học viên' : 'Thêm học viên' ?></h1>
<form method="post" action="<?= e($action) ?>" class="card form-card">
    <label>Tên</label><input type="text" name="name" value="<?= e($student['full_name'] ?? $student['name'] ?? '') ?>">
    <p class="error"><?= e($errors['name'] ?? '') ?></p>
    
    <label>Email</label><input type="email" name="email" value="<?= e($student['email'] ?? '') ?>">
    <p class="error"><?= e($errors['email'] ?? '') ?></p>

    <label>Phone</label><input type="text" name="phone" value="<?= e($student['phone'] ?? '') ?>">
    <label>Status</label>
    <select name="status">
        <option value="new" <?= ($student['status'] ?? '') == 'new' ? 'selected' : '' ?>>Mới</option>
        <option value="enrolled" <?= ($student['status'] ?? '') == 'enrolled' ? 'selected' : '' ?>>Đã đăng ký</option>
    </select>
    <label>Ghi chú</label><textarea name="note"><?= e($student['note'] ?? '') ?></textarea>
    <button class="btn primary" type="submit">Lưu</button> <a class="btn" href="/students">Hủy</a>
</form>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php'; ?>
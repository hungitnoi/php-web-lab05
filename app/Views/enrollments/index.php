<?php ob_start(); ?>
<h1>Quản lý Đơn đăng ký (Enrollments)</h1> 
<a class="btn primary" href="/enrollments/create">+ Thêm Đơn</a>

<form method="get" action="/enrollments" class="toolbar" style="margin-top:10px;">
    <input type="text" name="q" value="<?= e($q) ?>" placeholder="Tìm mã, email, tên khóa...">
    <button type="submit">Tìm</button>
</form>

<table>
    <tr>
        <th>ID</th><th><a href="/enrollments?<?= e(query_string(['sort'=>'enrollment_code'])) ?>">Mã Đơn</a></th>
        <th>Email Học Viên</th><th>Khóa Học</th><th>Học Phí</th><th>Trạng thái</th><th>Actions</th>
    </tr>
    <?php foreach ($enrollments as $e_item): ?>
    <tr>
        <td><?= e($e_item['id']) ?></td><td><span class="badge"><?= e($e_item['enrollment_code']) ?></span></td>
        <td><?= e($e_item['student_email']) ?></td><td><?= e($e_item['course_name']) ?></td>
        <td><?= number_format($e_item['total_fee']) ?> VNĐ</td><td><span class="badge"><?= e($e_item['status']) ?></span></td>
        <td>
            <a href="/enrollments/edit?id=<?= e($e_item['id']) ?>">Sửa</a>
            <form method="post" action="/enrollments/delete" class="inline" onsubmit="return confirm('Xóa đơn này?')">
                <input type="hidden" name="id" value="<?= e($e_item['id']) ?>"><button type="submit" class="link danger">Xóa</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php'; ?>
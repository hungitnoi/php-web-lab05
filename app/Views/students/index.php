<?php ob_start(); ?>
<h1>Danh sách học viên</h1> <a class="btn primary" href="/students/create">+ Thêm học viên</a>
<form method="get" action="/students" class="toolbar" style="margin-top:10px;">
    <input type="text" name="q" value="<?= e($q) ?>" placeholder="Tìm kiếm..."><button type="submit">Tìm</button>
</form>
<table>
    <tr><th>ID</th><th><a href="/students?<?= e(query_string(['sort'=>'full_name'])) ?>">Tên</a></th><th>Email</th><th>Trạng thái</th><th>Actions</th></tr>
    <?php foreach ($students as $s): ?>
    <tr>
        <td><?= e($s['id']) ?></td><td><?= e($s['full_name']) ?></td><td><?= e($s['email']) ?></td><td><span class="badge"><?= e($s['status']) ?></span></td>
        <td>
            <a href="/students/edit?id=<?= e($s['id']) ?>">Sửa</a>
            <form method="post" action="/students/delete" class="inline" onsubmit="return confirm('Xóa?')">
                <input type="hidden" name="id" value="<?= e($s['id']) ?>"><button type="submit" class="link danger">Xóa</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php'; ?>
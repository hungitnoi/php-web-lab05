<?php ob_start(); ?>
<h1>500 Lỗi máy chủ</h1>
<p>Hệ thống đang gặp sự cố, vui lòng thử lại sau.</p>
<?php 
$content = ob_get_clean(); 
require __DIR__ . '/../layout.php'; 
?>
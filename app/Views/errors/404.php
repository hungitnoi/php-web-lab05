<?php ob_start(); ?>
<h1>404 Không tìm thấy trang</h1>
<p>Trang bạn yêu cầu không tồn tại.</p>
<?php 
$content = ob_get_clean(); 
require __DIR__ . '/../layout.php'; 
?>
<?php ob_start(); ?>
<h1>Dashboard Course Enrollment</h1>
<p>Hệ thống quản lý học viên.</p>
<?php 
$content = ob_get_clean(); 
require 'layout.php'; 
?>
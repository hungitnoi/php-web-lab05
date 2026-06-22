<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Course App</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <nav class="navbar">
        <a href="/">Dashboard</a>
        <a href="/students">Students</a>
        <a href="/health">Health DB</a>
    </nav>
    <main class="container">
        <?php if ($msg = flash_get('success')): ?>
            <div class="alert success"><?= e($msg) ?></div>
        <?php endif; ?>
        
        <?= $content ?? '' ?>
    </main>
</body>
</html>
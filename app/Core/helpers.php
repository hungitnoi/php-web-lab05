<?php
function e(?string $value): string { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function redirect(string $path): void { header("Location: {$path}"); exit; }
function query_string(array $params = []): string { return http_build_query(array_merge($_GET, $params)); }
function flash_set(string $key, string $message): void { $_SESSION['flash'][$key] = $message; }
function flash_get(string $key): ?string { $message = $_SESSION['flash'][$key] ?? null; unset($_SESSION['flash'][$key]); return $message; }
function view(string $path, array $data = []): void { extract($data); require __DIR__ . '/../Views/' . $path . '.php'; }
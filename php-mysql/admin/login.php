<?php
require_once __DIR__ . '/../common.php';
if (isAdmin()) { header('Location: index.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $password = (string)($_POST['password'] ?? '');
    if (ADMIN_PASSWORD_HASH !== 'REPLACE_WITH_PASSWORD_HASH' && password_verify($password, ADMIN_PASSWORD_HASH)) {
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        header('Location: index.php'); exit;
    }
    $error = 'پاس ورڈ درست نہیں یا admin password hash ابھی config.php میں مقرر نہیں کیا گیا۔';
}
?><!doctype html><html lang="ur" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>انتظامیہ — <?= e(SITE_NAME) ?></title><link rel="stylesheet" href="../admin/admin.css"></head><body><main class="login"><div class="panel"><div class="brand">📚 <?= e(SITE_NAME) ?></div><h1>انتظامیہ میں داخلہ</h1><?php if($error): ?><div class="error"><?= e($error) ?></div><?php endif; ?><form method="post"><input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>"><label>پاس ورڈ</label><input type="password" name="password" required autofocus><button type="submit">داخل ہوں</button></form><a class="back" href="../index.php">← ویب سائٹ پر واپس جائیں</a></div></main></body></html>

<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

$allowedOrigin = 'https://mutalaa92.github.io';
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin === $allowedOrigin) {
    header('Access-Control-Allow-Origin: '.$allowedOrigin);
    header('Vary: Origin');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
}
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success'=>false,'message'=>'صرف POST درخواست قابل قبول ہے۔'], JSON_UNESCAPED_UNICODE);
    exit;
}

/*
 * IMPORTANT:
 * Replace the value below with a long private password.
 * Do NOT publish the real password in the GitHub repository.
 */
const UPLOAD_PASSWORD = 'CHANGE_THIS_TO_A_LONG_PRIVATE_PASSWORD';

function fail(string $message, int $status=400): never {
    http_response_code($status);
    echo json_encode(['success'=>false,'message'=>$message], JSON_UNESCAPED_UNICODE);
    exit;
}

$password = (string)($_POST['password'] ?? '');
if ($password === '' || !hash_equals(UPLOAD_PASSWORD, $password)) {
    fail('Upload Password درست نہیں ہے۔', 401);
}

if (!isset($_FILES['image']) || !is_array($_FILES['image'])) {
    fail('تصویر موصول نہیں ہوئی۔');
}

$file = $_FILES['image'];
if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    fail('تصویر اپ لوڈ نہیں ہوسکی۔');
}

if (!is_uploaded_file($file['tmp_name'])) {
    fail('غلط upload درخواست۔');
}

$maxBytes = 10 * 1024 * 1024;
if ((int)$file['size'] <= 0 || (int)$file['size'] > $maxBytes) {
    fail('تصویر خالی ہے یا 10MB سے بڑی ہے۔');
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($file['tmp_name']);
$allowed = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/gif'  => 'gif',
    'image/webp' => 'webp'
];

if (!isset($allowed[$mime])) {
    fail('صرف JPG, PNG, GIF اور WebP تصاویر قابل قبول ہیں۔');
}

if (@getimagesize($file['tmp_name']) === false) {
    fail('فائل درست تصویر نہیں ہے۔');
}

$uploadDir = dirname(__DIR__) . '/uploads/articles';
if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
    fail('Upload فولڈر بنایا نہیں جا سکا۔', 500);
}

$random = bin2hex(random_bytes(12));
$filename = date('Ymd-His') . '-' . $random . '.' . $allowed[$mime];
$target = $uploadDir . '/' . $filename;

if (!move_uploaded_file($file['tmp_name'], $target)) {
    fail('تصویر محفوظ نہیں ہوسکی۔', 500);
}

@chmod($target, 0644);

$url = 'https://mutalaa.cu.ma/uploads/articles/' . rawurlencode($filename);
echo json_encode([
    'success' => true,
    'url' => $url,
    'filename' => $filename
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

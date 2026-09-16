<?php
require_once __DIR__ . '/../common.php';
requireAdmin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
verifyCsrf();
$id = (int)($_POST['id'] ?? 0);
$title = trim((string)($_POST['title'] ?? ''));
$categoryId = (int)($_POST['category_id'] ?? 0) ?: null;
$excerptText = trim((string)($_POST['excerpt'] ?? ''));
$content = trim((string)($_POST['content'] ?? ''));
$featured = !empty($_POST['featured']) ? 1 : 0;
$published = !empty($_POST['published']) ? 1 : 0;
if ($title === '' || $content === '') { exit('عنوان اور مضمون کا متن ضروری ہے۔'); }
$slug = uniqueSlug($title, $id ?: null);
if ($id) {
    $stmt=db()->prepare('UPDATE articles SET category_id=?,title=?,slug=?,excerpt=?,content=?,featured=?,published=? WHERE id=?');
    $stmt->execute([$categoryId,$title,$slug,$excerptText,$content,$featured,$published,$id]);
} else {
    $stmt=db()->prepare('INSERT INTO articles (category_id,title,slug,excerpt,content,featured,published,published_at) VALUES (?,?,?,?,?,?,?,NOW())');
    $stmt->execute([$categoryId,$title,$slug,$excerptText,$content,$featured,$published]);
}
header('Location: index.php'); exit;

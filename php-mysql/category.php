<?php
require_once __DIR__ . '/common.php';
$slug = trim($_GET['slug'] ?? '');
$category = $slug !== '' ? categoryBySlug($slug) : null;
if ($slug !== '' && !$category) { http_response_code(404); }
if ($category) {
    $stmt = db()->prepare("SELECT a.*, c.name AS category_name, c.slug AS category_slug FROM articles a LEFT JOIN categories c ON c.id=a.category_id WHERE a.published=1 AND a.category_id=? ORDER BY a.published_at DESC");
    $stmt->execute([$category['id']]);
} else {
    $stmt = db()->query("SELECT a.*, c.name AS category_name, c.slug AS category_slug FROM articles a LEFT JOIN categories c ON c.id=a.category_id WHERE a.published=1 ORDER BY a.published_at DESC");
}
$articles = $stmt->fetchAll();
$cats = categories();
$title = $category['name'] ?? 'تمام مضامین';
?><!doctype html><html lang="ur" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?= e($title.' — '.SITE_NAME) ?></title><link rel="stylesheet" href="../style.css"></head><body>
<div class="top-bar"><div class="container top-bar-inner"><div class="bismillah">بسم اللہ الرحمن الرحیم</div><div class="top-links"><a href="index.php">مرکزی صفحہ</a><a href="admin/login.php">انتظامیہ</a></div></div></div>
<header class="site-header"><div class="container header-main"><a class="logo-area" href="index.php"><div class="logo-icon">📚</div><div class="logo-text"><h1><?= e(SITE_NAME) ?></h1><span><?= e(SITE_TAGLINE) ?></span></div></a><nav class="main-nav"><a href="index.php">🏠 مرکزی صفحہ</a><a href="category.php">📚 موضوعات</a><a href="index.php#latest">📝 تازہ مضامین</a></nav></div></header>
<main class="section"><div class="container"><div class="section-heading"><h2><?= e($title) ?></h2><a class="section-link" href="category.php">تمام مضامین</a></div><div class="category-grid" style="margin-bottom:35px;"><?php foreach($cats as $c): ?><a class="category-card" href="<?= e(categoryUrl($c)) ?>"><div class="category-icon">📚</div><h3><?= e($c['name']) ?></h3></a><?php endforeach; ?></div><div class="article-grid"><?php foreach($articles as $a): ?><article class="article-card"><div class="article-card-image">📖</div><div class="article-card-body"><div class="article-category"><?= e($a['category_name'] ?? 'مطالعہ') ?></div><h3><?= e($a['title']) ?></h3><p><?= e($a['excerpt'] ?: excerpt($a['content'])) ?></p><a href="<?= e(articleUrl($a)) ?>">مکمل مضمون پڑھیں ←</a></div></article><?php endforeach; ?></div><?php if(!$articles): ?><div class="empty"><h2>ابھی کوئی مضمون موجود نہیں</h2><p>اس موضوع کے تحت ابھی کوئی مضمون شائع نہیں کیا گیا۔</p></div><?php endif; ?></div></main>
<footer class="site-footer"><div class="container"><div class="copyright">© <?= date('Y') ?> <?= e(SITE_NAME) ?> — جملہ حقوق محفوظ ہیں۔</div></div></footer></body></html>

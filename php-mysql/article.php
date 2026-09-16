<?php
require_once __DIR__ . '/common.php';
$slug = trim($_GET['slug'] ?? '');
$stmt = db()->prepare("SELECT a.*, c.name AS category_name, c.slug AS category_slug FROM articles a LEFT JOIN categories c ON c.id=a.category_id WHERE a.slug=? AND a.published=1 LIMIT 1");
$stmt->execute([$slug]);
$article = $stmt->fetch();
if (!$article) { http_response_code(404); }
?><!doctype html>
<html lang="ur" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?= $article ? e($article['title'].' — '.SITE_NAME) : 'مضمون موجود نہیں' ?></title><link rel="stylesheet" href="../style.css"></head><body>
<div class="top-bar"><div class="container top-bar-inner"><div class="bismillah">بسم اللہ الرحمن الرحیم</div><div class="top-links"><a href="index.php">مرکزی صفحہ</a><a href="category.php">موضوعات</a></div></div></div>
<header class="site-header"><div class="container header-main"><a class="logo-area" href="index.php"><div class="logo-icon">📚</div><div class="logo-text"><h1><?= e(SITE_NAME) ?></h1><span><?= e(SITE_TAGLINE) ?></span></div></a><nav class="main-nav"><a href="index.php">🏠 مرکزی صفحہ</a><a href="category.php">📚 موضوعات</a><a href="index.php#latest">📝 تازہ مضامین</a></nav></div></header>
<section class="page-header"><div class="container"><div class="eyebrow">✦ علمی و تحقیقی مضمون ✦</div><h1><?= $article ? e($article['title']) : 'مضمون موجود نہیں' ?></h1></div></section>
<main class="section"><div class="container article-container">
<?php if (!$article): ?><div class="empty"><h2>مطلوبہ مضمون موجود نہیں</h2><p><a href="index.php">مرکزی صفحے پر واپس جائیں</a></p></div>
<?php else: ?><article class="article"><div class="post-meta"><?= e($article['category_name'] ?? 'مطالعہ') ?><?= $article['published_at'] ? ' • '.e(date('Y-m-d', strtotime($article['published_at']))) : '' ?></div><h1><?= e($article['title']) ?></h1><div class="article-body" style="text-align:justify;"><?= $article['content'] ?></div></article><?php endif; ?>
</div></main><footer class="site-footer"><div class="container"><div class="copyright">© <?= date('Y') ?> <?= e(SITE_NAME) ?> — جملہ حقوق محفوظ ہیں۔</div></div></footer></body></html>

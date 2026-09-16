<?php
require_once __DIR__ . '/common.php';
$pdo = db();
$cats = categories();
$featured = $pdo->query("SELECT a.*, c.name AS category_name, c.slug AS category_slug FROM articles a LEFT JOIN categories c ON c.id=a.category_id WHERE a.published=1 ORDER BY a.featured DESC, a.published_at DESC LIMIT 1")->fetch();
$latest = $pdo->query("SELECT a.*, c.name AS category_name, c.slug AS category_slug FROM articles a LEFT JOIN categories c ON c.id=a.category_id WHERE a.published=1 ORDER BY a.published_at DESC LIMIT 9")->fetchAll();
$q = trim($_GET['q'] ?? '');
$searchResults = [];
if ($q !== '') {
    $stmt = $pdo->prepare("SELECT a.*, c.name AS category_name, c.slug AS category_slug FROM articles a LEFT JOIN categories c ON c.id=a.category_id WHERE a.published=1 AND (a.title LIKE ? OR a.excerpt LIKE ? OR a.content LIKE ?) ORDER BY a.published_at DESC LIMIT 30");
    $like = '%' . $q . '%';
    $stmt->execute([$like, $like, $like]);
    $searchResults = $stmt->fetchAll();
}
?>
<!doctype html>
<html lang="ur" dir="rtl">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e(SITE_NAME) ?> — <?= e(SITE_TAGLINE) ?></title>
<link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="top-bar"><div class="container top-bar-inner"><div class="bismillah">بسم اللہ الرحمن الرحیم</div><div class="top-links"><a href="#about">تعارف</a><a href="#contact">رابطہ</a><a href="admin/login.php">انتظامیہ</a></div></div></div>
<header class="site-header"><div class="container header-main"><a class="logo-area" href="index.php"><div class="logo-icon">📚</div><div class="logo-text"><h1><?= e(SITE_NAME) ?></h1><span><?= e(SITE_TAGLINE) ?></span></div></a><nav class="main-nav"><a href="index.php">☰ 🏠 مرکزی صفحہ</a><a href="category.php">📚 موضوعات</a><a href="#latest">📝 تازہ مضامین</a><a href="category.php?slug=books">📖 کتب</a><a href="#about">ℹ️ تعارف</a></nav></div></header>
<section class="hero"><div class="container hero-content"><div class="eyebrow">✦ علمی و تحقیقی پلیٹ فارم ✦</div><h2>علم، تحقیق اور اسلامی آثار کا مستند اردو ذخیرہ</h2><p>مضامین، کتب، تاریخ، حدیث و آثار اور اسلامی علمی مباحث ایک منظم اور خوبصورت انداز میں۔</p><form class="search-box" method="get"><input name="q" value="<?= e($q) ?>" placeholder="مضمون، موضوع یا لفظ تلاش کریں…"><button type="submit">تلاش</button></form></div></section>
<?php if ($q !== ''): ?><section class="section"><div class="container"><div class="section-heading"><h2>تلاش کے نتائج</h2><a class="section-link" href="index.php">تمام مضامین</a></div><div class="article-grid"><?php foreach ($searchResults as $a): ?><article class="article-card"><div class="article-card-image">📖</div><div class="article-card-body"><div class="article-category"><?= e($a['category_name'] ?? 'مطالعہ') ?></div><h3><?= e($a['title']) ?></h3><p><?= e($a['excerpt'] ?: excerpt($a['content'])) ?></p><a href="<?= e(articleUrl($a)) ?>">مکمل مضمون پڑھیں ←</a></div></article><?php endforeach; ?><?php if (!$searchResults): ?><div class="empty"><h2>کوئی نتیجہ نہیں ملا</h2><p>دوسرے الفاظ کے ساتھ دوبارہ تلاش کریں۔</p></div><?php endif; ?></div></div></section><?php endif; ?>
<section class="section"><div class="container"><div class="section-heading"><h2>📚 موضوعات</h2><a class="section-link" href="category.php">تمام موضوعات دیکھیں</a></div><div class="category-grid"><?php foreach ($cats as $c): ?><a class="category-card" href="<?= e(categoryUrl($c)) ?>"><div class="category-icon">📚</div><h3><?= e($c['name']) ?></h3><p><?= e($c['description'] ?? '') ?></p></a><?php endforeach; ?></div></div></section>
<?php if ($featured): ?><section class="section featured"><div class="container"><div class="featured-box"><div class="featured-image">📜</div><div class="featured-content"><div class="eyebrow">✦ نمایاں مضمون ✦</div><h3><?= e($featured['title']) ?></h3><p><?= e($featured['excerpt'] ?: excerpt($featured['content'], 260)) ?></p><a class="btn btn-primary" href="<?= e(articleUrl($featured)) ?>">مضمون پڑھیں</a></div></div></div></section><?php endif; ?>
<section class="section" id="latest"><div class="container"><div class="section-heading"><h2>📝 تازہ مضامین</h2><a class="section-link" href="category.php">مزید مضامین</a></div><div class="article-grid"><?php foreach ($latest as $a): ?><article class="article-card"><div class="article-card-image">📖</div><div class="article-card-body"><div class="article-category"><?= e($a['category_name'] ?? 'مطالعہ') ?></div><h3><?= e($a['title']) ?></h3><p><?= e($a['excerpt'] ?: excerpt($a['content'])) ?></p><a href="<?= e(articleUrl($a)) ?>">مکمل مضمون پڑھیں ←</a></div></article><?php endforeach; ?></div></div></section>
<section class="section" id="about"><div class="container about-grid"><div class="about-text"><div class="eyebrow">✦ تعارف ✦</div><h2>مطالعہ</h2><p>یہ پلیٹ فارم اسلامی علمی مواد، تحقیق، تاریخ، حدیث و آثار اور منتخب کتب کو اردو قارئین کے لیے منظم انداز میں پیش کرنے کے لیے تیار کیا گیا ہے۔</p><p>PHP اور MySQL ورژن میں مضامین براہِ راست database سے آتے ہیں، جبکہ موجودہ بصری ڈیزائن برقرار رہتا ہے۔</p></div><div class="about-points"><div class="about-point"><span>✓</span><div><strong>منظم علمی ذخیرہ</strong><p>موضوعات اور مضامین الگ الگ منظم کیے جا سکتے ہیں۔</p></div></div><div class="about-point"><span>✓</span><div><strong>آسان انتظامیہ</strong><p>Admin panel سے مضمون شامل، ترمیم اور حذف کیا جا سکتا ہے۔</p></div></div></div></div></section>
<section class="section contact-section" id="contact"><div class="container"><div class="contact-box"><div><div class="eyebrow">✦ رابطہ ✦</div><h2>علمی تعاون اور رابطہ</h2><p>رابطے کی معلومات یہاں hosting کے مطابق شامل کی جا سکتی ہیں۔</p></div></div></div></section>
<footer class="site-footer"><div class="container"><div class="copyright">© <?= date('Y') ?> <?= e(SITE_NAME) ?> — جملہ حقوق محفوظ ہیں۔<br>کتاب و سنت</div></div></footer>
</body></html>

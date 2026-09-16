<?php
session_start();
require_once __DIR__ . '/config.php';

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}

function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function slugify(string $text): string {
    $text = trim($text);
    $text = preg_replace('/[^\p{L}\p{N}]+/u', '-', $text) ?? '';
    $text = trim($text, '-');
    return $text !== '' ? mb_strtolower($text, 'UTF-8') : 'article-' . time();
}

function uniqueSlug(string $title, ?int $ignoreId = null): string {
    $base = slugify($title);
    $slug = $base;
    $i = 2;
    $sql = 'SELECT id FROM articles WHERE slug = ?';
    while (true) {
        $stmt = db()->prepare($sql);
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        if (!$row || ($ignoreId !== null && (int)$row['id'] === $ignoreId)) return $slug;
        $slug = $base . '-' . $i++;
    }
}

function isAdmin(): bool {
    return !empty($_SESSION['admin_logged_in']);
}

function requireAdmin(): void {
    if (!isAdmin()) {
        header('Location: login.php');
        exit;
    }
}

function csrfToken(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}

function verifyCsrf(): void {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(419);
        exit('Invalid CSRF token');
    }
}

function categories(): array {
    return db()->query('SELECT * FROM categories ORDER BY name')->fetchAll();
}

function categoryBySlug(string $slug): ?array {
    $stmt = db()->prepare('SELECT * FROM categories WHERE slug = ? LIMIT 1');
    $stmt->execute([$slug]);
    return $stmt->fetch() ?: null;
}

function excerpt(string $content, int $length = 180): string {
    $plain = trim(preg_replace('/\s+/u', ' ', strip_tags($content)) ?? '');
    return mb_strlen($plain, 'UTF-8') > $length ? mb_substr($plain, 0, $length, 'UTF-8') . '…' : $plain;
}

function articleUrl(array $article): string {
    return 'article.php?slug=' . rawurlencode($article['slug']);
}

function categoryUrl(array $category): string {
    return 'category.php?slug=' . rawurlencode($category['slug']);
}

<?php
require_once __DIR__ . '/../common.php';
requireAdmin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
verifyCsrf();
$id=(int)($_POST['id'] ?? 0);
if($id){$s=db()->prepare('DELETE FROM articles WHERE id=?');$s->execute([$id]);}
header('Location: index.php'); exit;

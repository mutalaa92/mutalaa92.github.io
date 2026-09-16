<?php
// Run once on the PHP/MySQL server after importing database.sql and configuring config.php.
// It imports existing Markdown files from the repository's _posts folder when that folder
// is copied beside php-mysql as ../_posts. Delete this file after successful migration.
require_once __DIR__ . '/common.php';
if (PHP_SAPI !== 'cli') { http_response_code(403); exit('Run from PHP CLI only.'); }
$dir = realpath(__DIR__ . '/../_posts');
if (!$dir || !is_dir($dir)) exit("_posts folder not found.\n");
$pdo=db();
$catMap=[]; foreach(categories() as $c){$catMap[$c['name']]=$c['id'];}
$count=0;
foreach(glob($dir.'/*.md') as $file){
  $text=file_get_contents($file);
  if(!preg_match('/^---\s*([\s\S]*?)\s*---\s*([\s\S]*)$/',$text,$m)) continue;
  $data=[]; foreach(preg_split('/\R/',$m[1]) as $line){$i=strpos($line,':');if($i===false)continue;$k=trim(substr($line,0,$i));$v=trim(substr($line,$i+1));$v=trim($v," \t\"'");$data[$k]=$v;}
  $title=trim($data['title']??pathinfo($file,PATHINFO_FILENAME));
  $cat=$catMap[trim($data['category']??'')]??null;
  $content=trim($m[2]);
  $slug=uniqueSlug($title);
  $date=!empty($data['date'])?date('Y-m-d H:i:s',strtotime($data['date'])):date('Y-m-d H:i:s');
  $stmt=$pdo->prepare('INSERT INTO articles(category_id,title,slug,excerpt,content,featured,published,published_at) VALUES(?,?,?,?,?,?,?,?)');
  $stmt->execute([$cat,$title,$slug,excerpt($content),nl2br(e($content)),0,1,$date]);
  $count++;
  echo "Imported: {$title}\n";
}
echo "Done. Imported {$count} articles.\n";

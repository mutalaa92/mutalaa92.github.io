# مطالعہ — PHP + MySQL ورژن

یہ فولڈر موجودہ `مطالعہ` ڈیزائن کا PHP + MySQL ورژن ہے۔ موجودہ GitHub Pages والی static ویب سائٹ کو محفوظ رکھا گیا ہے؛ یہ ورژن الگ سے PHP/MySQL hosting پر چلانے کے لیے تیار کیا گیا ہے۔

## اہم بات

GitHub Pages PHP/MySQL نہیں چلاتا، اس لیے اس فولڈر کو GitHub Pages پر براہِ راست چلانے کے بجائے PHP 8.1+ اور MySQL/MariaDB والی hosting پر deploy کرنا ہوگا۔ GitHub Pages موجودہ static site کے لیے برقرار رہ سکتی ہے۔

## فولڈر

- `database.sql` — مکمل database schema اور ابتدائی categories
- `config.sample.php` — database configuration کی مثال
- `config.php` — hosting پر `config.sample.php` کی copy بنا کر اپنی credentials یہاں رکھیں
- `common.php` — database connection، helpers اور مشترکہ functions
- `index.php` — مرکزی صفحہ
- `article.php` — مکمل مضمون
- `category.php` — category کے مضامین
- `admin/login.php` — administrator login
- `admin/index.php` — articles dashboard
- `admin/save.php` — نیا/ترمیم شدہ مضمون محفوظ کرنا
- `admin/delete.php` — مضمون حذف کرنا
- `admin/logout.php` — logout
- `admin/admin.css` — admin panel styling

## Installation

1. Hosting پر PHP 8.1 یا اس سے جدید اور MySQL/MariaDB database بنائیں۔
2. `database.sql` کو phpMyAdmin میں import کریں۔
3. `config.sample.php` کو `config.php` کے نام سے copy کریں۔
4. `config.php` میں DB host، database name، username اور password درج کریں۔
5. `php-mysql/admin/index.php` کو پہلی بار کھولنے سے پہلے `common.php` میں `ADMIN_PASSWORD_HASH` کے لیے اپنی password کا hash رکھیں۔
6. Hash بنانے کے لیے hosting کے PHP CLI میں `php -r "echo password_hash('YOUR_PASSWORD', PASSWORD_DEFAULT), PHP_EOL;"` چلایا جا سکتا ہے۔
7. فولڈر کے اندر موجود PHP files کو hosting کے public web root میں رکھیں۔
8. پھر `/admin/login.php` سے login کریں۔

## Security

- `config.php` کو public repository میں نہ رکھیں۔
- database credentials کو GitHub پر commit نہ کریں۔
- HTTPS لازمی استعمال کریں۔
- admin password کے لیے مضبوط password رکھیں۔
- اگر hosting `.htaccess` support کرتی ہے تو sensitive files کو public access سے روکیں۔
- production میں database user کو صرف اسی database کی ضرورت کے مطابق permissions دیں۔

## موجودہ static site

اصل `index.html`, `article.html`, `category.html`, `_posts/` اور `style.css` کو اس conversion کے لیے تبدیل نہیں کیا گیا، تاکہ موجودہ GitHub Pages site محفوظ رہے۔ جب PHP/MySQL hosting مکمل طور پر تیار ہو جائے تو domain/hosting کے مطابق PHP version کو production site بنایا جا سکتا ہے۔

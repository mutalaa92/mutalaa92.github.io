CREATE DATABASE IF NOT EXISTS mutalaa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mutalaa;

CREATE TABLE IF NOT EXISTS categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(190) NOT NULL,
  slug VARCHAR(190) NOT NULL UNIQUE,
  description TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS articles (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED NULL,
  title VARCHAR(500) NOT NULL,
  slug VARCHAR(220) NOT NULL UNIQUE,
  excerpt TEXT NULL,
  content LONGTEXT NOT NULL,
  featured TINYINT(1) NOT NULL DEFAULT 0,
  published TINYINT(1) NOT NULL DEFAULT 1,
  published_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_articles_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL ON UPDATE CASCADE,
  INDEX idx_articles_category (category_id),
  INDEX idx_articles_published (published, published_at),
  FULLTEXT KEY ft_articles (title, excerpt, content)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO categories (name, slug, description) VALUES
('عقیدۂ توحید', 'tawheed', 'توحید، عقیدہ اور متعلقہ علمی مباحث'),
('تاریخِ اسلام', 'history', 'اسلامی تاریخ کے اہم واقعات اور شخصیات'),
('مشاجراتِ صحابہ', 'sahaba', 'صحابہ کرام رضی اللہ عنہم کے متعلق تاریخی و علمی مباحث'),
('فرق و مذاہب', 'sects', 'فرق و مذاہب کا تعارف اور علمی نقد'),
('حدیث و آثار', 'hadith', 'احادیث، آثار اور علومِ حدیث'),
('کتبِ اسلامیہ', 'books', 'اسلامی کتب اور ان کے تعارف و مباحث'),
('سیرت النبی ﷺ', 'seerah', 'سیرت رسول اللہ صلی اللہ علیہ وسلم'),
('سیرتِ صحابہ و تابعین', 'scholars', 'صحابہ و تابعین کی سیرت و آثار'),
('حالاتِ حاضرہ', 'current', 'معاصر حالات و واقعات کے علمی مضامین')
ON DUPLICATE KEY UPDATE name=VALUES(name);

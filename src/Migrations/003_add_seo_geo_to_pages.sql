-- Añadir columnas para metadatos SEO a la tabla de páginas estáticas
ALTER TABLE pages 
ADD COLUMN seo_title VARCHAR(255) DEFAULT NULL,
ADD COLUMN seo_description TEXT DEFAULT NULL,
ADD COLUMN seo_keywords TEXT DEFAULT NULL;

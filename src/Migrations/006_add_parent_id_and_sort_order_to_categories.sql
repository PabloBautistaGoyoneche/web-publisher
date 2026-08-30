-- Agregar parent_id y sort_order a la tabla de categorías para soporte de categorías anidadas
ALTER TABLE categories ADD COLUMN parent_id INT DEFAULT NULL;
ALTER TABLE categories ADD COLUMN sort_order INT DEFAULT 0;
ALTER TABLE categories ADD CONSTRAINT fk_categories_parent FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL;

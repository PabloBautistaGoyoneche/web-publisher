-- Migración 002: Crear la tabla system_logs para almacenar bitácoras de error
CREATE TABLE IF NOT EXISTS system_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    level VARCHAR(20) NOT NULL,
    message TEXT NOT NULL,
    file VARCHAR(255) DEFAULT NULL,
    line INT DEFAULT NULL,
    trace TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

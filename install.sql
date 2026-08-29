-- Tabla de usuarios (Autores y Administradores)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'author',
    display_name VARCHAR(100) NOT NULL,
    bio TEXT,
    avatar VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de categorías
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de posts (Entradas de blog)
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    excerpt TEXT,
    content LONGTEXT NOT NULL,
    featured_image VARCHAR(255) DEFAULT NULL,
    status VARCHAR(20) DEFAULT 'published',
    views_count INT DEFAULT 0,
    seo_title VARCHAR(255) DEFAULT NULL,
    seo_description TEXT DEFAULT NULL,
    seo_keywords TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Tabla de comentarios
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    author_name VARCHAR(100) NOT NULL,
    author_email VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    status VARCHAR(20) DEFAULT 'approved',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);

-- Tabla para páginas estáticas
CREATE TABLE IF NOT EXISTS pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content LONGTEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla para mensajes de contacto
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla para configuraciones del sistema
CREATE TABLE IF NOT EXISTS settings (
    `key` VARCHAR(100) PRIMARY KEY,
    `value` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de control de migraciones
CREATE TABLE IF NOT EXISTS migrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    migration VARCHAR(100) NOT NULL UNIQUE,
    executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla para almacenar los logs del sistema (errores y advertencias)
CREATE TABLE IF NOT EXISTS system_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    level VARCHAR(20) NOT NULL,
    message TEXT NOT NULL,
    file VARCHAR(255) DEFAULT NULL,
    line INT DEFAULT NULL,
    trace TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- --- DEFAULT DATA (Datos por Defecto de Estructura) ---

-- Insertar categorías por defecto
INSERT INTO categories (name, slug, description) VALUES
('Tecnología', 'tecnologia', 'Últimas novedades, tendencias y descubrimientos en el mundo tecnológico.'),
('Diseño', 'diseno', 'Artículos sobre UI/UX, tendencias visuales, branding y estética moderna.'),
('Desarrollo', 'desarrollo', 'Tutoriales, consejos y buenas prácticas para programación web y frontend.'),
('Estilo de Vida', 'estilo-de-vida', 'Consejos de productividad, bienestar y organización para creadores digitales.')
ON DUPLICATE KEY UPDATE id=id;

-- Insertar páginas estáticas por defecto (Obligatorias para Google AdSense)
INSERT INTO pages (title, slug, content) VALUES
('Política de Privacidad', 'politica-privacidad', '<h2>1. Información que recopilamos</h2><p>Recopilamos información para proporcionar mejores servicios a todos nuestros usuarios. Esto incluye información que nos proporcionas (como tu nombre y correo electrónico al comentar) e información que obtenemos de tu uso de nuestros servicios (como datos analíticos de navegación).</p><h2>2. Cookies y Tecnologías Similares</h2><p>Este sitio web utiliza cookies para personalizar el contenido, analizar nuestro tráfico y servir anuncios relevantes.</p><h3>Google AdSense y cookies de publicidad</h3><ul><li>Google, como proveedor asociado de terceros, utiliza cookies para publicar anuncios en este sitio web.</li><li>El uso de la cookie de publicidad por parte de Google y sus socios permite servir anuncios a nuestros usuarios en función de sus visitas a este sitio u otros sitios de Internet.</li><li>Los usuarios pueden optar por no recibir publicidad personalizada visitando la <a href=\"https://www.google.com/settings/ads\" target=\"_blank\">Configuración de anuncios de Google</a>.</li></ul><h2>3. Consentimiento y Seguridad</h2><p>Al navegar por nuestro sitio web, consientes el uso de cookies de acuerdo con los términos de esta política de privacidad. Protegemos tus datos utilizando conexiones cifradas SSL de extremo a extremo.</p>'),
('Términos y Condiciones', 'terminos-condiciones', '<h2>1. Aceptación de los Términos</h2><p>Al acceder y utilizar este sitio web, aceptas quedar vinculado por los siguientes términos y condiciones de uso. Si no estás de acuerdo con alguna parte de estos términos, te solicitamos que no accedas al sitio.</p><h2>2. Uso del Contenido</h2><p>Todo el contenido publicado en este blog (incluidos artículos, códigos de programación, tutoriales y recursos visuales) es propiedad intelectual de ModernBlog o se utiliza con las licencias correspondientes. Puedes compartir enlaces y citar fragmentos siempre que indiques y enlaces la fuente original. Queda prohibida la reproducción masiva o plagio comercial del contenido.</p><h2>3. Exención de Responsabilidad</h2><p>La información provista en este blog tiene fines exclusivamente educativos e informativos. No nos hacemos responsables de pérdidas o daños derivados del uso de la información técnica provista.</p>'),
('Sobre el Autor', 'sobre-el-autor', '<h2>Bienvenidos a nuestro Blog</h2><p>Este es un espacio técnico donde compartimos tutoriales prácticos de alta calidad, consejos de arquitectura frontend y backend, y novedades en el mundo de la tecnología.</p><h2>Nuestra Misión</h2><p>Buscamos simplificar conceptos complejos y promover el uso de tecnologías nativas y eficientes para la construcción de una web más accesible, rápida y estéticamente premium.</p>'),
('Contacto', 'contacto', '<p>¿Tienes alguna duda, propuesta de colaboración o comentario que quieras compartir directamente? Completa el siguiente formulario y nos pondremos en contacto contigo lo antes posible.</p>')
ON DUPLICATE KEY UPDATE id=id;

-- Insertar configuraciones básicas por defecto
INSERT INTO settings (`key`, `value`) VALUES
('theme_light_primary', '#0284C7'),
('theme_light_secondary', '#0369A1'),
('theme_dark_primary', '#38BDF8'),
('theme_dark_secondary', '#7DD3FC'),
('theme_light_bg', '#F0F9FF'),
('theme_dark_bg', '#082F49'),
('theme_light_header', '#006394'),
('theme_dark_header', '#0F172A'),
('theme_light_footer', '#006394'),
('theme_dark_footer', '#020617'),
('cta_ebook_title', 'Descarga nuestro eBook Gratuito'),
('cta_ebook_desc', 'Aprende los fundamentos del desarrollo web moderno con nuestra guía completa.'),
('cta_ebook_button', 'Descargar eBook'),
('cta_ebook_link', '#'),
('github_owner', ''),
('github_repo', ''),
('github_branch', 'main'),
('github_token', ''),
('current_commit', 'initial')
ON DUPLICATE KEY UPDATE `key`=`key`;

-- --- DEMO SEEDS DATA ---

-- Insertar posts de prueba
INSERT INTO posts (id, user_id, category_id, title, slug, excerpt, content, featured_image, views_count, created_at) VALUES
(1, 1, 3, 'El futuro del desarrollo web en 2026: CSS, IA y Reactividad', 'el-futuro-del-desarrollo-web-en-2026', 'Exploramos cómo las nuevas tecnologías de maquetación CSS, la integración de la IA y el rendimiento moderno están transformando el diseño de sitios web.', '<p>El desarrollo web está experimentando una revolución silenciosa pero acelerada. En este 2026, la intersección entre la inteligencia artificial generativa de código, el nuevo estándar de CSS y la reactividad sin frameworks pesados está definiendo la forma en que construimos para internet.</p>\r\n\r\n<h2>La Inteligencia Artificial como Copiloto</h2>\r\n<p>Ya no escribimos todo el código repetitivo desde cero. Las herramientas de asistencia en el editor nos permiten enfocarnos en la arquitectura, la UX y la optimización. Sin embargo, esto requiere que los desarrolladores tengan bases más sólidas para auditar y estructurar correctamente lo que los modelos de lenguaje proponen.</p>\r\n\r\n<h2>CSS Nativo: Adiós a los Preprocesadores</h2>\r\n<p>Con la adopción completa de variables CSS anidadas de forma nativa, contenedores de consultas (container queries) y colores manipulables dinámicamente con funciones como <code>color-mix()</code>, las herramientas externas de compilación están pasando a segundo plano. Escribir CSS puro vuelve a ser placentero, rápido y extremadamente eficiente para el navegador.</p>\r\n\r\n<blockquote>\r\n  \"La mejor optimización es aquella que se logra utilizando las capacidades nativas del navegador al máximo.\"\r\n</blockquote>\r\n\r\n<h2>Rendimiento y Carga Instantánea</h2>\r\n<p>Los usuarios no esperan más de 1.5 segundos a que una página sea interactiva. Frameworks súper rápidos y el retorno al procesamiento en servidor (SSR) e híbridos están ganando terreno. Mantener un DOM limpio, optimizar imágenes dinámicamente y minimizar el JavaScript bloqueante es ahora obligatorio.</p>', 'post_web_future.jpg', 145, '2026-08-10 10:00:00'),
(2, 1, 2, 'Diseñando interfaces premium: El poder del Glassmorphism y la tipografía', 'disenando-interfaces-premium-glassmorphism', 'Una guía práctica para elevar la calidad visual de tus aplicaciones web usando efectos de desenfoque de fondo y fuentes estilizadas.', '<p>El diseño de interfaces ha evolucionado de la rigidez del flat design a experiencias tridimensionales y táctiles digitales. Una de las técnicas más efectivas para denotar calidad premium hoy en día es el Glassmorphism combinado con una jerarquía tipográfica impecable.</p>\r\n\r\n<h2>¿Qué es el Glassmorphism y por qué funciona?</h2>\r\n<p>El Glassmorphism emula superficies de vidrio esmerilado que permiten ver sutilmente los colores y formas que se encuentran detrás. Funciona mediante tres pilares clave:</p>\r\n<ul>\r\n  <li><strong>Transparencia y Desenfoque:</strong> Uso de fondos con canal alfa (RGBA) y la propiedad <code>backdrop-filter: blur()</code>.</li>\r\n  <li><strong>Borde brillante:</strong> Un borde delgado y semitransparente que simula el grosor del cristal.</li>\r\n  <li><strong>Sombra sutil:</strong> Para dar una sensación de elevación en el espacio de la interfaz.</li>\r\n</ul>\r\n\r\n<h2>El Rol de la Tipografía</h2>\r\n<p>Una tipografía moderna y de alta legibilidad, como <strong>Outfit</strong> o <strong>Inter</strong>, complementa este efecto perfectamente. La clave es el contraste: títulos grandes con un peso de fuente alto y un espaciado entre letras optimizado, contrastados con descripciones de menor tamaño y mayor ligereza visual.</p>\r\n\r\n<p>Al aplicar estos conceptos de manera equilibrada, cualquier aplicación web común puede pasar a sentirse como una herramienta premium e interactiva.</p>', 'post_glassmorphism.jpg', 320, '2026-08-12 14:30:00'),
(3, 1, 1, 'Cómo optimizar tu flujo de trabajo con Inteligencia Artificial', 'como-optimizar-flujo-trabajo-con-ia', 'Descubre las herramientas y metodologías clave para automatizar tareas repetitivas y potenciar tu productividad diaria.', '<p>La Inteligencia Artificial ya no es una promesa futurista, es una realidad integrada en nuestro flujo de trabajo. Optimizar tus tareas cotidianas usando IA puede ahorrarte hasta un 40% de tiempo en tareas administrativas y de redacción.</p>\r\n\r\n<h2>Identifica los Cuellos de Botella</h2>\r\n<p>El primer paso no es elegir la herramienta, sino definir qué procesos consumen más tiempo de forma innecesaria: responder correos repetitivos, transcribir reuniones, resumir documentos largos o buscar errores en bases de datos.</p>\r\n\r\n<h2>Herramientas Imprescindibles</h2>\r\n<p>Existen asistentes específicos para cada disciplina:</p>\r\n<ul>\r\n  <li><strong>Redacción y Comunicación:</strong> Herramientas basadas en LLMs para redactar borradores iniciales o pulir el tono de tus mensajes.</li>\r\n  <li><strong>Desarrollo:</strong> Modelos integrados al editor para sugerencias de funciones o refactorización.</li>\r\n  <li><strong>Automatización:</strong> Plataformas sin código que conectan APIs para transferir información automáticamente usando disparadores inteligentes.</li>\r\n</ul>\r\n\r\n<p>Recuerda que la IA debe ser un asistente, no un reemplazo de tu criterio analítico o tu creatividad personal.</p>', 'post_ai_productivity.jpg', 98, '2026-08-14 09:15:00')
ON DUPLICATE KEY UPDATE id=id;

-- Insertar comentarios de prueba
INSERT INTO comments (post_id, author_name, author_email, content, status) VALUES
(1, 'Carlos Mendoza', 'carlos@mail.com', 'Excelente artículo. Realmente el CSS nativo ha mejorado tanto que ya casi no uso Sass en mis proyectos actuales.', 'approved'),
(1, 'Laura Gomez', 'laura.dev@mail.com', 'Me interesa mucho el tema de la reactividad nativa. ¿Podrías escribir un tutorial de eso próximamente?', 'approved'),
(2, 'Roberto Diaz', 'roberto@diseno.com', 'El Glassmorphism bien aplicado se ve espectacular. Lo importante es no abusar de él para no sobrecargar la vista del usuario.', 'approved')
ON DUPLICATE KEY UPDATE id=id;

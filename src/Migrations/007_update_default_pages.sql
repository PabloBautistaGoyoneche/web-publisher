-- Actualizar contenidos de las páginas estáticas por defecto a su versión optimizada en producción
UPDATE pages SET 
    title = 'Política de Privacidad',
    content = '<h2>1. Información que recopilamos</h2><p>Recopilamos información para proporcionar mejores servicios a todos nuestros usuarios. Esto incluye información que nos proporcionas (como tu nombre y correo electrónico al comentar) e información que obtenemos de tu uso de nuestros servicios (como datos analíticos de navegación).</p><h2>2. Cookies y Tecnologías Similares</h2><p>Este sitio web utiliza cookies para personalizar el contenido, analizar nuestro tráfico y servir anuncios relevantes.</p><h3>Google AdSense y cookies de publicidad</h3><p><br></p><h2>3. Consentimiento y Seguridad</h2><p>Al navegar por nuestro sitio web, consientes el uso de cookies de acuerdo con los términos de esta política de privacidad. Protegemos tus datos utilizando conexiones cifradas SSL de extremo a extremo.</p>',
    seo_description = '1. Información que recopilamos Recopilamos información para proporcionar mejores servicios a todos nuestros usuarios. Esto incluye información que nos p...'
WHERE slug = 'politica-privacidad';

UPDATE pages SET 
    title = 'Términos y Condiciones',
    content = '<h2>1. Aceptación de los Términos</h2><p>Al acceder y utilizar este sitio web, aceptas quedar vinculado por los siguientes términos y condiciones de uso. Si no estás de acuerdo con alguna parte de estos términos, te solicitamos que no accedas al sitio.</p><h2>2. Uso del Contenido</h2><p>Todo el contenido publicado en este blog (incluidos artículos, códigos de programación, tutoriales y recursos visuales) es propiedad intelectual de ModernBlog o se utiliza con las licencias correspondientes. Puedes compartir enlaces y citar fragmentos siempre que indiques y enlaces la fuente original. Queda prohibida la reproducción masiva o plagio comercial del contenido.</p><h2>3. Exención de Responsabilidad</h2><p>La información provista en este blog tiene fines exclusivamente educativos e informativos. No nos hacemos responsables de pérdidas o daños derivados del uso de la información técnica provista.</p>',
    seo_description = '1. Aceptación de los Términos Al acceder y utilizar este sitio web, aceptas quedar vinculado por los siguientes términos y condiciones de uso. Si no est...'
WHERE slug = 'terminos-condiciones';

UPDATE pages SET 
    title = 'Sobre el Autor',
    content = '<h2>Bienvenidos a ModernBlog</h2><p>ModernBlog es un espacio tecnico fundado y administrado por <strong>Alex Morgan y Equipo</strong>. Nuestro proposito es ofrecer a la comunidad de desarrolladores y disenadores tutoriales practicos de alta calidad, consejos de arquitectura frontend y backend, y novedades en el mundo de la tecnologia.</p><h2>Nuestra Mision</h2><p>Buscamos simplificar conceptos complejos y promover el uso de tecnologias nativas y eficientes para la construccion de una web mas accesible, rapida y esteticamente premium.</p><p>Si deseas colaborar o compartir tus articulos, ponte en contacto con nosotros.</p>',
    seo_description = 'Bienvenidos a ModernBlog ModernBlog es un espacio tecnico fundado y administrado por Alex Morgan y Equipo. Nuestro proposito es ofrecer a la comunidad d...'
WHERE slug = 'sobre-el-autor';

UPDATE pages SET 
    title = 'Contacto',
    content = '<p>¿Tienes alguna duda, propuesta de colaboración o comentario que quieras compartir directamente? Completa el siguiente formulario y nos pondremos en contacto contigo lo antes posible.</p>',
    seo_description = '¿Tienes alguna duda, propuesta de colaboración o comentario que quieras compartir directamente? Completa el siguiente formulario y nos pondremos en cont...'
WHERE slug = 'contacto';

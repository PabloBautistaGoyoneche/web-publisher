# Modern Web Publisher (ModernBlog) 🚀

ModernBlog es una plataforma web autohospedada de alto rendimiento diseñada para la publicación y administración de blogs. Cuenta con una arquitectura ligera basada en PHP nativo, estilos modernos responsivos con Tailwind CSS, y un sistema modular autónomo similar a WordPress.

---

## 🛠️ Requisitos del Sistema
- **Servidor Web:** Apache (con modulo `mod_rewrite` habilitado) o Nginx.
- **PHP:** Versión 8.0 o superior (extensiones requeridas: `pdo_mysql`, `zip` (opcional, con fallback), `curl`).
- **Base de Datos:** MySQL 5.7+ o MariaDB 10.3+.

---

## 📦 Guía de Comandos (Desarrollo y Compilación)

Para el ciclo de desarrollo local y distribución, cuentas con los siguientes comandos configurados:

1. **Ejecutar Servidor Local de Pruebas:**
   Inicia el servidor incorporado de PHP apuntando a la carpeta de archivos públicos:
   ```bash
   php -S localhost:8000 -t public
   ```

2. **Compilar CSS de Tailwind (Producción):**
   Genera y minifica los archivos de estilo a partir de `input.css`:
   ```bash
   npm run build:css
   ```

3. **Monitorear Cambios en CSS (Desarrollo):**
   Mantiene el compilador en escucha activa para regenerar estilos en caliente:
   ```bash
   npm run watch:css
   ```

4. **Compilar Archivo de Distribución (ZIP limpio):**
   Compila toda la aplicación en un archivo ZIP listo para instalar, excluyendo dependencias de desarrollo (`.git`, `node_modules`, archivos locales de credenciales y archivos fuente de Tailwind):
   ```bash
   npm run package
   ```
   *(Nota: Si no tienes habilitada la extensión `zip` de PHP localmente, el comando activará un **mecanismo de respaldo** automático utilizando el comando `tar` del sistema operativo).*

---

## 🚀 Guía de Instalación Paso a Paso

El sistema implementa un asistente de instalación automatizado en 4 sencillos pasos:

1. **Subir y Descomprimir:**
   Compila el ZIP con `npm run package` y descomprímelo directamente en el directorio raíz de tu servidor (ej. la carpeta `public_html` en cPanel). Gracias al archivo `.htaccess` raíz, todo el tráfico se redirigirá transparentemente sin necesidad de configurar subcarpetas.

2. **Acceso al Instalador:**
   Abre tu navegador y entra a la dirección de tu sitio (ej. `http://tudominio.com` o `http://localhost:8000`). El sistema detectará que no está instalado y te redirigirá a `install.php` de forma automática.

3. **Paso 1: Conexión de Base de Datos:**
   Ingresa los datos de acceso de tu base de datos:
   - **Host:** Por lo general `localhost`.
   - **Nombre de la base de datos**
   - **Usuario de la base de datos**
   - **Contraseña**
   *(Nota en cPanel: Si tu usuario no tiene permisos de crear bases de datos directamente, crea la base de datos manualmente en cPanel, asígnale el usuario y el instalador se conectará a ella directamente de forma robusta).*

4. **Paso 2: Datos de Identidad y Administrador:**
   Escribe los detalles iniciales de tu sitio y la cuenta administrativa principal:
   - **Título del sitio**
   - **Nombre del autor**
   - **Usuario administrador**
   - **Correo electrónico y Contraseña**
   - **Opción de Datos Semilla:** Deja marcada la casilla para cargar artículos y comentarios de prueba que te permitan ver el diseño interactivo de inmediato.

5. **Paso 3 y Finalización:**
   Haz clic en **Completar Instalación**. El sistema creará la base de datos (si no existía), ejecutará la estructura de tablas, creará tu usuario administrador e inicializará el archivo de configuración `config/database.php` de forma automática.

---

## 🔄 Sistema de Auto-Actualizaciones y Migraciones SQL

ModernBlog está preparado para integrarse directamente con GitHub, permitiéndote aplicar actualizaciones y subir nuevas bases de datos / módulos de forma inalámbrica en producción.

### 1. Vincular el Repositorio de GitHub
1. Accede al panel administrativo en `/admin/login`.
2. Dirígete a **Identidad del Sitio** (en la sección de ajustes).
3. En la sección **Configuración de GitHub**, ingresa:
   - **Usuario o Organización** en GitHub.
   - **Nombre del Repositorio**.
   - **Rama de Producción** (ej. `main` o `master`).
   - **Token de Acceso Personal (PAT):** Llénalo únicamente si tu repositorio es privado en GitHub.
4. Presiona **Guardar Identidad**.

### 2. Detección y Aplicación de Actualizaciones
- **Búsqueda automática:** El panel de administración realiza una consulta en segundo plano del último commit de la rama configurada en GitHub.
- **Notificación:** Si hay un nuevo commit disponible, aparecerá un banner informativo destacado en el Dashboard indicando que existe una actualización.
- **Ejecución:** Al presionar **Actualizar Ahora**, la plataforma:
  1. Descargará de forma segura el ZIP del repositorio.
  2. Reemplazará los archivos de código fuente recursivamente (omitiendo configuraciones personales como `config/database.php` y carpetas de archivos de usuario como `public/uploads/`).
  3. Escaneará el directorio `src/Migrations/` buscando archivos `.sql` nuevos.
  4. Ejecutará automáticamente los scripts SQL pendientes en tu base de datos y los registrará en la tabla `migrations`.
  5. Refrescará la plataforma y guardará el commit SHA actualizado.

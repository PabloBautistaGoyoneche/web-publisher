<?php

// Iniciar almacenamiento en búfer para reescribir URLs sobre la marcha a amigables
ob_start('rewriteUrls');

function rewriteUrls(string $html): string {
    // 1. /?route=post&slug=xxx -> /xxx, /?route=category&slug=xxx -> /xxx, /?route=page&slug=xxx -> /xxx
    $html = preg_replace('#/\?route=(post|category|page)&amp;slug=([a-zA-Z0-9/_-]+)#', '/$2', $html);
    $html = preg_replace('#/\?route=(post|category|page)&slug=([a-zA-Z0-9/_-]+)#', '/$2', $html);

    // 2. Rutas con parámetros adicionales (ej: admin/posts/duplicate&id=12)
    $html = preg_replace('#/\?route=([a-zA-Z0-9/_-]+)&amp;([a-zA-Z0-9_=\$\{\}-]+)#', '/$1?$2', $html);
    $html = preg_replace('#/\?route=([a-zA-Z0-9/_-]+)&([a-zA-Z0-9_=\$\{\}-]+)/#', '/$1?$2', $html); // Ajuste para slash final en parámetros JS si existiera
    $html = preg_replace('#/\?route=([a-zA-Z0-9/_-]+)&([a-zA-Z0-9_=\$\{\}-]+)#', '/$1?$2', $html);

    // 3. Rutas simples sin parámetros /?route=xxx  ->  /xxx
    $html = preg_replace('#/\?route=([a-zA-Z0-9/_-]+)#', '/$1', $html);
    
    return $html;
}

// PSR-4 Autoloader Autogestionado
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = dirname(__DIR__) . '/src/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

use App\Controllers\BlogController;
use App\Controllers\AdminController;

// Redirección 301 de URLs antiguas con query parameters a URLs amigables planas (solo para el frontend público)
if (isset($_GET['route']) && !isset($_GET['api']) && !isset($_GET['ajax']) && strpos($_GET['route'], 'admin/posts/get') === false) {
    $r = $_GET['route'];
    $s = $_GET['slug'] ?? '';
    
    if (($r === 'post' || $r === 'category' || $r === 'page') && !empty($s)) {
        header("Location: /$s", true, 301);
        exit;
    } elseif ($r === 'search') {
        $q = $_GET['q'] ?? '';
        header("Location: /search" . (!empty($q) ? "?q=" . urlencode($q) : ""), true, 301);
        exit;
    }
}

// Enrutador sencillo con soporte de URLs amigables
$route = $_GET['route'] ?? '';

if (empty($route)) {
    // Si estamos en el servidor de desarrollo de PHP, servir archivos estáticos directamente
    if (php_sapi_name() === 'cli-server') {
        $filePath = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if (file_exists($filePath) && !is_dir($filePath)) {
            return false;
        }
    }

    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uriPath = trim($requestUri, '/');
    $segments = explode('/', $uriPath);

    if (empty($uriPath)) {
        $route = 'home';
    } elseif ($uriPath === 'favicon.ico') {
        http_response_code(204);
        exit;
    } elseif ($uriPath === 'sitemap.xml') {
        ob_end_clean(); // Descartar y cerrar el búfer de reescritura HTML
        header("Content-Type: application/xml; charset=utf-8");
        
        $host = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // 1. Home
        echo '<url>';
        echo '<loc>' . $host . '/</loc>';
        echo '<changefreq>daily</changefreq>';
        echo '<priority>1.0</priority>';
        echo '</url>';
        
        // 2. Posts (Publicados)
        $db = \App\Database::getConnection();
        $postsStmt = $db->query("SELECT slug, created_at FROM posts WHERE status = 'published' ORDER BY created_at DESC");
        while ($row = $postsStmt->fetch()) {
            echo '<url>';
            echo '<loc>' . $host . '/' . htmlspecialchars($row['slug']) . '</loc>';
            echo '<lastmod>' . date('Y-m-d', strtotime($row['created_at'])) . '</lastmod>';
            echo '<changefreq>weekly</changefreq>';
            echo '<priority>0.8</priority>';
            echo '</url>';
        }
        
        // 3. Pages
        $pagesStmt = $db->query("SELECT slug, updated_at FROM pages ORDER BY title ASC");
        while ($row = $pagesStmt->fetch()) {
            echo '<url>';
            echo '<loc>' . $host . '/' . htmlspecialchars($row['slug']) . '</loc>';
            echo '<lastmod>' . date('Y-m-d', strtotime($row['updated_at'])) . '</lastmod>';
            echo '<changefreq>monthly</changefreq>';
            echo '<priority>0.6</priority>';
            echo '</url>';
        }
        
        // 4. Categories
        $catsStmt = $db->query("SELECT slug FROM categories ORDER BY name ASC");
        while ($row = $catsStmt->fetch()) {
            echo '<url>';
            echo '<loc>' . $host . '/' . htmlspecialchars($row['slug']) . '</loc>';
            echo '<changefreq>weekly</changefreq>';
            echo '<priority>0.5</priority>';
            echo '</url>';
        }
        
        echo '</urlset>';
        exit;
    } elseif (count($segments) >= 2 && $segments[0] === 'post') {
        $route = 'post';
        $_GET['slug'] = $segments[1];
    } elseif (count($segments) >= 2 && $segments[0] === 'category') {
        $route = 'category';
        $_GET['slug'] = $segments[1];
    } elseif (count($segments) >= 2 && $segments[0] === 'page') {
        $route = 'page';
        $_GET['slug'] = $segments[1];
    } elseif (count($segments) >= 1 && $segments[0] === 'search') {
        $route = 'search';
    } elseif (count($segments) === 1) {
        $slug = $segments[0];
        $adminRoutes = [
            'admin/login',
            'admin/logout',
            'admin/dashboard',
            'admin/settings',
            'admin/posts',
            'admin/posts/create',
            'admin/posts/edit',
            'admin/posts/get',
            'admin/posts/delete',
            'admin/posts/duplicate',
            'admin/categories',
            'admin/categories/delete',
            'admin/categories/reorder',
            'admin/comments',
            'admin/comments/approve',
            'admin/comments/delete',
            'admin/pages',
            'admin/pages/create',
            'admin/pages/edit',
            'admin/pages/delete',
            'admin/messages',
            'admin/messages/delete'
        ];
        if (in_array($slug, $adminRoutes)) {
            $route = $slug;
        } else {
            // Buscar en cascada (1. Páginas, 2. Categorías, 3. Posts)
            if (\App\Models\Page::findBySlug($slug) !== null) {
                $route = 'page';
                $_GET['slug'] = $slug;
            } elseif (\App\Models\Category::findBySlug($slug) !== null) {
                $route = 'category';
                $_GET['slug'] = $slug;
            } elseif (\App\Models\Post::findBySlug($slug) !== null) {
                $route = 'post';
                $_GET['slug'] = $slug;
            } else {
                $route = 'home';
            }
        }
    } else {
        $adminRoutes = [
            'admin/login',
            'admin/logout',
            'admin/dashboard',
            'admin/settings',
            'admin/posts',
            'admin/posts/create',
            'admin/posts/edit',
            'admin/posts/get',
            'admin/posts/delete',
            'admin/posts/duplicate',
            'admin/categories',
            'admin/categories/delete',
            'admin/categories/reorder',
            'admin/comments',
            'admin/comments/approve',
            'admin/comments/delete',
            'admin/pages',
            'admin/pages/create',
            'admin/pages/edit',
            'admin/pages/delete',
            'admin/messages',
            'admin/messages/delete'
        ];
        if (in_array($uriPath, $adminRoutes)) {
            $route = $uriPath;
        } else {
            $route = 'home';
        }
    }
}

$blog = new BlogController();
$admin = new AdminController();

try {
    switch ($route) {
        // Rutas Públicas
        case 'post':
            $slug = $_GET['slug'] ?? '';
            if (empty($slug)) {
                $blog->home();
            } else {
                $blog->post($slug);
            }
            break;
            
        case 'category':
            $slug = $_GET['slug'] ?? '';
            if (empty($slug)) {
                $blog->home();
            } else {
                $blog->archive($slug);
            }
            break;
            
        case 'search':
            $blog->search();
            break;
            
        case 'page':
            $slug = $_GET['slug'] ?? '';
            if (empty($slug)) {
                $blog->home();
            } else {
                $blog->page($slug);
            }
            break;
            
        // Rutas Administrativas (Panel)
        case 'admin/login':
            $admin->login();
            break;
            
        case 'admin/logout':
            $admin->logout();
            break;
            
        case 'admin/dashboard':
            $admin->dashboard();
            break;
            
        case 'admin/settings':
            $admin->settings();
            break;
            
        case 'admin/posts':
            $admin->posts();
            break;
            
        case 'admin/posts/create':
            header("Location: /?route=admin/posts#create");
            exit;
            
        case 'admin/posts/edit':
            $id = (int)($_GET['id'] ?? 0);
            header("Location: /?route=admin/posts#edit-$id");
            exit;
            
        case 'admin/posts/get':
            $admin->getPostJson();
            break;
            
        case 'admin/posts/delete':
            $admin->deletePost();
            break;
            
        case 'admin/posts/duplicate':
            $admin->duplicatePost();
            break;
            
        case 'admin/categories':
            $admin->categories();
            break;
            
        case 'admin/categories/delete':
            $admin->deleteCategory();
            break;
            
        case 'admin/categories/reorder':
            $admin->reorderCategories();
            break;
            
        case 'admin/comments':
            $admin->comments();
            break;
            
        case 'admin/comments/approve':
            $admin->approveComment();
            break;
            
        case 'admin/comments/delete':
            $admin->deleteComment();
            break;
            
        case 'admin/pages':
            $admin->pages();
            break;
            
        case 'admin/pages/create':
            $admin->createPage();
            break;
            
        case 'admin/pages/edit':
            $admin->editPage();
            break;
            
        case 'admin/pages/delete':
            $admin->deletePage();
            break;
            
        case 'admin/messages':
            $admin->messages();
            break;
            
        case 'admin/messages/delete':
            $admin->deleteMessage();
            break;
            
        case 'home':
        default:
            $blog->home();
            break;
    }
} catch (\Exception $e) {
    // Capturar cualquier error de base de datos o conexión y mostrar un mensaje limpio
    header("HTTP/1.1 500 Internal Server Error");
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error de Configuración - Modern Blog</title>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600&display=swap" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body { font-family: 'Outfit', sans-serif; }
        </style>
    </head>
    <body class="bg-slate-50 flex items-center justify-center min-h-screen text-slate-800 p-4">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 border border-slate-100">
            <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mb-6">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h1 class="text-2xl font-bold mb-2">Error de Conexión</h1>
            <p class="text-slate-600 mb-6">No se pudo establecer conexión con la base de datos o la configuración es errónea.</p>
            
            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 text-xs font-mono text-slate-500 overflow-x-auto mb-6">
                <?php echo htmlspecialchars($e->getMessage()); ?>
            </div>
            
            <div class="text-sm text-slate-500">
                <span class="font-semibold">Sugerencia:</span> Asegúrate de que tu servidor MySQL esté encendido y que el archivo de base de datos en <code class="bg-slate-100 px-1 py-0.5 rounded text-red-500">database.sql</code> haya sido importado en tu sistema.
            </div>
        </div>
    </body>
    </html>
    <?php
}

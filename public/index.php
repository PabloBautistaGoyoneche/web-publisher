<?php

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

// Enrutador sencillo
$route = $_GET['route'] ?? 'home';
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

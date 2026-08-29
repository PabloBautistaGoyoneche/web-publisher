<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$adminName = $_SESSION['admin_name'] ?? 'Administrador';
$adminUser = $_SESSION['admin_user'] ?? 'admin';
$currentRoute = $_GET['route'] ?? 'admin/dashboard';

// Cargar variables de identidad del sitio
$siteName = \App\Models\Setting::get('site_name', 'ModernBlog');
$themeLight = \App\Models\Setting::get('theme_light_primary', '#0284C7');
$themeLightSec = \App\Models\Setting::get('theme_light_secondary', '#0369A1');
$themeDark = \App\Models\Setting::get('theme_dark_primary', '#38BDF8');
$themeDarkSec = \App\Models\Setting::get('theme_dark_secondary', '#7DD3FC');

$themeLightBg = \App\Models\Setting::get('theme_light_bg', '#F0F9FF');
$themeDarkBg = \App\Models\Setting::get('theme_dark_bg', '#082F49');

$themeLightHeader = \App\Models\Setting::get('theme_light_header', '#006394');
$themeDarkHeader = \App\Models\Setting::get('theme_dark_header', '#0F172A');

$themeLightFooter = \App\Models\Setting::get('theme_light_footer', '#006394');
$themeDarkFooter = \App\Models\Setting::get('theme_dark_footer', '#020617');
?>
<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php 
        $pageTitle = isset($title) ? $title : 'Admin Panel'; 
        echo htmlspecialchars(str_ireplace(['Modern Blog', 'ModernBlog'], $siteName, $pageTitle)); 
    ?></title>
    <!-- Google Fonts & Tailwind -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo \App\Helpers::asset('css/styles.css'); ?>">
    
    <!-- Quill Editor (WYSIWYG) CDN -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    
    <!-- Prevent favicon requests & clear cached/default favicons -->
    <link rel="icon" href="data:,">
    
    <!-- Estilos de Identidad del Sitio Dinámicos -->
    <style>
        body {
            font-family: 'Outfit', 'Inter', sans-serif;
        }
        :root {
            /* Colores de Acento Principal */
            --brand-50: <?php echo \App\Helpers::adjustBrightnessRgb($themeLight, 0.95); ?>;
            --brand-100: <?php echo \App\Helpers::adjustBrightnessRgb($themeLight, 0.9); ?>;
            --brand-200: <?php echo \App\Helpers::adjustBrightnessRgb($themeLight, 0.75); ?>;
            --brand-300: <?php echo \App\Helpers::adjustBrightnessRgb($themeLight, 0.55); ?>;
            --brand-400: <?php echo \App\Helpers::adjustBrightnessRgb($themeLight, 0.35); ?>;
            --brand-500: <?php echo \App\Helpers::adjustBrightnessRgb($themeLight, 0.15); ?>;
            --brand-600: <?php echo \App\Helpers::hexToRgbValues($themeLight); ?>;
            --brand-700: <?php echo \App\Helpers::adjustBrightnessRgb($themeLight, -0.15); ?>;
            --brand-800: <?php echo \App\Helpers::adjustBrightnessRgb($themeLight, -0.3); ?>;
            --brand-900: <?php echo \App\Helpers::adjustBrightnessRgb($themeLight, -0.45); ?>;
            --brand-950: <?php echo \App\Helpers::adjustBrightnessRgb($themeLight, -0.6); ?>;

            /* Colores Secundarios (Degradado) */
            --secondary-50: <?php echo \App\Helpers::adjustBrightnessRgb($themeLightSec, 0.95); ?>;
            --secondary-100: <?php echo \App\Helpers::adjustBrightnessRgb($themeLightSec, 0.9); ?>;
            --secondary-200: <?php echo \App\Helpers::adjustBrightnessRgb($themeLightSec, 0.75); ?>;
            --secondary-300: <?php echo \App\Helpers::adjustBrightnessRgb($themeLightSec, 0.55); ?>;
            --secondary-400: <?php echo \App\Helpers::adjustBrightnessRgb($themeLightSec, 0.35); ?>;
            --secondary-500: <?php echo \App\Helpers::adjustBrightnessRgb($themeLightSec, 0.15); ?>;
            --secondary-600: <?php echo \App\Helpers::hexToRgbValues($themeLightSec); ?>;
            --secondary-700: <?php echo \App\Helpers::adjustBrightnessRgb($themeLightSec, -0.15); ?>;
            --secondary-800: <?php echo \App\Helpers::adjustBrightnessRgb($themeLightSec, -0.3); ?>;
            --secondary-900: <?php echo \App\Helpers::adjustBrightnessRgb($themeLightSec, -0.45); ?>;
            --secondary-950: <?php echo \App\Helpers::adjustBrightnessRgb($themeLightSec, -0.6); ?>;

            /* Fondos del Sitio */
            --sitebg: <?php echo \App\Helpers::hexToRgbValues($themeLightBg); ?>;
            --siteheader: <?php echo \App\Helpers::hexToRgbValues($themeLightHeader); ?>;
            --sitefooter: <?php echo \App\Helpers::hexToRgbValues($themeLightFooter); ?>;
        }
        .dark {
            /* Colores de Acento Principal */
            --brand-50: <?php echo \App\Helpers::adjustBrightnessRgb($themeDark, 0.95); ?>;
            --brand-100: <?php echo \App\Helpers::adjustBrightnessRgb($themeDark, 0.9); ?>;
            --brand-200: <?php echo \App\Helpers::adjustBrightnessRgb($themeDark, 0.75); ?>;
            --brand-300: <?php echo \App\Helpers::adjustBrightnessRgb($themeDark, 0.55); ?>;
            --brand-400: <?php echo \App\Helpers::hexToRgbValues($themeDark); ?>;
            --brand-500: <?php echo \App\Helpers::adjustBrightnessRgb($themeDark, -0.15); ?>;
            --brand-600: <?php echo \App\Helpers::adjustBrightnessRgb($themeDark, -0.3); ?>;
            --brand-700: <?php echo \App\Helpers::adjustBrightnessRgb($themeDark, -0.45); ?>;
            --brand-800: <?php echo \App\Helpers::adjustBrightnessRgb($themeDark, -0.6); ?>;
            --brand-900: <?php echo \App\Helpers::adjustBrightnessRgb($themeDark, -0.75); ?>;
            --brand-950: <?php echo \App\Helpers::adjustBrightnessRgb($themeDark, -0.85); ?>;

            /* Colores Secundarios (Degradado) */
            --secondary-50: <?php echo \App\Helpers::adjustBrightnessRgb($themeDarkSec, 0.95); ?>;
            --secondary-100: <?php echo \App\Helpers::adjustBrightnessRgb($themeDarkSec, 0.9); ?>;
            --secondary-200: <?php echo \App\Helpers::adjustBrightnessRgb($themeDarkSec, 0.75); ?>;
            --secondary-300: <?php echo \App\Helpers::adjustBrightnessRgb($themeDarkSec, 0.55); ?>;
            --secondary-400: <?php echo \App\Helpers::hexToRgbValues($themeDarkSec); ?>;
            --secondary-500: <?php echo \App\Helpers::adjustBrightnessRgb($themeDarkSec, -0.15); ?>;
            --secondary-600: <?php echo \App\Helpers::adjustBrightnessRgb($themeDarkSec, -0.3); ?>;
            --secondary-700: <?php echo \App\Helpers::adjustBrightnessRgb($themeDarkSec, -0.45); ?>;
            --secondary-800: <?php echo \App\Helpers::adjustBrightnessRgb($themeDarkSec, -0.6); ?>;
            --secondary-900: <?php echo \App\Helpers::adjustBrightnessRgb($themeDarkSec, -0.75); ?>;
            --secondary-950: <?php echo \App\Helpers::adjustBrightnessRgb($themeDarkSec, -0.85); ?>;

            /* Fondos del Sitio */
            --sitebg: <?php echo \App\Helpers::hexToRgbValues($themeDarkBg); ?>;
            --siteheader: <?php echo \App\Helpers::hexToRgbValues($themeDarkHeader); ?>;
            --sitefooter: <?php echo \App\Helpers::hexToRgbValues($themeDarkFooter); ?>;
        }
        @keyframes swing {
            0%, 100% { transform: rotate(0); }
            20% { transform: rotate(12deg); }
            40% { transform: rotate(-8deg); }
            60% { transform: rotate(4deg); }
            80% { transform: rotate(-4deg); }
        }
        .animate-swing {
            animation: swing 2s ease infinite;
            transform-origin: top center;
        }
        .sidebar-item-active {
            border-left: 4px solid <?php echo $themeLight; ?> !important;
            background-color: rgba(30, 41, 59, 0.8) !important;
            color: #ffffff !important;
            font-weight: 700 !important;
        }
        .sidebar-item-inactive {
            border-left: 4px solid transparent !important;
            color: #94a3b8 !important;
        }
    </style>
</head>
<body class="bg-sitebg text-slate-800 dark:text-slate-100 h-full flex flex-col md:flex-row overflow-hidden transition-colors duration-300">

    <!-- Sidebar Lateral de Administración -->
    <aside class="w-full md:w-64 bg-slate-900 text-slate-300 flex-shrink-0 flex flex-col z-20 border-r border-slate-800">
        <!-- Logo y Marca -->
        <div class="h-20 flex items-center px-6 border-b border-slate-800 gap-3">
            <span class="font-extrabold text-lg tracking-tight text-white">
                <?php 
                if (preg_match('/^(.*)(Blog)$/i', $siteName, $matches)) {
                    echo htmlspecialchars($matches[1]) . '<span class="text-brand-400">Admin</span>';
                } else {
                    echo htmlspecialchars($siteName);
                }
                ?>
            </span>
        </div>

        <!-- Menú de Navegación -->
        <nav class="flex-grow py-6 px-4 space-y-1.5 overflow-y-auto">
            
            <!-- Dashboard -->
            <a href="/?route=admin/dashboard" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all <?php echo $currentRoute === 'admin/dashboard' ? 'sidebar-item-active' : 'sidebar-item-inactive hover:bg-slate-800/40 hover:text-white'; ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path></svg>
                Dashboard
            </a>

            <!-- Entradas -->
            <a href="/?route=admin/posts" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all <?php echo strpos($currentRoute, 'admin/posts') === 0 ? 'sidebar-item-active' : 'sidebar-item-inactive hover:bg-slate-800/40 hover:text-white'; ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                Entradas
            </a>

            <!-- Categorías -->
            <a href="/?route=admin/categories" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all <?php echo $currentRoute === 'admin/categories' ? 'sidebar-item-active' : 'sidebar-item-inactive hover:bg-slate-800/40 hover:text-white'; ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Categorías
            </a>

            <!-- Comentarios -->
            <a href="/?route=admin/comments" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all <?php echo $currentRoute === 'admin/comments' ? 'sidebar-item-active' : 'sidebar-item-inactive hover:bg-slate-800/40 hover:text-white'; ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                Comentarios
            </a>

            <!-- Páginas -->
            <a href="/?route=admin/pages" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all <?php echo strpos($currentRoute, 'admin/pages') === 0 ? 'sidebar-item-active' : 'sidebar-item-inactive hover:bg-slate-800/40 hover:text-white'; ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Páginas
            </a>

            <!-- Mensajes -->
            <?php $msgCount = \App\Models\Message::count(); ?>
            <a href="/?route=admin/messages" class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-semibold transition-all <?php echo $currentRoute === 'admin/messages' ? 'sidebar-item-active' : 'sidebar-item-inactive hover:bg-slate-800/40 hover:text-white'; ?>">
                <div class="flex items-center gap-3.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <span>Mensajes</span>
                </div>
                <?php if ($msgCount > 0): ?>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold <?php echo $currentRoute === 'admin/messages' ? 'bg-white text-brand-650' : 'bg-brand-600 text-white'; ?>">
                        <?php echo $msgCount; ?>
                    </span>
                <?php endif; ?>
            </a>

            <!-- CTA eBook -->
            <a href="/?route=admin/cta-ebook" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all <?php echo $currentRoute === 'admin/cta-ebook' ? 'sidebar-item-active' : 'sidebar-item-inactive hover:bg-slate-800/40 hover:text-white'; ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                CTA eBook
            </a>

            <!-- Identidad del Sitio -->
            <a href="/?route=admin/settings" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all <?php echo $currentRoute === 'admin/settings' ? 'sidebar-item-active' : 'sidebar-item-inactive hover:bg-slate-800/40 hover:text-white'; ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-3"></path></svg>
                Identidad del Sitio
            </a>

            <!-- Logs de Errores -->
            <?php 
            $logCountDb = 0;
            try {
                if (file_exists(dirname(dirname(dirname(__DIR__))) . '/config/database.php')) {
                    $dbCount = \App\Database::getConnection();
                    $logCountDb = (int)$dbCount->query("SELECT COUNT(*) FROM system_logs")->fetchColumn();
                }
            } catch (\Throwable $e) {}
            
            $hasUpdate = isset($_SESSION['github_update_available']) && $_SESSION['github_update_available'];
            ?>
            <a href="/?route=admin/logs" class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-semibold transition-all <?php echo $currentRoute === 'admin/logs' ? 'sidebar-item-active' : 'sidebar-item-inactive hover:bg-slate-800/40 hover:text-white'; ?>">
                <div class="flex items-center gap-3.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span>Bitácora de Errores</span>
                </div>
                <?php if ($logCountDb > 0): ?>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold <?php echo $currentRoute === 'admin/logs' ? 'bg-white text-brand-650' : 'bg-red-500/20 text-red-500'; ?>">
                        <?php echo $logCountDb; ?>
                    </span>
                <?php endif; ?>
            </a>

            <!-- Actualización del Sistema -->
            <a href="/?route=admin/update" class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-semibold transition-all <?php echo $currentRoute === 'admin/update' ? 'sidebar-item-active' : 'sidebar-item-inactive hover:bg-slate-800/40 hover:text-white'; ?>">
                <div class="flex items-center gap-3.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.914 13.784A8 8 0 1119.5 8.5M21 5v4h-4"></path></svg>
                    <span>Actualización del Sistema</span>
                </div>
                <?php if ($hasUpdate): ?>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold <?php echo $currentRoute === 'admin/update' ? 'bg-white text-brand-650' : 'bg-amber-500/20 text-amber-500'; ?>">
                        NUEVO
                    </span>
                <?php endif; ?>
            </a>

        </nav>

        <!-- Acciones del pie de barra lateral -->
        <div class="p-4 border-t border-slate-800 space-y-1.5 flex-shrink-0">
            <!-- Ver Sitio -->
            <a href="/" target="_blank" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                Ver Sitio Público
            </a>

            <!-- Salir -->
            <a href="/?route=admin/logout" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold text-red-400 hover:text-white hover:bg-red-950/40 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Cerrar Sesión
            </a>
        </div>
    </aside>

    <!-- Contenido Principal -->
    <div class="flex-grow flex flex-col overflow-hidden h-full">
        <!-- Topbar Horizontal -->
        <header class="h-20 bg-siteheader border-b border-slate-200/60 dark:border-slate-800/80 px-6 sm:px-8 flex items-center justify-between flex-shrink-0 transition-colors duration-300">
            
            <!-- Título de Sección -->
            <h2 class="text-xl font-bold tracking-tight text-white">
                Panel de Administración
            </h2>

            <!-- Perfil del Administrador -->
            <div class="flex items-center gap-3.5">
                <!-- Campana de Notificación de Actualizaciones -->
                <?php 
                $hasUpdate = isset($_SESSION['github_update_available']) && $_SESSION['github_update_available'];
                ?>
                <a href="/?route=admin/update" class="relative p-2.5 rounded-xl hover:bg-white/10 text-white/70 hover:text-white transition-all mr-2 group" title="<?php echo $hasUpdate ? 'Actualización Disponible' : 'Sistema al día'; ?>">
                    <svg class="w-5 h-5 <?php echo $hasUpdate ? 'animate-swing' : ''; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <?php if ($hasUpdate): ?>
                        <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 border-2 border-slate-900 rounded-full animate-ping"></span>
                        <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 border-2 border-slate-900 rounded-full"></span>
                    <?php endif; ?>
                </a>

                <?php if (isset($headerActions)): ?>
                    <div class="mr-4">
                        <?php echo $headerActions; ?>
                    </div>
                <?php endif; ?>
                <div class="flex flex-col text-right hidden sm:flex">
                    <span class="text-sm font-bold text-white"><?php echo htmlspecialchars($adminName); ?></span>
                    <span class="text-xs text-white/65 font-medium">@<?php echo htmlspecialchars($adminUser); ?></span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 to-secondary-600 flex items-center justify-center text-white font-extrabold shadow-md shadow-brand-500/10 uppercase">
                    <?php echo substr($adminName, 0, 1); ?>
                </div>
            </div>
        </header>

        <!-- Contenedor del Cuerpo (Scrollable) -->
        <main class="flex-grow overflow-y-auto p-6 sm:p-8 bg-sitebg transition-colors duration-300">

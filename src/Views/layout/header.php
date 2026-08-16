<?php
use App\Helpers;
?>
<!DOCTYPE html>
<html lang="es" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <?php
    $siteName = \App\Models\Setting::get('site_name', 'ModernBlog');
    $themeLight = \App\Models\Setting::get('theme_light_primary', '#7c3aed');
    $themeLightSec = \App\Models\Setting::get('theme_light_secondary', '#4f46e5');
    $themeDark = \App\Models\Setting::get('theme_dark_primary', '#a78bfa');
    $themeDarkSec = \App\Models\Setting::get('theme_dark_secondary', '#6366f1');
    
    $themeLightBg = \App\Models\Setting::get('theme_light_bg', '#f8fafc');
    $themeDarkBg = \App\Models\Setting::get('theme_dark_bg', '#020617');
    
    $themeLightHeader = \App\Models\Setting::get('theme_light_header', '#ffffff');
    $themeDarkHeader = \App\Models\Setting::get('theme_dark_header', '#020617');
    
    $themeLightFooter = \App\Models\Setting::get('theme_light_footer', '#ffffff');
    $themeDarkFooter = \App\Models\Setting::get('theme_dark_footer', '#0f172a');
    
    // Reemplazar nombre del sitio en el título de la página
    $pageTitle = isset($title) ? $title : $siteName;
    $pageTitle = str_ireplace(['Modern Blog', 'ModernBlog'], $siteName, $pageTitle);
    ?>
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    
    <!-- Tailwind Styles (Compilado) -->
    <link rel="stylesheet" href="<?php echo Helpers::asset('css/styles.css'); ?>">

    <!-- Estilos de Identidad del Sitio Dinámicos -->
    <style>
        :root {
            /* Colores de Acento Principal */
            --brand-50: <?php echo Helpers::adjustBrightnessRgb($themeLight, 0.95); ?>;
            --brand-100: <?php echo Helpers::adjustBrightnessRgb($themeLight, 0.9); ?>;
            --brand-200: <?php echo Helpers::adjustBrightnessRgb($themeLight, 0.75); ?>;
            --brand-300: <?php echo Helpers::adjustBrightnessRgb($themeLight, 0.55); ?>;
            --brand-400: <?php echo Helpers::adjustBrightnessRgb($themeLight, 0.35); ?>;
            --brand-500: <?php echo Helpers::adjustBrightnessRgb($themeLight, 0.15); ?>;
            --brand-600: <?php echo Helpers::hexToRgbValues($themeLight); ?>;
            --brand-700: <?php echo Helpers::adjustBrightnessRgb($themeLight, -0.15); ?>;
            --brand-800: <?php echo Helpers::adjustBrightnessRgb($themeLight, -0.3); ?>;
            --brand-900: <?php echo Helpers::adjustBrightnessRgb($themeLight, -0.45); ?>;
            --brand-950: <?php echo Helpers::adjustBrightnessRgb($themeLight, -0.6); ?>;

            /* Colores Secundarios (Degradado) */
            --secondary-50: <?php echo Helpers::adjustBrightnessRgb($themeLightSec, 0.95); ?>;
            --secondary-100: <?php echo Helpers::adjustBrightnessRgb($themeLightSec, 0.9); ?>;
            --secondary-200: <?php echo Helpers::adjustBrightnessRgb($themeLightSec, 0.75); ?>;
            --secondary-300: <?php echo Helpers::adjustBrightnessRgb($themeLightSec, 0.55); ?>;
            --secondary-400: <?php echo Helpers::adjustBrightnessRgb($themeLightSec, 0.35); ?>;
            --secondary-500: <?php echo Helpers::adjustBrightnessRgb($themeLightSec, 0.15); ?>;
            --secondary-600: <?php echo Helpers::hexToRgbValues($themeLightSec); ?>;
            --secondary-700: <?php echo Helpers::adjustBrightnessRgb($themeLightSec, -0.15); ?>;
            --secondary-800: <?php echo Helpers::adjustBrightnessRgb($themeLightSec, -0.3); ?>;
            --secondary-900: <?php echo Helpers::adjustBrightnessRgb($themeLightSec, -0.45); ?>;
            --secondary-950: <?php echo Helpers::adjustBrightnessRgb($themeLightSec, -0.6); ?>;

            /* Fondos del Sitio */
            --sitebg: <?php echo Helpers::hexToRgbValues($themeLightBg); ?>;
            --siteheader: <?php echo Helpers::hexToRgbValues($themeLightHeader); ?>;
            --sitefooter: <?php echo Helpers::hexToRgbValues($themeLightFooter); ?>;
        }
        .dark {
            /* Colores de Acento Principal */
            --brand-50: <?php echo Helpers::adjustBrightnessRgb($themeDark, 0.95); ?>;
            --brand-100: <?php echo Helpers::adjustBrightnessRgb($themeDark, 0.9); ?>;
            --brand-200: <?php echo Helpers::adjustBrightnessRgb($themeDark, 0.75); ?>;
            --brand-300: <?php echo Helpers::adjustBrightnessRgb($themeDark, 0.55); ?>;
            --brand-400: <?php echo Helpers::hexToRgbValues($themeDark); ?>;
            --brand-500: <?php echo Helpers::adjustBrightnessRgb($themeDark, -0.15); ?>;
            --brand-600: <?php echo Helpers::adjustBrightnessRgb($themeDark, -0.3); ?>;
            --brand-700: <?php echo Helpers::adjustBrightnessRgb($themeDark, -0.45); ?>;
            --brand-800: <?php echo Helpers::adjustBrightnessRgb($themeDark, -0.6); ?>;
            --brand-900: <?php echo Helpers::adjustBrightnessRgb($themeDark, -0.75); ?>;
            --brand-950: <?php echo Helpers::adjustBrightnessRgb($themeDark, -0.85); ?>;

            /* Colores Secundarios (Degradado) */
            --secondary-50: <?php echo Helpers::adjustBrightnessRgb($themeDarkSec, 0.95); ?>;
            --secondary-100: <?php echo Helpers::adjustBrightnessRgb($themeDarkSec, 0.9); ?>;
            --secondary-200: <?php echo Helpers::adjustBrightnessRgb($themeDarkSec, 0.75); ?>;
            --secondary-300: <?php echo Helpers::adjustBrightnessRgb($themeDarkSec, 0.55); ?>;
            --secondary-400: <?php echo Helpers::hexToRgbValues($themeDarkSec); ?>;
            --secondary-500: <?php echo Helpers::adjustBrightnessRgb($themeDarkSec, -0.15); ?>;
            --secondary-600: <?php echo Helpers::adjustBrightnessRgb($themeDarkSec, -0.3); ?>;
            --secondary-700: <?php echo Helpers::adjustBrightnessRgb($themeDarkSec, -0.45); ?>;
            --secondary-800: <?php echo Helpers::adjustBrightnessRgb($themeDarkSec, -0.6); ?>;
            --secondary-900: <?php echo Helpers::adjustBrightnessRgb($themeDarkSec, -0.75); ?>;
            --secondary-950: <?php echo Helpers::adjustBrightnessRgb($themeDarkSec, -0.85); ?>;

            /* Fondos del Sitio */
            --sitebg: <?php echo Helpers::hexToRgbValues($themeDarkBg); ?>;
            --siteheader: <?php echo Helpers::hexToRgbValues($themeDarkHeader); ?>;
            --sitefooter: <?php echo Helpers::hexToRgbValues($themeDarkFooter); ?>;
        }
    </style>
    
    <!-- Script de Inicialización de Modo Oscuro Rápido (Previene parpadeo) -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-sitebg text-slate-900 transition-colors duration-300 dark:text-slate-100 flex flex-col min-h-screen">

    <!-- Navbar flotante con Glassmorphism -->
    <header class="sticky top-0 z-50 glass-navbar transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="flex items-center gap-2 group">
                        <span class="font-extrabold text-xl tracking-tight text-white">
                            <?php echo htmlspecialchars($siteName); ?>
                        </span>
                    </a>
                </div>

                <!-- Enlaces de navegación desktop -->
                <nav class="hidden xl:flex items-center space-x-8 font-medium">
                    <a href="/" class="text-white hover:text-white/80 transition-colors">Inicio</a>
                    <a href="/?route=page&slug=sobre-el-autor" class="text-white hover:text-white/80 transition-colors">Sobre Nosotros</a>
                    
                    <!-- Menú dinámico de categorías -->
                    <div class="relative group">
                        <button class="flex items-center gap-1 text-white hover:text-white/80 transition-colors focus:outline-none">
                            Categorías
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        
                        <!-- Dropdown de categorías -->
                        <div class="absolute left-0 mt-2 w-48 rounded-xl bg-white dark:bg-slate-900 shadow-xl border border-slate-100 dark:border-slate-800 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <?php if (isset($categories)): ?>
                                <?php foreach($categories as $cat): ?>
                                    <a href="/?route=category&slug=<?php echo $cat->slug; ?>" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">
                                        <?php echo htmlspecialchars($cat->name); ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <a href="/?route=page&slug=contacto" class="text-white hover:text-white/80 transition-colors">Contacto</a>
                </nav>

                <!-- Buscador y Botón de Modo Oscuro -->
                <div class="flex items-center gap-4">
                    
                    <!-- Formulario de Búsqueda -->
                    <form action="/" method="GET" class="hidden sm:block relative">
                        <input type="hidden" name="route" value="search">
                        <input type="text" name="s" placeholder="Buscar posts..." value="<?php echo isset($query) ? htmlspecialchars($query) : ''; ?>" class="w-48 focus:w-64 px-4 py-2 pl-10 text-sm bg-white/10 text-white border border-transparent focus:border-white/30 rounded-xl focus:outline-none transition-all duration-300 placeholder:text-white/60">
                        <div class="absolute left-3 top-2.5 text-white/60">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </form>

                    <!-- Interruptor Modo Oscuro -->
                    <button id="theme-toggle" class="p-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white transition-colors focus:outline-none" aria-label="Cambiar tema">
                        <!-- Icono Sol -->
                        <svg id="theme-toggle-light-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.46 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 100 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                        <!-- Icono Luna -->
                        <svg id="theme-toggle-dark-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    </button>
                    
                    <!-- Hamburguesa menú móvil -->
                    <button id="mobile-menu-button" class="xl:hidden p-2 rounded-xl bg-white/10 hover:bg-white/20 text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Menú Móvil -->
        <div id="mobile-menu" class="hidden xl:hidden border-t border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-4 space-y-3">
            <a href="/" class="block font-medium py-2 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400">Inicio</a>
            <a href="/?route=page&slug=sobre-el-autor" class="block font-medium py-2 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400">Sobre Nosotros</a>
            <div class="font-medium text-slate-500 py-1">Categorías:</div>
            <div class="pl-4 space-y-2 border-l border-slate-200 dark:border-slate-800 mb-2">
                <?php if (isset($categories)): ?>
                    <?php foreach($categories as $cat): ?>
                        <a href="/?route=category&slug=<?php echo $cat->slug; ?>" class="block text-sm text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400">
                            <?php echo htmlspecialchars($cat->name); ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <a href="/?route=page&slug=contacto" class="block font-medium py-2 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400">Contacto</a>
            
            <form action="/" method="GET" class="relative pt-2">
                <input type="hidden" name="route" value="search">
                <input type="text" name="s" placeholder="Buscar..." value="<?php echo isset($query) ? htmlspecialchars($query) : ''; ?>" class="w-full px-4 py-2 pl-10 text-sm bg-slate-100 dark:bg-slate-900 border border-transparent rounded-xl focus:outline-none">
                <div class="absolute left-3 top-4.5 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </form>
        </div>
    </header>

    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10">

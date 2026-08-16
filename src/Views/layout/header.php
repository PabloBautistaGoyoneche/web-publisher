<?php
use App\Helpers;
?>
<!DOCTYPE html>
<html lang="es" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? htmlspecialchars($title) : 'Modern Blog'; ?></title>
    
    <!-- Tailwind Styles (Compilado) -->
    <link rel="stylesheet" href="<?php echo Helpers::asset('css/styles.css'); ?>">
    
    <!-- Script de Inicialización de Modo Oscuro Rápido (Previene parpadeo) -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-900 transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100 flex flex-col min-h-screen">

    <!-- Navbar flotante con Glassmorphism -->
    <header class="sticky top-0 z-50 glass-navbar transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="flex items-center gap-2 group">
                        <span class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-600 flex items-center justify-center text-white font-extrabold text-xl shadow-md shadow-brand-500/20 group-hover:scale-105 transition-transform duration-200">
                            M
                        </span>
                        <span class="font-extrabold text-xl tracking-tight bg-gradient-to-r from-slate-900 to-slate-700 dark:from-white dark:to-slate-300 bg-clip-text text-transparent">
                            Modern<span class="text-brand-600 dark:text-brand-400">Blog</span>
                        </span>
                    </a>
                </div>

                <!-- Enlaces de navegación desktop -->
                <nav class="hidden md:flex items-center space-x-8 font-medium">
                    <a href="/" class="text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Inicio</a>
                    <a href="/?route=page&slug=sobre-el-autor" class="text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Sobre Nosotros</a>
                    <a href="/?route=page&slug=contacto" class="text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Contacto</a>
                    
                    <!-- Menú dinámico de categorías -->
                    <div class="relative group">
                        <button class="flex items-center gap-1 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 transition-colors focus:outline-none">
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
                </nav>

                <!-- Buscador y Botón de Modo Oscuro -->
                <div class="flex items-center gap-4">
                    
                    <!-- Formulario de Búsqueda -->
                    <form action="/" method="GET" class="hidden sm:block relative">
                        <input type="hidden" name="route" value="search">
                        <input type="text" name="s" placeholder="Buscar posts..." value="<?php echo isset($query) ? htmlspecialchars($query) : ''; ?>" class="w-48 focus:w-64 px-4 py-2 pl-10 text-sm bg-slate-100 dark:bg-slate-900 border border-transparent focus:border-brand-500 dark:focus:border-brand-400 rounded-xl focus:outline-none transition-all duration-300">
                        <div class="absolute left-3 top-2.5 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </form>

                    <!-- Interruptor Modo Oscuro -->
                    <button id="theme-toggle" class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-900 hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition-colors focus:outline-none" aria-label="Cambiar tema">
                        <!-- Icono Sol -->
                        <svg id="theme-toggle-light-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.46 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 100 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                        <!-- Icono Luna -->
                        <svg id="theme-toggle-dark-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    </button>
                    
                    <!-- Hamburguesa menú móvil -->
                    <button id="mobile-menu-button" class="md:hidden p-2 rounded-xl bg-slate-100 dark:bg-slate-900 hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Menú Móvil -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-4 space-y-3">
            <a href="/" class="block font-medium py-2 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400">Inicio</a>
            <a href="/?route=page&slug=sobre-el-autor" class="block font-medium py-2 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400">Sobre Nosotros</a>
            <a href="/?route=page&slug=contacto" class="block font-medium py-2 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400">Contacto</a>
            <div class="font-medium text-slate-500 py-1">Categorías:</div>
            <div class="pl-4 space-y-2 border-l border-slate-200 dark:border-slate-800">
                <?php if (isset($categories)): ?>
                    <?php foreach($categories as $cat): ?>
                        <a href="/?route=category&slug=<?php echo $cat->slug; ?>" class="block text-sm text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400">
                            <?php echo htmlspecialchars($cat->name); ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
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

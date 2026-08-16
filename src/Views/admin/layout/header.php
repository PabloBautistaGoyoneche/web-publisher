<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$adminName = $_SESSION['admin_name'] ?? 'Administrador';
$adminUser = $_SESSION['admin_user'] ?? 'admin';
$currentRoute = $_GET['route'] ?? 'admin/dashboard';
?>
<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? htmlspecialchars($title) : 'Admin Panel'; ?></title>
    <!-- Google Fonts & Tailwind -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/styles.css">
    
    <style>
        body {
            font-family: 'Outfit', 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 h-full flex flex-col md:flex-row overflow-hidden transition-colors duration-300">

    <!-- Sidebar Lateral de Administración -->
    <aside class="w-full md:w-64 bg-slate-900 text-slate-300 flex-shrink-0 flex flex-col z-20 border-r border-slate-800">
        <!-- Logo y Marca -->
        <div class="h-20 flex items-center px-6 border-b border-slate-800 gap-3">
            <span class="w-9 h-9 rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-600 flex items-center justify-center text-white font-extrabold text-lg shadow-md shadow-brand-500/20">
                M
            </span>
            <span class="font-extrabold text-lg tracking-tight text-white">
                Modern<span class="text-brand-400">Admin</span>
            </span>
        </div>

        <!-- Menú de Navegación -->
        <nav class="flex-grow py-6 px-4 space-y-1.5 overflow-y-auto">
            
            <!-- Dashboard -->
            <a href="/?route=admin/dashboard" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-colors <?php echo $currentRoute === 'admin/dashboard' ? 'bg-brand-600 text-white shadow-md shadow-brand-500/10' : 'hover:bg-slate-800 hover:text-white'; ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path></svg>
                Dashboard
            </a>

            <!-- Entradas -->
            <a href="/?route=admin/posts" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-colors <?php echo strpos($currentRoute, 'admin/posts') === 0 ? 'bg-brand-600 text-white shadow-md shadow-brand-500/10' : 'hover:bg-slate-800 hover:text-white'; ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                Entradas
            </a>

            <!-- Categorías -->
            <a href="/?route=admin/categories" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-colors <?php echo $currentRoute === 'admin/categories' ? 'bg-brand-600 text-white shadow-md shadow-brand-500/10' : 'hover:bg-slate-800 hover:text-white'; ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Categorías
            </a>

            <!-- Comentarios -->
            <a href="/?route=admin/comments" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-colors <?php echo $currentRoute === 'admin/comments' ? 'bg-brand-600 text-white shadow-md shadow-brand-500/10' : 'hover:bg-slate-800 hover:text-white'; ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                Comentarios
            </a>

            <!-- Páginas -->
            <a href="/?route=admin/pages" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-colors <?php echo strpos($currentRoute, 'admin/pages') === 0 ? 'bg-brand-600 text-white shadow-md shadow-brand-500/10' : 'hover:bg-slate-800 hover:text-white'; ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Páginas
            </a>

            <!-- Mensajes -->
            <?php $msgCount = \App\Models\Message::count(); ?>
            <a href="/?route=admin/messages" class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-semibold transition-colors <?php echo $currentRoute === 'admin/messages' ? 'bg-brand-600 text-white shadow-md shadow-brand-500/10' : 'hover:bg-slate-800 hover:text-white'; ?>">
                <div class="flex items-center gap-3.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <span>Mensajes</span>
                </div>
                <?php if ($msgCount > 0): ?>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold <?php echo $currentRoute === 'admin/messages' ? 'bg-white text-brand-600' : 'bg-brand-600 text-white'; ?>">
                        <?php echo $msgCount; ?>
                    </span>
                <?php endif; ?>
            </a>

            <div class="h-px bg-slate-800 my-6"></div>

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
        </nav>
    </aside>

    <!-- Contenido Principal -->
    <div class="flex-grow flex flex-col overflow-hidden h-full">
        <!-- Topbar Horizontal -->
        <header class="h-20 bg-white dark:bg-slate-900 border-b border-slate-200/60 dark:border-slate-800/80 px-6 sm:px-8 flex items-center justify-between flex-shrink-0 transition-colors duration-300">
            
            <!-- Título de Sección -->
            <h2 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">
                Panel de Administración
            </h2>

            <!-- Perfil del Administrador -->
            <div class="flex items-center gap-3.5">
                <div class="flex flex-col text-right hidden sm:flex">
                    <span class="text-sm font-bold text-slate-800 dark:text-slate-200"><?php echo htmlspecialchars($adminName); ?></span>
                    <span class="text-xs text-slate-400 font-medium">@<?php echo htmlspecialchars($adminUser); ?></span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-600 flex items-center justify-center text-white font-extrabold shadow-md shadow-brand-500/10 uppercase">
                    <?php echo substr($adminName, 0, 1); ?>
                </div>
            </div>
        </header>

        <!-- Contenedor del Cuerpo (Scrollable) -->
        <main class="flex-grow overflow-y-auto p-6 sm:p-8 bg-slate-50 dark:bg-slate-950 transition-colors duration-300">

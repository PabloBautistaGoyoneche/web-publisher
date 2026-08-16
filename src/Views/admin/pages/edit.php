<?php
require __DIR__ . '/../layout/header.php';
?>

<!-- Enlace de regreso -->
<div class="mb-6">
    <a href="/?route=admin/pages" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-600 dark:text-brand-400 hover:underline">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Volver a la lista de páginas
    </a>
</div>

<!-- Cabecera de Página -->
<div class="mb-10 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
            Editar Página Estática
        </h1>
        <p class="text-sm text-slate-500 mt-1">
            Modifica la estructura y el contenido legal o institucional de la página.
        </p>
    </div>
    
    <!-- Ver Página -->
    <a href="/?route=page&slug=<?php echo $page->slug; ?>" target="_blank" class="btn-secondary py-2.5 px-4 text-xs rounded-xl inline-flex items-center gap-1.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
        Ver en la Web
    </a>
</div>

<!-- Alerta de Error -->
<?php if (isset($error) && $error): ?>
    <div class="mb-6 p-4 bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900/60 rounded-2xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <span><?php echo htmlspecialchars($error); ?></span>
    </div>
<?php endif; ?>

<!-- Formulario de Edición -->
<form action="/?route=admin/pages/edit&id=<?php echo $page->id; ?>" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Contenido (Izquierda) -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Campo: Título -->
        <div class="glass-card rounded-3xl p-6 border border-slate-200/50 dark:border-slate-800/80 space-y-2">
            <label for="title" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Título de la Página *</label>
            <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($page->title); ?>" class="w-full px-4 py-3 text-sm bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none transition-colors">
        </div>

        <!-- Campo: Contenido -->
        <div class="glass-card rounded-3xl p-6 border border-slate-200/50 dark:border-slate-800/80 space-y-2">
            <label for="content" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Contenido de la Página *</label>
            <p class="text-[10px] text-slate-400 mb-2">Escribe usando etiquetas HTML para estructurar títulos, párrafos y listas.</p>
            <textarea id="content" name="content" required rows="18" class="w-full px-4 py-3 text-sm bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none transition-colors font-mono"><?php echo htmlspecialchars($page->content); ?></textarea>
        </div>

    </div>

    <!-- Ajustes laterales (Derecha) -->
    <div class="lg:col-span-1 space-y-6">
        
        <!-- Tarjeta: Publicación -->
        <div class="glass-card rounded-3xl p-6 border border-slate-200/50 dark:border-slate-800/80 space-y-4">
            <h3 class="font-extrabold text-sm border-b border-slate-100 dark:border-slate-800 pb-3 uppercase tracking-wider text-slate-400">Publicación</h3>

            <!-- Slug -->
            <div class="space-y-2">
                <label for="slug" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Slug (Enlace Permanente)</label>
                <input type="text" id="slug" name="slug" value="<?php echo htmlspecialchars($page->slug); ?>" class="w-full px-3.5 py-2.5 text-sm bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none transition-colors">
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full btn-primary py-3 text-sm font-semibold rounded-xl">
                    Actualizar Página
                </button>
            </div>
        </div>

    </div>

</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>

<?php
require __DIR__ . '/../layout/header.php';
?>

<!-- Cabecera de Página -->
<div class="mb-10">
    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
        Categorías del Blog
    </h1>
    <p class="text-sm text-slate-500 mt-1">
        Organiza tus artículos agregando y administrando categorías principales.
    </p>
</div>

<!-- Alerta de Error / Éxito -->
<?php if (isset($error) && $error): ?>
    <div class="mb-6 p-4 bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900/60 rounded-2xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <span><?php echo htmlspecialchars($error); ?></span>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Formulario de Creación (Izquierda) -->
    <div class="lg:col-span-1">
        <div class="glass-card rounded-3xl p-6 border border-slate-200/50 dark:border-slate-800/80 space-y-5">
            <h3 class="font-extrabold text-sm border-b border-slate-100 dark:border-slate-800 pb-3 uppercase tracking-wider text-slate-400">Añadir Nueva Categoría</h3>
            
            <form action="/?route=admin/categories" method="POST" class="space-y-4">
                
                <!-- Nombre -->
                <div class="space-y-2">
                    <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Nombre *</label>
                    <input type="text" id="name" name="name" required placeholder="ej: Diseño" class="w-full px-4 py-2.5 text-sm bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none transition-colors">
                </div>

                <!-- Slug -->
                <div class="space-y-2">
                    <label for="slug" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Slug</label>
                    <input type="text" id="slug" name="slug" placeholder="ej: diseno" class="w-full px-4 py-2.5 text-sm bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none transition-colors">
                </div>

                <!-- Descripción -->
                <div class="space-y-2">
                    <label for="description" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Descripción</label>
                    <textarea id="description" name="description" rows="4" placeholder="Breve resumen de la categoría..." class="w-full px-4 py-2.5 text-sm bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none transition-colors"></textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" name="create_category" class="w-full btn-primary py-3 text-sm font-semibold rounded-xl">
                        Añadir Categoría
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- Listado de Categorías (Derecha) -->
    <div class="lg:col-span-2">
        <div class="glass-card rounded-3xl border border-slate-200/50 dark:border-slate-800/80 overflow-hidden shadow-sm">
            <div class="overflow-x-auto w-full">
                <?php if (!empty($categories)): ?>
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 dark:bg-slate-900/40 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-200/50 dark:border-slate-800/50">
                                <th class="px-6 py-4">Nombre</th>
                                <th class="px-6 py-4">Descripción</th>
                                <th class="px-6 py-4">Slug</th>
                                <th class="px-6 py-4 text-center">Posts</th>
                                <th class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                            <?php foreach($categories as $cat): ?>
                                <tr class="hover:bg-slate-100/30 dark:hover:bg-slate-900/30 transition-colors">
                                    
                                    <!-- Nombre -->
                                    <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-100">
                                        <?php echo htmlspecialchars($cat->name); ?>
                                    </td>

                                    <!-- Descripción -->
                                    <td class="px-6 py-4 text-slate-500 dark:text-slate-400 max-w-xs truncate">
                                        <?php echo htmlspecialchars($cat->description ?? 'Sin descripción.'); ?>
                                    </td>

                                    <!-- Slug -->
                                    <td class="px-6 py-4 font-mono text-xs text-slate-400">
                                        <?php echo htmlspecialchars($cat->slug); ?>
                                    </td>

                                    <!-- Posts Count -->
                                    <td class="px-6 py-4 text-center font-mono font-medium text-slate-600 dark:text-slate-400">
                                        <?php echo $cat->getPostCount(); ?>
                                    </td>

                                    <!-- Acciones -->
                                    <td class="px-6 py-4 text-right">
                                        <!-- Eliminar (WordPress style warning since categories delete posts in cascade) -->
                                        <a href="/?route=admin/categories/delete&id=<?php echo $cat->id; ?>" onclick="return confirm('¿Seguro que deseas eliminar esta categoría? ATENCIÓN: Al eliminarla se eliminarán todos los posts asociados a ella.');" class="p-2 text-slate-400 hover:text-red-500 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors inline-block" title="Eliminar categoría">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </a>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="p-12 text-center text-slate-400">
                        No hay categorías creadas.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<!-- Autogenerador de Slug en categorías -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        
        if (nameInput && slugInput) {
            nameInput.addEventListener('input', function() {
                let slug = nameInput.value.toLowerCase()
                    .trim()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/[\s_]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                
                slugInput.value = slug;
            });
        }
    });
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>

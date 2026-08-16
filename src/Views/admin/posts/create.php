<?php
require __DIR__ . '/../layout/header.php';
?>

<!-- Enlace de regreso -->
<div class="mb-6">
    <a href="/?route=admin/posts" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-600 dark:text-brand-400 hover:underline">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Volver a la lista de entradas
    </a>
</div>

<!-- Cabecera de Página -->
<div class="mb-10">
    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
        Nueva Entrada
    </h1>
    <p class="text-sm text-slate-500 mt-1">
        Crea y redacta una nueva entrada de blog en tu sistema.
    </p>
</div>

<!-- Alerta de Error -->
<?php if (isset($error) && $error): ?>
    <div class="mb-6 p-4 bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900/60 rounded-2xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <span><?php echo htmlspecialchars($error); ?></span>
    </div>
<?php endif; ?>

<!-- Formulario de Creación -->
<form action="/?route=admin/posts/create" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Contenido de la entrada (Izquierda) -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Campo: Título -->
        <div class="glass-card rounded-3xl p-6 border border-slate-200/50 dark:border-slate-800/80 space-y-2">
            <label for="title" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Título de la Entrada *</label>
            <input type="text" id="title" name="title" required placeholder="Ingresa el título del post..." class="w-full px-4 py-3 text-sm bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none transition-colors">
        </div>

        <!-- Campo: Contenido -->
        <div class="glass-card rounded-3xl p-6 border border-slate-200/50 dark:border-slate-800/80 space-y-2">
            <label for="content" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Cuerpo del Artículo *</label>
            <p class="text-[10px] text-slate-400 mb-2">Puedes usar etiquetas HTML o Markdown simple.</p>
            <textarea id="content" name="content" required rows="15" placeholder="Escribe el contenido completo del artículo aquí..." class="w-full px-4 py-3 text-sm bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none transition-colors font-mono"></textarea>
        </div>

        <!-- Campo: Extracto -->
        <div class="glass-card rounded-3xl p-6 border border-slate-200/50 dark:border-slate-800/80 space-y-2">
            <label for="excerpt" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Extracto (Resumen)</label>
            <p class="text-[10px] text-slate-400 mb-2">Un breve resumen introductorio para las tarjetas de presentación en el listado.</p>
            <textarea id="excerpt" name="excerpt" rows="3" placeholder="Ingresa una breve descripción..." class="w-full px-4 py-3 text-sm bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none transition-colors"></textarea>
        </div>

    </div>

    <!-- Ajustes del post (Derecha/Barra Lateral) -->
    <div class="lg:col-span-1 space-y-6">
        
        <!-- Tarjeta: Publicación -->
        <div class="glass-card rounded-3xl p-6 border border-slate-200/50 dark:border-slate-800/80 space-y-4">
            <h3 class="font-extrabold text-sm border-b border-slate-100 dark:border-slate-800 pb-3 uppercase tracking-wider text-slate-400">Publicación</h3>
            
            <!-- Estado -->
            <div class="space-y-2">
                <label for="status" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Estado</label>
                <select id="status" name="status" class="w-full px-3.5 py-2.5 text-sm bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none">
                    <option value="published">Publicado</option>
                    <option value="draft">Borrador</option>
                </select>
            </div>

            <!-- Slug -->
            <div class="space-y-2">
                <label for="slug" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Enlace Permanente (Slug)</label>
                <input type="text" id="slug" name="slug" placeholder="mi-enlace-permanente" class="w-full px-3.5 py-2.5 text-sm bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none transition-colors">
            </div>

            <!-- Categoría -->
            <div class="space-y-2">
                <label for="category_id" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Categoría *</label>
                <select id="category_id" name="category_id" required class="w-full px-3.5 py-2.5 text-sm bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none">
                    <option value="">Seleccione una categoría</option>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?php echo $cat->id; ?>"><?php echo htmlspecialchars($cat->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full btn-primary py-3 text-sm font-semibold rounded-xl">
                    Guardar Entrada
                </button>
            </div>
        </div>

        <!-- Tarjeta: Imagen Destacada -->
        <div class="glass-card rounded-3xl p-6 border border-slate-200/50 dark:border-slate-800/80 space-y-4">
            <h3 class="font-extrabold text-sm border-b border-slate-100 dark:border-slate-800 pb-3 uppercase tracking-wider text-slate-400">Imagen Destacada</h3>
            
            <div class="space-y-3">
                <p class="text-[10px] text-slate-400">Sube una portada llamativa. Formatos permitidos: JPG, PNG, WEBP.</p>
                <input type="file" id="featured_image" name="featured_image" accept="image/*" class="text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer w-full">
            </div>
        </div>

    </div>

</form>

<!-- Autogenerador de Slug en tiempo real -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        
        if (titleInput && slugInput) {
            titleInput.addEventListener('input', function() {
                // Solo autogenerar si el usuario no ha modificado el slug manualmente
                let slug = titleInput.value.toLowerCase()
                    .trim()
                    .normalize('NFD') // Quitar acentos españoles
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

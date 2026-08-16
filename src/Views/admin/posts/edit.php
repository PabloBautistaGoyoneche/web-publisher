<?php
use App\Helpers;
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
<div class="mb-10 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
            Editar Entrada
        </h1>
        <p class="text-sm text-slate-500 mt-1">
            Modifica los detalles y contenido de tu publicación.
        </p>
    </div>
    
    <!-- Ver Post -->
    <a href="/?route=post&slug=<?php echo $post->slug; ?>" target="_blank" class="btn-secondary py-2.5 px-4 text-xs rounded-xl inline-flex items-center gap-1.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
        Ver en el Blog
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
<form action="/?route=admin/posts/edit&id=<?php echo $post->id; ?>" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Contenido de la entrada (Izquierda) -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Campo: Título -->
        <div class="glass-card rounded-3xl p-6 border border-slate-200/50 dark:border-slate-800/80 space-y-2">
            <label for="title" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Título de la Entrada *</label>
            <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($post->title); ?>" class="w-full px-4 py-3 text-sm bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none transition-colors">
        </div>

        <!-- Campo: Contenido -->
        <div class="glass-card rounded-3xl p-6 border border-slate-200/50 dark:border-slate-800/80 space-y-2">
            <label for="content" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Cuerpo del Artículo *</label>
            <p class="text-[10px] text-slate-400 mb-2">Puedes usar etiquetas HTML o Markdown simple.</p>
            <textarea id="content" name="content" required rows="15" class="w-full px-4 py-3 text-sm bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none transition-colors font-mono"><?php echo htmlspecialchars($post->content); ?></textarea>
        </div>

        <!-- Campo: Extracto -->
        <div class="glass-card rounded-3xl p-6 border border-slate-200/50 dark:border-slate-800/80 space-y-2">
            <label for="excerpt" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Extracto (Resumen)</label>
            <p class="text-[10px] text-slate-400 mb-2">Un breve resumen introductorio para las tarjetas de presentación.</p>
            <textarea id="excerpt" name="excerpt" rows="3" class="w-full px-4 py-3 text-sm bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none transition-colors"><?php echo htmlspecialchars($post->excerpt); ?></textarea>
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
                    <option value="published" <?php echo $post->status === 'published' ? 'selected' : ''; ?>>Publicado</option>
                    <option value="draft" <?php echo $post->status === 'draft' ? 'selected' : ''; ?>>Borrador</option>
                </select>
            </div>

            <!-- Slug -->
            <div class="space-y-2">
                <label for="slug" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Enlace Permanente (Slug)</label>
                <input type="text" id="slug" name="slug" value="<?php echo htmlspecialchars($post->slug); ?>" class="w-full px-3.5 py-2.5 text-sm bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none transition-colors">
            </div>

            <!-- Categoría -->
            <div class="space-y-2">
                <label for="category_id" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Categoría *</label>
                <select id="category_id" name="category_id" required class="w-full px-3.5 py-2.5 text-sm bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none">
                    <?php foreach($categories as $cat): ?>
                        <option value="<?php echo $cat->id; ?>" <?php echo $post->category_id === $cat->id ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full btn-primary py-3 text-sm font-semibold rounded-xl">
                    Actualizar Entrada
                </button>
            </div>
        </div>

        <!-- Tarjeta: Imagen Destacada -->
        <div class="glass-card rounded-3xl p-6 border border-slate-200/50 dark:border-slate-800/80 space-y-4">
            <h3 class="font-extrabold text-sm border-b border-slate-100 dark:border-slate-800 pb-3 uppercase tracking-wider text-slate-400">Imagen Destacada</h3>
            
            <?php if ($post->featured_image): ?>
                <div class="aspect-video w-full rounded-2xl bg-slate-100 dark:bg-slate-950 overflow-hidden border border-slate-200/50 dark:border-slate-800/80 relative group">
                    <img src="<?php echo Helpers::asset('uploads/' . $post->featured_image); ?>" alt="" class="w-full h-full object-cover">
                </div>
            <?php endif; ?>

            <div class="space-y-3">
                <p class="text-[10px] text-slate-400">Sube un archivo para reemplazar la portada actual (JPG, PNG, WEBP).</p>
                <input type="file" id="featured_image" name="featured_image" accept="image/*" class="text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer w-full">
            </div>
        </div>

    </div>

</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>

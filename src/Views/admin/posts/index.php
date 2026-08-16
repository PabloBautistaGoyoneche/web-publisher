<?php
use App\Helpers;
require __DIR__ . '/../layout/header.php';
?>

<!-- Cabecera de Página -->
<div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
            Gestionar Entradas
        </h1>
        <p class="text-sm text-slate-500 mt-1">
            Crea, edita, redacta borradores y gestiona las publicaciones de tu blog.
        </p>
    </div>
    <a href="/?route=admin/posts/create" class="btn-primary py-2.5 px-5 text-sm rounded-xl inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Nueva Entrada
    </a>
</div>

<!-- Tabla CRUD de Posts -->
<div class="glass-card rounded-3xl border border-slate-200/50 dark:border-slate-800/80 overflow-hidden shadow-sm">
    <div class="overflow-x-auto w-full">
        <?php if (!empty($posts)): ?>
            <table class="w-full text-left text-sm border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-900/40 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-200/50 dark:border-slate-800/50">
                        <th class="px-6 py-4">Portada</th>
                        <th class="px-6 py-4">Título</th>
                        <th class="px-6 py-4">Categoría</th>
                        <th class="px-6 py-4">Estado</th>
                        <th class="px-6 py-4 text-center">Vistas</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    <?php foreach($posts as $post): ?>
                        <tr class="hover:bg-slate-100/30 dark:hover:bg-slate-900/30 transition-colors">
                            
                            <!-- Portada Thumbnail -->
                            <td class="px-6 py-4">
                                <div class="w-14 h-10 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800">
                                    <?php if ($post->featured_image): ?>
                                        <img src="<?php echo Helpers::asset('uploads/' . $post->featured_image); ?>" alt="" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center text-xs text-slate-400 font-bold uppercase">S/I</div>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- Título -->
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-800 dark:text-slate-100 line-clamp-1">
                                    <?php echo htmlspecialchars($post->title); ?>
                                </span>
                                <span class="text-xs text-slate-400 block mt-0.5 font-medium">
                                    Por <?php echo htmlspecialchars($post->getAuthor()->display_name); ?> &bull; <?php echo Helpers::formatDate($post->created_at); ?>
                                </span>
                            </td>

                            <!-- Categoría -->
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                    <?php echo htmlspecialchars($post->getCategory()->name); ?>
                                </span>
                            </td>

                            <!-- Estado -->
                            <td class="px-6 py-4">
                                <?php if ($post->status === 'published'): ?>
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400">
                                        Publicado
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400">
                                        Borrador
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Vistas -->
                            <td class="px-6 py-4 text-center font-mono font-medium text-slate-600 dark:text-slate-400">
                                <?php echo $post->views_count; ?>
                            </td>

                            <!-- Acciones -->
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    
                                    <!-- Ver Post (Público) -->
                                    <a href="/?route=post&slug=<?php echo $post->slug; ?>" target="_blank" class="p-2 text-slate-400 hover:text-brand-500 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="Ver entrada">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>

                                    <!-- Editar -->
                                    <a href="/?route=admin/posts/edit&id=<?php echo $post->id; ?>" class="p-2 text-slate-400 hover:text-brand-500 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="Editar entrada">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>

                                    <!-- Eliminar -->
                                    <a href="/?route=admin/posts/delete&id=<?php echo $post->id; ?>" onclick="return confirm('¿Seguro que deseas eliminar permanentemente esta entrada? Todas sus vistas y comentarios se perderán.');" class="p-2 text-slate-400 hover:text-red-500 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="Eliminar entrada">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </a>

                                </div>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="p-12 text-center text-slate-400">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                <h3 class="text-lg font-bold mb-1 text-slate-700">No hay entradas creadas</h3>
                <p class="text-sm text-slate-500 mb-6">Comienza a escribir tu primera entrada ahora.</p>
                <a href="/?route=admin/posts/create" class="btn-primary py-2 px-5 text-sm rounded-xl">
                    Nueva Entrada
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

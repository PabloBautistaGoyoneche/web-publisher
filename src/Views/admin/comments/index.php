<?php
use App\Helpers;

$commentsEnabled = \App\Models\Setting::get('enable_comments', '1') === '1';

// Definir el interruptor para inyectarlo en la barra de navegación superior (sección celeste/azul)
ob_start();
?>
<div class="flex items-center gap-3 bg-white/10 dark:bg-slate-900/40 px-4 py-2 rounded-2xl border border-white/10 dark:border-slate-800/30 shadow-sm backdrop-blur-sm">
    <span class="text-xs font-semibold text-white/90">Caja de comentarios:</span>
    <form id="toggle-comments-form" action="/?route=admin/comments/toggle" method="POST" class="flex items-center">
        <button type="button" id="comments-toggle-btn" 
                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none <?php echo $commentsEnabled ? 'bg-emerald-500' : 'bg-white/20 dark:bg-slate-800/30'; ?>"
                onclick="document.getElementById('comments-toggle-input').value = '<?php echo $commentsEnabled ? '0' : '1'; ?>'; document.getElementById('toggle-comments-form').submit();">
            <span style="display: none;">Habilitar/Deshabilitar comentarios</span>
            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out <?php echo $commentsEnabled ? 'translate-x-5' : 'translate-x-0'; ?>"></span>
        </button>
        <input type="hidden" id="comments-toggle-input" name="enable_comments" value="<?php echo $commentsEnabled ? '1' : '0'; ?>">
    </form>
</div>
<?php
$headerActions = ob_get_clean();

require __DIR__ . '/../layout/header.php';
?>

<!-- Cabecera de Página -->
<div class="mb-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
            Moderación de Comentarios
        </h1>
        <p class="text-sm text-slate-500 mt-1">
            Revisa, aprueba y elimina comentarios escritos por los lectores en tus entradas.
        </p>
    </div>
</div>

<!-- Tabla CRUD de Comentarios -->
<div class="glass-card rounded-3xl border border-slate-200/50 dark:border-slate-800/80 overflow-hidden shadow-sm">
    <div class="overflow-x-auto w-full">
        <?php if (!empty($comments)): ?>
            <table class="w-full text-left text-sm border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-900/40 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-200/50 dark:border-slate-800/50">
                        <th class="px-6 py-4">Autor</th>
                        <th class="px-6 py-4">Comentario</th>
                        <th class="px-6 py-4">En Respuesta A</th>
                        <th class="px-6 py-4">Fecha</th>
                        <th class="px-6 py-4">Estado</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    <?php foreach($comments as $comment): ?>
                        <?php $post = $comment->getPost(); ?>
                        <tr class="hover:bg-slate-100/30 dark:hover:bg-slate-900/30 transition-colors">
                            
                            <!-- Autor -->
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800 dark:text-slate-100">
                                    <?php echo htmlspecialchars($comment->author_name); ?>
                                </div>
                                <div class="text-xs text-slate-400 font-mono">
                                    <?php echo htmlspecialchars($comment->author_email); ?>
                                </div>
                            </td>

                            <!-- Contenido del comentario -->
                            <td class="px-6 py-4 max-w-sm">
                                <p class="text-slate-600 dark:text-slate-300 break-words leading-relaxed text-xs">
                                    <?php echo nl2br(htmlspecialchars($comment->content)); ?>
                                </p>
                            </td>

                            <!-- Post -->
                            <td class="px-6 py-4">
                                <?php if ($post): ?>
                                    <a href="/?route=post&slug=<?php echo $post->slug; ?>" target="_blank" class="font-semibold text-brand-600 dark:text-brand-400 hover:underline line-clamp-1">
                                        <?php echo htmlspecialchars($post->title); ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-xs text-red-500 font-medium italic">Post eliminado</span>
                                <?php endif; ?>
                            </td>

                            <!-- Fecha -->
                            <td class="px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                <?php echo Helpers::formatDate($comment->created_at); ?>
                            </td>

                            <!-- Estado -->
                            <td class="px-6 py-4">
                                <?php if ($comment->status === 'approved'): ?>
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400">
                                        Aprobado
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 animate-pulse">
                                        Pendiente
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Acciones -->
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    
                                    <!-- Aprobar (Si está pendiente) -->
                                    <?php if ($comment->status !== 'approved'): ?>
                                        <a href="/?route=admin/comments/approve&id=<?php echo $comment->id; ?>" class="p-2 text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 rounded-xl transition-colors" title="Aprobar comentario">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </a>
                                    <?php endif; ?>

                                    <!-- Eliminar -->
                                    <a href="/?route=admin/comments/delete&id=<?php echo $comment->id; ?>" onclick="return confirm('¿Seguro que deseas eliminar permanentemente este comentario?');" class="p-2 text-slate-400 hover:text-red-500 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="Eliminar comentario">
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
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                <h3 class="text-lg font-bold mb-1 text-slate-700">No hay comentarios</h3>
                <p class="text-sm text-slate-500">Los comentarios que escriban los usuarios aparecerán aquí para moderación.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

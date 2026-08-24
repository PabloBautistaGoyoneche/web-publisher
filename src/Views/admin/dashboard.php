<?php
use App\Helpers;
require __DIR__ . '/layout/header.php';
?>

<?php if (isset($_SESSION['update_success'])): ?>
    <div class="mb-6 p-4 bg-emerald-950/40 border border-emerald-900/50 text-emerald-400 text-xs font-medium rounded-2xl flex items-center gap-3">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
        <span><?php echo htmlspecialchars($_SESSION['update_success']); ?></span>
    </div>
    <?php unset($_SESSION['update_success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['update_error'])): ?>
    <div class="mb-6 p-4 bg-red-950/40 border border-red-900/50 text-red-400 text-xs font-medium rounded-2xl flex items-center gap-3">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <span><?php echo htmlspecialchars($_SESSION['update_error']); ?></span>
    </div>
    <?php unset($_SESSION['update_error']); ?>
<?php endif; ?>

<?php if (isset($updateAvailable) && $updateAvailable): ?>
    <div class="mb-8 p-6 bg-gradient-to-r from-violet-600 to-indigo-600 rounded-3xl text-white shadow-lg flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="space-y-1">
            <h3 class="text-lg font-bold">¡Nueva actualización disponible!</h3>
            <p class="text-sm text-violet-100">Hay un nuevo commit en el repositorio de GitHub: <code class="bg-violet-800 px-1.5 py-0.5 rounded font-mono text-xs"><?php echo substr($latestCommitSha, 0, 7); ?></code>. Puedes aplicar los cambios y ejecutar las migraciones automáticamente ahora.</p>
        </div>
        <a href="/?route=admin/update" class="w-full sm:w-auto px-5 py-3 bg-white text-violet-700 hover:bg-violet-50 text-sm font-semibold rounded-2xl text-center shadow-md active:scale-95 transition-all flex items-center justify-center gap-2">
            <svg class="w-4 h-4 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Actualizar Ahora
        </a>
    </div>
<?php endif; ?>

<!-- Sección: Cabecera con saludo -->
<div class="mb-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">
            ¡Hola, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            Aquí tienes un resumen del estado actual de tu blog al estilo WordPress.
        </p>
    </div>
    
    <a href="/?route=admin/posts/create" class="btn-primary py-2.5 px-5 text-sm rounded-xl inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Crear Entrada
    </a>
</div>

<!-- Estadísticas en Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-10">
    
    <!-- Tarjeta: Posts -->
    <div class="glass-card rounded-3xl p-6 border border-slate-200/50 dark:border-slate-800/80 flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Entradas Totales</span>
            <div class="text-3xl font-extrabold text-slate-800 dark:text-white"><?php echo $stats['posts']; ?></div>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-brand-50 dark:bg-brand-950 text-brand-600 dark:text-brand-400 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
        </div>
    </div>

    <!-- Tarjeta: Comentarios -->
    <div class="glass-card rounded-3xl p-6 border border-slate-200/50 dark:border-slate-800/80 flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Comentarios</span>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-slate-800 dark:text-white"><?php echo $stats['comments']; ?></span>
                <?php if ($stats['comments_pending'] > 0): ?>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 animate-pulse">
                        <?php echo $stats['comments_pending']; ?> pnd.
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
        </div>
    </div>

    <!-- Tarjeta: Vistas -->
    <div class="glass-card rounded-3xl p-6 border border-slate-200/50 dark:border-slate-800/80 flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Lecturas Totales</span>
            <div class="text-3xl font-extrabold text-slate-800 dark:text-white"><?php echo number_format($stats['views']); ?></div>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-secondary-50 dark:bg-secondary-950/60 text-secondary-600 dark:text-secondary-400 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
        </div>
    </div>

    <!-- Tarjeta: Categorías -->
    <div class="glass-card rounded-3xl p-6 border border-slate-200/50 dark:border-slate-800/80 flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Categorías Activas</span>
            <div class="text-3xl font-extrabold text-slate-800 dark:text-white"><?php echo $stats['categories']; ?></div>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-violet-50 dark:bg-violet-950/60 text-violet-600 dark:text-violet-400 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
    </div>

    <!-- Tarjeta: Mensajes -->
    <div class="glass-card rounded-3xl p-6 border border-slate-200/50 dark:border-slate-800/80 flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Mensajes Recibidos</span>
            <div class="text-3xl font-extrabold text-slate-800 dark:text-white"><?php echo $stats['messages']; ?></div>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
        </div>
    </div>

</div>

<!-- Sección: Última Actividad (Tablas) -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    <!-- Entradas Recientes -->
    <div class="glass-card rounded-3xl border border-slate-200/50 dark:border-slate-800/80 overflow-hidden flex flex-col">
        <div class="p-6 border-b border-slate-200/60 dark:border-slate-800/60 flex items-center justify-between">
            <h3 class="font-extrabold text-lg text-slate-900 dark:text-white">Últimas Entradas</h3>
            <a href="/?route=admin/posts" class="text-xs font-bold text-brand-600 dark:text-brand-400 hover:underline">Ver todas</a>
        </div>
        <div class="divide-y divide-slate-100 dark:divide-slate-800/80 overflow-x-auto">
            <?php if (!empty($recentPosts)): ?>
                <table class="w-full text-left text-sm border-collapse min-w-[500px]">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-slate-900/40 text-slate-400 text-xs font-bold uppercase tracking-wider">
                            <th class="px-6 py-4">Título</th>
                            <th class="px-6 py-4">Estado</th>
                            <th class="px-6 py-4 text-right">Vistas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        <?php foreach($recentPosts as $post): ?>
                            <tr class="hover:bg-slate-100/30 dark:hover:bg-slate-900/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800 dark:text-slate-100 line-clamp-1">
                                        <?php echo htmlspecialchars($post->title); ?>
                                    </div>
                                    <div class="text-xs text-slate-400 mt-0.5">
                                        <?php echo Helpers::formatDate($post->created_at); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($post->status === 'published'): ?>
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400">
                                            Publicado
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">
                                            Borrador
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-medium text-slate-600 dark:text-slate-400">
                                    <?php echo $post->views_count; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="p-8 text-center text-slate-400 text-sm">
                    No hay entradas redactadas.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Comentarios Recientes -->
    <div class="glass-card rounded-3xl border border-slate-200/50 dark:border-slate-800/80 overflow-hidden flex flex-col">
        <div class="p-6 border-b border-slate-200/60 dark:border-slate-800/60 flex items-center justify-between">
            <h3 class="font-extrabold text-lg text-slate-900 dark:text-white">Comentarios Recientes</h3>
            <a href="/?route=admin/comments" class="text-xs font-bold text-brand-600 dark:text-brand-400 hover:underline">Ver todos</a>
        </div>
        <div class="divide-y divide-slate-100 dark:divide-slate-800/80">
            <?php if (!empty($latestComments)): ?>
                <?php foreach($latestComments as $comment): ?>
                    <div class="p-5 flex items-start gap-4 hover:bg-slate-100/30 dark:hover:bg-slate-900/30 transition-colors">
                        <div class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center justify-center font-bold text-xs uppercase flex-shrink-0">
                            <?php echo substr($comment->author_name, 0, 1); ?>
                        </div>
                        <div class="flex-grow min-w-0">
                            <div class="flex justify-between items-baseline gap-2">
                                <span class="font-bold text-xs text-slate-800 dark:text-slate-200 block truncate">
                                    <?php echo htmlspecialchars($comment->author_name); ?>
                                </span>
                                <span class="text-[10px] text-slate-400 flex-shrink-0 font-medium">
                                    <?php echo Helpers::formatDate($comment->created_at); ?>
                                </span>
                            </div>
                            <span class="text-[10px] text-slate-400 font-semibold mb-1 block truncate">
                                En: <span class="text-brand-600 dark:text-brand-400 hover:underline"><?php echo htmlspecialchars($comment->getPost()->title); ?></span>
                            </span>
                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 italic break-words">
                                &ldquo;<?php echo htmlspecialchars($comment->content); ?>&rdquo;
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="p-8 text-center text-slate-400 text-sm">
                    No hay comentarios recientes.
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php require __DIR__ . '/layout/footer.php'; ?>

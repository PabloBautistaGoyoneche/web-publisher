<?php
use App\Helpers;
require __DIR__ . '/layout/header.php';
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
    
    <!-- Detalle del Post (Izquierda) -->
    <div class="lg:col-span-2 space-y-10">
        
        <!-- Migas de Pan (Breadcrumbs) -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400 flex-wrap" aria-label="Breadcrumb">
            <a href="/" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors flex items-center gap-1">
                <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Inicio
            </a>
            <?php 
            $cat = $post->getCategory();
            if ($cat) {
                $breadcrumbs = [];
                if ($cat->parent_id) {
                    $parentCat = \App\Models\Category::find($cat->parent_id);
                    if ($parentCat) {
                        $breadcrumbs[] = $parentCat;
                    }
                }
                $breadcrumbs[] = $cat;

                foreach ($breadcrumbs as $bCat) {
                    echo '<svg style="width: 12px; height: 12px; flex-shrink: 0;" class="text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>';
                    echo '<a href="/' . htmlspecialchars($bCat->slug) . '" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">' . htmlspecialchars($bCat->name) . '</a>';
                }
            }
            ?>
            <svg style="width: 12px; height: 12px; flex-shrink: 0;" class="text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-slate-800 dark:text-slate-200 truncate max-w-[200px]" title="<?php echo htmlspecialchars($post->title); ?>"><?php echo htmlspecialchars($post->title); ?></span>
        </nav>

        <!-- Encabezado del Post -->
        <header class="space-y-4">
            <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-semibold text-white bg-brand-600">
                <?php echo htmlspecialchars($post->getCategory()->name); ?>
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white leading-tight">
                <?php echo htmlspecialchars($post->title); ?>
            </h1>
            
            <!-- Metadatos de publicación -->
            <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800/80 pb-6">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-slate-800 flex items-center justify-center text-xs font-bold text-white uppercase">
                        <?php echo substr($post->getAuthor()->display_name, 0, 1); ?>
                    </span>
                    <span class="font-medium text-slate-700 dark:text-slate-300"><?php echo htmlspecialchars($post->getAuthor()->display_name); ?></span>
                </div>
                <span>&bull;</span>
                <span><?php echo Helpers::formatDate($post->created_at); ?></span>
                <span>&bull;</span>
                <span><?php echo Helpers::readTime($post->content); ?></span>
                <span>&bull;</span>
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <?php echo $post->views_count; ?> vistas
                </span>
            </div>
        </header>

        <!-- Imagen Destacada -->
        <?php if ($post->featured_image): ?>
            <div class="rounded-3xl overflow-hidden shadow-lg aspect-video w-full bg-slate-100 dark:bg-slate-900 border border-slate-200/20">
                <img src="<?php echo Helpers::asset('uploads/' . $post->featured_image); ?>" alt="<?php echo htmlspecialchars($post->title); ?>" class="w-full h-full object-cover">
            </div>
        <?php endif; ?>

        <!-- Contenido del Post (Formateado con Tailwind Typography) -->
        <article class="prose prose-slate dark:prose-invert max-w-none prose-headings:font-extrabold prose-h2:text-2xl prose-a:text-brand-600 dark:prose-a:text-brand-400 hover:prose-a:underline prose-blockquote:border-l-4 prose-blockquote:border-brand-500 prose-blockquote:bg-slate-50 dark:prose-blockquote:bg-slate-900/40 prose-blockquote:px-6 prose-blockquote:py-2 prose-blockquote:rounded-r-xl">
            <?php echo $post->content; ?>
        </article>

        <!-- Caja de Autor (WordPress Author Box) -->
        <div class="glass-card rounded-3xl p-6 border border-slate-100 dark:border-slate-800/80 flex flex-col sm:flex-row items-center sm:items-start gap-6">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-600 to-secondary-600 flex items-center justify-center text-white text-2xl font-bold uppercase shadow-md shadow-brand-500/10 flex-shrink-0">
                <?php echo substr($post->getAuthor()->display_name, 0, 1); ?>
            </div>
            <div class="space-y-2 text-center sm:text-left">
                <h4 class="font-bold text-slate-800 dark:text-slate-200">
                    Escrito por <span class="text-brand-600 dark:text-brand-400"><?php echo htmlspecialchars($post->getAuthor()->display_name); ?></span>
                </h4>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    <?php echo htmlspecialchars($post->getAuthor()->bio ?? 'Autor del blog. Apasionado por compartir conocimiento e inspirar a la comunidad web.'); ?>
                </p>
            </div>
        </div>

        <!-- Sección de Posts Relacionados -->
        <?php if (!empty($relatedPosts)): ?>
            <div class="space-y-6 pt-6 border-t border-slate-100 dark:border-slate-800/80">
                <h3 class="text-xl font-bold tracking-tight text-slate-800 dark:text-slate-100">
                    Te puede interesar
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php foreach($relatedPosts as $rPost): ?>
                        <a href="/?route=post&slug=<?php echo $rPost->slug; ?>" class="glass-card rounded-2xl overflow-hidden p-4 border border-slate-100 dark:border-slate-900/60 block hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                            <div class="aspect-video w-full rounded-xl bg-slate-100 dark:bg-slate-800 overflow-hidden mb-3">
                                <?php if ($rPost->featured_image): ?>
                                    <img src="<?php echo Helpers::asset('uploads/' . $rPost->featured_image); ?>" alt="<?php echo htmlspecialchars($rPost->title); ?>" class="w-full h-full object-cover">
                                <?php endif; ?>
                            </div>
                            <h4 class="font-bold text-sm text-slate-800 dark:text-slate-200 line-clamp-2 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">
                                <?php echo htmlspecialchars($rPost->title); ?>
                            </h4>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Sección de Comentarios -->
        <div class="space-y-8 pt-8 border-t border-slate-100 dark:border-slate-800/80">
            <h3 class="text-xl font-bold tracking-tight text-slate-800 dark:text-slate-100">
                Comentarios (<?php echo count($comments); ?>)
            </h3>

            <!-- Lista de comentarios -->
            <?php if (!empty($comments)): ?>
                <div class="space-y-4">
                    <?php foreach($comments as $comment): ?>
                        <div class="glass-card rounded-2xl p-5 border border-slate-100 dark:border-slate-800/60 flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center justify-center font-bold text-sm uppercase flex-shrink-0">
                                <?php echo substr($comment->author_name, 0, 1); ?>
                            </div>
                            <div class="space-y-2 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-sm text-slate-800 dark:text-slate-200"><?php echo htmlspecialchars($comment->author_name); ?></span>
                                    <span class="text-xs text-slate-400"><?php echo Helpers::formatDate($comment->created_at); ?></span>
                                </div>
                                <p class="text-sm text-slate-600 dark:text-slate-400 break-words">
                                    <?php echo nl2br(htmlspecialchars($comment->content)); ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-sm text-slate-400">No hay comentarios en este artículo aún. ¡Sé el primero en comentar!</p>
            <?php endif; ?>

            <!-- Formulario de comentarios -->
            <div class="glass-card rounded-3xl p-6 border border-slate-100 dark:border-slate-800/80 space-y-6">
                <h4 class="text-lg font-bold text-slate-800 dark:text-slate-100">Deja un comentario</h4>
                
                <?php if ($commentSuccess): ?>
                    <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/60 rounded-xl text-sm font-medium">
                        ¡Comentario publicado con éxito!
                    </div>
                <?php endif; ?>

                <?php if ($commentError): ?>
                    <div class="p-4 bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900/60 rounded-xl text-sm font-medium">
                        <?php echo $commentError; ?>
                    </div>
                <?php endif; ?>

                <form action="/?route=post&slug=<?php echo $post->slug; ?>" method="POST" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Nombre *</label>
                            <input type="text" name="author_name" required class="w-full px-4 py-2.5 text-sm bg-slate-100 dark:bg-slate-950 border border-transparent focus:border-brand-500 rounded-xl focus:outline-none transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Correo electrónico *</label>
                            <input type="email" name="author_email" required class="w-full px-4 py-2.5 text-sm bg-slate-100 dark:bg-slate-950 border border-transparent focus:border-brand-500 rounded-xl focus:outline-none transition-colors">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Comentario *</label>
                        <textarea name="content" rows="5" required class="w-full px-4 py-2.5 text-sm bg-slate-100 dark:bg-slate-950 border border-transparent focus:border-brand-500 rounded-xl focus:outline-none transition-colors"></textarea>
                    </div>
                    
                    <button type="submit" name="submit_comment" class="btn-primary text-sm font-semibold py-3 px-6 rounded-xl">
                        Enviar Comentario
                    </button>
                </form>
            </div>
        </div>

    </div>

    <!-- Barra Lateral (Derecha) -->
    <div class="lg:col-span-1">
        <?php require __DIR__ . '/layout/sidebar.php'; ?>
    </div>

</div>

<?php require __DIR__ . '/layout/footer.php'; ?>

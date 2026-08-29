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
                <div class="flex items-center gap-1">
                    <span>Autor:</span>
                    <a href="/?route=page&slug=sobre-el-autor" class="font-semibold text-brand-600 dark:text-brand-400 hover:underline"><?php echo htmlspecialchars($post->getAuthor()->display_name); ?></a>
                </div>
                <span>&bull;</span>
                <span><?php echo Helpers::formatDate($post->created_at); ?></span>
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


        <!-- Sección de Posts Relacionados -->
        <?php if (!empty($relatedPosts)): ?>
            <div class="space-y-6 pt-10 border-t border-slate-100 dark:border-slate-800/80">
                <h2 class="text-xl font-bold tracking-tight text-slate-800 dark:text-slate-100">
                    Te puede interesar
                </h2>
                
                <style>
                    .related-carousel::-webkit-scrollbar {
                        display: none;
                    }
                    .related-carousel {
                        -ms-overflow-style: none;
                        scrollbar-width: none;
                    }
                    .carousel-card {
                        width: 100%;
                        flex-shrink: 0;
                    }
                    .carousel-wrapper {
                        position: relative;
                        padding-left: 48px;
                        padding-right: 48px;
                    }
                    .carousel-btn-prev {
                        position: absolute;
                        left: 8px;
                        top: 40%;
                        transform: translateY(-50%);
                        z-index: 10;
                        border: none;
                        background: transparent;
                        padding: 6px;
                        cursor: pointer;
                        color: rgb(var(--brand-600));
                    }
                    .carousel-btn-next {
                        position: absolute;
                        right: 8px;
                        top: 40%;
                        transform: translateY(-50%);
                        z-index: 10;
                        border: none;
                        background: transparent;
                        padding: 6px;
                        cursor: pointer;
                        color: rgb(var(--brand-600));
                    }
                    @media (min-width: 768px) {
                        .carousel-card {
                            width: calc((100% - 24px) / 2);
                        }
                    }
                    @media (min-width: 1024px) {
                        .carousel-card {
                            width: calc((100% - 48px) / 3);
                        }
                        .carousel-wrapper {
                            padding-left: 8px;
                            padding-right: 8px;
                        }
                        .carousel-btn-prev {
                            left: -40px;
                        }
                        .carousel-btn-next {
                            right: -40px;
                        }
                    }
                </style>

                <!-- Contenedor relativo del carrusel con flechas laterales responsivas -->
                <div class="carousel-wrapper group">
                    
                    <!-- Contenedor del Carrusel -->
                    <div id="related-carousel" class="related-carousel flex gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth py-2">
                        <?php foreach($relatedPosts as $rPost): ?>
                            <article class="carousel-card glass-card rounded-3xl overflow-hidden card-hover border border-slate-100 dark:border-slate-900/60 flex flex-col justify-between h-auto snap-start">
                                <!-- Miniatura -->
                                <a href="/?route=post&slug=<?php echo $rPost->slug; ?>" class="block aspect-video bg-slate-100 dark:bg-slate-800 overflow-hidden relative">
                                    <?php if ($rPost->featured_image): ?>
                                        <img src="<?php echo Helpers::asset('uploads/' . $rPost->featured_image); ?>" alt="<?php echo htmlspecialchars($rPost->title); ?>" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                    <?php endif; ?>
                                    
                                    <!-- Insignia de categoría -->
                                    <span class="absolute top-4 left-4 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold text-white bg-slate-900/80 backdrop-blur-md">
                                        <?php echo htmlspecialchars($rPost->getCategory()->name); ?>
                                    </span>
                                </a>

                                <!-- Cuerpo del Post -->
                                <div class="p-6 flex flex-col flex-grow justify-between">
                                    <div class="space-y-3">
                                        <!-- Título -->
                                        <h3 class="text-base font-bold text-slate-800 dark:text-slate-100 hover:text-brand-600 dark:hover:text-brand-400 transition-colors leading-snug">
                                            <a href="/?route=post&slug=<?php echo $rPost->slug; ?>">
                                                <?php echo htmlspecialchars($rPost->title); ?>
                                            </a>
                                        </h3>
                                        
                                        <!-- Fecha -->
                                        <span class="text-xs text-slate-400 dark:text-slate-500 font-medium block">
                                            <?php echo Helpers::formatDate($rPost->created_at); ?>
                                        </span>
                                        
                                        <!-- Extracto (3 líneas de texto con line-clamp-3) -->
                                        <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-3">
                                            <?php echo htmlspecialchars($rPost->excerpt); ?>
                                        </p>
                                    </div>

                                    <!-- Meta / Pie de la Tarjeta -->
                                    <div class="flex items-center justify-between text-xs text-slate-400 dark:text-slate-500 font-medium mt-6 pt-4 border-t border-slate-100 dark:border-slate-800/80">
                                        <div class="flex items-center gap-2">
                                            <span class="w-6 h-6 rounded-full bg-brand-100 dark:bg-brand-950 text-brand-700 dark:text-brand-300 flex items-center justify-center font-extrabold text-[10px] uppercase">
                                                <?php echo substr($rPost->getAuthor()->display_name, 0, 1); ?>
                                            </span>
                                            <span><?php echo htmlspecialchars($rPost->getAuthor()->display_name); ?></span>
                                        </div>
                                        <a href="/?route=post&slug=<?php echo $rPost->slug; ?>" class="text-brand-600 dark:text-brand-400 hover:text-brand-700 dark:hover:text-brand-350 font-bold transition-colors inline-flex items-center gap-1">
                                            Ver más
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Flechas de navegación posicionadas a los costados (Sin círculos, color de tema dinámico) -->
                    <?php if (count($relatedPosts) > 1): ?>
                        <!-- Botón Izquierdo (Anterior) -->
                        <button onclick="scrollCarousel(-1)" class="carousel-btn-prev hover:opacity-75 active:scale-90 transition-all duration-200" title="Anterior">
                            <svg style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <!-- Botón Derecho (Siguiente) -->
                        <button onclick="scrollCarousel(1)" class="carousel-btn-next hover:opacity-75 active:scale-90 transition-all duration-200" title="Siguiente">
                            <svg style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    <?php endif; ?>
                </div>

                <script>
                    function scrollCarousel(direction) {
                        const carousel = document.getElementById('related-carousel');
                        const card = carousel.querySelector('article');
                        if (!card) return;
                        const cardWidth = card.offsetWidth + 24; // Ancho de la tarjeta + gap (24px)
                        carousel.scrollBy({
                            left: direction * cardWidth,
                            behavior: 'smooth'
                        });
                    }
                </script>
            </div>
        <?php endif; ?>

        <!-- Sección de Comentarios -->
        <?php if (\App\Models\Setting::get('enable_comments', '1') === '1'): ?>
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
        <?php endif; ?>

    </div>

    <!-- Barra Lateral (Derecha) -->
    <div class="lg:col-span-1">
        <?php require __DIR__ . '/layout/sidebar.php'; ?>
    </div>

</div>

<?php require __DIR__ . '/layout/footer.php'; ?>

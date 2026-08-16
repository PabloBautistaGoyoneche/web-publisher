<?php
use App\Helpers;
require __DIR__ . '/layout/header.php';
?>

<!-- Sección Hero: Post Destacado (WordPress Style) -->
<?php if ($featuredPost): ?>
    <section class="mb-16">
        <a href="/?route=post&slug=<?php echo $featuredPost->slug; ?>" class="group block relative rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
            <!-- Imagen de Fondo -->
            <div class="aspect-video md:aspect-[21/9] w-full bg-slate-200 dark:bg-slate-900 overflow-hidden relative">
                <?php if ($featuredPost->featured_image): ?>
                    <img src="<?php echo Helpers::asset('uploads/' . $featuredPost->featured_image); ?>" alt="<?php echo htmlspecialchars($featuredPost->title); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-[600ms]">
                <?php endif; ?>
                <!-- Gradiente de Oscurecimiento -->
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
            </div>

            <!-- Contenido encima de la imagen -->
            <div class="absolute inset-0 flex flex-col justify-end p-6 sm:p-10 lg:p-12">
                <div class="max-w-3xl space-y-4">
                    
                    <!-- Badge de categoría -->
                    <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-semibold text-white bg-brand-600/90 backdrop-blur shadow-sm">
                        <?php echo htmlspecialchars($featuredPost->getCategory()->name); ?>
                    </span>
                    
                    <!-- Título -->
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white leading-tight tracking-tight group-hover:text-brand-300 transition-colors">
                        <?php echo htmlspecialchars($featuredPost->title); ?>
                    </h1>
                    
                    <!-- Extracto (Hidden on small mobile) -->
                    <p class="hidden sm:block text-slate-200 text-sm sm:text-base font-light line-clamp-2">
                        <?php echo htmlspecialchars($featuredPost->excerpt); ?>
                    </p>
                    
                    <!-- Metadatos -->
                    <div class="flex items-center gap-4 text-xs sm:text-sm text-slate-300 font-medium pt-2">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full bg-slate-800 flex items-center justify-center text-xs font-bold text-white uppercase border border-slate-700">
                                <?php echo substr($featuredPost->getAuthor()->display_name, 0, 1); ?>
                            </span>
                            <span><?php echo htmlspecialchars($featuredPost->getAuthor()->display_name); ?></span>
                        </div>
                        <span>&bull;</span>
                        <span><?php echo Helpers::formatDate($featuredPost->created_at); ?></span>
                        <span>&bull;</span>
                        <span><?php echo Helpers::readTime($featuredPost->content); ?></span>
                    </div>
                </div>
            </div>
        </a>
    </section>
<?php endif; ?>

<!-- Estructura del Blog: Grid de Posts + Sidebar -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
    
    <!-- Lista de Entradas (Izquierda) -->
    <div class="lg:col-span-2 space-y-10">
        
        <h2 class="text-2xl font-bold tracking-tight bg-gradient-to-r from-slate-900 to-slate-700 dark:from-white dark:to-slate-300 bg-clip-text text-transparent mb-6">
            Últimas Publicaciones
        </h2>

        <?php if (!empty($posts)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php foreach($posts as $post): ?>
                    <article class="glass-card rounded-3xl overflow-hidden card-hover border border-slate-100 dark:border-slate-900/60 flex flex-col h-full">
                        
                        <!-- Miniatura -->
                        <a href="/?route=post&slug=<?php echo $post->slug; ?>" class="block aspect-video bg-slate-100 dark:bg-slate-800 overflow-hidden relative">
                            <?php if ($post->featured_image): ?>
                                <img src="<?php echo Helpers::asset('uploads/' . $post->featured_image); ?>" alt="<?php echo htmlspecialchars($post->title); ?>" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            <?php endif; ?>
                            
                            <!-- Insignia flotante de la categoría -->
                            <span class="absolute top-4 left-4 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold text-white bg-slate-900/80 backdrop-blur-md">
                                <?php echo htmlspecialchars($post->getCategory()->name); ?>
                            </span>
                        </a>

                        <!-- Cuerpo del Post -->
                        <div class="p-6 flex flex-col flex-grow justify-between">
                            <div class="space-y-3">
                                
                                <!-- Título -->
                                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 hover:text-brand-600 dark:hover:text-brand-400 transition-colors leading-snug">
                                    <a href="/?route=post&slug=<?php echo $post->slug; ?>">
                                        <?php echo htmlspecialchars($post->title); ?>
                                    </a>
                                </h3>
                                
                                <!-- Extracto -->
                                <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-3">
                                    <?php echo htmlspecialchars($post->excerpt); ?>
                                </p>
                            </div>

                            <!-- Meta / Pie de la Tarjeta -->
                            <div class="flex items-center justify-between text-xs text-slate-400 dark:text-slate-500 font-medium mt-6 pt-4 border-t border-slate-100 dark:border-slate-800/80">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-brand-100 dark:bg-brand-950 text-brand-700 dark:text-brand-300 flex items-center justify-center font-extrabold text-[10px] uppercase">
                                        <?php echo substr($post->getAuthor()->display_name, 0, 1); ?>
                                    </span>
                                    <span><?php echo htmlspecialchars($post->getAuthor()->display_name); ?></span>
                                </div>
                                <span><?php echo Helpers::formatDate($post->created_at); ?></span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="glass-card rounded-3xl p-12 text-center border border-slate-100 dark:border-slate-800">
                <svg class="w-12 h-12 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                <h3 class="text-lg font-bold mb-1 text-slate-700 dark:text-slate-300">No se encontraron artículos</h3>
                <p class="text-sm text-slate-500">Pronto publicaremos nuevo contenido. ¡Vuelve pronto!</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Barra Lateral (Derecha) -->
    <div class="lg:col-span-1">
        <?php require __DIR__ . '/layout/sidebar.php'; ?>
    </div>

</div>

<?php require __DIR__ . '/layout/footer.php'; ?>

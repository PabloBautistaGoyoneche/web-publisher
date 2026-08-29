<?php
use App\Helpers;
require __DIR__ . '/layout/header.php';
?>

<!-- Cabecera de Búsqueda -->
<section class="mb-12">
    <div class="glass-card rounded-3xl p-8 sm:p-10 border border-slate-100 dark:border-slate-800/80">
        <span class="text-xs font-semibold text-brand-600 dark:text-brand-400 uppercase tracking-widest block mb-2">Búsqueda en el Blog</span>
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white mb-3">
            Buscando: &ldquo;<?php echo htmlspecialchars($query); ?>&rdquo;
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">
            Se encontraron <span class="font-bold text-slate-800 dark:text-slate-200"><?php echo count($posts); ?></span> resultados coincidentes.
        </p>
    </div>
</section>

<!-- Grid de Posts + Sidebar -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
    
    <!-- Lista de Resultados (Izquierda) -->
    <div class="lg:col-span-2 space-y-8">
        
        <?php if (!empty($posts)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php foreach($posts as $post): ?>
                    <article class="glass-card rounded-3xl overflow-hidden card-hover border border-slate-100 dark:border-slate-900/60 flex flex-col h-full">
                        
                        <!-- Miniatura -->
                        <a href="/?route=post&slug=<?php echo $post->slug; ?>" class="block aspect-video bg-slate-100 dark:bg-slate-800 overflow-hidden relative">
                            <?php if ($post->featured_image): ?>
                                <img src="<?php echo Helpers::asset('uploads/' . $post->featured_image); ?>" alt="<?php echo htmlspecialchars(!empty($post->image_alt) ? $post->image_alt : $post->title); ?>" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" loading="lazy">
                            <?php endif; ?>
                            
                            <!-- Insignia de Categoría -->
                            <span class="absolute top-4 left-4 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold text-white bg-slate-900/80 backdrop-blur-md">
                                <?php echo htmlspecialchars($post->getCategory()->name); ?>
                            </span>
                        </a>

                        <!-- Cuerpo del Post -->
                        <div class="p-6 flex flex-col flex-grow justify-between">
                            <div class="space-y-3">
                                <!-- Título -->
                                <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 hover:text-brand-600 dark:hover:text-brand-400 transition-colors leading-snug">
                                    <a href="/?route=post&slug=<?php echo $post->slug; ?>">
                                        <?php echo htmlspecialchars($post->title); ?>
                                    </a>
                                </h2>
                                
                                <!-- Fecha -->
                                <span class="text-xs text-slate-400 dark:text-slate-500 font-medium block">
                                    <?php echo Helpers::formatDate($post->created_at); ?>
                                </span>
                                
                                <!-- Extracto -->
                                <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-3">
                                    <?php echo htmlspecialchars($post->excerpt); ?>
                                </p>
                            </div>

                            <!-- Meta -->
                            <div class="flex items-center justify-between text-xs text-slate-400 dark:text-slate-500 font-medium mt-6 pt-4 border-t border-slate-100 dark:border-slate-800/80">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-brand-100 dark:bg-brand-950 text-brand-700 dark:text-brand-300 flex items-center justify-center font-extrabold text-[10px] uppercase">
                                        <?php echo substr($post->getAuthor()->display_name, 0, 1); ?>
                                    </span>
                                    <span><?php echo htmlspecialchars($post->getAuthor()->display_name); ?></span>
                                </div>
                                <a href="/?route=post&slug=<?php echo $post->slug; ?>" class="text-brand-600 dark:text-brand-400 hover:text-brand-700 dark:hover:text-brand-350 font-bold transition-colors inline-flex items-center gap-1">
                                    Ver más
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="glass-card rounded-3xl p-12 text-center border border-slate-100 dark:border-slate-800">
                <svg class="w-12 h-12 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <h3 class="text-lg font-bold mb-1 text-slate-700 dark:text-slate-300">No se encontraron coincidencias</h3>
                <p class="text-sm text-slate-500 mb-6">Prueba a buscar con palabras clave diferentes o explora las categorías principales.</p>
                <a href="/" class="btn-primary py-2 px-5 text-sm rounded-xl inline-flex">
                    Volver al inicio
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Barra Lateral (Derecha) -->
    <div class="lg:col-span-1">
        <?php require __DIR__ . '/layout/sidebar.php'; ?>
    </div>

</div>

<?php require __DIR__ . '/layout/footer.php'; ?>

<?php
use App\Helpers;
require __DIR__ . '/layout/header.php';
?>

<!-- Cabecera de Categoría -->
<section class="mb-12">
    <!-- Migas de Pan (Breadcrumbs) -->
    <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400 flex-wrap mb-4" aria-label="Breadcrumb">
        <a href="/" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors flex items-center gap-1">
            <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Inicio
        </a>
        <?php 
        $breadcrumbs = [];
        if ($category->parent_id) {
            $parentCat = \App\Models\Category::find($category->parent_id);
            if ($parentCat) {
                $breadcrumbs[] = $parentCat;
            }
        }
        $breadcrumbs[] = $category;

        foreach ($breadcrumbs as $index => $bCat) {
            echo '<svg style="width: 12px; height: 12px; flex-shrink: 0;" class="text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>';
            if ($index < count($breadcrumbs) - 1) {
                echo '<a href="/' . htmlspecialchars($bCat->slug) . '" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">' . htmlspecialchars($bCat->name) . '</a>';
            } else {
                echo '<span class="text-slate-800 dark:text-slate-200">' . htmlspecialchars($bCat->name) . '</span>';
            }
        }
        ?>
    </nav>

    <div class="glass-card rounded-3xl p-8 sm:p-10 border border-slate-100 dark:border-slate-800/80">
        <span class="text-xs font-semibold text-brand-600 dark:text-brand-400 uppercase tracking-widest block mb-2">Archivo por Categoría</span>
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white mb-3">
            <?php echo htmlspecialchars($category->name); ?>
        </h1>
        <p class="text-sm sm:text-base text-slate-500 dark:text-slate-400 max-w-2xl font-light">
            <?php echo htmlspecialchars($category->description ?? 'Listado de todas las publicaciones archivadas en esta categoría.'); ?>
        </p>
    </div>
</section>

<!-- Grid de Posts + Sidebar -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
    
    <!-- Lista de Entradas (Izquierda) -->
    <div class="lg:col-span-2 space-y-8">
        
        <?php if (!empty($posts)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php foreach($posts as $post): ?>
                    <article class="glass-card rounded-3xl overflow-hidden card-hover border border-slate-100 dark:border-slate-900/60 flex flex-col h-full">
                        
                        <!-- Miniatura -->
                        <a href="/?route=post&slug=<?php echo $post->slug; ?>" class="block aspect-video bg-slate-100 dark:bg-slate-800 overflow-hidden relative">
                            <?php if ($post->featured_image): ?>
                                <img src="<?php echo Helpers::asset('uploads/' . $post->featured_image); ?>" alt="<?php echo htmlspecialchars($post->title); ?>" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            <?php endif; ?>
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
                <svg class="w-12 h-12 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                <h3 class="text-lg font-bold mb-1 text-slate-700 dark:text-slate-300">No hay artículos en esta categoría</h3>
                <p class="text-sm text-slate-500">Pronto publicaremos nuevo contenido en esta sección.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Barra Lateral (Derecha) -->
    <div class="lg:col-span-1">
        <?php require __DIR__ . '/layout/sidebar.php'; ?>
    </div>

</div>

<?php require __DIR__ . '/layout/footer.php'; ?>

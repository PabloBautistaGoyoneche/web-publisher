<?php
use App\Helpers;
use App\Models\Post;

// Obtener posts recientes para el widget lateral
$recentPosts = Post::latest(4);
?>
<aside class="space-y-8 lg:sticky lg:top-28">
    

    <!-- Widget: Categorías -->
    <div class="glass-card rounded-3xl p-6 border border-slate-100 dark:border-slate-800/80">
        <h2 class="text-lg font-bold mb-4 bg-gradient-to-r from-slate-900 to-slate-700 dark:from-white dark:to-slate-300 bg-clip-text text-transparent">
            Categorías
        </h2>
        <style>
            .category-submenu {
                transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.2s ease-in-out;
                max-height: 0;
                opacity: 0;
                overflow: hidden;
                margin-left: 1.5rem !important;
                padding-left: 0.75rem !important;
                border-left: 1px solid rgba(148, 163, 184, 0.15) !important;
            }
            .category-submenu.open-active {
                max-height: 500px;
                opacity: 1;
            }
            
            /* Clases CSS puras e independientes de compilación */
            .category-parent-row {
                background-color: transparent !important;
                transition: all 0.2s ease !important;
            }
            .category-parent-row:hover {
                background-color: rgba(148, 163, 184, 0.08) !important;
            }
            .category-parent-row.active {
                background-color: rgba(148, 163, 184, 0.12) !important;
            }
            .category-parent-row.active a {
                color: <?php echo $themeLight; ?> !important;
                font-weight: 700 !important;
            }
            
            .category-submenu a {
                background-color: transparent !important;
                transition: all 0.2s ease !important;
                display: flex !important;
                align-items: center !important;
            }
            .category-submenu a:hover {
                background-color: rgba(148, 163, 184, 0.08) !important;
            }
            .category-submenu a.active {
                background-color: rgba(148, 163, 184, 0.12) !important;
                color: <?php echo $themeLight; ?> !important;
                font-weight: 700 !important;
            }
        </style>
        <div class="space-y-2">
            <?php if (isset($categories)): ?>
                <?php 
                $currentCatId = 0;
                if (isset($category) && $category instanceof \App\Models\Category) {
                    $currentCatId = $category->id;
                } elseif (isset($post) && $post instanceof \App\Models\Post) {
                    $currentCatId = $post->category_id;
                }

                $parentCategories = array_filter($categories, function($c) { return $c->parent_id === null; });
                foreach($parentCategories as $cat): 
                    $subcategories = array_filter($categories, function($c) use ($cat) { return $c->parent_id === $cat->id; });
                    
                    // Comprobar si esta categoría o alguna de sus hijas está activa
                    $isCurrentParentOrChildActive = false;
                    if ($currentCatId > 0) {
                        if ($currentCatId === $cat->id) {
                            $isCurrentParentOrChildActive = true;
                        } else {
                            foreach ($subcategories as $sub) {
                                if ($sub->id === $currentCatId) {
                                    $isCurrentParentOrChildActive = true;
                                    break;
                                }
                            }
                        }
                    }
                ?>
                    <!-- Fila de Categoría Padre -->
                    <div class="flex items-center justify-between group/parent rounded-xl transition-all category-parent-row <?php echo $currentCatId === $cat->id ? 'active' : ''; ?>">
                        <!-- Enlace Principal (Sin contador de artículos) -->
                        <a href="/?route=category&slug=<?php echo $cat->slug; ?>" class="flex-grow flex items-center px-3 py-2 text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-300 transition-all">
                            <span class="text-sm font-medium"><?php echo htmlspecialchars($cat->name); ?></span>
                        </a>
                        
                        <!-- Flechita para Acordeón (solo si tiene hijas) -->
                        <?php if (!empty($subcategories)): ?>
                            <button class="category-accordion-toggle p-2 text-slate-400 hover:text-brand-600 dark:hover:text-brand-300 focus:outline-none transition-transform duration-300 mr-1" data-target="sub-<?php echo $cat->id; ?>">
                                <svg class="w-4 h-4 transform transition-transform duration-300 <?php echo $isCurrentParentOrChildActive ? 'rotate-180 text-brand-600' : ''; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Subcategorías Anidadas (Acordeón y Sin contadores de artículos) -->
                    <?php if (!empty($subcategories)): ?>
                        <div id="sub-<?php echo $cat->id; ?>" class="category-submenu space-y-1 my-1 <?php echo $isCurrentParentOrChildActive ? 'open-active' : ''; ?>" style="<?php echo $isCurrentParentOrChildActive ? 'max-height: none; opacity: 1;' : 'max-height: 0px; opacity: 0;'; ?>">
                            <?php foreach($subcategories as $sub): ?>
                                <a href="/?route=category&slug=<?php echo $sub->slug; ?>" class="px-3 py-1.5 rounded-lg text-slate-500 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-300 transition-all <?php echo $currentCatId === $sub->id ? 'active' : ''; ?>">
                                    <span class="text-xs font-medium"><?php echo htmlspecialchars($sub->name); ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Widget: Entradas Recientes -->
    <div class="glass-card rounded-3xl p-6 border border-slate-100 dark:border-slate-800/80">
        <h2 class="text-lg font-bold mb-4 bg-gradient-to-r from-slate-900 to-slate-700 dark:from-white dark:to-slate-300 bg-clip-text text-transparent">
            Artículos Recientes
        </h2>
        <div class="space-y-4">
            <?php foreach($recentPosts as $rPost): ?>
                <a href="/?route=post&slug=<?php echo $rPost->slug; ?>" class="flex gap-3 group">
                    <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 flex-shrink-0">
                            <?php if ($rPost->featured_image): ?>
                                <img src="<?php echo Helpers::asset('uploads/' . $rPost->featured_image); ?>" alt="<?php echo htmlspecialchars(!empty($rPost->image_alt) ? $rPost->image_alt : $rPost->title); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200" loading="lazy">
                            <?php endif; ?>
                    </div>
                    <div class="flex flex-col justify-center min-w-0">
                        <span class="text-xs text-brand-600 dark:text-brand-400 font-semibold mb-0.5">
                            <?php echo htmlspecialchars($rPost->getCategory()->name); ?>
                        </span>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors line-clamp-2 leading-tight">
                            <?php echo htmlspecialchars($rPost->title); ?>
                        </h3>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</aside>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.category-accordion-toggle').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            
            const targetId = button.getAttribute('data-target');
            const target = document.getElementById(targetId);
            const icon = button.querySelector('svg');
            
            if (target.classList.contains('open-active') || target.style.maxHeight === 'none' || parseFloat(target.style.maxHeight) > 0) {
                // Colapsar
                target.style.maxHeight = target.scrollHeight + 'px';
                target.offsetHeight; // Force reflow
                target.style.maxHeight = '0px';
                target.style.opacity = '0';
                target.classList.remove('open-active');
                icon.classList.remove('rotate-180', 'text-brand-600');
            } else {
                // Expandir
                target.style.maxHeight = '0px';
                target.style.opacity = '0';
                target.offsetHeight; // Force reflow
                target.style.maxHeight = target.scrollHeight + 'px';
                target.style.opacity = '1';
                target.classList.add('open-active');
                icon.classList.add('rotate-180', 'text-brand-600');
                
                // Limpiar inline max-height después de terminar la transición para permitir fluidez si cambian los elementos
                setTimeout(() => {
                    if (target.classList.contains('open-active')) {
                        target.style.maxHeight = 'none';
                    }
                }, 300);
            }
        });
    });
});
</script>

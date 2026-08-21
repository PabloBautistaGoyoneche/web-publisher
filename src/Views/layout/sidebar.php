<?php
use App\Helpers;
use App\Models\Post;

// Obtener posts recientes para el widget lateral
$recentPosts = Post::latest(4);
?>
<aside class="space-y-8 lg:sticky lg:top-28">
    

    <!-- Widget: Categorías -->
    <div class="glass-card rounded-3xl p-6 border border-slate-100 dark:border-slate-800/80">
        <h3 class="text-lg font-bold mb-4 bg-gradient-to-r from-slate-900 to-slate-700 dark:from-white dark:to-slate-300 bg-clip-text text-transparent">
            Categorías
        </h3>
        <div class="space-y-2">
            <?php if (isset($categories)): ?>
                <?php foreach($categories as $cat): ?>
                    <?php $count = $cat->getPostCount(); ?>
                    <a href="/?route=category&slug=<?php echo $cat->slug; ?>" class="flex items-center justify-between px-3 py-2 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-brand-600 dark:hover:text-brand-300 transition-all">
                        <span class="text-sm font-medium"><?php echo htmlspecialchars($cat->name); ?></span>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-950 border border-slate-200/40 dark:border-slate-800/80 font-mono">
                            <?php echo $count; ?>
                        </span>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Widget: Entradas Recientes -->
    <div class="glass-card rounded-3xl p-6 border border-slate-100 dark:border-slate-800/80">
        <h3 class="text-lg font-bold mb-4 bg-gradient-to-r from-slate-900 to-slate-700 dark:from-white dark:to-slate-300 bg-clip-text text-transparent">
            Artículos Recientes
        </h3>
        <div class="space-y-4">
            <?php foreach($recentPosts as $rPost): ?>
                <a href="/?route=post&slug=<?php echo $rPost->slug; ?>" class="flex gap-3 group">
                    <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 flex-shrink-0">
                        <?php if ($rPost->featured_image): ?>
                            <img src="<?php echo Helpers::asset('uploads/' . $rPost->featured_image); ?>" alt="<?php echo htmlspecialchars($rPost->title); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                        <?php endif; ?>
                    </div>
                    <div class="flex flex-col justify-center min-w-0">
                        <span class="text-xs text-brand-600 dark:text-brand-400 font-semibold mb-0.5">
                            <?php echo htmlspecialchars($rPost->getCategory()->name); ?>
                        </span>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors line-clamp-2 leading-tight">
                            <?php echo htmlspecialchars($rPost->title); ?>
                        </h4>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</aside>

<?php
use App\Helpers;
?>
    </main>

    <!-- Footer Moderno -->
    <footer class="bg-sitefooter border-t border-slate-200/50 dark:border-slate-800/50 py-12 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                
                <!-- Info del Blog -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-2">
                        <?php $siteName = \App\Models\Setting::get('site_name', 'ModernBlog'); ?>
                        <span class="font-extrabold text-lg tracking-tight text-white">
                            <?php echo htmlspecialchars($siteName); ?>
                        </span>
                    </div>
                    <p class="text-sm text-white/70 max-w-sm">
                        Un espacio moderno e interactivo para compartir artículos técnicos de alta calidad sobre desarrollo web, diseño de interfaces, inteligencia artificial y productividad.
                    </p>
                </div>

                <!-- Enlaces rápidos -->
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Navegación</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="/" class="text-white/60 hover:text-white transition-colors">Inicio</a></li>
                        <li><a href="/?route=search" class="text-white/60 hover:text-white transition-colors">Buscar Artículos</a></li>
                    </ul>
                </div>

                <!-- Legal / Categorías principales -->
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Categorías</h3>
                    <ul class="space-y-2.5 text-sm">
                        <?php if (isset($categories)): ?>
                            <?php foreach(array_slice($categories, 0, 4) as $cat): ?>
                                <li>
                                    <a href="/?route=category&slug=<?php echo $cat->slug; ?>" class="text-white/60 hover:text-white transition-colors">
                                        <?php echo htmlspecialchars($cat->name); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="pt-8 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-white/50">
                <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars(\App\Models\Setting::get('site_name', 'ModernBlog')); ?>. Creado con PHP, MySQL y Tailwind CSS.</p>
                <div class="flex gap-4 items-center">
                    <a href="/?route=page&slug=politica-privacidad" class="hover:text-white transition-colors">Privacidad</a>
                    <a href="/?route=page&slug=terminos-condiciones" class="hover:text-white transition-colors">Términos</a>
                    <a href="/?route=page&slug=contacto" class="hover:text-white transition-colors">Contacto</a>
                    <span class="text-white/20">|</span>
                    <a href="/?route=admin/login" class="text-white/60 hover:text-white transition-colors">CMS</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Banner de Cookies Sutil -->
    <div id="cookie-banner" class="fixed bottom-6 right-6 max-w-sm p-5 rounded-2xl glass-card border border-slate-200/60 dark:border-slate-800/80 shadow-2xl z-50 transform translate-y-12 opacity-0 pointer-events-none transition-all duration-500">
        <div class="space-y-4">
            <div class="flex items-start gap-3">
                <div class="p-2 bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 rounded-xl flex-shrink-0">
                    <!-- Icono Galleta -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                </div>
                <div class="space-y-1 min-w-0">
                    <h4 class="font-bold text-sm text-slate-800 dark:text-slate-200">Consentimiento de Cookies</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        Utilizamos cookies para ofrecerte la mejor experiencia y servir publicidad de Google AdSense. Consulta nuestra <a href="/?route=page&slug=politica-privacidad" class="text-brand-600 dark:text-brand-400 hover:underline">política de privacidad</a>.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3 justify-end">
                <button id="decline-cookies" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/80 active:scale-95 transition-all">Rechazar</button>
                <button id="accept-cookies" class="px-4 py-1.5 rounded-lg text-xs font-semibold text-white bg-brand-600 hover:bg-brand-700 active:scale-95 transition-all shadow-md shadow-brand-500/10">Aceptar</button>
            </div>
        </div>
    </div>

    <!-- Script de Banner de Cookies -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const banner = document.getElementById('cookie-banner');
            const acceptBtn = document.getElementById('accept-cookies');
            const declineBtn = document.getElementById('decline-cookies');

            if (banner && acceptBtn && declineBtn) {
                // Si no ha aceptado o rechazado cookies, mostrar el banner después de 1.5 segundos
                if (localStorage.getItem('cookiesConsent') === null) {
                    setTimeout(() => {
                        banner.classList.remove('translate-y-12', 'opacity-0', 'pointer-events-none');
                    }, 1500);
                }

                acceptBtn.addEventListener('click', () => {
                    localStorage.setItem('cookiesConsent', 'accepted');
                    hideBanner();
                });

                declineBtn.addEventListener('click', () => {
                    localStorage.setItem('cookiesConsent', 'declined');
                    hideBanner();
                });

                function hideBanner() {
                    banner.classList.add('translate-y-12', 'opacity-0', 'pointer-events-none');
                }
            }
        });
    </script>

    <!-- Lógica de control JS en frontend -->
    <script src="<?php echo Helpers::asset('js/main.js'); ?>"></script>
</body>
</html>

<?php
use App\Helpers;
?>
    </main>

    <!-- Sección Call to Action (Descarga de eBook) de Ancho Completo -->
    <?php
    $ctaTitle = \App\Models\Setting::get('cta_ebook_title', 'Descarga nuestro eBook Gratuito');
    $ctaText = \App\Models\Setting::get('cta_ebook_desc', 'Aprende los fundamentos del desarrollo web moderno con nuestra guía completa.');
    $ctaButton = \App\Models\Setting::get('cta_ebook_button', 'Descargar eBook');
    $ctaLink = \App\Models\Setting::get('cta_ebook_link', '#');
    
    $downloadUrl = $ctaLink;
    if (!empty($ctaLink) && $ctaLink !== '#' && !str_starts_with($ctaLink, 'http://') && !str_starts_with($ctaLink, 'https://') && !str_starts_with($ctaLink, '/')) {
        $downloadUrl = '/uploads/' . $ctaLink;
    }
    ?>
    <section class="w-full bg-slate-50/50 dark:bg-slate-900/30 border-t border-slate-100 dark:border-slate-800/50 py-16 transition-colors duration-300">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <div class="inline-flex items-center justify-center p-3 bg-brand-50 dark:bg-brand-950/40 text-brand-600 dark:text-brand-400 rounded-2xl mb-2">
                <!-- Icono de eBook (Libro abierto) -->
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-800 dark:text-white leading-tight">
                <?php echo htmlspecialchars($ctaTitle); ?>
            </h2>
            
            <p class="text-base text-slate-500 dark:text-slate-400 max-w-2xl mx-auto leading-relaxed">
                <?php echo htmlspecialchars($ctaText); ?>
            </p>
            
            <div class="pt-2">
                <a href="<?php echo htmlspecialchars($downloadUrl); ?>" target="_blank" class="inline-flex items-center gap-2.5 btn-primary py-3.5 px-8 text-sm font-semibold rounded-2xl shadow-md active:scale-95 transition-all">
                    <!-- Icono de descarga -->
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <?php echo htmlspecialchars($ctaButton); ?>
                </a>
            </div>
        </div>
    </section>

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

    <!-- Ventana Modal de eBook (Pop-up) -->
    <?php
    $ctaTitleModal = \App\Models\Setting::get('cta_ebook_title', 'Descarga nuestro eBook Gratuito');
    $ctaTextModal = \App\Models\Setting::get('cta_ebook_desc', 'Aprende los fundamentos del desarrollo web moderno con nuestra guía completa.');
    $ctaButtonModal = \App\Models\Setting::get('cta_ebook_button', 'Descargar eBook');
    $ctaLinkModal = \App\Models\Setting::get('cta_ebook_link', '#');
    $ctaDelayModal = (int)\App\Models\Setting::get('cta_ebook_delay', '5');

    $downloadUrlModal = $ctaLinkModal;
    if (!empty($ctaLinkModal) && $ctaLinkModal !== '#' && !str_starts_with($ctaLinkModal, 'http://') && !str_starts_with($ctaLinkModal, 'https://') && !str_starts_with($ctaLinkModal, '/')) {
        $downloadUrlModal = '/uploads/' . $ctaLinkModal;
    }
    ?>
    <div id="ebook-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300">
        <!-- Contenido de la Ventana Modal -->
        <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/60 dark:border-slate-800 shadow-2xl p-8 text-center space-y-6 transform scale-90 opacity-0 transition-all duration-300 ease-out" id="ebook-modal-content">
            <!-- Botón Cerrar (X) -->
            <button onclick="closeEbookModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-650 dark:hover:text-slate-200 p-1.5 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800/80 transition-colors" title="Cerrar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <!-- Icono de eBook (Libro abierto) -->
            <div class="inline-flex items-center justify-center p-4 bg-brand-50 dark:bg-brand-950/40 text-brand-600 dark:text-brand-400 rounded-2xl mb-2">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>

            <!-- Títulos -->
            <div class="space-y-2">
                <h3 class="text-2xl font-extrabold tracking-tight text-slate-800 dark:text-white leading-tight">
                    <?php echo htmlspecialchars($ctaTitleModal); ?>
                </h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                    <?php echo htmlspecialchars($ctaTextModal); ?>
                </p>
            </div>

            <!-- Botón de Descarga -->
            <div class="pt-2">
                <a href="<?php echo htmlspecialchars($downloadUrlModal); ?>" target="_blank" onclick="closeEbookModal()" class="inline-flex items-center justify-center gap-2.5 btn-primary py-3.5 px-8 text-sm font-semibold rounded-2xl shadow-md active:scale-95 transition-all w-full sm:w-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <?php echo htmlspecialchars($ctaButtonModal); ?>
                </a>
            </div>
        </div>
    </div>

    <!-- Script de Ventana Modal de eBook -->
    <script>
        function openEbookModal() {
            const modal = document.getElementById('ebook-modal');
            const content = document.getElementById('ebook-modal-content');
            if (modal && content) {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                setTimeout(() => {
                    content.classList.remove('scale-90', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 50);
            }
        }

        function closeEbookModal() {
            const modal = document.getElementById('ebook-modal');
            const content = document.getElementById('ebook-modal-content');
            if (modal && content) {
                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-90', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('opacity-0', 'pointer-events-none');
                }, 300);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Mostrar la ventana modal tras el retraso configurado, solo una vez por sesión
            if (sessionStorage.getItem('cta_ebook_modal_shown') === null) {
                const delayMs = <?php echo $ctaDelayModal; ?> * 1000;
                setTimeout(() => {
                    openEbookModal();
                    sessionStorage.setItem('cta_ebook_modal_shown', 'true');
                }, delayMs);
            }
        });
    </script>

    <!-- Lógica de control JS en frontend -->
    <script src="<?php echo Helpers::asset('js/main.js'); ?>"></script>
</body>
</html>

<?php
use App\Helpers;
?>
    </main>

    <!-- Sección Call to Action (Descarga de eBook) de Ancho Completo -->
    <?php
    $activeCta = \App\Models\Cta::getActive();
    if ($activeCta):
        $ctaTitle = $activeCta->title;
        $ctaText = $activeCta->description;
        $ctaButton = $activeCta->button_text;
        $ctaLink = $activeCta->link;
        
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
            
            <div class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-800 dark:text-white leading-tight">
                <?php echo htmlspecialchars($ctaTitle); ?>
            </div>
            
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
    <?php endif; ?>

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
                    <div class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Navegación</div>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="/" class="text-white/60 hover:text-white transition-colors">Inicio</a></li>
                        <li><a href="/?route=search" class="text-white/60 hover:text-white transition-colors">Buscar Artículos</a></li>
                    </ul>
                </div>

                <!-- Redes Sociales -->
                <div>
                    <div class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Síguenos</div>
                    <?php
                    $socials = [
                        'facebook' => [
                            'url' => \App\Models\Setting::get('social_facebook', ''),
                            'name' => 'Facebook',
                            'svg' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c4.56-.93 8-4.96 8-9.75z"/></svg>'
                        ],
                        'instagram' => [
                            'url' => \App\Models\Setting::get('social_instagram', ''),
                            'name' => 'Instagram',
                            'svg' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>'
                        ],
                        'twitter' => [
                            'url' => \App\Models\Setting::get('social_twitter', ''),
                            'name' => 'Twitter / X',
                            'svg' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>'
                        ],
                        'linkedin' => [
                            'url' => \App\Models\Setting::get('social_linkedin', ''),
                            'name' => 'LinkedIn',
                            'svg' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>'
                        ],
                        'youtube' => [
                            'url' => \App\Models\Setting::get('social_youtube', ''),
                            'name' => 'YouTube',
                            'svg' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.163a3.003 3.003 0 00-2.11-2.11C19.518 3.545 12 3.545 12 3.545s-7.519 0-9.388.508a3.003 3.003 0 00-2.11 2.11C0 8.033 0 12 0 12s0 3.967.502 5.837a3.003 3.003 0 002.11 2.11c1.87.507 9.388.507 9.388.507s7.518 0 9.388-.507a3.003 3.003 0 002.11-2.11C24 15.967 24 12 24 12s0-3.967-.502-5.837zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>'
                        ],
                        'github' => [
                            'url' => \App\Models\Setting::get('social_github', ''),
                            'name' => 'GitHub',
                            'svg' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.167 6.839 9.49.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.464-1.11-1.464-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.831.092-.646.35-1.086.636-1.336-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.579.688.481C19.137 20.164 22 16.418 22 12c0-5.523-4.477-10-10-10z"/></svg>'
                        ]
                    ];
                    $hasSocials = false;
                    ?>
                    <div class="flex flex-wrap gap-3">
                        <?php foreach ($socials as $key => $social): ?>
                            <?php if (!empty($social['url'])): ?>
                                <?php $hasSocials = true; ?>
                                <a href="<?php echo htmlspecialchars($social['url']); ?>" target="_blank" rel="noopener noreferrer" 
                                   class="w-10 h-10 rounded-full bg-white/10 text-white/70 hover:text-white hover:bg-brand-600 dark:bg-white/5 dark:hover:bg-brand-500 flex items-center justify-center transition-all duration-300 hover:scale-110" 
                                   title="<?php echo htmlspecialchars($social['name']); ?>">
                                    <?php echo $social['svg']; ?>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if (!$hasSocials): ?>
                            <span class="text-xs text-white/50 italic">No se han configurado redes sociales.</span>
                        <?php endif; ?>
                    </div>
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
                    <div class="font-bold text-sm text-slate-800 dark:text-slate-200">Consentimiento de Cookies</div>
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
    if ($activeCta):
        $ctaTitleModal = $activeCta->title;
        $ctaTextModal = $activeCta->description;
        $ctaButtonModal = $activeCta->button_text;
        $ctaLinkModal = $activeCta->link;
        $ctaDelayModal = $activeCta->delay;

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
                <div class="text-2xl font-extrabold tracking-tight text-slate-800 dark:text-white leading-tight">
                    <?php echo htmlspecialchars($ctaTitleModal); ?>
                </div>
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
    <?php endif; ?>

    <!-- Lógica de control JS en frontend -->
    <script src="<?php echo Helpers::asset('js/main.js'); ?>"></script>
</body>
</html>

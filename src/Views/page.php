<?php
use App\Helpers;
require __DIR__ . '/layout/header.php';
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
    
    <!-- Detalle de la Página Estática (Izquierda) -->
    <div class="lg:col-span-2 space-y-10">
        
        <!-- Enlace de regreso -->
        <a href="/" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-600 dark:text-brand-400 hover:text-brand-700 dark:hover:text-brand-300 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver al inicio
        </a>

        <!-- Encabezado de la Página -->
        <header class="border-b border-slate-100 dark:border-slate-800/80 pb-6">
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white leading-tight">
                <?php echo htmlspecialchars($page->title); ?>
            </h1>
            <p class="text-xs text-slate-400 mt-2 font-medium">
                Última actualización: <?php echo Helpers::formatDate($page->updated_at); ?>
            </p>
        </header>

        <!-- Contenido de la Página (Formateado con Tailwind Typography) -->
        <article class="prose prose-slate dark:prose-invert max-w-none prose-headings:font-extrabold prose-a:text-brand-600 dark:prose-a:text-brand-400 hover:prose-a:underline">
            <?php echo $page->content; ?>
        </article>

        <!-- Formulario de Contacto (Se renderiza dinámicamente si el slug es 'contacto') -->
        <?php if ($page->slug === 'contacto'): ?>
            <div class="glass-card rounded-3xl p-6 sm:p-8 border border-slate-200/50 dark:border-slate-800/80 mt-10 space-y-6">
                <h2 class="text-xl font-bold text-slate-800 dark:text-white">Envíanos un mensaje</h2>

                <!-- Alertas de Éxito / Error -->
                <?php if (isset($contactSuccess) && $contactSuccess): ?>
                    <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/60 rounded-xl text-sm font-medium">
                        ¡Mensaje enviado con éxito! Nos pondremos en contacto contigo lo antes posible.
                    </div>
                <?php endif; ?>

                <?php if (isset($contactError) && $contactError): ?>
                    <div class="p-4 bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900/60 rounded-xl text-sm font-medium">
                        <?php echo $contactError; ?>
                    </div>
                <?php endif; ?>

                <form action="/?route=page&slug=contacto" method="POST" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nombre Completo *</label>
                            <input type="text" name="name" required class="w-full px-4 py-2.5 text-sm bg-slate-100 dark:bg-slate-950 border border-transparent focus:border-brand-500 rounded-xl focus:outline-none transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Correo Electrónico *</label>
                            <input type="email" name="email" required class="w-full px-4 py-2.5 text-sm bg-slate-100 dark:bg-slate-950 border border-transparent focus:border-brand-500 rounded-xl focus:outline-none transition-colors">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Asunto *</label>
                        <input type="text" name="subject" required class="w-full px-4 py-2.5 text-sm bg-slate-100 dark:bg-slate-950 border border-transparent focus:border-brand-500 rounded-xl focus:outline-none transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Mensaje *</label>
                        <textarea name="message" rows="6" required class="w-full px-4 py-2.5 text-sm bg-slate-100 dark:bg-slate-950 border border-transparent focus:border-brand-500 rounded-xl focus:outline-none transition-colors"></textarea>
                    </div>
                    
                    <button type="submit" name="submit_contact" class="btn-primary text-sm font-semibold py-3 px-6 rounded-xl">
                        Enviar Consulta
                    </button>
                </form>
            </div>
        <?php endif; ?>

    </div>

    <!-- Barra Lateral (Derecha) -->
    <div class="lg:col-span-1">
        <?php require __DIR__ . '/layout/sidebar.php'; ?>
    </div>

</div>

<?php require __DIR__ . '/layout/footer.php'; ?>

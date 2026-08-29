<?php
use App\Helpers;
require __DIR__ . '/layout/header.php';
?>

<div class="flex flex-col items-center justify-center py-16 px-4">
    <div class="glass-card max-w-lg w-full rounded-3xl p-8 border border-slate-200/50 dark:border-slate-800/80 shadow-2xl text-center space-y-6">
        
        <div class="space-y-2">
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white leading-tight">
                ¡Gracias por escribirnos!
            </h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">
                Tu mensaje ha sido recibido con éxito en nuestro sistema.
            </p>
        </div>

        <div class="p-4 bg-brand-50/50 dark:bg-brand-950/20 border border-brand-100/50 dark:border-brand-900/40 rounded-2xl text-xs text-brand-600 dark:text-brand-400 font-semibold leading-relaxed">
            Nos pondremos en contacto contigo a la brevedad a la dirección de correo proporcionada.
        </div>

        <!-- Botón para ir al Inicio -->
        <div class="pt-4">
            <a href="/" class="btn-primary inline-flex items-center gap-2 text-sm font-bold py-3.5 px-8 rounded-2xl transition-all shadow-md hover:shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Volver al Inicio
            </a>
        </div>
        
    </div>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>

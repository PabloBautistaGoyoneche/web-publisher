<?php
use App\Helpers;
require __DIR__ . '/layout/header.php';
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
    
    <!-- Contenido 404 (Izquierda) -->
    <div class="lg:col-span-2 flex items-center justify-center min-h-[50vh]">
        <div class="text-center space-y-6 max-w-md">
            <span class="text-8xl font-black bg-gradient-to-tr from-brand-600 to-indigo-600 bg-clip-text text-transparent block select-none">
                404
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 dark:text-slate-100">
                Página No Encontrada
            </h1>
            <p class="text-sm sm:text-base text-slate-500 dark:text-slate-400">
                El artículo, categoría o sección que estás buscando no existe o fue movido temporalmente.
            </p>
            <div class="pt-4">
                <a href="/" class="btn-primary py-3 px-6 rounded-xl inline-flex text-sm">
                    Ir al Inicio
                </a>
            </div>
        </div>
    </div>

    <!-- Barra Lateral (Derecha) -->
    <div class="lg:col-span-1">
        <?php require __DIR__ . '/layout/sidebar.php'; ?>
    </div>

</div>

<?php require __DIR__ . '/layout/footer.php'; ?>

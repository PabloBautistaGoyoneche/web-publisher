<?php
$title = "Actualizando Sistema - Admin Panel";
require __DIR__ . '/layout/header.php';
?>

<div class="max-w-2xl mx-auto py-12">
    <!-- Card Principal de Glassmorphism -->
    <div class="glass-card rounded-3xl p-8 border border-slate-200/50 dark:border-slate-800/80 shadow-xl space-y-8">
        
        <div class="text-center space-y-2">
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Actualización del Sistema</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Descargando y aplicando los últimos cambios desde GitHub de forma segura.</p>
        </div>

        <!-- Barra de Progreso Visual -->
        <div class="space-y-3">
            <div class="flex justify-between items-center text-xs font-bold uppercase tracking-wider text-slate-450 dark:text-slate-400">
                <span id="update-status">Inicializando...</span>
                <span id="update-percent">0%</span>
            </div>
            <div class="w-full bg-slate-200 dark:bg-slate-800 h-3 rounded-full overflow-hidden">
                <div id="progress-bar" class="bg-gradient-to-r from-brand-600 to-secondary-600 h-full w-0 transition-all duration-500 ease-out"></div>
            </div>
        </div>

        <!-- Consola de Estado / Log -->
        <div class="bg-slate-900 dark:bg-black/45 rounded-2xl p-5 border border-slate-800 dark:border-slate-850 font-mono text-[11px] text-slate-300 space-y-2 max-h-48 overflow-y-auto" id="log-console">
            <div class="text-emerald-400 font-bold">[INFO] Iniciando proceso de actualización automática...</div>
        </div>

        <!-- Botones finalización (ocultos inicialmente) -->
        <div id="finish-actions" class="hidden pt-4 flex justify-center">
            <a href="/?route=admin/dashboard" class="btn-primary py-3 px-6 text-sm rounded-xl flex items-center gap-2">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                Volver al Panel de Control
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusText = document.getElementById('update-status');
    const percentText = document.getElementById('update-percent');
    const progressBar = document.getElementById('progress-bar');
    const consoleLog = document.getElementById('log-console');
    const finishActions = document.getElementById('finish-actions');

    function log(message, type = 'info') {
        const div = document.createElement('div');
        if (type === 'error') {
            div.className = 'text-red-400 font-bold';
            div.textContent = `[ERROR] ${message}`;
        } else if (type === 'success') {
            div.className = 'text-emerald-400 font-bold';
            div.textContent = `[SUCCESS] ${message}`;
        } else {
            div.className = 'text-slate-300';
            div.textContent = `[INFO] ${message}`;
        }
        consoleLog.appendChild(div);
        consoleLog.scrollTop = consoleLog.scrollHeight;
    }

    function setProgress(percent, status) {
        progressBar.style.width = `${percent}%`;
        percentText.textContent = `${percent}%`;
        statusText.textContent = status;
    }

    // Pipeline de pasos AJAX
    async function runStep(action) {
        const response = await fetch(`/?route=admin/update/api&action=${action}`);
        if (!response.ok) {
            throw new Error(`Error en el servidor: HTTP ${response.status}`);
        }
        const data = await response.json();
        if (!data.success) {
            throw new Error(data.message || 'Error desconocido en el paso de actualización.');
        }
        return data;
    }

    async function startUpdate() {
        try {
            // Paso 1: Descargar
            setProgress(15, 'Descargando paquete de GitHub...');
            log('Iniciando descarga de los archivos actualizados desde GitHub...');
            let res = await runStep('download');
            log(`Paquete descargado con éxito. Commit: ${res.commit_short_sha}`, 'success');

            // Paso 2: Extraer
            setProgress(45, 'Extrayendo archivos...');
            log('Descomprimiendo archivos temporales de actualización...');
            await runStep('extract');
            log('Archivos extraídos y validados.', 'success');

            // Paso 3: Instalar y migrar
            setProgress(75, 'Instalando archivos y ejecutando migraciones...');
            log('Copiando archivos al sitio de producción (omitiendo configuraciones locales)...');
            res = await runStep('install');
            if (res.migrations_count > 0) {
                log(`Se ejecutaron ${res.migrations_count} migraciones SQL pendientes.`, 'success');
            } else {
                log('No se requirieron migraciones SQL adicionales.');
            }

            // Paso 4: Finalizar
            setProgress(100, '¡Actualización completada!');
            log('Limpiando archivos temporales del servidor...');
            await runStep('cleanup');
            log('¡El sistema ha sido actualizado y reiniciado con éxito!', 'success');

            finishActions.classList.remove('hidden');
        } catch (err) {
            setProgress(100, 'Error en la actualización');
            log(err.message, 'error');
            log('Proceso abortado. Por favor, revisa tus permisos de servidor.', 'error');
            
            // Mostrar botón para volver aunque falle
            finishActions.innerHTML = `
                <a href="/?route=admin/dashboard" class="btn-secondary py-3 px-6 text-sm rounded-xl">
                    Volver al Dashboard
                </a>
            `;
            finishActions.classList.remove('hidden');
        }
    }

    // Iniciar automáticamente tras 1 segundo
    setTimeout(startUpdate, 1000);
});
</script>

<?php require __DIR__ . '/layout/footer.php'; ?>

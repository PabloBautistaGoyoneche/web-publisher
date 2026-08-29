<?php
$title = "Actualizando Sistema - Admin Panel";
require __DIR__ . '/layout/header.php';
?>

<div class="max-w-2xl mx-auto py-12">
    <!-- Card Principal de Glassmorphism -->
    <div class="glass-card rounded-3xl p-8 border border-slate-200/50 dark:border-slate-800/80 shadow-xl space-y-8">
        
        <div class="text-center space-y-2">
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Actualización del Sistema</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Controla y aplica de manera segura las actualizaciones desde tu repositorio de GitHub.</p>
        </div>

        <!-- Vista Previa / Control de Usuario (Antes de actualizar) -->
        <div id="update-summary-panel" class="space-y-6">
            <div class="p-6 bg-slate-100/50 dark:bg-slate-900/30 rounded-2xl border border-slate-200/50 dark:border-slate-800/80 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Estado del Sistema</span>
                    <?php if ($updateAvailable): ?>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 text-amber-500 border border-amber-500/20 animate-pulse">
                            Actualización Disponible
                        </span>
                    <?php else: ?>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                            Sitio al día
                        </span>
                    <?php endif; ?>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Versión Instalada (Commit)</span>
                        <code class="px-2 py-1 bg-slate-200 dark:bg-slate-800 rounded-lg font-mono text-xs text-brand-600 dark:text-brand-400 break-all inline-block">
                            <?php echo htmlspecialchars(substr($currentCommit ?? 'initial', 0, 10)); ?>
                        </code>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Último en GitHub (main)</span>
                        <code class="px-2 py-1 bg-slate-200 dark:bg-slate-800 rounded-lg font-mono text-xs text-slate-600 dark:text-slate-350 break-all inline-block">
                            <?php echo htmlspecialchars(substr($latestCommit ?? 'Desconocido', 0, 10)); ?>
                        </code>
                    </div>
                </div>

                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed pt-2">
                    Al iniciar la actualización, el sistema descargará el código fuente desde GitHub y aplicará las migraciones de base de datos de manera fluida. Tus credenciales locales y archivos subidos no se verán afectados.
                </p>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="/?route=admin/dashboard" class="btn-secondary py-3 px-6 text-sm rounded-xl">Cancelar</a>
                
                <?php if ($updateAvailable): ?>
                    <button id="btn-start-update" class="btn-primary py-3 px-6 text-sm rounded-xl">
                        Actualizar Ahora
                    </button>
                <?php else: ?>
                    <button id="btn-start-update" class="bg-slate-800 hover:bg-slate-750 text-white font-bold py-3 px-6 text-sm rounded-xl transition-all font-semibold">
                        Reinstalar Versión Actual
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Pantalla de Progreso (Oculta al inicio, se muestra al hacer clic en Actualizar) -->
        <div id="update-progress-panel" class="hidden space-y-6">
            <!-- Barra de Progreso Visual -->
            <div class="space-y-3">
                <div class="flex justify-between items-center text-xs font-bold uppercase tracking-wider text-slate-450 dark:text-slate-400">
                    <span id="update-status font-bold text-slate-700 dark:text-slate-200">Inicializando...</span>
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
        </div>

        <!-- Botones finalización (ocultos inicialmente) -->
        <div id="finish-actions" class="hidden pt-4 flex justify-center">
            <a href="/?route=admin/dashboard" class="btn-primary py-3 px-6 text-sm rounded-xl font-semibold">
                Volver al Panel de Control
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const summaryPanel = document.getElementById('update-summary-panel');
    const progressPanel = document.getElementById('update-progress-panel');
    const btnStartUpdate = document.getElementById('btn-start-update');
    
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
        if (statusText) statusText.textContent = status;
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
        // Mostrar panel de progreso e iniciar
        summaryPanel.classList.add('hidden');
        progressPanel.classList.remove('hidden');
        
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

    if (btnStartUpdate) {
        btnStartUpdate.addEventListener('click', startUpdate);
    }
});
</script>

<?php require __DIR__ . '/layout/footer.php'; ?>

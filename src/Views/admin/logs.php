<?php
require __DIR__ . '/layout/header.php';
?>

<!-- Sección: Cabecera -->
<div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">Bitácora de Errores</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            Monitorea excepciones, advertencias y logs de la aplicación.
        </p>
    </div>
    
    <?php if (count($logs) > 0): ?>
        <a href="/?route=admin/logs/clear" 
           onclick="return confirm('¿Estás seguro de que deseas vaciar todo el historial de errores?');" 
           class="bg-red-650 hover:bg-red-700 text-white py-2.5 px-5 text-sm font-semibold rounded-xl inline-flex items-center gap-2 transition-all shadow-md shadow-red-950/10">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            Vaciar Bitácora
        </a>
    <?php endif; ?>
</div>

<!-- Alertas de Sesión -->
<?php if (isset($_SESSION['logs_success'])): ?>
    <div class="mb-6 p-4 bg-emerald-950/40 border border-emerald-900/50 text-emerald-400 text-xs font-medium rounded-2xl flex items-center gap-3">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
        <span><?php echo htmlspecialchars($_SESSION['logs_success']); ?></span>
    </div>
    <?php unset($_SESSION['logs_success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['logs_error'])): ?>
    <div class="mb-6 p-4 bg-red-950/40 border border-red-900/50 text-red-400 text-xs font-medium rounded-2xl flex items-center gap-3">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <span><?php echo htmlspecialchars($_SESSION['logs_error']); ?></span>
    </div>
    <?php unset($_SESSION['logs_error']); ?>
<?php endif; ?>

<!-- Contenedor Principal -->
<div class="glass-card rounded-3xl border border-slate-200/50 dark:border-slate-800/80 shadow-md overflow-hidden">
    <?php if (count($logs) === 0): ?>
        <div class="p-12 text-center space-y-4">
            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-900 text-slate-400 dark:text-slate-600 rounded-full flex items-center justify-center mx-auto">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="space-y-1">
                <h3 class="text-base font-bold text-slate-850 dark:text-white">Bitácora Vacía</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xs mx-auto leading-relaxed">No se han registrado errores o advertencias en el sistema. ¡Todo funciona a la perfección!</p>
            </div>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/40 border-b border-slate-200/60 dark:border-slate-800/80 text-slate-400 uppercase tracking-wider font-semibold">
                        <th class="px-6 py-4 w-24">Nivel</th>
                        <th class="px-6 py-4">Mensaje</th>
                        <th class="px-6 py-4">Archivo / Línea</th>
                        <th class="px-6 py-4 w-40">Fecha</th>
                        <th class="px-6 py-4 w-28 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 font-medium">
                    <?php foreach ($logs as $log): ?>
                        <?php 
                        $levelClass = 'bg-blue-500/10 text-blue-500';
                        if ($log['level'] === 'error') {
                            $levelClass = 'bg-red-500/10 text-red-500';
                        } elseif ($log['level'] === 'warning') {
                            $levelClass = 'bg-amber-500/10 text-amber-500';
                        }
                        ?>
                        <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-900/20 transition-colors">
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider <?php echo $levelClass; ?>">
                                    <?php echo htmlspecialchars($log['level']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-800 dark:text-slate-200 break-words max-w-md">
                                <?php echo htmlspecialchars($log['message']); ?>
                            </td>
                            <td class="px-6 py-4 text-slate-500 dark:text-slate-400 font-mono text-[10px]">
                                <?php if ($log['file']): ?>
                                    <?php echo htmlspecialchars(basename($log['file'])); ?>
                                    <span class="text-brand-500 font-bold">:<?php echo $log['line']; ?></span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-slate-400 dark:text-slate-500">
                                <?php echo date('d/m/Y H:i:s', strtotime($log['created_at'])); ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <?php if ($log['trace'] || $log['file']): ?>
                                    <button onclick="toggleTrace(<?php echo $log['id']; ?>)" 
                                            class="text-brand-600 dark:text-brand-400 font-bold hover:underline select-none">
                                        Detalles
                                    </button>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                        
                        <!-- Fila de Detalles Expandible (Stack Trace) -->
                        <?php if ($log['trace'] || $log['file']): ?>
                            <tr id="trace-row-<?php echo $log['id']; ?>" class="hidden bg-slate-900 dark:bg-black/60 border-y border-slate-850">
                                <td colspan="5" class="p-6">
                                    <div class="space-y-3 font-mono text-[10px] text-slate-300">
                                        <?php if ($log['file']): ?>
                                            <div>
                                                <span class="text-red-400 font-bold">Ruta completa del archivo:</span>
                                                <code class="text-slate-200"><?php echo htmlspecialchars($log['file']); ?>:<?php echo $log['line']; ?></code>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($log['trace']): ?>
                                            <div class="space-y-1">
                                                <span class="text-red-400 font-bold">Pila de ejecución (Stack Trace):</span>
                                                <pre class="bg-slate-950 p-4 rounded-xl border border-slate-800 text-slate-350 max-h-60 overflow-y-auto whitespace-pre-wrap word-break-all"><?php echo htmlspecialchars($log['trace']); ?></pre>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
function toggleTrace(id) {
    const row = document.getElementById(`trace-row-${id}`);
    if (row.classList.contains('hidden')) {
        row.classList.remove('hidden');
    } else {
        row.classList.add('hidden');
    }
}
</script>

<?php require __DIR__ . '/layout/footer.php'; ?>

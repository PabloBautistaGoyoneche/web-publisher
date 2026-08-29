<?php
use App\Helpers;
require __DIR__ . '/../layout/header.php';
?>

<!-- Cabecera de Página -->
<div class="mb-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
            Bandeja de Mensajes de Contacto
        </h1>
        <p class="text-sm text-slate-500 mt-1">
            Lee y administra las consultas enviadas por los usuarios desde la página de contacto de tu blog.
        </p>
    </div>
    <?php if (!empty($messages)): ?>
        <div class="flex-shrink-0">
            <a href="/?route=admin/messages/export" class="btn-primary text-xs font-bold py-3.5 px-5 rounded-xl flex items-center gap-2 shadow-sm hover:shadow-md transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Exportar a Excel (.csv)
            </a>
        </div>
    <?php endif; ?>
</div>

<!-- Tabla de Mensajes Recibidos -->
<div class="glass-card rounded-3xl border border-slate-200/50 dark:border-slate-800/80 overflow-hidden shadow-sm">
    <div class="overflow-x-auto w-full">
        <?php if (!empty($messages)): ?>
            <table class="w-full text-left text-sm border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-900/40 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-200/50 dark:border-slate-800/50">
                        <th class="px-6 py-4">Remitente</th>
                        <th class="px-6 py-4">Asunto</th>
                        <th class="px-6 py-4">Mensaje</th>
                        <th class="px-6 py-4">Recibido el</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    <?php foreach($messages as $msg): ?>
                        <tr class="hover:bg-slate-100/30 dark:hover:bg-slate-900/30 transition-colors">
                            
                            <!-- Remitente -->
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800 dark:text-slate-100">
                                    <?php echo htmlspecialchars($msg->name); ?>
                                </div>
                                <a href="mailto:<?php echo htmlspecialchars($msg->email); ?>" class="text-xs text-brand-600 dark:text-brand-400 hover:underline font-mono">
                                    <?php echo htmlspecialchars($msg->email); ?>
                                </a>
                            </td>

                            <!-- Asunto -->
                            <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-200">
                                <?php echo htmlspecialchars($msg->subject); ?>
                            </td>

                            <!-- Cuerpo del Mensaje (Truncado en la tabla) -->
                            <td class="px-6 py-4 max-w-xs">
                                <p class="text-slate-500 dark:text-slate-400 truncate text-xs">
                                    <?php echo htmlspecialchars(strip_tags($msg->message)); ?>
                                </p>
                            </td>

                            <!-- Fecha -->
                            <td class="px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                <?php echo Helpers::formatDate($msg->created_at); ?>
                            </td>

                            <!-- Acciones -->
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    
                                    <!-- Ver Mensaje Completo (Modal) -->
                                    <button type="button"
                                            onclick="openViewModal(this)"
                                            data-name="<?php echo htmlspecialchars($msg->name); ?>"
                                            data-email="<?php echo htmlspecialchars($msg->email); ?>"
                                            data-subject="<?php echo htmlspecialchars($msg->subject); ?>"
                                            data-message="<?php echo htmlspecialchars($msg->message); ?>"
                                            data-date="<?php echo Helpers::formatDate($msg->created_at); ?>"
                                            class="p-2 text-slate-450 hover:text-brand-600 dark:text-slate-400 dark:hover:text-brand-400 hover:bg-slate-100 dark:hover:bg-slate-800/80 rounded-xl transition-all flex items-center justify-center"
                                            title="Ver mensaje completo">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>

                                    <!-- Responder (mailto:) -->
                                    <a href="mailto:<?php echo htmlspecialchars($msg->email); ?>?subject=RE: <?php echo urlencode($msg->subject); ?>" class="p-2 text-slate-450 hover:text-brand-600 dark:text-slate-400 dark:hover:text-brand-400 hover:bg-slate-100 dark:hover:bg-slate-800/80 rounded-xl transition-all flex items-center justify-center" title="Responder por correo">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </a>

                                    <!-- Eliminar -->
                                    <a href="/?route=admin/messages/delete&id=<?php echo $msg->id; ?>" onclick="return confirm('¿Seguro que deseas eliminar este mensaje de contacto?');" class="p-2 text-slate-450 hover:text-red-500 dark:text-slate-400 dark:hover:text-red-400 hover:bg-slate-100 dark:hover:bg-slate-800/80 rounded-xl transition-all flex items-center justify-center" title="Eliminar mensaje">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </a>

                                </div>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="p-12 text-center text-slate-400">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5M14 10h1.01M9 10h.01M10 14h4"></path></svg>
                <h3 class="text-lg font-bold mb-1 text-slate-700">Tu bandeja de entrada está vacía</h3>
                <p class="text-sm text-slate-500">Los mensajes enviados a través de la página de contacto aparecerán aquí.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Ventana Modal para Ver Mensaje Completo -->
<div id="view-message-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden" role="dialog" aria-modal="true">
    <div class="bg-white dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800/80 w-full max-w-2xl rounded-3xl shadow-2xl flex flex-col overflow-hidden transition-all transform scale-100" style="max-height: 85vh;">
        
        <!-- Cabecera del Modal -->
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
            <div>
                <h2 id="modal-subject" class="text-lg font-extrabold text-slate-900 dark:text-white leading-tight truncate max-w-lg">
                    Asunto del Mensaje
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">
                    Detalles completos de la consulta de contacto.
                </p>
            </div>
            <button type="button" onclick="closeViewModal()" class="p-2 text-slate-400 hover:text-slate-650 dark:hover:text-slate-250 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Cuerpo del Modal -->
        <div class="p-6 space-y-6 overflow-y-auto flex-grow text-sm">
            <!-- Datos del Remitente -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-slate-50 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-800/60 rounded-2xl">
                <div>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nombre del Remitente</span>
                    <span id="modal-name" class="font-bold text-slate-800 dark:text-slate-200"></span>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Correo Electrónico</span>
                    <a id="modal-email" href="" class="font-semibold text-brand-600 dark:text-brand-400 hover:underline"></a>
                </div>
                <div class="md:col-span-2">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Fecha de Recepción</span>
                    <span id="modal-date" class="font-medium text-slate-600 dark:text-slate-400"></span>
                </div>
            </div>

            <!-- Contenido del Mensaje -->
            <div class="space-y-2">
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Mensaje</span>
                <div id="modal-message" class="p-5 bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 rounded-2xl text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-wrap font-medium"></div>
            </div>
        </div>

        <!-- Pie del Modal -->
        <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-900/40 border-t border-slate-100 dark:border-slate-800/80 flex justify-end gap-3">
            <a id="modal-reply-btn" href="" class="btn-primary px-5 py-2.5 text-xs font-semibold rounded-xl flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                Responder
            </a>
            <button type="button" onclick="closeViewModal()" class="px-5 py-2.5 text-xs font-semibold text-slate-500 hover:text-slate-700 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors">
                Cerrar
            </button>
        </div>

    </div>
</div>

<script>
    function openViewModal(btn) {
        const name = btn.getAttribute('data-name');
        const email = btn.getAttribute('data-email');
        const subject = btn.getAttribute('data-subject');
        const message = btn.getAttribute('data-message');
        const date = btn.getAttribute('data-date');

        document.getElementById('modal-name').textContent = name;
        
        const emailLink = document.getElementById('modal-email');
        emailLink.textContent = email;
        emailLink.href = 'mailto:' + email;
        
        document.getElementById('modal-subject').textContent = subject;
        document.getElementById('modal-message').textContent = message;
        document.getElementById('modal-date').textContent = date;
        
        document.getElementById('modal-reply-btn').href = 'mailto:' + email + '?subject=RE: ' + encodeURIComponent(subject);

        document.getElementById('view-message-modal').classList.remove('hidden');
    }

    function closeViewModal() {
        document.getElementById('view-message-modal').classList.add('hidden');
    }

    // Cerrar modal haciendo clic fuera de él (protegiendo la selección de texto)
    let isMouseDownOnBackdrop = false;
    const messageModal = document.getElementById('view-message-modal');
    messageModal.addEventListener('mousedown', function(e) {
        isMouseDownOnBackdrop = (e.target === this);
    });
    messageModal.addEventListener('mouseup', function(e) {
        if (e.target === this && isMouseDownOnBackdrop) {
            closeViewModal();
        }
        isMouseDownOnBackdrop = false;
    });
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>

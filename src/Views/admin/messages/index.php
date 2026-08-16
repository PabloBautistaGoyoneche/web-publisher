<?php
use App\Helpers;
require __DIR__ . '/../layout/header.php';
?>

<!-- Cabecera de Página -->
<div class="mb-10">
    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
        Bandeja de Mensajes de Contacto
    </h1>
    <p class="text-sm text-slate-500 mt-1">
        Lee y administra las consultas enviadas por los usuarios desde la página de contacto de tu blog.
    </p>
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

                            <!-- Cuerpo del Mensaje -->
                            <td class="px-6 py-4 max-w-md">
                                <p class="text-slate-600 dark:text-slate-400 break-words text-xs leading-relaxed">
                                    <?php echo nl2br(htmlspecialchars($msg->message)); ?>
                                </p>
                            </td>

                            <!-- Fecha -->
                            <td class="px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                <?php echo Helpers::formatDate($msg->created_at); ?>
                            </td>

                            <!-- Acciones -->
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    
                                    <!-- Responder (mailto:) -->
                                    <a href="mailto:<?php echo htmlspecialchars($msg->email); ?>?subject=RE: <?php echo urlencode($msg->subject); ?>" class="p-2 text-slate-400 hover:text-brand-500 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors inline-block" title="Responder por correo">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </a>

                                    <!-- Eliminar -->
                                    <a href="/?route=admin/messages/delete&id=<?php echo $msg->id; ?>" onclick="return confirm('¿Seguro que deseas eliminar este mensaje de contacto?');" class="p-2 text-slate-400 hover:text-red-500 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors inline-block" title="Eliminar mensaje">
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

<?php require __DIR__ . '/../layout/footer.php'; ?>

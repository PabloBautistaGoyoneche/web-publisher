<?php
use App\Helpers;
require __DIR__ . '/../layout/header.php';
?>

<!-- Cabecera de Página -->
<div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
            Gestionar Páginas Estáticas
        </h1>
        <p class="text-sm text-slate-500 mt-1">
            Administra los contenidos obligatorios del sitio y las secciones estáticas.
        </p>
    </div>
    <a href="/?route=admin/pages/create" class="btn-primary py-2.5 px-5 text-sm rounded-xl inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Nueva Página
    </a>
</div>

<!-- Tabla CRUD de Páginas -->
<div class="glass-card rounded-3xl border border-slate-200/50 dark:border-slate-800/80 overflow-hidden shadow-sm">
    <div class="overflow-x-auto w-full">
        <?php if (!empty($pages)): ?>
            <table class="w-full text-left text-sm border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-900/40 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-200/50 dark:border-slate-800/50">
                        <th class="px-6 py-4">Título</th>
                        <th class="px-6 py-4">Enlace Permanente (Slug)</th>
                        <th class="px-6 py-4">Última Modificación</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    <?php foreach($pages as $page): ?>
                        <tr class="hover:bg-slate-100/30 dark:hover:bg-slate-900/30 transition-colors">
                            
                            <!-- Título -->
                            <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-100">
                                <?php echo htmlspecialchars($page->title); ?>
                            </td>

                            <!-- Slug -->
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs text-brand-600 dark:text-brand-400 bg-brand-50 dark:bg-brand-950/40 px-2.5 py-1 rounded-lg">
                                    /?route=page&slug=<?php echo htmlspecialchars($page->slug); ?>
                                </span>
                            </td>

                            <!-- Fecha Modificación -->
                            <td class="px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-400">
                                <?php echo Helpers::formatDate($page->updated_at); ?>
                            </td>

                            <!-- Acciones -->
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    
                                    <!-- Ver Página Pública -->
                                    <a href="/?route=page&slug=<?php echo $page->slug; ?>" target="_blank" class="p-2 text-slate-400 hover:text-brand-500 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="Ver página pública">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>

                                    <!-- Editar -->
                                    <a href="/?route=admin/pages/edit&id=<?php echo $page->id; ?>" class="p-2 text-slate-400 hover:text-brand-500 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="Editar página">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>

                                    <!-- Eliminar (No permitir borrar políticas y contacto básicas directamente sin confirmación crítica, ya que son obligatorias) -->
                                    <?php 
                                    $isSystemPage = in_array($page->slug, ['politica-privacidad', 'terminos-condiciones', 'contacto', 'sobre-el-autor']);
                                    ?>
                                    <a href="/?route=admin/pages/delete&id=<?php echo $page->id; ?>" 
                                       onclick="return confirm('<?php echo $isSystemPage ? '¡ADVERTENCIA! Esta página es obligatoria para Google AdSense. Si la eliminas, podrías perder la monetización. ¿Estás seguro?' : '¿Seguro que deseas eliminar esta página estática?'; ?>');" 
                                       class="p-2 text-slate-400 hover:text-red-500 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" 
                                       title="Eliminar página">
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
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <h3 class="text-lg font-bold mb-1 text-slate-700">No hay páginas creadas</h3>
                <p class="text-sm text-slate-500 mb-6">Comienza creando tu primera página institucional.</p>
                <a href="/?route=admin/pages/create" class="btn-primary py-2 px-5 text-sm rounded-xl">
                    Nueva Página
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

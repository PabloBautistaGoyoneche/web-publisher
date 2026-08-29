<?php
use App\Helpers;
require dirname(__DIR__) . '/layout/header.php';
?>

<!-- Sección: Cabecera -->
<div class="mb-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">
            Módulo CTA eBook
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            Crea y administra múltiples CTAs (Llamadas a la Acción) para el pie de página y ventanas modales de tu blog.
        </p>
    </div>
    <div class="flex-shrink-0">
        <button type="button" onclick="openCreateModal()" class="btn-primary text-xs font-bold py-3.5 px-5 rounded-xl flex items-center gap-2 shadow-sm hover:shadow-md transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            Crear CTA
        </button>
    </div>
</div>

<!-- Alertas -->
<?php if (isset($success) && $success): ?>
    <div class="mb-8 p-4 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900/50 rounded-2xl text-sm font-semibold flex items-center gap-2 shadow-sm">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span><?php echo htmlspecialchars($success); ?></span>
    </div>
<?php endif; ?>

<?php if (isset($error) && $error): ?>
    <div class="mb-8 p-4 bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-900/50 rounded-2xl text-sm font-semibold flex items-center gap-2 shadow-sm">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <span><?php echo htmlspecialchars($error); ?></span>
    </div>
<?php endif; ?>

<div class="space-y-6 mb-10">
    <div class="glass-card rounded-3xl border border-slate-200/50 dark:border-slate-800/80 overflow-hidden shadow-sm">
            <div class="overflow-x-auto w-full">
                <?php if (!empty($ctas)): ?>
                    <table class="w-full text-left text-sm border-collapse min-w-[700px]">
                        <thead>
                            <tr class="bg-slate-50/50 dark:bg-slate-900/40 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-200/50 dark:border-slate-800/50">
                                <th class="px-6 py-4">Título del CTA</th>
                                <th class="px-6 py-4">Botón y Enlace</th>
                                <th class="px-6 py-4">Espera Pop-up</th>
                                <th class="px-6 py-4 text-center">Estado</th>
                                <th class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                            <?php foreach($ctas as $cta): ?>
                                <tr class="hover:bg-slate-100/30 dark:hover:bg-slate-900/30 transition-colors">
                                    <!-- Título -->
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800 dark:text-slate-100 leading-tight">
                                            <?php echo htmlspecialchars($cta->title); ?>
                                        </div>
                                        <div class="text-xs text-slate-400 mt-1 max-w-xs truncate">
                                            <?php echo htmlspecialchars($cta->description); ?>
                                        </div>
                                    </td>

                                    <!-- Botón / Enlace -->
                                    <td class="px-6 py-4">
                                        <span class="inline-block px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-350 text-xs font-bold rounded-lg mb-1 leading-none">
                                            <?php echo htmlspecialchars($cta->button_text); ?>
                                        </span>
                                        <div class="text-[10px] text-slate-400 font-mono truncate max-w-xs" title="<?php echo htmlspecialchars($cta->link); ?>">
                                            <?php echo htmlspecialchars($cta->link); ?>
                                        </div>
                                    </td>

                                    <!-- Delay -->
                                    <td class="px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-400">
                                        <?php echo $cta->delay; ?> segundos
                                    </td>

                                    <!-- Estado -->
                                    <td class="px-6 py-4 text-center">
                                        <?php if ($cta->is_active === 1): ?>
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 text-xs font-bold rounded-full border border-emerald-100 dark:border-emerald-900/40">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                Activo
                                            </span>
                                        <?php else: ?>
                                            <a href="/?route=admin/cta-ebook/toggle&id=<?php echo $cta->id; ?>" class="inline-flex items-center gap-1 px-3 py-1 bg-slate-100 dark:bg-slate-800/60 text-slate-450 dark:text-slate-400 text-xs font-bold rounded-full hover:bg-brand-50 dark:hover:bg-brand-950/40 hover:text-brand-650 dark:hover:text-brand-400 hover:border-brand-100 dark:hover:border-brand-900/40 transition-colors border border-transparent">
                                                Inactivo
                                            </a>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Acciones -->
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <!-- Activar (Solo si está inactivo) -->
                                            <?php if ($cta->is_active === 0): ?>
                                                <a href="/?route=admin/cta-ebook/toggle&id=<?php echo $cta->id; ?>" class="p-2 text-slate-400 hover:text-emerald-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all flex items-center justify-center" title="Activar este CTA">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                </a>
                                            <?php endif; ?>

                                            <!-- Editar (Modal) -->
                                            <button type="button" onclick="openEditModal(<?php echo $cta->id; ?>)" class="p-2 text-slate-400 hover:text-brand-650 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all flex items-center justify-center" title="Editar CTA">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>

                                            <!-- Eliminar -->
                                            <a href="/?route=admin/cta-ebook/delete&id=<?php echo $cta->id; ?>" onclick="return confirm('¿Seguro que deseas eliminar este CTA? El archivo del eBook subido también se borrará.');" class="p-2 text-slate-400 hover:text-red-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all flex items-center justify-center" title="Eliminar CTA">
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
                        <svg class="w-12 h-12 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <h3 class="text-lg font-bold mb-1 text-slate-700">No hay ningún CTA configurado</h3>
                        <p class="text-sm text-slate-500">Crea tu primer CTA haciendo clic en el botón "Crear CTA" superior.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<!-- Ventana Modal de Creación / Edición -->
<div id="cta-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden" role="dialog" aria-modal="true">
    <div class="bg-white dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800/80 w-full max-w-2xl rounded-3xl shadow-2xl flex flex-col overflow-hidden transition-all transform scale-100" style="max-height: 95vh;">
        
        <!-- Cabecera del Modal -->
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
            <div>
                <h2 id="modal-title-label" class="text-lg font-extrabold text-slate-900 dark:text-white leading-tight">
                    Crear Llamada a la Acción (CTA)
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">
                    Rellena los campos para dar de alta o actualizar este recurso.
                </p>
            </div>
            <button type="button" onclick="closeCtaModal()" class="p-2 text-slate-400 hover:text-slate-650 dark:hover:text-slate-250 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Formulario -->
        <form id="cta-form" action="/?route=admin/cta-ebook/create" method="POST" enctype="multipart/form-data" class="flex flex-col flex-grow overflow-hidden">
            <!-- Cuerpo del Modal -->
            <div class="p-6 space-y-6 overflow-y-auto flex-grow text-sm">
                
                <!-- Título -->
                <div class="space-y-2">
                    <label for="modal-title-input" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Título del CTA</label>
                    <input type="text" id="modal-title-input" name="title" required 
                           class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-2xl focus:outline-none transition-all font-semibold text-slate-800 dark:text-slate-200" placeholder="ej: Descarga nuestro eBook Gratuito">
                </div>

                <!-- Descripción -->
                <div class="space-y-2">
                    <label for="modal-desc-input" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Descripción del CTA</label>
                    <textarea id="modal-desc-input" name="description" rows="3" required 
                              class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-2xl focus:outline-none transition-all font-medium text-slate-800 dark:text-slate-200" placeholder="ej: Aprende los fundamentos del desarrollo web moderno con nuestra guía completa."></textarea>
                </div>

                <!-- Botón y Delay en Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label for="modal-btn-input" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Texto del Botón</label>
                        <input type="text" id="modal-btn-input" name="button_text" required 
                               class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-2xl focus:outline-none transition-all font-semibold text-slate-800 dark:text-slate-200" placeholder="ej: Descargar eBook">
                    </div>
                    <div class="space-y-2">
                        <label for="modal-delay-input" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Segundos de Espera (Pop-up)</label>
                        <input type="number" id="modal-delay-input" name="delay" min="1" max="120" required 
                               class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-2xl focus:outline-none transition-all font-semibold text-slate-800 dark:text-slate-200" value="5">
                    </div>
                </div>

                <!-- Archivo y Enlace -->
                <div class="space-y-4 border-t border-slate-100 dark:border-slate-850 pt-4">
                    <div class="space-y-2">
                        <label for="modal-file-input" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Subir Archivo de Recurso (Opcional)</label>
                        
                        <!-- Caja informativa de archivo actual (Solo se muestra en edición) -->
                        <div id="modal-current-file-box" class="p-3 bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800 rounded-xl hidden items-center justify-between gap-3 mb-2">
                            <span id="modal-current-file-name" class="text-xs font-bold text-slate-650 dark:text-slate-350 truncate"></span>
                            <div class="flex items-center gap-2">
                                <input type="hidden" id="modal-delete-file-hidden" name="delete_file" value="0">
                                <button type="button" onclick="markDeleteCurrentFile()" class="text-[10px] font-bold text-red-600 bg-red-50 dark:bg-red-950/20 hover:bg-red-100 dark:hover:bg-red-900/30 px-2 py-1.5 rounded-lg transition-all">
                                    Eliminar Archivo
                                </button>
                            </div>
                        </div>

                        <input type="file" id="modal-file-input" name="cta_file" accept=".pdf,.epub,.zip,.rar,.mobi,.docx" class="text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer w-full">
                        <p class="text-[11px] text-slate-400">PDF, EPUB, MOBI, ZIP, RAR, DOCX. Max: 20MB.</p>
                    </div>

                    <div class="space-y-2">
                        <label for="modal-link-input" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Enlace del Botón (Opcional)</label>
                        <input type="text" id="modal-link-input" name="link" 
                               class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-2xl focus:outline-none transition-all font-mono text-xs font-bold text-slate-800 dark:text-slate-200" value="#">
                        <p class="text-[10px] text-slate-400">Si subes un archivo, este campo puede quedarse en blanco o con "#" y se autogestionará automáticamente al guardar.</p>
                    </div>
                </div>

                <!-- Activar -->
                <div class="pt-2">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" id="modal-active-input" name="is_active" value="1" class="w-4 h-4 text-brand-600 border-slate-300 rounded focus:ring-brand-500">
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Activar de inmediato (desactivará los otros CTAs)</span>
                    </label>
                </div>
            </div>

            <!-- Pie del Modal -->
            <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-900/40 border-t border-slate-100 dark:border-slate-800/80 flex justify-end gap-3">
                <button type="button" onclick="closeCtaModal()" class="px-5 py-2.5 text-xs font-semibold text-slate-500 hover:text-slate-700 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors">
                    Cancelar
                </button>
                <button type="submit" class="btn-primary px-5 py-2.5 text-xs font-semibold rounded-xl flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Guardar CTA
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    const modal = document.getElementById('cta-modal');
    const form = document.getElementById('cta-form');
    const modalTitle = document.getElementById('modal-title-label');
    
    const titleInput = document.getElementById('modal-title-input');
    const descInput = document.getElementById('modal-desc-input');
    const btnInput = document.getElementById('modal-btn-input');
    const delayInput = document.getElementById('modal-delay-input');
    const linkInput = document.getElementById('modal-link-input');
    const activeInput = document.getElementById('modal-active-input');

    const currentFileBox = document.getElementById('modal-current-file-box');
    const currentFileName = document.getElementById('modal-current-file-name');
    const deleteFileHidden = document.getElementById('modal-delete-file-hidden');

    function openCreateModal() {
        modalTitle.textContent = "Crear Llamada a la Acción (CTA)";
        form.action = "/?route=admin/cta-ebook/create";
        
        // Reset fields
        titleInput.value = "";
        descInput.value = "";
        btnInput.value = "";
        delayInput.value = "5";
        linkInput.value = "#";
        activeInput.checked = false;

        currentFileBox.classList.add('hidden');
        deleteFileHidden.value = "0";

        modal.classList.remove('hidden');
    }

    function openEditModal(id) {
        modalTitle.textContent = "Editar Llamada a la Acción (CTA)";
        form.action = "/?route=admin/cta-ebook/edit&id=" + id;

        // Cargar por AJAX
        fetch("/?route=admin/cta-ebook/get&id=" + id)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const c = data.cta;
                    titleInput.value = c.title;
                    descInput.value = c.description;
                    btnInput.value = c.button_text;
                    delayInput.value = c.delay;
                    linkInput.value = c.link;
                    activeInput.checked = (c.is_active === 1);

                    deleteFileHidden.value = "0";

                    // Mostrar archivo actual si no es enlace manual '#'
                    const isManualLink = c.link === '#' || c.link.startsWith('http') || c.link.startsWith('/');
                    if (c.link && !isManualLink) {
                        currentFileName.textContent = c.link;
                        currentFileBox.classList.remove('hidden');
                        currentFileBox.style.display = "flex";
                    } else {
                        currentFileBox.classList.add('hidden');
                        currentFileBox.style.display = "none";
                    }

                    modal.classList.remove('hidden');
                } else {
                    alert("Error al recuperar los datos del CTA.");
                }
            })
            .catch(err => {
                console.error(err);
                alert("Error al conectar con el servidor.");
            });
    }

    function markDeleteCurrentFile() {
        if (confirm("¿Estás seguro de que quieres eliminar el archivo cargado?")) {
            deleteFileHidden.value = "1";
            currentFileBox.classList.add('hidden');
            currentFileBox.style.display = "none";
            linkInput.value = "#";
        }
    }

    function closeCtaModal() {
        modal.classList.add('hidden');
    }

    // Cerrar haciendo clic fuera del modal (protegiendo la selección de texto)
    let isMouseDownOnBackdrop = false;
    modal.addEventListener('mousedown', function(e) {
        isMouseDownOnBackdrop = (e.target === this);
    });
    modal.addEventListener('mouseup', function(e) {
        if (e.target === this && isMouseDownOnBackdrop) {
            closeCtaModal();
        }
        isMouseDownOnBackdrop = false;
    });
</script>

<?php require dirname(__DIR__) . '/layout/footer.php'; ?>

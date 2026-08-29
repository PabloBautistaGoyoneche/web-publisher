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
                                     <button type="button" onclick="openEditModal(<?php echo $page->id; ?>)" class="p-2 text-slate-400 hover:text-brand-500 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="Editar página">
                                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                     </button>

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

<!-- Ventana Modal de Edición de Página Estática -->
<div id="edit-page-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden" role="dialog" aria-modal="true">
    <div class="bg-white dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800/80 w-full max-w-4xl rounded-3xl shadow-2xl transition-all transform scale-100" style="height: 95vh; max-height: 95vh; display: flex; flex-direction: column; overflow: hidden;">
        
        <!-- Cabecera del Modal -->
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                    Editar Página Estática
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Modifica los campos principales y el contenido institucional de la página.
                </p>
            </div>
            <button type="button" onclick="closeEditModal()" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Formulario -->
        <form id="edit-page-form" method="POST" style="display: flex; flex-direction: column; flex-grow: 1; min-height: 0; overflow: hidden;">
            
            <!-- Contenedor con Scroll para el Formulario -->
            <div class="p-6 space-y-5" style="flex-grow: 1; overflow-y: auto; min-height: 0;">
                
                <!-- Campo: Título -->
                <div class="space-y-1.5">
                    <label for="modal-title" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Título de la Página *</label>
                    <input type="text" id="modal-title" name="title" required class="w-full px-4 py-2.5 text-sm bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none transition-colors">
                </div>

                <!-- Campo: Slug -->
                <div class="space-y-1.5">
                    <label for="modal-slug" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Slug (Enlace Permanente)</label>
                    <input type="text" id="modal-slug" name="slug" class="w-full px-4 py-2.5 text-sm bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none transition-colors">
                    <p class="text-[10px] text-slate-400">Identificador amigable en la URL (ej. terminos-condiciones).</p>
                </div>

                <!-- Campo: Nombre del Autor (Opcional - solo visible para sobre-el-autor) -->
                <div id="modal-author-container" class="space-y-1.5 hidden">
                    <label for="modal-author-name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Nombre del Autor *</label>
                    <input type="text" id="modal-author-name" name="author_name" class="w-full px-4 py-2.5 text-sm bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none transition-colors">
                </div>

                <!-- Campo: Contenido (Quill Editor) -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider font-semibold">Contenido de la Página *</label>
                    
                    <div id="modal-editor-container" class="bg-white dark:bg-slate-950 text-slate-850 dark:text-slate-100">
                        <!-- Quill inyectará contenido aquí -->
                    </div>
                    
                    <textarea id="modal-content-textarea" name="content" style="display:none;"></textarea>
                </div>

                <!-- Sección: Optimización SEO (Siempre visible y separada del editor) -->
                <div class="border border-slate-200/60 dark:border-slate-800 rounded-2xl overflow-hidden bg-slate-50/30 dark:bg-slate-900/10" style="margin-top: 1.5rem;">
                    <div class="px-5 py-4 font-bold text-xs uppercase tracking-wider text-slate-400 border-b border-slate-100 dark:border-slate-800/40">
                        Optimización SEO (Opcional)
                    </div>
                    <div class="px-5 py-5 space-y-4">
                        <!-- Meta Título -->
                        <div class="space-y-1.5">
                            <label for="modal-seo-title" class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Meta Título</label>
                            <input type="text" id="modal-seo-title" name="seo_title" placeholder="Ej. Términos de Servicio - NombreSitio" class="w-full px-4 py-2.5 text-sm bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none transition-colors">
                            <p class="text-[9px] text-slate-400">Recomendado: 50-60 caracteres. Si se deja vacío, se usará el título de la página.</p>
                        </div>
                        
                        <!-- Meta Descripción -->
                        <div class="space-y-1.5">
                            <label for="modal-seo-description" class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Meta Descripción</label>
                            <textarea id="modal-seo-description" name="seo_description" rows="3" placeholder="Resumen atractivo para los motores de búsqueda..." class="w-full px-4 py-2.5 text-sm bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none transition-colors resize-none"></textarea>
                            <p class="text-[9px] text-slate-400">Recomendado: 140-155 caracteres. Si se deja vacío, se autogenerará a partir del contenido.</p>
                        </div>
                        
                        <!-- Palabras Clave -->
                        <div class="space-y-1.5">
                            <label for="modal-seo-keywords" class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Palabras Clave (Keywords)</label>
                            <input type="text" id="modal-seo-keywords" name="seo_keywords" placeholder="ej. terminos, condiciones, legal, privacidad" class="w-full px-4 py-2.5 text-sm bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none transition-colors">
                            <p class="text-[9px] text-slate-400">Separadas por comas.</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Pie del Modal (Botones de Acción) -->
            <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-900/40 border-t border-slate-100 dark:border-slate-800/80 flex justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 text-sm font-semibold text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors">
                    Cancelar
                </button>
                <button type="submit" class="btn-primary px-5 py-2.5 text-sm font-semibold rounded-xl">
                    Guardar Cambios
                </button>
            </div>

        </form>

    </div>
</div>

<style>
    /* Estilos Quill personalizados para el modal en modo oscuro y claro */
    .ql-toolbar.ql-snow {
        border-color: rgba(226, 232, 240, 0.8) !important;
        background-color: #f8fafc;
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
    }
    .dark .ql-toolbar.ql-snow {
        border-color: rgba(30, 41, 59, 0.8) !important;
        background-color: #0f172a;
        color: #e2e8f0;
    }
    .dark .ql-toolbar.ql-snow .ql-stroke {
        stroke: #94a3b8;
    }
    .dark .ql-toolbar.ql-snow .ql-fill {
        fill: #94a3b8;
    }
    .dark .ql-toolbar.ql-snow .ql-picker {
        color: #94a3b8;
    }
    .dark .ql-toolbar.ql-snow .ql-picker-options {
        background-color: #0f172a;
        border-color: rgba(30, 41, 59, 0.8);
    }
    
    #modal-editor-container {
        border-bottom-left-radius: 0.75rem;
        border-bottom-right-radius: 0.75rem;
        border-color: rgba(226, 232, 240, 0.8) !important;
    }
    .dark #modal-editor-container {
        border-color: rgba(30, 41, 59, 0.8) !important;
    }
    .ql-container.ql-snow {
        border-color: rgba(226, 232, 240, 0.8) !important;
    }
    .ql-editor {
        min-height: 250px;
        font-family: 'Outfit', 'Inter', sans-serif;
        font-size: 14px;
    }
    .ql-editor.ql-blank::before {
        color: #94a3b8 !important;
        font-style: normal;
    }
</style>

<script>
    let quillInstance = null;
    let isMetaTitleAuto = true;
    let isMetaDescAuto = true;

    function initQuill() {
        if (!quillInstance) {
            quillInstance = new Quill('#modal-editor-container', {
                theme: 'snow',
                placeholder: 'Escribe el contenido de la página aquí...',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'image', 'clean']
                    ]
                }
            });

            // Autocompletar Meta Descripción a partir del editor de contenido
            quillInstance.on('text-change', function() {
                if (isMetaDescAuto) {
                    const text = quillInstance.getText().trim();
                    const cleanText = text.replace(/\s+/g, ' ');
                    const seoDescInput = document.getElementById('modal-seo-description');
                    if (cleanText.length > 155) {
                        seoDescInput.value = cleanText.substring(0, 152) + '...';
                    } else {
                        seoDescInput.value = cleanText;
                    }
                }
            });
        }
    }

    function openEditModal(pageId) {
        fetch('/?route=admin/pages/get&id=' + pageId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const page = data.page;
                    
                    // Configurar acción del formulario
                    document.getElementById('edit-page-form').action = '/?route=admin/pages/edit&id=' + page.id;
                    
                    // Llenar campos del modal
                    document.getElementById('modal-title').value = page.title;
                    document.getElementById('modal-slug').value = page.slug;
                    
                    // Inicializar Quill
                    initQuill();
                    
                    // Cargar contenido en Quill
                    quillInstance.root.innerHTML = page.content;
                    
                    // Manejar lógica especial para "sobre-el-autor"
                    const authorContainer = document.getElementById('modal-author-container');
                    const authorInput = document.getElementById('modal-author-name');
                    if (page.slug === 'sobre-el-autor') {
                        authorContainer.classList.remove('hidden');
                        authorInput.value = page.author_name || '';
                        authorInput.required = true;
                    } else {
                        authorContainer.classList.add('hidden');
                        authorInput.value = '';
                        authorInput.required = false;
                    }
                    
                    // Llenar campos de SEO
                    document.getElementById('modal-seo-title').value = page.seo_title || '';
                    document.getElementById('modal-seo-description').value = page.seo_description || '';
                    document.getElementById('modal-seo-keywords').value = page.seo_keywords || '';

                    // Inicializar estados de autocompletado SEO
                    isMetaTitleAuto = !page.seo_title;
                    isMetaDescAuto = !page.seo_description;

                    // Mostrar el modal
                    document.getElementById('edit-page-modal').classList.remove('hidden');
                } else {
                    alert('No se pudieron recuperar los datos de la página estática.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error al intentar conectar con el servidor.');
            });
    }

    function closeEditModal() {
        document.getElementById('edit-page-modal').classList.add('hidden');
    }

    // Sincronizar editor Quill con textarea oculto antes de enviar el formulario
    document.getElementById('edit-page-form').addEventListener('submit', function(e) {
        if (quillInstance) {
            document.getElementById('modal-content-textarea').value = quillInstance.root.innerHTML;
        }
    });

    // Autocompletar Slug y Meta Título a partir del Título en tiempo real
    document.getElementById('modal-title').addEventListener('input', function() {
        const titleVal = this.value;
        const slugInput = document.getElementById('modal-slug');
        
        const slugVal = titleVal
            .toLowerCase()
            .trim()
            .normalize('NFD') // Quitar acentos y diacríticos
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9\s-]/g, '') // Quitar caracteres no permitidos
            .replace(/[\s_]+/g, '-') // Reemplazar espacios y guiones bajos por un solo guion
            .replace(/-+/g, '-'); // Quitar guiones múltiples
            
        slugInput.value = slugVal;

        // Autocompletar Meta Título respetando límite de 60 caracteres
        if (isMetaTitleAuto) {
            const seoTitleInput = document.getElementById('modal-seo-title');
            const cleanTitle = titleVal.trim();
            if (cleanTitle.length > 60) {
                seoTitleInput.value = cleanTitle.substring(0, 57) + '...';
            } else {
                seoTitleInput.value = cleanTitle;
            }
        }
    });

    // Detectar cambios manuales para apagar autocompletado
    document.getElementById('modal-seo-title').addEventListener('input', function() {
        isMetaTitleAuto = (this.value.trim() === '');
    });

    document.getElementById('modal-seo-description').addEventListener('input', function() {
        isMetaDescAuto = (this.value.trim() === '');
    });

    // Cerrar modal al hacer clic fuera del contenido del modal (protegiendo la selección de texto)
    let isMouseDownOnBackdrop = false;
    const pageModal = document.getElementById('edit-page-modal');
    pageModal.addEventListener('mousedown', function(e) {
        isMouseDownOnBackdrop = (e.target === this);
    });
    pageModal.addEventListener('mouseup', function(e) {
        if (e.target === this && isMouseDownOnBackdrop) {
            closeEditModal();
        }
        isMouseDownOnBackdrop = false;
    });
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>

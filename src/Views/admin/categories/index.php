<?php
require __DIR__ . '/../layout/header.php';
?>

<!-- Cabecera de Página -->
<div class="mb-10 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
            Categorías del Blog
        </h1>
        <p class="text-sm text-slate-500 mt-1">
            Reordena las categorías arrastrándolas. Puedes anidarlas hasta un máximo de 2 niveles.
        </p>
    </div>
    <div class="flex items-center gap-3">
        <!-- Toast de guardado automático -->
        <div id="save-toast" class="hidden items-center gap-2 px-4 py-2 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/40 rounded-xl text-xs font-semibold shadow-sm transition-all duration-300">
            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.228 10H18.22"></path></svg>
            <span>Guardando cambios...</span>
        </div>
        
        <button onclick="openCreateModal()" class="btn-primary py-2.5 px-5 text-sm font-semibold rounded-xl flex items-center gap-2 shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Añadir Categoría
        </button>
    </div>
</div>

<!-- Alerta de Error / Éxito -->
<?php if (isset($error) && $error): ?>
    <div class="mb-6 p-4 bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900/60 rounded-2xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <span><?php echo htmlspecialchars($error); ?></span>
    </div>
<?php endif; ?>

<!-- Estilos específicos para Nested Sortable -->
<style>
    .category-list-nested {
        min-height: 24px;
        transition: background-color 0.2s ease;
    }
    .category-list-nested:empty {
        min-height: 48px;
        border: 2px dashed rgba(148, 163, 184, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .category-list-nested:empty::after {
        content: 'Arrastra subcategorías aquí';
        font-size: 0.75rem;
        color: #94a3b8;
        font-weight: 500;
    }
    .sortable-ghost {
        opacity: 0.4;
        background-color: rgb(var(--brand-50)) !important;
        border: 2px dashed rgb(var(--brand-500)) !important;
    }
    /* Ocultar el contenedor de subcategorías para elementos que ya son subcategorías (nivel 2) */
    .category-list-nested:not([data-parent-id="none"]) .subcategory-wrapper {
        display: none !important;
    }
</style>

<!-- Listado de Categorías Jerárquicas -->
<div class="glass-card rounded-3xl border border-slate-200/50 dark:border-slate-800/80 p-6 shadow-sm w-full">
    <?php if (!empty($categories)): ?>
        
        <!-- Función recursiva para renderizar el árbol de categorías limitado a 2 niveles -->
        <?php
        function renderCategoryTree(array $categories, ?int $parentId = null, int $depth = 1) {
            $filtered = array_filter($categories, function($cat) use ($parentId) {
                return $cat->parent_id === $parentId;
            });
            
            usort($filtered, function($a, $b) {
                return $a->sort_order <=> $b->sort_order;
            });
            ?>
            <ul class="category-list-nested space-y-3 p-1 rounded-2xl" data-parent-id="<?php echo $parentId ?? 'none'; ?>">
                <?php foreach ($filtered as $cat): ?>
                    <li class="category-item bg-white dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800/80 rounded-2xl overflow-hidden shadow-sm mt-2" data-id="<?php echo $cat->id; ?>">
                        
                        <!-- Encabezado del Item -->
                        <div class="flex items-center justify-between p-4 bg-slate-50/50 dark:bg-slate-900/30">
                            <div class="flex items-center gap-3">
                                <!-- Drag Handle -->
                                <span class="drag-handle cursor-move text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                </span>
                                <span class="font-extrabold text-sm text-slate-800 dark:text-slate-100">
                                    <?php echo htmlspecialchars($cat->name); ?>
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-mono bg-slate-100 dark:bg-slate-800/80 text-slate-500 dark:text-slate-400 border border-slate-200/30 dark:border-slate-700/30 font-semibold">
                                    /<?php echo htmlspecialchars($cat->slug); ?>
                                </span>
                                <span class="text-xs text-slate-400 font-medium">
                                    (<?php echo $cat->getPostCount(); ?> posts)
                                </span>
                            </div>
                            
                            <!-- Acciones -->
                            <div class="flex items-center gap-1">
                                <!-- Clonar -->
                                <button onclick="openCloneModal('<?php echo htmlspecialchars(addslashes($cat->name)); ?>', '<?php echo htmlspecialchars(addslashes($cat->slug)); ?>', '<?php echo $cat->parent_id; ?>')" class="p-2 text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors inline-block" title="Clonar categoría">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                                </button>
                                <!-- Editar -->
                                <?php 
                                $hasChildren = !empty(array_filter($categories, function($c) use ($cat) { return $c->parent_id === $cat->id; }));
                                ?>
                                <button onclick="openEditModal(<?php echo $cat->id; ?>, '<?php echo htmlspecialchars(addslashes($cat->name)); ?>', '<?php echo htmlspecialchars(addslashes($cat->slug)); ?>', '<?php echo $cat->parent_id; ?>', <?php echo $hasChildren ? 'true' : 'false'; ?>)" class="p-2 text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors inline-block" title="Editar categoría">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <!-- Eliminar -->
                                <a href="/?route=admin/categories/delete&id=<?php echo $cat->id; ?>" onclick="return confirm('¿Seguro que deseas eliminar esta categoría? ATENCIÓN: Al eliminarla se eliminarán todos los posts asociados a ella.');" class="p-2 text-slate-400 hover:text-red-500 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors inline-block" title="Eliminar categoría">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Lista anidada de subcategorías (solo se renderiza para el primer nivel para forzar un máximo de 2 niveles) -->
                        <?php if ($depth < 2): ?>
                            <div class="subcategory-wrapper bg-slate-50/10 dark:bg-slate-900/5 border-t border-slate-100/50 dark:border-slate-800/20 pl-6 pb-2">
                                <?php renderCategoryTree($categories, $cat->id, $depth + 1); ?>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php
        }
        
        // Renderizar el árbol principal
        renderCategoryTree($categories, null, 1);
        ?>
        
    <?php else: ?>
        <div class="p-12 text-center text-slate-400">
            No hay categorías creadas.
        </div>
    <?php endif; ?>
</div>

<!-- Modal de Categoría -->
<div id="category-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4">
    <div class="bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-2xl max-w-md w-full overflow-hidden transform scale-95 opacity-0 transition-all duration-200" id="category-modal-card">
        <!-- Cabecera -->
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/30">
            <h3 id="modal-title" class="font-extrabold text-slate-800 dark:text-white text-base">Añadir Nueva Categoría</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <!-- Formulario -->
        <form action="/?route=admin/categories" method="POST" class="p-6 space-y-4">
            <input type="hidden" id="modal-category-id" name="id" value="">
            
            <!-- Nombre -->
            <div class="space-y-2">
                <label for="modal-name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Nombre *</label>
                <input type="text" id="modal-name" name="name" required placeholder="ej: Diseño" class="w-full px-4 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-xl focus:outline-none transition-colors">
            </div>

            <!-- Slug -->
            <div class="space-y-2">
                <label for="modal-slug" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Slug</label>
                <input type="text" id="modal-slug" name="slug" placeholder="ej: diseno" class="w-full px-4 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none transition-colors">
            </div>

            <!-- Categoría Padre -->
            <div class="space-y-2">
                <label for="modal-parent-id" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Categoría Padre</label>
                <select id="modal-parent-id" name="parent_id" class="w-full px-4 py-2.5 text-sm bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none transition-colors text-slate-700 dark:text-slate-300">
                    <option value="none">Ninguna (Categoría Principal)</option>
                    <?php foreach(\App\Models\Category::parents() as $parent): ?>
                        <option value="<?php echo $parent->id; ?>"><?php echo htmlspecialchars($parent->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Botones de Acción -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-5 py-2.5 rounded-xl font-semibold text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-900 active:scale-95 transition-all text-sm">Cancelar</button>
                <button type="submit" id="modal-submit-btn" name="create_category" class="btn-primary py-2.5 px-6 text-sm font-semibold rounded-xl">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Cargar SortableJS desde CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<!-- Scripts de Control -->
<script>
    const modal = document.getElementById('category-modal');
    const modalCard = document.getElementById('category-modal-card');
    const modalTitle = document.getElementById('modal-title');
    const modalId = document.getElementById('modal-category-id');
    const modalName = document.getElementById('modal-name');
    const modalSlug = document.getElementById('modal-slug');
    const modalParentId = document.getElementById('modal-parent-id');
    const modalSubmitBtn = document.getElementById('modal-submit-btn');
    const saveToast = document.getElementById('save-toast');

    function openModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modalCard.classList.remove('scale-95', 'opacity-0');
            modalCard.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeModal() {
        modalCard.classList.remove('scale-100', 'opacity-100');
        modalCard.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }, 200);
    }

    function openCreateModal() {
        modalTitle.textContent = "Añadir Nueva Categoría";
        modalId.value = "";
        modalName.value = "";
        modalSlug.value = "";
        modalParentId.value = "none";
        modalParentId.disabled = false;
        modalSubmitBtn.name = "create_category";
        modalSubmitBtn.textContent = "Añadir Categoría";
        
        Array.from(modalParentId.options).forEach(opt => opt.disabled = false);
        openModal();
    }

    function openEditModal(id, name, slug, parentId, hasChildren) {
        modalTitle.textContent = "Editar Categoría";
        modalId.value = id;
        modalName.value = name;
        modalSlug.value = slug;
        modalSubmitBtn.name = "update_category";
        modalSubmitBtn.textContent = "Guardar Cambios";

        // Si la categoría tiene subcategorías hijas, no puede ser subcategoría de otra (límite de 2 niveles)
        if (hasChildren) {
            modalParentId.value = "none";
            modalParentId.disabled = true;
        } else {
            modalParentId.disabled = false;
            modalParentId.value = parentId ? parentId : "none";
            Array.from(modalParentId.options).forEach(opt => {
                opt.disabled = (opt.value == id);
            });
        }
        
        openModal();
    }

    function openCloneModal(name, slug, parentId) {
        modalTitle.textContent = "Clonar Categoría";
        modalId.value = "";
        modalName.value = name + " (Copia)";
        modalSlug.value = slug + "-copia";
        modalParentId.value = parentId ? parentId : "none";
        modalParentId.disabled = false;
        modalSubmitBtn.name = "create_category";
        modalSubmitBtn.textContent = "Clonar Categoría";
        
        Array.from(modalParentId.options).forEach(opt => opt.disabled = false);
        openModal();
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Asegurar que el select deshabilitado de parent_id se habilite antes de enviar el formulario
        const categoryForm = document.querySelector('form');
        if (categoryForm) {
            categoryForm.addEventListener('submit', function() {
                modalParentId.disabled = false;
            });
        }

        // Inicializar generador automático de slug
        if (modalName && modalSlug) {
            modalName.addEventListener('input', function() {
                if (modalSubmitBtn.name === "create_category") {
                    let slug = modalName.value.toLowerCase()
                        .trim()
                        .normalize('NFD')
                        .replace(/[\u0300-\u036f]/g, '')
                        .replace(/[^a-z0-9\s-]/g, '')
                        .replace(/[\s_]+/g, '-')
                        .replace(/^-+|-+$/g, '');
                    modalSlug.value = slug;
                }
            });
        }

        // Inicializar Drag and Drop recursivo en las listas anidadas con SortableJS
        initializeSortableTree();
    });

    function initializeSortableTree() {
        const nestedLists = document.querySelectorAll('.category-list-nested');
        nestedLists.forEach(list => {
            new Sortable(list, {
                group: 'nested', // Permite arrastrar elementos entre cualquier lista del mismo grupo
                handle: '.drag-handle', // Limitar el arrastre al icono del tirador
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                ghostClass: 'sortable-ghost',
                onMove: function(evt) {
                    // Evitar arrastrar una categoría que tiene subcategorías hijas dentro de una lista anidada (para respetar el límite de 2 niveles)
                    const isTargetRoot = evt.to.getAttribute('data-parent-id') === 'none';
                    const hasChildren = evt.dragged.querySelector('.category-item') !== null;
                    if (hasChildren && !isTargetRoot) {
                        return false; // Cancela el preview de la posición de drop
                    }

                    // Evitar anidamiento de tercer nivel (nieto)
                    if (!isTargetRoot) {
                        const targetParentItem = evt.to.closest('.category-item');
                        if (targetParentItem) {
                            const targetGrandparentItem = targetParentItem.parentElement.closest('.category-item');
                            if (targetGrandparentItem) {
                                return false; // Cancela si se intenta colocar como nieto
                            }
                        }
                    }
                },
                onEnd: function() {
                    saveAutoReorder();
                }
            });
        });
    }

    // Guardado automático mediante AJAX
    function saveAutoReorder() {
        // Mostrar indicador visual de guardado
        saveToast.classList.remove('hidden');
        saveToast.classList.add('flex');
        
        const payload = [];
        
        // Recorrer todos los elementos de categoría del árbol
        const items = document.querySelectorAll('.category-item');
        items.forEach(item => {
            const id = item.getAttribute('data-id');
            const parentList = item.closest('.category-list-nested');
            const parentId = parentList ? parentList.getAttribute('data-parent-id') : 'none';
            
            // Obtener el índice relativo de este elemento en su respectiva lista
            const siblings = Array.from(parentList.children);
            const index = siblings.indexOf(item);
            
            payload.push({
                id: id,
                parent_id: parentId === 'none' ? null : parentId,
                order: index
            });
        });

        // Enviar al controlador de backend en tiempo real
        fetch('/?route=admin/categories/reorder', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Éxito: ocultar indicador después de 1 segundo
                setTimeout(() => {
                    saveToast.classList.remove('flex');
                    saveToast.classList.add('hidden');
                }, 800);
            } else {
                console.error("Error al reordenar las categorías");
                alert("Ocurrió un error al guardar el orden de las categorías. Intente de nuevo.");
            }
        })
        .catch(err => {
            console.error(err);
            alert("Error de conexión al guardar el orden.");
        });
    }
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>

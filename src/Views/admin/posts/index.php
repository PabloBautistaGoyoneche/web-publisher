<?php
use App\Helpers;
require __DIR__ . '/../layout/header.php';
?>

<!-- Cabecera de Página -->
<div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
            Gestionar Entradas
        </h1>
        <p class="text-sm text-slate-500 mt-1">
            Crea, edita, redacta borradores y gestiona las publicaciones de tu blog.
        </p>
    </div>
    <button onclick="openCreatePostModal()" class="btn-primary py-2.5 px-5 text-sm rounded-xl flex items-center gap-2 shadow-md">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Nueva Entrada
    </button>
</div>

<!-- Alerta de Error / Éxito -->
<?php if (isset($error) && $error): ?>
    <div class="mb-6 p-4 bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900/60 rounded-2xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <span><?php echo htmlspecialchars($error); ?></span>
    </div>
<?php endif; ?>

<!-- Tabla CRUD de Posts -->
<div class="glass-card rounded-3xl border border-slate-200/50 dark:border-slate-800/80 overflow-hidden shadow-sm">
    <div class="overflow-x-auto w-full">
        <?php if (!empty($posts)): ?>
            <table class="w-full text-left text-sm border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-900/40 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-200/50 dark:border-slate-800/50">
                        <th class="px-6 py-4">Portada</th>
                        <th class="px-6 py-4">Título</th>
                        <th class="px-6 py-4">Categoría</th>
                        <th class="px-6 py-4">Estado</th>
                        <th class="px-6 py-4 text-center">Vistas</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    <?php foreach($posts as $post): ?>
                        <tr class="hover:bg-slate-100/30 dark:hover:bg-slate-900/30 transition-colors">
                            
                            <!-- Portada Thumbnail -->
                            <td class="px-6 py-4">
                                <div class="w-14 h-10 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800">
                                    <?php if ($post->featured_image): ?>
                                        <img src="<?php echo Helpers::asset('uploads/' . $post->featured_image); ?>" alt="" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center text-xs text-slate-400 font-bold uppercase">S/I</div>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- Título -->
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-800 dark:text-slate-100 line-clamp-1">
                                    <?php echo htmlspecialchars($post->title); ?>
                                </span>
                                <span class="text-xs text-slate-400 block mt-0.5 font-medium">
                                    Por <?php echo htmlspecialchars($post->getAuthor()->display_name); ?> &bull; <?php echo Helpers::formatDate($post->created_at); ?>
                                </span>
                            </td>

                            <!-- Categoría -->
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                    <?php echo htmlspecialchars($post->getCategory()->name); ?>
                                </span>
                            </td>

                            <!-- Estado -->
                            <td class="px-6 py-4">
                                <?php if ($post->status === 'published'): ?>
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400">
                                        Publicado
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400">
                                        Borrador
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Vistas -->
                            <td class="px-6 py-4 text-center font-mono font-medium text-slate-600 dark:text-slate-400">
                                <?php echo $post->views_count; ?>
                            </td>

                            <!-- Acciones -->
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    
                                    <!-- Ver Post (Público) -->
                                    <a href="/?route=post&slug=<?php echo $post->slug; ?>" target="_blank" class="p-2 text-slate-400 hover:text-brand-500 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="Ver entrada">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>

                                    <!-- Editar -->
                                    <button onclick="openEditPostModal(<?php echo $post->id; ?>)" class="p-2 text-slate-400 hover:text-brand-500 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors inline-block" title="Editar entrada">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>

                                    <!-- Duplicar -->
                                    <a href="/?route=admin/posts/duplicate&id=<?php echo $post->id; ?>" class="p-2 text-slate-400 hover:text-brand-500 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors inline-block" title="Duplicar entrada">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                                    </a>

                                    <!-- Eliminar -->
                                    <a href="/?route=admin/posts/delete&id=<?php echo $post->id; ?>" onclick="return confirm('¿Seguro que deseas eliminar permanentemente esta entrada? Todas sus vistas y comentarios se perderán.');" class="p-2 text-slate-400 hover:text-red-500 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors inline-block" title="Eliminar entrada">
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
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                <h3 class="text-lg font-bold mb-1 text-slate-700">No hay entradas creadas</h3>
                <p class="text-sm text-slate-500 mb-6">Comienza a escribir tu primera entrada ahora.</p>
                <button onclick="openCreatePostModal()" class="btn-primary py-2 px-5 text-sm rounded-xl">
                    Nueva Entrada
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de Entrada Reorganizado con Pestañas (Post Modal) -->
<div id="post-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4 overflow-y-auto py-8">
    <div class="bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-2xl max-w-2xl w-full overflow-hidden transform scale-95 opacity-0 transition-all duration-200 my-auto flex flex-col" style="height: 80vh; max-height: 80vh;" id="post-modal-card">
        
        <!-- Cabecera del Modal -->
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/30">
            <h3 id="modal-title" class="font-extrabold text-slate-800 dark:text-white text-base">Nueva Entrada</h3>
            <button onclick="closePostModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <!-- Formulario -->
        <form action="/?route=admin/posts" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; flex-grow: 1; min-height: 0;">
            <input type="hidden" id="modal-post-id" name="id" value="">
            
            <!-- Cuerpo del Formulario (Desplazable) -->
            <div class="p-6 space-y-6" style="overflow-y: auto; max-height: calc(80vh - 140px); flex-grow: 1;">
                <!-- Título -->
                <div class="space-y-2">
                    <label for="modal-post-title" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Título de la Entrada *</label>
                    <input type="text" id="modal-post-title" name="title" required maxlength="60" placeholder="Ingresa el título del post..." class="w-full px-4 py-3 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-xl focus:outline-none transition-colors text-slate-800 dark:text-slate-100">
                </div>

                <!-- Enlace Permanente (Slug) -->
                <div class="space-y-2">
                    <label for="modal-post-slug" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Enlace Permanente (Slug)</label>
                    <input type="text" id="modal-post-slug" name="slug" placeholder="mi-enlace-permanente" class="w-full px-4 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-xl focus:outline-none transition-colors text-slate-800 dark:text-slate-100">
                </div>

                <!-- Contenido (Editor) -->
                <div class="space-y-2">
                    <label for="modal-post-content" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Cuerpo del Artículo *</label>
                    <p class="text-[10px] text-slate-400">Puedes usar etiquetas HTML o Markdown simple.</p>
                    <textarea id="modal-post-content" name="content" required rows="10" placeholder="Escribe el contenido completo del artículo aquí..." class="w-full px-4 py-3 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-xl focus:outline-none transition-colors font-mono text-slate-800 dark:text-slate-100"></textarea>
                </div>

                <!-- Clasificación y Portada -->
                <div class="border-t border-slate-100 dark:border-slate-800 pt-5 mt-5">
                    <h4 class="text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        Clasificación y Portada
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <!-- Columna 1: Estado y Categoría -->
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Estado</label>
                                <input type="hidden" id="modal-post-status" name="status" value="published">
                                <div class="flex items-center gap-3 pt-1">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" id="modal-post-status-toggle" class="sr-only peer" onchange="togglePostStatus(this)">
                                        <div class="w-11 h-6 bg-slate-200 dark:bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-brand-500"></div>
                                    </label>
                                    <span id="modal-post-status-label" class="text-sm font-semibold text-slate-700 dark:text-slate-300">Publicado</span>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="modal-post-category" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Categoría</label>
                                <select id="modal-post-category" name="category_id" class="w-full px-3.5 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-xl focus:outline-none text-slate-700 dark:text-slate-300">
                                    <option value="0">Sin Categoría (Opcional)</option>
                                    <?php 
                                    $parents = [];
                                    $children = [];
                                    foreach($categories as $cat) {
                                        if ($cat->parent_id === null) {
                                            $parents[] = $cat;
                                        } else {
                                            $children[$cat->parent_id][] = $cat;
                                        }
                                    }
                                    foreach($parents as $parent): 
                                    ?>
                                        <option value="<?php echo $parent->id; ?>"><?php echo htmlspecialchars($parent->name); ?></option>
                                        <?php if (isset($children[$parent->id])): ?>
                                            <?php foreach($children[$parent->id] as $child): ?>
                                                <option value="<?php echo $child->id; ?>">&nbsp;&nbsp;&nbsp;&nbsp;↳ <?php echo htmlspecialchars($child->name); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Columna 2: Imagen Destacada -->
                        <div class="space-y-3">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Imagen Destacada</label>
                            <p class="text-[10px] text-slate-400 font-medium">Formatos permitidos: JPG, PNG, WEBP.</p>
                            <input type="file" id="modal-post-image-input" name="featured_image" accept="image/*" class="text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer w-full">
                            
                            <!-- Contenedor Vista Previa -->
                            <div id="modal-post-image-preview-container" class="hidden aspect-video w-full rounded-2xl bg-slate-100 dark:bg-slate-900 overflow-hidden border border-slate-200/50 dark:border-slate-800/80 relative">
                                <img id="modal-post-image-preview" src="#" alt="Vista previa" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección SEO Integrada -->
                <div class="border-t border-slate-100 dark:border-slate-800 pt-5 mt-5">
                    <h4 class="text-xs font-extrabold text-brand-600 dark:text-brand-400 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Optimización SEO (Opcional)
                    </h4>
                </div>

                <!-- Meta Título SEO -->
                <div class="space-y-2">
                    <label for="modal-post-seo-title" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Meta Título SEO</label>
                    <input type="text" id="modal-post-seo-title" name="seo_title" maxlength="60" placeholder="Meta título personalizado para buscadores (sugerido: máx 60 caracteres)..." class="w-full px-4 py-3 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-xl focus:outline-none transition-colors text-slate-800 dark:text-slate-100">
                </div>

                <!-- Meta Descripción SEO -->
                <div class="space-y-2">
                    <label for="modal-post-seo-description" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Meta Descripción SEO</label>
                    <textarea id="modal-post-seo-description" name="seo_description" maxlength="155" rows="3" placeholder="Descripción resumida para buscadores (sugerido: máx 155 caracteres)..." class="w-full px-4 py-3 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-xl focus:outline-none transition-colors text-slate-800 dark:text-slate-100"></textarea>
                </div>

                <!-- Meta Palabras Clave -->
                <div class="space-y-2">
                    <label for="modal-post-seo-keywords" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Palabras Clave (Separadas por comas)</label>
                    <input type="text" id="modal-post-seo-keywords" name="seo_keywords" placeholder="ejemplo, blog, tecnología, desarrollo" class="w-full px-4 py-3 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-xl focus:outline-none transition-colors text-slate-800 dark:text-slate-100">
                </div>
            </div>

            <!-- Botones de Acción (Fijos en la base) -->
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3 bg-slate-50/50 dark:bg-slate-900/30">
                <button type="button" onclick="closePostModal()" class="px-5 py-2.5 rounded-xl font-semibold text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-900 active:scale-95 transition-all text-sm">Cancelar</button>
                <button type="submit" id="modal-post-submit-btn" name="create_post" class="btn-primary py-2.5 px-6 text-sm font-semibold rounded-xl shadow-md">
                    Guardar
                </button>
            </div>
        </form>

    </div>
</div>

<!-- Scripts de control del Modal -->
<script>
    const modal = document.getElementById('post-modal');
    const modalCard = document.getElementById('post-modal-card');
    const modalTitle = document.getElementById('modal-title');
    const modalId = document.getElementById('modal-post-id');
    const modalPostTitle = document.getElementById('modal-post-title');
    const modalPostContent = document.getElementById('modal-post-content');
    const modalPostStatus = document.getElementById('modal-post-status');
    const modalPostSlug = document.getElementById('modal-post-slug');
    const modalPostCategory = document.getElementById('modal-post-category');
    const modalImageInput = document.getElementById('modal-post-image-input');
    const modalImagePreviewContainer = document.getElementById('modal-post-image-preview-container');
    const modalImagePreview = document.getElementById('modal-post-image-preview');
    const modalSubmitBtn = document.getElementById('modal-post-submit-btn');

    const modalPostSeoTitle = document.getElementById('modal-post-seo-title');
    const modalPostSeoDescription = document.getElementById('modal-post-seo-description');
    const modalPostSeoKeywords = document.getElementById('modal-post-seo-keywords');

    const modalPostStatusToggle = document.getElementById('modal-post-status-toggle');

    let userManuallyEditedSlug = false;
    let userManuallyEditedSeoTitle = false;
    let userManuallyEditedSeoDesc = false;

    function generateSlug(text) {
        return text.toLowerCase()
            .trim()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/[\s_]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    function generateSeoDescription(text) {
        // Eliminar HTML tags
        let clean = text.replace(/<[^>]*>/g, '');
        // Eliminar Markdown de enlaces/imágenes
        clean = clean.replace(/!\[.*?\]\(.*?\)/g, '');
        clean = clean.replace(/\[(.*?)\]\(.*?\)/g, '$1');
        // Eliminar Markdown de títulos y estilos
        clean = clean.replace(/^[#\s=*-]+/gm, '');
        clean = clean.replace(/[*_`~]/g, '');
        // Normalizar espacios en blanco
        clean = clean.replace(/\s+/g, ' ').trim();
        // Truncar a 155 caracteres
        if (clean.length > 155) {
            clean = clean.substring(0, 152) + '...';
        }
        return clean;
    }

    function togglePostStatus(checkbox) {
        const hiddenInput = document.getElementById('modal-post-status');
        const label = document.getElementById('modal-post-status-label');
        if (checkbox.checked) {
            hiddenInput.value = 'published';
            label.textContent = 'Publicado';
            label.className = 'text-sm font-semibold text-slate-700 dark:text-slate-300';
        } else {
            hiddenInput.value = 'draft';
            label.textContent = 'Borrador';
            label.className = 'text-sm font-semibold text-slate-400 dark:text-slate-500';
        }
    }

    function openPostModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        setTimeout(() => {
            modalCard.classList.remove('scale-95', 'opacity-0');
            modalCard.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closePostModal() {
        modalCard.classList.remove('scale-100', 'opacity-100');
        modalCard.classList.add('scale-95', 'opacity-0');
        document.body.classList.remove('overflow-hidden');
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }, 200);
        if(window.location.hash) {
            history.pushState("", document.title, window.location.pathname + window.location.search);
        }
    }

    function openCreatePostModal() {
        userManuallyEditedSlug = false;
        userManuallyEditedSeoTitle = false;
        userManuallyEditedSeoDesc = false;
        modalTitle.textContent = "Nueva Entrada";
        modalId.value = "";
        modalPostTitle.value = "";
        modalPostContent.value = "";
        modalPostStatus.value = "published";
        if (modalPostStatusToggle) {
            modalPostStatusToggle.checked = true;
            togglePostStatus(modalPostStatusToggle);
        }
        modalPostSlug.value = "";
        modalPostCategory.value = "0";
        modalImageInput.value = "";
        modalImagePreviewContainer.classList.remove('block');
        modalImagePreviewContainer.classList.add('hidden');
        modalImagePreview.setAttribute('src', '#');
        modalSubmitBtn.name = "create_post";
        modalSubmitBtn.textContent = "Guardar Entrada";
        
        // Limpiar nuevos campos SEO
        modalPostSeoTitle.value = "";
        modalPostSeoDescription.value = "";
        modalPostSeoKeywords.value = "";
        
        openPostModal();
    }

    function openEditPostModal(id) {
        userManuallyEditedSlug = false;
        userManuallyEditedSeoTitle = false;
        userManuallyEditedSeoDesc = false;
        modalTitle.textContent = "Cargando...";
        openPostModal();

        fetch(`/?route=admin/posts/get&id=${id}`)
            .then(res => res.json())
            .then(data => {
                if (data.success && data.post) {
                    const post = data.post;
                    modalTitle.textContent = "Editar Entrada";
                    modalId.value = post.id;
                    modalPostTitle.value = post.title;
                    modalPostContent.value = post.content;
                    modalPostStatus.value = post.status;
                    if (modalPostStatusToggle) {
                        modalPostStatusToggle.checked = (post.status === 'published');
                        togglePostStatus(modalPostStatusToggle);
                    }
                    modalPostSlug.value = post.slug;
                    modalPostCategory.value = post.category_id ? post.category_id : "0";
                    modalImageInput.value = "";
                    modalSubmitBtn.name = "update_post";
                    modalSubmitBtn.textContent = "Guardar Cambios";

                    if (post.featured_image) {
                        modalImagePreview.setAttribute('src', `/uploads/${post.featured_image}`);
                        modalImagePreviewContainer.classList.remove('hidden');
                        modalImagePreviewContainer.classList.add('block');
                    } else {
                        modalImagePreviewContainer.classList.remove('block');
                        modalImagePreviewContainer.classList.add('hidden');
                        modalImagePreview.setAttribute('src', '#');
                    }
                    
                    // Asignar nuevos campos SEO
                    modalPostSeoTitle.value = post.seo_title ? post.seo_title : "";
                    modalPostSeoDescription.value = post.seo_description ? post.seo_description : "";
                    modalPostSeoKeywords.value = post.seo_keywords ? post.seo_keywords : "";

                    // Determinar si ya fueron editados manualmente antes de abrir
                    userManuallyEditedSeoTitle = (post.seo_title && post.seo_title !== post.title);
                    userManuallyEditedSeoDesc = (post.seo_description && post.seo_description !== generateSeoDescription(post.content));
                } else {
                    alert("Error al cargar la entrada.");
                    closePostModal();
                }
            })
            .catch(err => {
                console.error(err);
                alert("Error de red al conectar con el servidor.");
                closePostModal();
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (modalPostTitle && modalPostSlug) {
            modalPostTitle.addEventListener('input', () => {
                if (!userManuallyEditedSlug) {
                    modalPostSlug.value = generateSlug(modalPostTitle.value);
                }
                if (!userManuallyEditedSeoTitle && modalPostSeoTitle) {
                    modalPostSeoTitle.value = modalPostTitle.value;
                }
            });

            modalPostSlug.addEventListener('input', () => {
                if (modalPostSlug.value.trim() === '') {
                    userManuallyEditedSlug = false;
                    modalPostSlug.value = generateSlug(modalPostTitle.value);
                } else {
                    userManuallyEditedSlug = true;
                }
            });
        }

        if (modalPostSeoTitle) {
            modalPostSeoTitle.addEventListener('input', () => {
                if (modalPostSeoTitle.value.trim() === '') {
                    userManuallyEditedSeoTitle = false;
                    modalPostSeoTitle.value = modalPostTitle.value;
                } else {
                    userManuallyEditedSeoTitle = true;
                }
            });
        }

        if (modalPostContent && modalPostSeoDescription) {
            modalPostContent.addEventListener('input', () => {
                if (!userManuallyEditedSeoDesc) {
                    modalPostSeoDescription.value = generateSeoDescription(modalPostContent.value);
                }
            });
        }

        if (modalPostSeoDescription) {
            modalPostSeoDescription.addEventListener('input', () => {
                if (modalPostSeoDescription.value.trim() === '') {
                    userManuallyEditedSeoDesc = false;
                    modalPostSeoDescription.value = generateSeoDescription(modalPostContent.value);
                } else {
                    userManuallyEditedSeoDesc = true;
                }
            });
        }

        if (modalImageInput && modalImagePreviewContainer && modalImagePreview) {
            modalImageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        modalImagePreview.setAttribute('src', e.target.result);
                        modalImagePreviewContainer.classList.remove('hidden');
                        modalImagePreviewContainer.classList.add('block');
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        const hash = window.location.hash;
        if (hash === '#create') {
            openCreatePostModal();
        } else if (hash.startsWith('#edit-')) {
            const id = parseInt(hash.replace('#edit-', ''));
            if (id > 0) {
                openEditPostModal(id);
            }
        }
    });
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>

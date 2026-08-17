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
    <div class="bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-2xl max-w-2xl w-full overflow-hidden transform scale-95 opacity-0 transition-all duration-200 my-auto" id="post-modal-card">
        
        <!-- Cabecera del Modal -->
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/30">
            <h3 id="modal-title" class="font-extrabold text-slate-800 dark:text-white text-base">Nueva Entrada</h3>
            <button onclick="closePostModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <!-- Formulario Multipart -->
        <form action="/?route=admin/posts" method="POST" enctype="multipart/form-data" class="p-6">
            <input type="hidden" id="modal-post-id" name="id" value="">
            
            <!-- Barra de Pestañas (Tabs Header) -->
            <div class="flex border-b border-slate-200/60 dark:border-slate-800 gap-6 mb-6">
                <button type="button" id="tab-btn-content" onclick="switchPostTab('content')" class="pb-3 text-sm font-bold border-b-2 border-brand-500 text-brand-600 dark:text-brand-400 focus:outline-none transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Contenido
                </button>
                <button type="button" id="tab-btn-settings" onclick="switchPostTab('settings')" class="pb-3 text-sm font-medium border-b-2 border-transparent text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 focus:outline-none transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Ajustes
                </button>
                <button type="button" id="tab-btn-seo" onclick="switchPostTab('seo')" class="pb-3 text-sm font-medium border-b-2 border-transparent text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 focus:outline-none transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    SEO (Vista Previa)
                </button>
            </div>
            
            <!-- Panel 1: Contenido -->
            <div id="tab-panel-content" class="space-y-6">
                <!-- Título -->
                <div class="space-y-2">
                    <label for="modal-post-title" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Título de la Entrada *</label>
                    <input type="text" id="modal-post-title" name="title" required placeholder="Ingresa el título del post..." class="w-full px-4 py-3 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-xl focus:outline-none transition-colors text-slate-800 dark:text-slate-100">
                </div>

                <!-- Contenido (Editor) -->
                <div class="space-y-2">
                    <label for="modal-post-content" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Cuerpo del Artículo *</label>
                    <p class="text-[10px] text-slate-400">Puedes usar etiquetas HTML o Markdown simple.</p>
                    <textarea id="modal-post-content" name="content" required rows="10" placeholder="Escribe el contenido completo del artículo aquí..." class="w-full px-4 py-3 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-xl focus:outline-none transition-colors font-mono text-slate-800 dark:text-slate-100"></textarea>
                </div>
            </div>
            
            <!-- Panel 2: Ajustes -->
            <div id="tab-panel-settings" class="hidden space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Columna 1 de Ajustes -->
                    <div class="space-y-4">
                        <!-- Estado -->
                        <div class="space-y-2">
                            <label for="modal-post-status" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Estado</label>
                            <select id="modal-post-status" name="status" class="w-full px-3.5 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-xl focus:outline-none text-slate-700 dark:text-slate-300">
                                <option value="published">Publicado</option>
                                <option value="draft">Borrador</option>
                            </select>
                        </div>

                        <!-- Enlace Permanente (Slug) -->
                        <div class="space-y-2">
                            <label for="modal-post-slug" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Enlace Permanente (Slug)</label>
                            <input type="text" id="modal-post-slug" name="slug" placeholder="mi-enlace-permanente" class="w-full px-4 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-xl focus:outline-none transition-colors text-slate-800 dark:text-slate-100">
                        </div>

                        <!-- Categoría -->
                        <div class="space-y-2">
                            <label for="modal-post-category" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Categoría</label>
                            <select id="modal-post-category" name="category_id" class="w-full px-3.5 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-xl focus:outline-none text-slate-700 dark:text-slate-300">
                                <option value="0">Sin Categoría (Opcional)</option>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?php echo $cat->id; ?>"><?php echo htmlspecialchars($cat->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Columna 2 de Ajustes: Imagen Destacada -->
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

            <!-- Panel 3: SEO Vista Previa -->
            <div id="tab-panel-seo" class="hidden space-y-6">
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Previsualización en Buscadores (Google Snippet)</h4>
                    
                    <!-- Tarjeta Google Mockup -->
                    <div class="bg-white border border-slate-200 dark:border-slate-800 p-5 rounded-2xl shadow-inner font-sans space-y-1.5 dark:bg-slate-900/40">
                        <!-- URL del Sitio -->
                        <div class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1.5 truncate">
                            <span class="bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded text-[10px] text-slate-600 dark:text-slate-300">Artículo</span>
                            <span><?php echo $_SERVER['HTTP_HOST'] ?? 'localhost:8000'; ?>/articulo/<span id="seo-preview-slug" class="font-medium text-slate-700 dark:text-slate-200">mi-entrada</span></span>
                        </div>
                        
                        <!-- Meta Título -->
                        <h3 id="seo-preview-title" class="text-[19px] font-medium text-[#1a0dab] dark:text-[#8ab4f8] hover:underline cursor-pointer leading-tight truncate">
                            Mi Título de la Entrada
                        </h3>
                        
                        <!-- Meta Descripción -->
                        <p id="seo-preview-desc" class="text-xs text-[#4d5156] dark:text-[#bdc1c6] leading-relaxed break-words">
                            Escribe contenido en el cuerpo del artículo para ver la previsualización del extracto de descripción de Google...
                        </p>
                    </div>
                </div>

                <!-- Palabras Clave -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex justify-between items-center">
                        <span>Palabras Clave Extraídas (Automático)</span>
                        <span class="text-[10px] text-slate-400 font-normal">Hasta 15 palabras clave</span>
                    </h4>
                    
                    <!-- Contenedor de Palabras Clave Tags -->
                    <div id="seo-preview-keywords-container" class="flex flex-wrap gap-2 min-h-12 p-4 bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-800/80 rounded-2xl">
                        <span class="text-xs text-slate-400 italic">Escribe título y contenido para ver las palabras clave...</span>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="pt-5 mt-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
                <button type="button" onclick="closePostModal()" class="px-5 py-2.5 rounded-xl font-semibold text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-900 active:scale-95 transition-all text-sm">Cancelar</button>
                <button type="submit" id="modal-post-submit-btn" name="create_post" class="btn-primary py-2.5 px-6 text-sm font-semibold rounded-xl shadow-md">
                    Guardar
                </button>
            </div>
        </form>

    </div>
</div>

<!-- Scripts de control del Modal y Vista previa -->
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

    function switchPostTab(tab) {
        const btnContent = document.getElementById('tab-btn-content');
        const btnSettings = document.getElementById('tab-btn-settings');
        const btnSeo = document.getElementById('tab-btn-seo');
        const panelContent = document.getElementById('tab-panel-content');
        const panelSettings = document.getElementById('tab-panel-settings');
        const panelSeo = document.getElementById('tab-panel-seo');
        
        // Contenido
        if (tab === 'content') {
            btnContent.classList.add('border-brand-500', 'text-brand-600', 'dark:text-brand-400', 'font-bold');
            btnContent.classList.remove('border-transparent', 'text-slate-400', 'font-medium');
            
            btnSettings.classList.remove('border-brand-500', 'text-brand-600', 'dark:text-brand-400', 'font-bold');
            btnSettings.classList.add('border-transparent', 'text-slate-400', 'font-medium');
            
            btnSeo.classList.remove('border-brand-500', 'text-brand-600', 'dark:text-brand-400', 'font-bold');
            btnSeo.classList.add('border-transparent', 'text-slate-400', 'font-medium');
            
            panelContent.classList.remove('hidden');
            panelSettings.classList.add('hidden');
            panelSeo.classList.add('hidden');
        } 
        // Ajustes
        else if (tab === 'settings') {
            btnSettings.classList.add('border-brand-500', 'text-brand-600', 'dark:text-brand-400', 'font-bold');
            btnSettings.classList.remove('border-transparent', 'text-slate-400', 'font-medium');
            
            btnContent.classList.remove('border-brand-500', 'text-brand-600', 'dark:text-brand-400', 'font-bold');
            btnContent.classList.add('border-transparent', 'text-slate-400', 'font-medium');
            
            btnSeo.classList.remove('border-brand-500', 'text-brand-600', 'dark:text-brand-400', 'font-bold');
            btnSeo.classList.add('border-transparent', 'text-slate-400', 'font-medium');
            
            panelSettings.classList.remove('hidden');
            panelContent.classList.add('hidden');
            panelSeo.classList.add('hidden');
        }
        // SEO Preview
        else if (tab === 'seo') {
            btnSeo.classList.add('border-brand-500', 'text-brand-600', 'dark:text-brand-400', 'font-bold');
            btnSeo.classList.remove('border-transparent', 'text-slate-400', 'font-medium');
            
            btnContent.classList.remove('border-brand-500', 'text-brand-600', 'dark:text-brand-400', 'font-bold');
            btnContent.classList.add('border-transparent', 'text-slate-400', 'font-medium');
            
            btnSettings.classList.remove('border-brand-500', 'text-brand-600', 'dark:text-brand-400', 'font-bold');
            btnSettings.classList.add('border-transparent', 'text-slate-400', 'font-medium');
            
            panelSeo.classList.remove('hidden');
            panelContent.classList.add('hidden');
            panelSettings.classList.add('hidden');
        }
    }

    function updateSeoPreview() {
        const titleVal = modalPostTitle.value.trim();
        const contentVal = modalPostContent.value.trim();
        const slugVal = modalPostSlug.value.trim();

        // 1. Meta Título
        const seoPreviewTitle = document.getElementById('seo-preview-title');
        if (titleVal) {
            if (titleVal.length > 60) {
                seoPreviewTitle.textContent = titleVal.substring(0, 57) + '...';
            } else {
                seoPreviewTitle.textContent = titleVal;
            }
        } else {
            seoPreviewTitle.textContent = "Mi Título de la Entrada";
        }

        // 2. Slug
        const seoPreviewSlug = document.getElementById('seo-preview-slug');
        seoPreviewSlug.textContent = slugVal ? slugVal : (titleVal ? titleVal.toLowerCase().replace(/[^a-z0-9]+/g, '-') : 'mi-entrada');

        // 3. Meta Descripción
        const seoPreviewDesc = document.getElementById('seo-preview-desc');
        // Limpiar HTML
        let cleanText = contentVal.replace(/<\/?[^>]+(>|$)/g, ""); 
        cleanText = cleanText.replace(/\s+/g, ' ').trim();
        
        if (cleanText) {
            if (cleanText.length > 155) {
                seoPreviewDesc.textContent = cleanText.substring(0, 152) + '...';
            } else {
                if (cleanText.length > 152) {
                    seoPreviewDesc.textContent = cleanText.substring(0, 152) + '...';
                } else {
                    seoPreviewDesc.textContent = cleanText;
                }
            }
        } else {
            seoPreviewDesc.textContent = "Escribe contenido en el cuerpo del artículo para ver la previsualización del extracto de descripción de Google...";
        }

        // 4. Palabras Clave
        const keywordsContainer = document.getElementById('seo-preview-keywords-container');
        let keywordsList = [];

        // Palabras del título
        if (titleVal) {
            let titleClean = titleVal.toLowerCase().replace(/[^a-záéíóúüñ\s]/g, '');
            let titleWords = titleClean.split(/\s+/);
            titleWords.forEach(w => {
                if (w.length >= 4) keywordsList.push(w);
            });
        }

        // Palabras del contenido
        const stopWords = [
            'para', 'como', 'este', 'esta', 'estos', 'estas', 'todo', 'toda', 'todos', 'todas', 
            'sobre', 'entre', 'desde', 'hasta', 'hacia', 'donde', 'cuando', 'quien', 'cual', 'cuyo', 
            'pero', 'sino', 'porque', 'pues', 'aunque', 'tambien', 'tampoco', 'luego', 'despues', 
            'antes', 'ahora', 'mientras', 'durante', 'contra', 'segundo', 'primero', 'suyo', 'suya', 
            'suyos', 'suyas', 'nuestro', 'nuestra', 'nuestros', 'nuestras', 'vuestro', 'vuestra', 
            'vuestros', 'vuestras', 'con', 'sin', 'por', 'del', 'los', 'las', 'una', 'uno', 'unos', 
            'unas', 'sus', 'ese', 'esa', 'esos', 'esas', 'muy', 'mas', 'bien', 'mal', 'siempre', 
            'nunca', 'jamas', 'tal', 'tales', 'otro', 'otra', 'otros', 'otras', 'algun', 'alguna', 
            'algunos', 'algunas', 'ningun', 'ninguna', 'ningunos', 'ningunas', 'cada', 'ambos', 'ambas', 
            'mucho', 'mucha', 'muchos', 'muchas', 'poco', 'poca', 'pocos', 'pocas', 'tanto', 'tanta', 
            'tantos', 'tantas', 'demas', 'mismo', 'misma', 'mismos', 'mismas', 'propio', 'propia', 
            'propios', 'propias', 'tiene', 'tienen', 'hacer', 'puede', 'pueden', 'crear', 'nuevo', 'nueva'
        ];

        if (cleanText) {
            let contentClean = cleanText.toLowerCase().replace(/[^a-záéíóúüñ\s]/g, '');
            let contentWords = contentClean.split(/\s+/);
            let wordCounts = {};

            contentWords.forEach(w => {
                if (w.length >= 4 && !stopWords.includes(w)) {
                    wordCounts[w] = (wordCounts[w] || 0) + 1;
                }
            });

            let sortedWords = Object.keys(wordCounts).sort((a, b) => wordCounts[b] - wordCounts[a]);
            let topContentWords = sortedWords.slice(0, 10);
            keywordsList = keywordsList.concat(topContentWords);
        }

        keywordsList = [...new Set(keywordsList)];
        keywordsList = keywordsList.slice(0, 15);

        keywordsContainer.innerHTML = "";
        if (keywordsList.length > 0) {
            keywordsList.forEach(k => {
                const badge = document.createElement('span');
                badge.className = "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-brand-50 dark:bg-brand-950/40 text-brand-600 dark:text-brand-400 border border-brand-100 dark:border-brand-900/40";
                badge.textContent = k;
                keywordsContainer.appendChild(badge);
            });
        } else {
            keywordsContainer.innerHTML = `<span class="text-xs text-slate-400 italic">Escribe título y contenido para ver las palabras clave...</span>`;
        }
    }

    function openCreatePostModal() {
        switchPostTab('content');
        modalTitle.textContent = "Nueva Entrada";
        modalId.value = "";
        modalPostTitle.value = "";
        modalPostContent.value = "";
        modalPostStatus.value = "published";
        modalPostSlug.value = "";
        modalPostCategory.value = "0";
        modalImageInput.value = "";
        modalImagePreviewContainer.classList.remove('block');
        modalImagePreviewContainer.classList.add('hidden');
        modalImagePreview.setAttribute('src', '#');
        modalSubmitBtn.name = "create_post";
        modalSubmitBtn.textContent = "Guardar Entrada";
        
        updateSeoPreview();
        openPostModal();
    }

    function openEditPostModal(id) {
        switchPostTab('content');
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
                    updateSeoPreview();
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
        if (modalPostTitle && modalPostContent && modalPostSlug) {
            modalPostTitle.addEventListener('input', () => {
                if (modalSubmitBtn.name === "create_post") {
                    let slug = modalPostTitle.value.toLowerCase()
                        .trim()
                        .normalize('NFD')
                        .replace(/[\u0300-\u036f]/g, '')
                        .replace(/[^a-z0-9\s-]/g, '')
                        .replace(/[\s_]+/g, '-')
                        .replace(/^-+|-+$/g, '');
                    modalPostSlug.value = slug;
                }
                updateSeoPreview();
            });
            modalPostContent.addEventListener('input', updateSeoPreview);
            modalPostSlug.addEventListener('input', updateSeoPreview);
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

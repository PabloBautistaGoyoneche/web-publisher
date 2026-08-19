<?php
require __DIR__ . '/../layout/header.php';
?>

<!-- Enlace de regreso -->
<div class="mb-6">
    <a href="/?route=admin/pages" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-600 dark:text-brand-400 hover:underline">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Volver a la lista de páginas
    </a>
</div>

<!-- Cabecera de Página -->
<div class="mb-10">
    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
        Nueva Página Estática
    </h1>
    <p class="text-sm text-slate-500 mt-1">
        Crea una nueva página de información u obligatoria en el sitio.
    </p>
</div>

<!-- Alerta de Error -->
<?php if (isset($error) && $error): ?>
    <div class="mb-6 p-4 bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900/60 rounded-2xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <span><?php echo htmlspecialchars($error); ?></span>
    </div>
<?php endif; ?>

<!-- Formulario de Creación -->
<form action="/?route=admin/pages/create" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Contenido (Izquierda) -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Campo: Título -->
        <div class="glass-card rounded-3xl p-6 border border-slate-200/50 dark:border-slate-800/80 space-y-2">
            <label for="title" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Título de la Página *</label>
            <input type="text" id="title" name="title" required placeholder="ej: Política de Cookies" class="w-full px-4 py-3 text-sm bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none transition-colors">
        </div>

        <!-- Campo: Contenido -->
        <div class="glass-card rounded-3xl p-6 border border-slate-200/50 dark:border-slate-800/80 space-y-2">
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider font-semibold">Contenido de la Página *</label>
            <p class="text-[10px] text-slate-400 mb-2">Utiliza el editor visual enriquecido para formatear la página de forma dinámica.</p>
            
            <!-- Estilos de Quill personalizados para modo oscuro -->
            <style>
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
                
                #editor-container {
                    border-bottom-left-radius: 0.75rem;
                    border-bottom-right-radius: 0.75rem;
                    border-color: rgba(226, 232, 240, 0.8) !important;
                }
                .dark #editor-container {
                    border-color: rgba(30, 41, 59, 0.8) !important;
                }
                .ql-container.ql-snow {
                    border-color: rgba(226, 232, 240, 0.8) !important;
                }
                .ql-editor {
                    min-height: 350px;
                    font-family: 'Outfit', 'Inter', sans-serif;
                    font-size: 14px;
                }
                .ql-editor.ql-blank::before {
                    color: #94a3b8 !important;
                    font-style: normal;
                }
            </style>

            <div id="editor-container" class="bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100"></div>
            
            <textarea id="content" name="content" style="display:none;" required></textarea>
        </div>

    </div>

    <!-- Ajustes laterales (Derecha) -->
    <div class="lg:col-span-1 space-y-6">
        
        <!-- Tarjeta: Publicación -->
        <div class="glass-card rounded-3xl p-6 border border-slate-200/50 dark:border-slate-800/80 space-y-4">
            <h3 class="font-extrabold text-sm border-b border-slate-100 dark:border-slate-800 pb-3 uppercase tracking-wider text-slate-400">Publicación</h3>

            <!-- Slug -->
            <div class="space-y-2">
                <label for="slug" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Slug (Enlace Permanente)</label>
                <input type="text" id="slug" name="slug" placeholder="ej: politica-cookies" class="w-full px-3.5 py-2.5 text-sm bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 focus:border-brand-500 rounded-xl focus:outline-none transition-colors">
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full btn-primary py-3 text-sm font-semibold rounded-xl">
                    Guardar Página
                </button>
            </div>
        </div>

    </div>

</form>

<!-- Autogenerador de Slug en tiempo real -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        
        if (titleInput && slugInput) {
            titleInput.addEventListener('input', function() {
                let slug = titleInput.value.toLowerCase()
                    .trim()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/[\s_]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                
                slugInput.value = slug;
            });
        }

        // Inicializar Quill
        const quill = new Quill('#editor-container', {
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

        // Sincronizar contenido antes de enviar el formulario
        const form = document.querySelector('form');
        form.addEventListener('submit', function() {
            const contentInput = document.getElementById('content');
            contentInput.value = quill.root.innerHTML;
        });
    });
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>

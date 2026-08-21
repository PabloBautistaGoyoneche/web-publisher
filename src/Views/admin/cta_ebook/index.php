<?php
use App\Helpers;
require dirname(__DIR__) . '/layout/header.php';
?>

<!-- Sección: Cabecera -->
<div class="mb-10">
    <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">
        Módulo CTA eBook
    </h1>
    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
        Personaliza los textos del bloque de llamada a la acción inferior y gestiona el archivo del eBook que tus visitantes podrán descargar.
    </p>
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

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
    <!-- Formulario de Configuración -->
    <div class="lg:col-span-2 glass-card rounded-3xl p-8 border border-slate-200/50 dark:border-slate-800/80 shadow-md">
        <form action="/?route=admin/cta-ebook" method="POST" enctype="multipart/form-data" class="space-y-8">
            <input type="hidden" name="action" value="save">

            <!-- CONFIGURACIÓN DE TEXTOS -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-brand-600 dark:text-brand-400 uppercase tracking-wider border-b border-slate-200/50 dark:border-slate-800/50 pb-2">Contenido de la Sección</h3>
                
                <div class="space-y-2">
                    <label for="cta_ebook_title" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Título del CTA</label>
                    <input type="text" id="cta_ebook_title" name="cta_ebook_title" required 
                           value="<?php echo htmlspecialchars($ctaEbookTitle); ?>" 
                           class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-2xl focus:outline-none transition-all font-semibold text-slate-800 dark:text-slate-200">
                </div>

                <div class="space-y-2">
                    <label for="cta_ebook_desc" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Descripción del CTA</label>
                    <textarea id="cta_ebook_desc" name="cta_ebook_desc" rows="3" required 
                              class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-2xl focus:outline-none transition-all font-medium text-slate-800 dark:text-slate-200"><?php echo htmlspecialchars($ctaEbookDesc); ?></textarea>
                </div>

                <div class="space-y-2">
                    <label for="cta_ebook_button" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Texto del Botón</label>
                    <input type="text" id="cta_ebook_button" name="cta_ebook_button" required 
                           value="<?php echo htmlspecialchars($ctaEbookButton); ?>" 
                           class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-2xl focus:outline-none transition-all font-semibold text-slate-800 dark:text-slate-200">
                </div>

                <div class="space-y-2">
                    <label for="cta_ebook_delay" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Segundos de espera para el Pop-up</label>
                    <input type="number" id="cta_ebook_delay" name="cta_ebook_delay" min="1" max="120" required 
                           value="<?php echo htmlspecialchars($ctaEbookDelay); ?>" 
                           class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-2xl focus:outline-none transition-all font-semibold text-slate-800 dark:text-slate-200">
                    <p class="text-[11px] text-slate-400">Determina cuántos segundos deben transcurrir tras cargar la página para que se muestre automáticamente la ventana modal.</p>
                </div>
            </div>

            <!-- GESTIÓN DE ARCHIVO -->
            <div class="space-y-4 pt-2">
                <h3 class="text-xs font-bold text-brand-600 dark:text-brand-400 uppercase tracking-wider border-b border-slate-200/50 dark:border-slate-800/50 pb-2">Archivo del eBook</h3>
                
                <?php 
                $hasUploadedFile = (!empty($ctaEbookLink) && $ctaEbookLink !== '#' && !str_starts_with($ctaEbookLink, 'http'));
                ?>

                <?php if ($hasUploadedFile): ?>
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 rounded-xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div class="min-w-0">
                                <span class="block text-xs font-semibold text-slate-400">Archivo actual</span>
                                <a href="/uploads/<?php echo $ctaEbookLink; ?>" target="_blank" class="text-sm font-bold text-slate-800 dark:text-slate-200 hover:text-brand-600 dark:hover:text-brand-400 hover:underline break-all">
                                    <?php echo htmlspecialchars($ctaEbookLink); ?>
                                </a>
                            </div>
                        </div>
                        <button type="button" onclick="confirmDeleteFile()" class="px-4 py-2 text-xs font-bold text-red-600 bg-red-50 dark:bg-red-950/30 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-xl transition-all flex items-center gap-1.5 active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Eliminar
                        </button>
                    </div>
                <?php endif; ?>

                <div class="space-y-3">
                    <label for="cta_ebook_file" class="block text-xs font-semibold text-slate-400">
                        <?php echo $hasUploadedFile ? 'Reemplazar archivo del eBook' : 'Subir archivo del eBook'; ?>
                    </label>
                    <input type="file" id="cta_ebook_file" name="cta_ebook_file" accept=".pdf,.epub,.zip,.rar,.mobi,.docx" class="text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer w-full">
                    <p class="text-[11px] text-slate-400 leading-normal">Tipos de archivo permitidos: PDF, EPUB, MOBI, ZIP, RAR, DOCX. Peso máximo recomendado: 20MB.</p>
                </div>

                <div class="space-y-3 pt-2">
                    <label for="cta_ebook_link" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Enlace manual del recurso (Opcional)</label>
                    <input type="text" id="cta_ebook_link" name="cta_ebook_link" 
                           value="<?php echo htmlspecialchars($ctaEbookLink); ?>" 
                           class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-2xl focus:outline-none transition-all font-mono text-xs font-bold text-slate-800 dark:text-slate-200">
                    <p class="text-xs text-slate-400">Si subes un archivo, este campo se actualizará automáticamente con el nombre del archivo. También puedes escribir un enlace externo manualmente (ej: <code>https://tu-sitio.com/recurso</code>).</p>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="pt-6 border-t border-slate-200/50 dark:border-slate-800/80 flex items-center justify-end gap-3">
                <a href="/?route=admin/dashboard" class="btn-secondary text-sm rounded-xl py-2.5 px-5">Cancelar</a>
                <button type="submit" class="btn-primary text-sm rounded-xl py-2.5 px-5 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>

    <!-- Panel de Vista Previa -->
    <div class="space-y-6">
        <div class="glass-card rounded-3xl p-6 border border-slate-200/50 dark:border-slate-800/80 shadow-md">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-4">Vista Previa del CTA</h3>
            
            <!-- Simulación del CTA en el Frontend -->
            <div class="rounded-2xl border border-slate-100 dark:border-slate-850 p-6 space-y-4 bg-slate-50/50 dark:bg-slate-900/30 text-center transition-colors">
                <div class="inline-flex items-center justify-center p-2 bg-brand-50 dark:bg-brand-950/40 text-brand-600 dark:text-brand-400 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                
                <h4 id="preview-title" class="font-extrabold text-slate-800 dark:text-white text-base leading-snug">
                    <?php echo htmlspecialchars($ctaEbookTitle); ?>
                </h4>
                
                <p id="preview-desc" class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                    <?php echo htmlspecialchars($ctaEbookDesc); ?>
                </p>
                
                <div class="pt-1">
                    <span id="preview-btn" class="inline-flex items-center gap-1.5 btn-primary py-2 px-4 text-xs font-semibold rounded-xl cursor-default">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span><?php echo htmlspecialchars($ctaEbookButton); ?></span>
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-900/50 rounded-3xl p-6 text-xs text-blue-700 dark:text-blue-400 space-y-2.5">
            <h4 class="font-bold flex items-center gap-1.5">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Instrucciones del Módulo
            </h4>
            <p class="leading-relaxed">
                Este módulo administra la llamada a la acción (CTA) para promocionar y descargar un eBook o guía en el pie de página de todo el blog.
            </p>
            <p class="leading-relaxed">
                Puedes subir el archivo (ej. PDF) directamente o colocar una URL externa. Si subes un archivo, el sistema se encargará de gestionarlo y generar el enlace de descarga automáticamente.
            </p>
        </div>
    </div>
</div>

<!-- Formulario oculto para eliminar el archivo -->
<form id="delete-file-form" action="/?route=admin/cta-ebook" method="POST" style="display: none;">
    <input type="hidden" name="action" value="delete_file">
</form>

<script>
    function confirmDeleteFile() {
        if (confirm('¿Estás seguro de que deseas eliminar el archivo del eBook del servidor? Esto restablecerá el enlace de descarga a "#".')) {
            document.getElementById('delete-file-form').submit();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('cta_ebook_title');
        const descInput = document.getElementById('cta_ebook_desc');
        const btnInput = document.getElementById('cta_ebook_button');
        const linkInput = document.getElementById('cta_ebook_link');
        const fileInput = document.getElementById('cta_ebook_file');

        const previewTitle = document.getElementById('preview-title');
        const previewDesc = document.getElementById('preview-desc');
        const previewBtn = document.getElementById('preview-btn').querySelector('span');

        if (titleInput && previewTitle) {
            titleInput.addEventListener('input', function() {
                previewTitle.textContent = this.value || 'Descarga nuestro eBook';
            });
        }

        if (descInput && previewDesc) {
            descInput.addEventListener('input', function() {
                previewDesc.textContent = this.value || 'Descripción del recurso...';
            });
        }

        if (btnInput && previewBtn) {
            btnInput.addEventListener('input', function() {
                previewBtn.textContent = this.value || 'Descargar';
            });
        }

        if (fileInput && linkInput) {
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    // Mostrar cambio sugerido de nombre de archivo en la ayuda
                    const fileName = this.files[0].name;
                    linkInput.placeholder = 'ebook_[fecha]_[id].' + fileName.split('.').pop() + ' (Se autogenerará al guardar)';
                }
            });
        }
    });
</script>

<?php require dirname(__DIR__) . '/layout/footer.php'; ?>

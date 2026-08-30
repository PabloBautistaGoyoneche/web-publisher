<?php
$title = "Editar Perfil - Admin Panel";
require __DIR__ . '/layout/header.php';
?>

<div class="max-w-3xl mx-auto py-8">
    <div class="glass-card rounded-3xl p-8 border border-slate-200/50 dark:border-slate-800/80 shadow-xl space-y-8">
        
        <div class="flex items-center gap-4 border-b border-slate-200/50 dark:border-slate-800/50 pb-6">
            <div class="p-3 bg-brand-500/10 text-brand-600 dark:text-brand-400 rounded-2xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Editar Perfil de Autor</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Actualiza tus detalles personales, biografía, foto de perfil y contraseña.</p>
            </div>
        </div>

        <?php if (!empty($error)): ?>
            <div class="p-4 bg-red-500/10 border border-red-500/20 text-red-500 rounded-2xl text-sm font-semibold flex items-center gap-3 animate-fade-in">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-2xl text-sm font-semibold flex items-center gap-3 animate-fade-in">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span><?php echo htmlspecialchars($success); ?></span>
            </div>
        <?php endif; ?>

        <form action="/?route=admin/profile" method="POST" enctype="multipart/form-data" class="space-y-8">
            
            <!-- SECCIÓN 1: FOTO DE PERFIL / AVATAR -->
            <div class="flex flex-col sm:flex-row items-center gap-6 p-6 bg-slate-50/50 dark:bg-slate-900/30 rounded-2xl border border-slate-200/50 dark:border-slate-800/80">
                <div class="relative group">
                    <div class="w-24 h-24 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-md flex items-center justify-center bg-gradient-to-tr from-brand-600 to-secondary-600 text-white font-extrabold text-3xl select-none" id="avatar-preview-container">
                        <?php if (!empty($user->avatar) && file_exists(dirname(dirname(dirname(dirname(__DIR__)))) . '/public/uploads/' . $user->avatar)): ?>
                            <img src="/uploads/<?php echo htmlspecialchars($user->avatar); ?>" id="avatar-preview-img" class="w-full h-full object-cover" alt="Avatar">
                        <?php else: ?>
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="avatar-preview-placeholder">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="space-y-2 text-center sm:text-left flex-grow">
                    <span class="block text-sm font-bold text-slate-700 dark:text-slate-300">Foto de Perfil (Avatar)</span>
                    <p class="text-xs text-slate-400">Esta imagen se mostrará en la caja de autor al final de tus entradas y en la barra superior. Formatos: JPG, PNG, WEBP (se optimizará a WebP).</p>
                    
                    <div class="pt-2">
                        <label for="avatar-input" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold cursor-pointer transition-colors shadow-md shadow-brand-500/10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Subir Nueva Foto
                        </label>
                        <input type="file" id="avatar-input" name="avatar" accept="image/jpeg, image/png, image/webp" class="hidden">
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 2: DETALLES PERSONALES -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="display_name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Nombre Completo (Autor)</label>
                    <input type="text" id="display_name" name="display_name" required 
                           value="<?php echo htmlspecialchars($user->display_name); ?>" 
                           class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 dark:focus:border-brand-400 rounded-2xl focus:outline-none focus:ring-1 focus:ring-brand-500 transition-all font-semibold text-slate-800 dark:text-slate-200">
                </div>

                <div class="space-y-2">
                    <label for="username" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Nombre de Usuario</label>
                    <input type="text" id="username" name="username" required 
                           value="<?php echo htmlspecialchars($user->username); ?>" 
                           class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 dark:focus:border-brand-400 rounded-2xl focus:outline-none focus:ring-1 focus:ring-brand-500 transition-all font-semibold text-slate-800 dark:text-slate-200">
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Correo Electrónico</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo htmlspecialchars($user->email); ?>" 
                           class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 dark:focus:border-brand-400 rounded-2xl focus:outline-none focus:ring-1 focus:ring-brand-500 transition-all font-semibold text-slate-800 dark:text-slate-200">
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label for="bio" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Biografía / Sobre Mí</label>
                    <textarea id="bio" name="bio" rows="4" 
                              class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 dark:focus:border-brand-400 rounded-2xl focus:outline-none focus:ring-1 focus:ring-brand-500 transition-all font-semibold text-slate-800 dark:text-slate-200"
                              placeholder="Cuéntanos un poco sobre ti. Esta biografía aparecerá al final de tus artículos."><?php echo htmlspecialchars($user->bio ?? ''); ?></textarea>
                </div>
            </div>

            <!-- SECCIÓN 3: CAMBIO DE CONTRASEÑA (OPCIONAL) -->
            <div class="pt-4 border-t border-slate-200/50 dark:border-slate-800/50 space-y-4">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Seguridad (Cambiar Contraseña)</h3>
                <p class="text-xs text-slate-400">Si deseas conservar tu contraseña actual, deja estos campos en blanco.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="new_password" class="block text-xs font-semibold text-slate-400">Nueva Contraseña</label>
                        <div class="relative">
                            <input type="password" id="new_password" name="new_password" autocomplete="new-password"
                                   class="w-full pl-4 pr-12 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 dark:focus:border-brand-400 rounded-2xl focus:outline-none focus:ring-1 focus:ring-brand-500 transition-all font-semibold text-slate-800 dark:text-slate-200"
                                   placeholder="Crea una contraseña segura">
                            <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-650 dark:hover:text-slate-250 transition-colors focus:outline-none toggle-password" data-target="new_password" title="Mostrar/Ocultar contraseña">
                                <!-- Eye Icon -->
                                <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <!-- Eye-slash Icon (hidden by default) -->
                                <svg class="w-5 h-5 eye-slash-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="confirm_password" class="block text-xs font-semibold text-slate-400">Confirmar Nueva Contraseña</label>
                        <div class="relative">
                            <input type="password" id="confirm_password" name="confirm_password" autocomplete="new-password"
                                   class="w-full pl-4 pr-12 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 dark:focus:border-brand-400 rounded-2xl focus:outline-none focus:ring-1 focus:ring-brand-500 transition-all font-semibold text-slate-800 dark:text-slate-200"
                                   placeholder="Repite la contraseña">
                            <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-650 dark:hover:text-slate-250 transition-colors focus:outline-none toggle-password" data-target="confirm_password" title="Mostrar/Ocultar contraseña">
                                <!-- Eye Icon -->
                                <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <!-- Eye-slash Icon (hidden by default) -->
                                <svg class="w-5 h-5 eye-slash-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BOTONES DE ACCIÓN -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200/50 dark:border-slate-800/50">
                <a href="/?route=admin/dashboard" class="btn-secondary py-3 px-6 text-sm rounded-xl font-semibold">Cancelar</a>
                <button type="submit" class="btn-primary py-3 px-6 text-sm rounded-xl font-semibold">
                    Guardar Perfil
                </button>
            </div>

        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarInput = document.getElementById('avatar-input');
    const avatarPreviewContainer = document.getElementById('avatar-preview-container');
    
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    let img = document.getElementById('avatar-preview-img');
                    if (!img) {
                        // Si no había imagen previa (estaba el placeholder SVG)
                        const placeholder = document.getElementById('avatar-preview-placeholder');
                        if (placeholder) placeholder.remove();
                        
                        img = document.createElement('img');
                        img.id = 'avatar-preview-img';
                        img.className = 'w-full h-full object-cover';
                        avatarPreviewContainer.appendChild(img);
                    }
                    img.src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Mostrar/ocultar contraseñas
    const toggleButtons = document.querySelectorAll('.toggle-password');
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const eyeIcon = this.querySelector('.eye-icon');
            const eyeSlashIcon = this.querySelector('.eye-slash-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
            }
        });
    });
});
</script>

<?php require __DIR__ . '/layout/footer.php'; ?>

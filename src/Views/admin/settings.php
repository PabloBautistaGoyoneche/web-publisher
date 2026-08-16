<?php
use App\Helpers;
require __DIR__ . '/layout/header.php';
?>

<!-- Sección: Cabecera -->
<div class="mb-10">
    <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">
        Identidad del Sitio
    </h1>
    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
        Personaliza el nombre del blog y controla todos los colores de fondo, cabecera, pie de página y botones para los modos claro y oscuro.
    </p>
</div>

<!-- Alertas -->
<?php if (isset($success) && $success): ?>
    <div class="mb-8 p-4 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900/50 rounded-2xl text-sm font-semibold flex items-center gap-2 shadow-sm">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span>¡Configuraciones guardadas con éxito! Los colores y el nombre del blog se han actualizado en todo el sitio de inmediato.</span>
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
        <form action="/?route=admin/settings" method="POST" class="space-y-8">
            
            <!-- SECCIÓN 1: NOMBRE DEL BLOG -->
            <div class="space-y-2.5">
                <h3 class="text-xs font-bold text-brand-600 dark:text-brand-400 uppercase tracking-wider border-b border-slate-200/50 dark:border-slate-800/50 pb-2">Configuración General</h3>
                <label for="site_name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mt-3">Nombre del Blog</label>
                <input type="text" id="site_name" name="site_name" required 
                       value="<?php echo htmlspecialchars($siteName); ?>" 
                       class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 dark:focus:border-brand-400 rounded-2xl focus:outline-none focus:ring-1 focus:ring-brand-500 transition-all font-semibold text-slate-800 dark:text-slate-200">
                <p class="text-xs text-slate-400">Este nombre se usará en el logo de la cabecera, copyright de pie de página y títulos HTML del navegador.</p>
            </div>

            <!-- SECCIÓN 2: COLORES DE ACENTO -->
            <div class="space-y-4 pt-2">
                <h3 class="text-xs font-bold text-brand-600 dark:text-brand-400 uppercase tracking-wider border-b border-slate-200/50 dark:border-slate-800/50 pb-2">Colores de Acento y Botones</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Acento Claro (Primary & Secondary) -->
                    <div class="space-y-4">
                        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Paleta del Modo Claro</h4>
                        
                        <div class="space-y-3">
                            <label class="block text-xs font-semibold text-slate-400">Color Primario (Base)</label>
                            <div class="flex gap-3">
                                <div class="relative w-12 h-12 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 flex-shrink-0">
                                    <input type="color" id="theme_light_picker" value="<?php echo htmlspecialchars($themeLight); ?>" class="absolute inset-0 w-full h-full p-0 border-0 cursor-pointer scale-150">
                                </div>
                                <input type="text" id="theme_light_primary" name="theme_light_primary" required value="<?php echo htmlspecialchars($themeLight); ?>" class="flex-grow px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-2xl focus:outline-none transition-all font-mono font-bold text-slate-800 dark:text-slate-200 uppercase">
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-xs font-semibold text-slate-400">Color Secundario (Degradados)</label>
                            <div class="flex gap-3">
                                <div class="relative w-12 h-12 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 flex-shrink-0">
                                    <input type="color" id="theme_light_sec_picker" value="<?php echo htmlspecialchars($themeLightSec); ?>" class="absolute inset-0 w-full h-full p-0 border-0 cursor-pointer scale-150">
                                </div>
                                <input type="text" id="theme_light_secondary" name="theme_light_secondary" required value="<?php echo htmlspecialchars($themeLightSec); ?>" class="flex-grow px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-2xl focus:outline-none transition-all font-mono font-bold text-slate-800 dark:text-slate-200 uppercase">
                            </div>
                        </div>
                    </div>

                    <!-- Acento Oscuro (Primary & Secondary) -->
                    <div class="space-y-4">
                        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Paleta del Modo Oscuro</h4>
                        
                        <div class="space-y-3">
                            <label class="block text-xs font-semibold text-slate-400">Color Primario (Base)</label>
                            <div class="flex gap-3">
                                <div class="relative w-12 h-12 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 flex-shrink-0">
                                    <input type="color" id="theme_dark_picker" value="<?php echo htmlspecialchars($themeDark); ?>" class="absolute inset-0 w-full h-full p-0 border-0 cursor-pointer scale-150">
                                </div>
                                <input type="text" id="theme_dark_primary" name="theme_dark_primary" required value="<?php echo htmlspecialchars($themeDark); ?>" class="flex-grow px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-2xl focus:outline-none transition-all font-mono font-bold text-slate-800 dark:text-slate-200 uppercase">
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-xs font-semibold text-slate-400">Color Secundario (Degradados)</label>
                            <div class="flex gap-3">
                                <div class="relative w-12 h-12 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 flex-shrink-0">
                                    <input type="color" id="theme_dark_sec_picker" value="<?php echo htmlspecialchars($themeDarkSec); ?>" class="absolute inset-0 w-full h-full p-0 border-0 cursor-pointer scale-150">
                                </div>
                                <input type="text" id="theme_dark_secondary" name="theme_dark_secondary" required value="<?php echo htmlspecialchars($themeDarkSec); ?>" class="flex-grow px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-2xl focus:outline-none transition-all font-mono font-bold text-slate-800 dark:text-slate-200 uppercase">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 3: FONDOS DEL SITIO -->
            <div class="space-y-4 pt-2">
                <h3 class="text-xs font-bold text-brand-600 dark:text-brand-400 uppercase tracking-wider border-b border-slate-200/50 dark:border-slate-800/50 pb-2">Colores de Fondo (Cuerpo del Sitio)</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Fondo Light -->
                    <div class="space-y-3">
                        <label class="block text-xs font-semibold text-slate-400">Fondo General (Modo Claro)</label>
                        <div class="flex gap-3">
                            <div class="relative w-12 h-12 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 flex-shrink-0">
                                <input type="color" id="theme_light_bg_picker" value="<?php echo htmlspecialchars($themeLightBg); ?>" class="absolute inset-0 w-full h-full p-0 border-0 cursor-pointer scale-150">
                            </div>
                            <input type="text" id="theme_light_bg" name="theme_light_bg" required value="<?php echo htmlspecialchars($themeLightBg); ?>" class="flex-grow px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-2xl focus:outline-none transition-all font-mono font-bold text-slate-800 dark:text-slate-200 uppercase">
                        </div>
                    </div>

                    <!-- Fondo Dark -->
                    <div class="space-y-3">
                        <label class="block text-xs font-semibold text-slate-400">Fondo General (Modo Oscuro)</label>
                        <div class="flex gap-3">
                            <div class="relative w-12 h-12 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 flex-shrink-0">
                                <input type="color" id="theme_dark_bg_picker" value="<?php echo htmlspecialchars($themeDarkBg); ?>" class="absolute inset-0 w-full h-full p-0 border-0 cursor-pointer scale-150">
                            </div>
                            <input type="text" id="theme_dark_bg" name="theme_dark_bg" required value="<?php echo htmlspecialchars($themeDarkBg); ?>" class="flex-grow px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-2xl focus:outline-none transition-all font-mono font-bold text-slate-800 dark:text-slate-200 uppercase">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 4: CABECERA Y FOOTER -->
            <div class="space-y-4 pt-2">
                <h3 class="text-xs font-bold text-brand-600 dark:text-brand-400 uppercase tracking-wider border-b border-slate-200/50 dark:border-slate-800/50 pb-2">Colores de Cabecera y Pie de Página</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Cabecera -->
                    <div class="space-y-4">
                        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Cabecera / Navbar</h4>
                        <div class="space-y-3">
                            <label class="block text-xs font-semibold text-slate-400">Fondo de Cabecera (Modo Claro)</label>
                            <div class="flex gap-3">
                                <div class="relative w-12 h-12 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 flex-shrink-0">
                                    <input type="color" id="theme_light_header_picker" value="<?php echo htmlspecialchars($themeLightHeader); ?>" class="absolute inset-0 w-full h-full p-0 border-0 cursor-pointer scale-150">
                                </div>
                                <input type="text" id="theme_light_header" name="theme_light_header" required value="<?php echo htmlspecialchars($themeLightHeader); ?>" class="flex-grow px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-2xl focus:outline-none transition-all font-mono font-bold text-slate-800 dark:text-slate-200 uppercase">
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="block text-xs font-semibold text-slate-400">Fondo de Cabecera (Modo Oscuro)</label>
                            <div class="flex gap-3">
                                <div class="relative w-12 h-12 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 flex-shrink-0">
                                    <input type="color" id="theme_dark_header_picker" value="<?php echo htmlspecialchars($themeDarkHeader); ?>" class="absolute inset-0 w-full h-full p-0 border-0 cursor-pointer scale-150">
                                </div>
                                <input type="text" id="theme_dark_header" name="theme_dark_header" required value="<?php echo htmlspecialchars($themeDarkHeader); ?>" class="flex-grow px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-2xl focus:outline-none transition-all font-mono font-bold text-slate-800 dark:text-slate-200 uppercase">
                            </div>
                        </div>
                    </div>

                    <!-- Pie de Página -->
                    <div class="space-y-4">
                        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pie de Página / Footer</h4>
                        <div class="space-y-3">
                            <label class="block text-xs font-semibold text-slate-400">Fondo de Footer (Modo Claro)</label>
                            <div class="flex gap-3">
                                <div class="relative w-12 h-12 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 flex-shrink-0">
                                    <input type="color" id="theme_light_footer_picker" value="<?php echo htmlspecialchars($themeLightFooter); ?>" class="absolute inset-0 w-full h-full p-0 border-0 cursor-pointer scale-150">
                                </div>
                                <input type="text" id="theme_light_footer" name="theme_light_footer" required value="<?php echo htmlspecialchars($themeLightFooter); ?>" class="flex-grow px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-2xl focus:outline-none transition-all font-mono font-bold text-slate-800 dark:text-slate-200 uppercase">
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="block text-xs font-semibold text-slate-400">Fondo de Footer (Modo Oscuro)</label>
                            <div class="flex gap-3">
                                <div class="relative w-12 h-12 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 flex-shrink-0">
                                    <input type="color" id="theme_dark_footer_picker" value="<?php echo htmlspecialchars($themeDarkFooter); ?>" class="absolute inset-0 w-full h-full p-0 border-0 cursor-pointer scale-150">
                                </div>
                                <input type="text" id="theme_dark_footer" name="theme_dark_footer" required value="<?php echo htmlspecialchars($themeDarkFooter); ?>" class="flex-grow px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-2xl focus:outline-none transition-all font-mono font-bold text-slate-800 dark:text-slate-200 uppercase">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="pt-6 border-t border-slate-200/50 dark:border-slate-800/80 flex items-center justify-end gap-3">
                <a href="/?route=admin/dashboard" class="btn-secondary text-sm rounded-xl py-2.5 px-5">Cancelar</a>
                <button type="submit" class="btn-primary text-sm rounded-xl py-2.5 px-5 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Guardar Identidad
                </button>
            </div>

        </form>
    </div>

    <!-- Panel de Vista Previa -->
    <div class="space-y-6">
        <div class="glass-card rounded-3xl p-6 border border-slate-200/50 dark:border-slate-800/80 shadow-md">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-4">Vista Previa del Sitio</h3>
            
            <div class="space-y-6">
                <!-- Logotipo Lateral -->
                <div class="space-y-2">
                    <span class="text-xs text-slate-400">Logotipo en Barra Lateral</span>
                    <div class="flex items-center gap-2 p-3 bg-slate-900 rounded-xl">
                        <span class="font-extrabold text-sm tracking-tight text-white">
                            <?php echo htmlspecialchars($siteName); ?>
                        </span>
                    </div>
                </div>

                <!-- Botón de Degradado -->
                <div class="space-y-2">
                    <span class="text-xs text-slate-400">Botón Primario (Degradado)</span>
                    <div>
                        <button class="btn-primary text-xs py-2 px-4 rounded-lg font-semibold cursor-default">
                            Guardar Cambios
                        </button>
                    </div>
                </div>

                <!-- Insignia / Badge -->
                <div class="space-y-2">
                    <span class="text-xs text-slate-400">Acento de Texto / Badge</span>
                    <div class="flex gap-4 items-center">
                        <span class="text-sm font-bold text-brand-600 dark:text-brand-400">Enlace Activo</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400">
                            Activo
                        </span>
                    </div>
                </div>

                <!-- Simulación de Estructura de Página -->
                <div class="space-y-2 pt-2 border-t border-slate-200/50 dark:border-slate-800/50">
                    <span class="text-xs text-slate-400">Maqueta de Colores de Página</span>
                    <div class="rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden text-[10px]">
                        <!-- Cabecera sim -->
                        <div class="p-2 border-b border-slate-200/50 dark:border-slate-800/50 flex justify-between items-center" style="background-color: <?php echo htmlspecialchars($themeLightHeader); ?>;">
                            <span class="font-bold" style="color: <?php echo htmlspecialchars($themeLight); ?>;"><?php echo htmlspecialchars($siteName); ?></span>
                            <span class="text-slate-400">Menú</span>
                        </div>
                        <!-- Cuerpo sim -->
                        <div class="p-4" style="background-color: <?php echo htmlspecialchars($themeLightBg); ?>;">
                            <h5 class="font-bold mb-1 text-slate-800">Título del Artículo</h5>
                            <p class="text-slate-500 leading-relaxed">Contenido del blog utilizando los colores de fondo y acentos definidos en este módulo.</p>
                        </div>
                        <!-- Footer sim -->
                        <div class="p-2 border-t border-slate-200/50 dark:border-slate-800/50 text-slate-400 text-center" style="background-color: <?php echo htmlspecialchars($themeLightFooter); ?>;">
                            &copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($siteName); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-900/50 rounded-3xl p-6 text-xs text-blue-700 dark:text-blue-400 space-y-2.5">
            <h4 class="font-bold flex items-center gap-1.5">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Control Total de la Identidad
            </h4>
            <p class="leading-relaxed">
                Este panel centraliza todos los colores estructurales y estéticos de la web. Los degradados de los logos y botones se calculan combinando el color primario de acento con el secundario. Al presionar <strong>Guardar Identidad</strong>, todos los cambios se verán reflejados en tiempo real.
            </p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mapeo de pickers y text inputs
        const colorSyncMap = [
            { picker: 'theme_light_picker', input: 'theme_light_primary' },
            { picker: 'theme_light_sec_picker', input: 'theme_light_secondary' },
            { picker: 'theme_dark_picker', input: 'theme_dark_primary' },
            { picker: 'theme_dark_sec_picker', input: 'theme_dark_secondary' },
            { picker: 'theme_light_bg_picker', input: 'theme_light_bg' },
            { picker: 'theme_dark_bg_picker', input: 'theme_dark_bg' },
            { picker: 'theme_light_header_picker', input: 'theme_light_header' },
            { picker: 'theme_dark_header_picker', input: 'theme_dark_header' },
            { picker: 'theme_light_footer_picker', input: 'theme_light_footer' },
            { picker: 'theme_dark_footer_picker', input: 'theme_dark_footer' }
        ];

        colorSyncMap.forEach(function(item) {
            const pickerEl = document.getElementById(item.picker);
            const inputEl = document.getElementById(item.input);

            if (pickerEl && inputEl) {
                // Sincronizar Picker -> Input
                pickerEl.addEventListener('input', function() {
                    inputEl.value = this.value.toUpperCase();
                });

                // Sincronizar Input -> Picker
                inputEl.addEventListener('input', function() {
                    if (/^#[0-9a-fA-F]{6}$/.test(this.value)) {
                        pickerEl.value = this.value;
                    }
                });
            }
        });
    });
</script>

<?php require __DIR__ . '/layout/footer.php'; ?>

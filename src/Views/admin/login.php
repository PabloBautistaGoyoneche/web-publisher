<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? htmlspecialchars($title) : 'Iniciar Sesión'; ?></title>
    <!-- Google Fonts & Tailwind -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/styles.css">
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-950 flex items-center justify-center min-h-screen p-4 overflow-hidden relative">
    
    <!-- Efectos de Luces de Fondo (Gradientes) -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-brand-500/10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>

    <!-- Tarjeta de Login Glassmorphic -->
    <div class="w-full max-w-md bg-slate-900/60 backdrop-blur-xl border border-slate-800 rounded-3xl p-8 sm:p-10 shadow-2xl relative z-10">
        
        <!-- Logo y cabecera -->
        <div class="text-center space-y-3 mb-8">
            <div class="inline-flex w-14 w-14 h-14 bg-gradient-to-tr from-brand-600 to-indigo-600 rounded-2xl items-center justify-center text-white font-extrabold text-2xl shadow-lg shadow-brand-500/25 mb-2">
                M
            </div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">
                Modern<span class="text-brand-400">Blog</span> Admin
            </h1>
            <p class="text-sm text-slate-400 font-light">
                Panel de control administrativo
            </p>
        </div>

        <!-- Alerta de Error -->
        <?php if (isset($error) && $error): ?>
            <div class="mb-6 p-4 bg-red-950/40 text-red-400 border border-red-900/50 rounded-2xl text-xs font-semibold flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <!-- Formulario -->
        <form action="/?route=admin/login" method="POST" class="space-y-6">
            <div class="space-y-2">
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Nombre de Usuario</label>
                <div class="relative">
                    <input type="text" name="username" required placeholder="ej: admin" class="w-full px-4 py-3 pl-10 text-sm bg-slate-950 text-white border border-slate-800 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 rounded-2xl focus:outline-none transition-all placeholder:text-slate-600">
                    <div class="absolute left-3.5 top-3.5 text-slate-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Contraseña</label>
                <div class="relative">
                    <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-3 pl-10 text-sm bg-slate-950 text-white border border-slate-800 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 rounded-2xl focus:outline-none transition-all placeholder:text-slate-600">
                    <div class="absolute left-3.5 top-3.5 text-slate-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full btn-primary py-3 rounded-2xl font-bold text-sm tracking-wide mt-2">
                Acceder al Panel
            </button>
        </form>
        
        <!-- Enlace de regreso al blog -->
        <div class="text-center mt-8 pt-4 border-t border-slate-800/60">
            <a href="/" class="text-xs text-slate-500 hover:text-brand-400 transition-colors font-medium inline-flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Blog Público
            </a>
        </div>
    </div>
</body>
</html>

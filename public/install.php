<?php
/**
 * Asistente de Instalación al Estilo WordPress
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$configPath = dirname(__DIR__) . '/config/database.php';

// Si ya está instalado, no permitir volver a correr el instalador
if (file_exists($configPath)) {
    header("Location: /");
    exit;
}

$step = (int)($_GET['step'] ?? 1);
$error = null;
$success = false;

// Procesar pasos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step === 1) {
        // Guardar y probar datos de conexión de base de datos
        $dbHost = trim($_POST['db_host'] ?? 'localhost');
        $dbName = trim($_POST['db_name'] ?? 'modern_blog');
        $dbUser = trim($_POST['db_user'] ?? 'root');
        $dbPass = trim($_POST['db_pass'] ?? '');

        try {
            // Intentar conectar al servidor sin base de datos específica primero (en caso de que deba crearse)
            $dsn = "mysql:host=$dbHost;charset=utf8mb4";
            $pdo = new PDO($dsn, $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 5
            ]);
            
            // Guardar en sesión temporal
            $_SESSION['install_db_host'] = $dbHost;
            $_SESSION['install_db_name'] = $dbName;
            $_SESSION['install_db_user'] = $dbUser;
            $_SESSION['install_db_pass'] = $dbPass;

            header("Location: ?step=2");
            exit;
        } catch (PDOException $e) {
            $error = "Error de conexión: " . $e->getMessage();
        }
    } elseif ($step === 2) {
        // Configuración final del sitio y administrador
        $siteTitle = trim($_POST['site_title'] ?? 'Mi Blog Moderno');
        $adminName = trim($_POST['admin_name'] ?? 'Administrador');
        $adminUser = trim($_POST['admin_user'] ?? 'admin');
        $adminEmail = trim($_POST['admin_email'] ?? '');
        $adminPass = trim($_POST['admin_pass'] ?? '');
        $installDemo = isset($_POST['install_demo']);

        // Recuperar datos de conexión
        $dbHost = $_SESSION['install_db_host'] ?? null;
        $dbName = $_SESSION['install_db_name'] ?? null;
        $dbUser = $_SESSION['install_db_user'] ?? null;
        $dbPass = $_SESSION['install_db_pass'] ?? null;

        if (!$dbHost || !$dbName || !$dbUser) {
            $error = "Los datos de conexión se han perdido. Por favor, vuelve al paso 1.";
            $step = 1;
        } elseif (empty($siteTitle) || empty($adminName) || empty($adminUser) || empty($adminEmail) || empty($adminPass)) {
            $error = "Por favor, completa todos los campos del administrador.";
        } elseif (!filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
            $error = "Por favor, introduce un correo electrónico válido.";
        } else {
            try {
                // 1. Conectar y crear base de datos si no existe (robusto para cPanel)
                $dbExists = false;
                try {
                    $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";
                    $pdo = new PDO($dsn, $dbUser, $dbPass, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_TIMEOUT => 5
                    ]);
                    $dbExists = true;
                } catch (PDOException $e) {
                    // No se pudo conectar directamente, intentaremos crear la base de datos a continuación
                }

                if (!$dbExists) {
                    $dsn = "mysql:host=$dbHost;charset=utf8mb4";
                    $pdo = new PDO($dsn, $dbUser, $dbPass, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_TIMEOUT => 5
                    ]);
                    try {
                        $pdo->exec("CREATE DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
                        $pdo->exec("USE `$dbName`;");
                    } catch (PDOException $e) {
                        throw new Exception("No se pudo crear la base de datos '$dbName' automáticamente debido a restricciones de privilegios. Por favor, créala manualmente desde el panel de control de tu servidor (cPanel) y vuelve a intentar este paso.");
                    }
                }

                // 2. Leer install.sql
                $sqlPath = dirname(__DIR__) . '/install.sql';
                if (!file_exists($sqlPath)) {
                    throw new Exception("El archivo install.sql no se encuentra en el servidor.");
                }

                $sqlContent = file_get_contents($sqlPath);
                
                // Dividir por la sección de semillas opcionales si corresponde
                $parts = explode('-- --- DEMO SEEDS DATA ---', $sqlContent);
                $installSql = $parts[0];
                if ($installDemo) {
                    $installSql .= "\n" . ($parts[1] ?? '');
                }

                // Ejecutar scripts SQL de estructura y carga
                $pdo->exec($installSql);

                // 3. Crear el administrador
                $passHash = password_hash($adminPass, PASSWORD_BCRYPT, ['cost' => 12]);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role, display_name, bio) VALUES (:user, :email, :pass, 'admin', :name, 'Administrador del sitio.')");
                $stmt->execute([
                    'user' => $adminUser,
                    'email' => $adminEmail,
                    'pass' => $passHash,
                    'name' => $adminName
                ]);
                $adminId = $pdo->lastInsertId();

                // Actualizar posts para que pertenezcan al admin recién creado
                $pdo->exec("UPDATE posts SET user_id = " . (int)$adminId);

                // 4. Guardar Nombre del sitio en settings
                $stmt = $pdo->prepare("INSERT INTO settings (`key`, `value`) VALUES ('site_name', :site_name) ON DUPLICATE KEY UPDATE `value` = :site_name_update");
                $stmt->execute([
                    'site_name' => $siteTitle,
                    'site_name_update' => $siteTitle
                ]);

                // 5. Generar archivo config/database.php
                $configContent = "<?php\n" .
                                 "/**\n" .
                                 " * Configuración de la Base de Datos Generada por el Instalador\n" .
                                 " */\n\n" .
                                 "return [\n" .
                                 "    'host' => '" . addslashes($dbHost) . "',\n" .
                                 "    'dbname' => '" . addslashes($dbName) . "',\n" .
                                 "    'username' => '" . addslashes($dbUser) . "',\n" .
                                 "    'password' => '" . addslashes($dbPass) . "',\n" .
                                 "    'charset' => 'utf8mb4',\n" .
                                 "    'options' => [\n" .
                                 "        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,\n" .
                                 "        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,\n" .
                                 "        PDO::ATTR_EMULATE_PREPARES => false,\n" .
                                 "    ]\n" .
                                 "];\n";

                // Asegurar que la carpeta config exista
                if (!is_dir(dirname(__DIR__) . '/config')) {
                    mkdir(dirname(__DIR__) . '/config', 0755, true);
                }

                if (file_put_contents($configPath, $configContent) === false) {
                    throw new Exception("No se pudo escribir el archivo de configuración en 'config/database.php'. Por favor, verifica que la carpeta de la aplicación tenga permisos de escritura.");
                }

                // Limpiar variables de instalación de la sesión
                unset($_SESSION['install_db_host']);
                unset($_SESSION['install_db_name']);
                unset($_SESSION['install_db_user']);
                unset($_SESSION['install_db_pass']);

                header("Location: ?step=3");
                exit;
            } catch (Exception $e) {
                $error = "Error durante la instalación: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalador del Blog - Modern Web Publisher</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
        }
        .heading-font {
            font-family: 'Outfit', sans-serif;
        }
        .glass-card {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="min-h-screen text-slate-200 flex items-center justify-center p-4">
    <div class="max-w-xl w-full">
        
        <!-- Logo e Introducción -->
        <div class="text-center mb-8">
            <h1 class="heading-font text-3xl font-extrabold text-white tracking-tight flex items-center justify-center gap-2">
                <svg class="w-8 h-8 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                Instalación de ModernBlog
            </h1>
            <p class="text-xs text-slate-400 mt-2">
                Configura tu base de datos y tu cuenta de administrador en solo dos minutos.
            </p>
        </div>

        <!-- Indicador de Pasos -->
        <div class="flex items-center justify-between mb-8 px-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
            <div class="flex items-center gap-2 <?php echo $step === 1 ? 'text-violet-400' : 'text-slate-400'; ?>">
                <span class="w-5 h-5 rounded-full flex items-center justify-center border <?php echo $step === 1 ? 'border-violet-500 bg-violet-950/50' : 'border-slate-700 bg-slate-800'; ?>">1</span>
                Base de Datos
            </div>
            <div class="h-px bg-slate-800 flex-grow mx-4"></div>
            <div class="flex items-center gap-2 <?php echo $step === 2 ? 'text-violet-400' : 'text-slate-400'; ?>">
                <span class="w-5 h-5 rounded-full flex items-center justify-center border <?php echo $step === 2 ? 'border-violet-500 bg-violet-950/50' : 'border-slate-700 bg-slate-800'; ?>">2</span>
                Administración
            </div>
            <div class="h-px bg-slate-800 flex-grow mx-4"></div>
            <div class="flex items-center gap-2 <?php echo $step === 3 ? 'text-emerald-400' : 'text-slate-400'; ?>">
                <span class="w-5 h-5 rounded-full flex items-center justify-center border <?php echo $step === 3 ? 'border-emerald-500 bg-emerald-950/50' : 'border-slate-700 bg-slate-800'; ?>">3</span>
                Completado
            </div>
        </div>

        <!-- Mensajes de Error -->
        <?php if ($error): ?>
            <div class="mb-6 p-4 bg-red-950/40 border border-red-900/50 text-red-400 text-xs font-medium rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <!-- Tarjeta de Contenido Principal -->
        <div class="glass-card rounded-3xl p-8 shadow-2xl">
            
            <?php if ($step === 1): ?>
                <form action="?step=1" method="POST" class="space-y-6">
                    <div class="space-y-1">
                        <h2 class="heading-font text-lg font-bold text-white">Paso 1: Conexión de Base de Datos</h2>
                        <p class="text-xs text-slate-400 leading-relaxed">ModernBlog utiliza MySQL para almacenar contenidos. Rellena los datos de tu servidor local o de hosting.</p>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label for="db_host" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Servidor de Base de Datos (Host)</label>
                            <input type="text" id="db_host" name="db_host" required value="localhost" 
                                   class="w-full px-4 py-3 bg-slate-900/60 border border-slate-800 focus:border-violet-500 rounded-2xl focus:outline-none transition-all text-sm text-white">
                        </div>

                        <div class="space-y-2">
                            <label for="db_name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Nombre de la Base de Datos</label>
                            <input type="text" id="db_name" name="db_name" required value="modern_blog" 
                                   class="w-full px-4 py-3 bg-slate-900/60 border border-slate-800 focus:border-violet-500 rounded-2xl focus:outline-none transition-all text-sm text-white">
                        </div>

                        <div class="space-y-2">
                            <label for="db_user" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Usuario</label>
                            <input type="text" id="db_user" name="db_user" required value="root" 
                                   class="w-full px-4 py-3 bg-slate-900/60 border border-slate-800 focus:border-violet-500 rounded-2xl focus:outline-none transition-all text-sm text-white">
                        </div>

                        <div class="space-y-2">
                            <label for="db_pass" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Contraseña</label>
                            <input type="password" id="db_pass" name="db_pass" placeholder="Dejar en blanco si no tiene"
                                   class="w-full px-4 py-3 bg-slate-900/60 border border-slate-800 focus:border-violet-500 rounded-2xl focus:outline-none transition-all text-sm text-white">
                        </div>
                    </div>

                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-3.5 px-5 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 text-sm font-semibold text-white rounded-2xl shadow-lg shadow-violet-500/10 active:scale-95 transition-all">
                        Probar Conexión
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </form>

            <?php elseif ($step === 2): ?>
                <form action="?step=2" method="POST" class="space-y-6">
                    <div class="space-y-1">
                        <h2 class="heading-font text-lg font-bold text-white">Paso 2: Configuración del Sitio</h2>
                        <p class="text-xs text-slate-400 leading-relaxed">Crea tu sitio y la cuenta del administrador para acceder al panel de control.</p>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label for="site_title" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Título del Blog</label>
                            <input type="text" id="site_title" name="site_title" required value="Blog de Pablo" placeholder="Ej. Mi Blog Técnico"
                                   class="w-full px-4 py-3 bg-slate-900/60 border border-slate-800 focus:border-violet-500 rounded-2xl focus:outline-none transition-all text-sm text-white">
                        </div>

                        <div class="space-y-2">
                            <label for="admin_name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Nombre Completo (Autor)</label>
                            <input type="text" id="admin_name" name="admin_name" required value="Pablo Bautista" placeholder="Ej. Alex Morgan"
                                   class="w-full px-4 py-3 bg-slate-900/60 border border-slate-800 focus:border-violet-500 rounded-2xl focus:outline-none transition-all text-sm text-white">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label for="admin_user" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Usuario Admin</label>
                                <input type="text" id="admin_user" name="admin_user" required value="admin"
                                       class="w-full px-4 py-3 bg-slate-900/60 border border-slate-800 focus:border-violet-500 rounded-2xl focus:outline-none transition-all text-sm text-white">
                            </div>

                            <div class="space-y-2">
                                <label for="admin_email" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Email</label>
                                <input type="email" id="admin_email" name="admin_email" required placeholder="admin@sitio.com"
                                       class="w-full px-4 py-3 bg-slate-900/60 border border-slate-800 focus:border-violet-500 rounded-2xl focus:outline-none transition-all text-sm text-white">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="admin_pass" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Contraseña del Administrador</label>
                            <input type="password" id="admin_pass" name="admin_pass" required placeholder="Crea una contraseña segura"
                                   class="w-full px-4 py-3 bg-slate-900/60 border border-slate-800 focus:border-violet-500 rounded-2xl focus:outline-none transition-all text-sm text-white">
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <input type="checkbox" id="install_demo" name="install_demo" value="1" checked
                                   class="w-4 h-4 text-violet-600 bg-slate-900 border-slate-800 rounded focus:ring-violet-500 focus:ring-2">
                            <label for="install_demo" class="text-xs font-medium text-slate-350 cursor-pointer">
                                Instalar artículos y comentarios de prueba por defecto
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <a href="?step=1" class="w-1/3 text-center py-3.5 px-5 bg-slate-800 hover:bg-slate-700 text-sm font-semibold text-slate-300 rounded-2xl transition-all">
                            Atrás
                        </a>
                        <button type="submit" class="w-2/3 flex items-center justify-center gap-2 py-3.5 px-5 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 text-sm font-semibold text-white rounded-2xl shadow-lg shadow-violet-500/10 active:scale-95 transition-all">
                            Completar Instalación
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </button>
                    </div>
                </form>

            <?php elseif ($step === 3): ?>
                <div class="text-center space-y-6 py-6">
                    <div class="w-16 h-16 bg-emerald-950/60 text-emerald-400 border border-emerald-900/50 rounded-full flex items-center justify-center mx-auto shadow-lg shadow-emerald-500/5">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>

                    <div class="space-y-2">
                        <h2 class="heading-font text-2xl font-extrabold text-white">¡Instalación Exitosa!</h2>
                        <p class="text-xs text-slate-400 max-w-sm mx-auto leading-relaxed">El archivo de configuración ha sido escrito con éxito en el servidor y la estructura ha sido importada de inmediato.</p>
                    </div>

                    <div class="bg-slate-900/40 border border-slate-800 rounded-2xl p-4 max-w-sm mx-auto text-left text-xs text-slate-350 space-y-2">
                        <p><strong>Configuración guardada:</strong></p>
                        <ul class="space-y-1 list-disc list-inside text-slate-400 font-mono">
                            <li>Base de datos creada</li>
                            <li>Archivo: config/database.php</li>
                            <li>Credenciales del CMS listas</li>
                        </ul>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-3 pt-4 justify-center">
                        <a href="./" class="w-full sm:w-auto py-3.5 px-6 bg-slate-850 hover:bg-slate-800 text-sm font-semibold text-slate-200 rounded-2xl transition-all">
                            Ver Sitio Público
                        </a>
                        <a href="./?route=admin/login" class="w-full sm:w-auto py-3.5 px-6 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 text-sm font-semibold text-white rounded-2xl shadow-lg shadow-violet-500/10 transition-all">
                            Acceder al CMS
                        </a>
                    </div>
                </div>
            <?php endif; ?>

        </div>

    </div>
</body>
</html>

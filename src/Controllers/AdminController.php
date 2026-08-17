<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use App\Helpers;

class AdminController {
    
    /**
     * Inicializa la sesión y verifica si el usuario está autenticado como administrador.
     */
    private function checkAuth(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['admin_user']) || $_SESSION['admin_role'] !== 'admin') {
            header("Location: /?route=admin/login");
            exit;
        }
    }

    /**
     * Procesa la vista y acción de login.
     */
    public function login(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Si ya está logueado, ir directo al dashboard
        if (isset($_SESSION['admin_user']) && $_SESSION['admin_role'] === 'admin') {
            header("Location: /?route=admin/dashboard");
            exit;
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($username) || empty($password)) {
                $error = 'Por favor, ingresa tu usuario y contraseña.';
            } else {
                $user = User::verify($username, $password);
                if ($user && $user->role === 'admin') {
                    $_SESSION['admin_user'] = $user->username;
                    $_SESSION['admin_name'] = $user->display_name;
                    $_SESSION['admin_role'] = $user->role;
                    $_SESSION['admin_id'] = $user->id;
                    
                    header("Location: /?route=admin/dashboard");
                    exit;
                } else {
                    $error = 'Usuario o contraseña incorrectos, o no tienes permisos de administrador.';
                }
            }
        }

        // Cargar vista de login directa
        $this->render('admin/login', [
            'title' => 'Iniciar Sesión - Admin Panel',
            'error' => $error
        ]);
    }

    /**
     * Cierra la sesión administrativa.
     */
    public function logout(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();
        header("Location: /?route=admin/login");
        exit;
    }

    /**
     * Muestra el Dashboard administrativo con estadísticas.
     */
    public function dashboard(): void {
        $this->checkAuth();

        $stats = [
            'posts' => Post::count(),
            'comments' => Comment::count(),
            'comments_pending' => Comment::countPending(),
            'views' => Post::totalViews(),
            'categories' => Category::count(),
            'messages' => \App\Models\Message::count()
        ];

        $latestComments = array_slice(Comment::all(), 0, 5);
        $recentPosts = array_slice(Post::all(), 0, 5);

        $this->render('admin/dashboard', [
            'title' => 'Dashboard - Admin Panel',
            'stats' => $stats,
            'latestComments' => $latestComments,
            'recentPosts' => $recentPosts
        ]);
    }

    /**
     * CRUD: Listar, Crear y Editar Entradas
     */
    public function posts(): void {
        $this->checkAuth();
        
        $error = null;
        $categories = Category::all();

        // Manejar Creación
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_post'])) {
            $title = trim($_POST['title'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $categoryIdInput = $_POST['category_id'] ?? '';
            $category_id = (!empty($categoryIdInput) && $categoryIdInput !== '0') ? (int)$categoryIdInput : null;
            $content = trim($_POST['content'] ?? '');
            $excerpt = \App\Helpers::excerpt($content);
            $status = trim($_POST['status'] ?? 'draft');

            if (empty($slug)) {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
            }

            if (empty($title) || empty($content)) {
                $error = 'Por favor, completa todos los campos requeridos (Título y Contenido).';
            } else {
                $imageName = null;
                if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                    $tmpName = $_FILES['featured_image']['tmp_name'];
                    $origName = basename($_FILES['featured_image']['name']);
                    $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
                    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                    
                    if (in_array($ext, $allowed)) {
                        $imageName = uniqid('post_') . '.' . $ext;
                        $destPath = dirname(dirname(__DIR__)) . '/public/uploads/' . $imageName;
                        move_uploaded_file($tmpName, $destPath);
                    } else {
                        $error = 'Tipo de imagen no permitido. Solo se permiten JPG, PNG y WEBP.';
                    }
                }

                if (!$error) {
                    $success = Post::create(
                        $_SESSION['admin_id'],
                        $category_id,
                        $title,
                        $slug,
                        $excerpt,
                        $content,
                        $imageName,
                        $status
                    );

                    if ($success) {
                        header("Location: /?route=admin/posts");
                        exit;
                    } else {
                        $error = 'Ocurrió un error al guardar la entrada.';
                    }
                }
            }
        }

        // Manejar Actualización
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_post'])) {
            $id = (int)($_POST['id'] ?? 0);
            $post = Post::find($id);
            if ($post) {
                $title = trim($_POST['title'] ?? '');
                $slug = trim($_POST['slug'] ?? '');
                $categoryIdInput = $_POST['category_id'] ?? '';
                $category_id = (!empty($categoryIdInput) && $categoryIdInput !== '0') ? (int)$categoryIdInput : null;
                $content = trim($_POST['content'] ?? '');
                $excerpt = \App\Helpers::excerpt($content);
                $status = trim($_POST['status'] ?? 'draft');

                if (empty($slug)) {
                    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
                }

                if (empty($title) || empty($content)) {
                    $error = 'Por favor, completa todos los campos requeridos (Título y Contenido).';
                } else {
                    $imageName = $post->featured_image;

                    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                        $tmpName = $_FILES['featured_image']['tmp_name'];
                        $origName = basename($_FILES['featured_image']['name']);
                        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
                        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                        
                        if (in_array($ext, $allowed)) {
                            $imageName = uniqid('post_') . '.' . $ext;
                            $destPath = dirname(dirname(__DIR__)) . '/public/uploads/' . $imageName;
                            move_uploaded_file($tmpName, $destPath);
                            
                            if ($post->featured_image && file_exists(dirname(dirname(__DIR__)) . '/public/uploads/' . $post->featured_image)) {
                                @unlink(dirname(dirname(__DIR__)) . '/public/uploads/' . $post->featured_image);
                            }
                        } else {
                            $error = 'Tipo de imagen no permitido. Solo se permiten JPG, PNG y WEBP.';
                        }
                    }

                    if (!$error) {
                        $success = Post::update(
                            $post->id,
                            $category_id,
                            $title,
                            $slug,
                            $excerpt,
                            $content,
                            $imageName,
                            $status
                        );

                        if ($success) {
                            header("Location: /?route=admin/posts");
                            exit;
                        } else {
                            $error = 'Ocurrió un error al actualizar la entrada.';
                        }
                    }
                }
            }
        }

        $posts = Post::all();

        $this->render('admin/posts/index', [
            'title' => 'Entradas - Admin Panel',
            'posts' => $posts,
            'categories' => $categories,
            'error' => $error
        ]);
    }

    /**
     * AJAX: Obtener datos de un post en formato JSON
     */
    public function getPostJson(): void {
        $this->checkAuth();
        $id = (int)($_GET['id'] ?? 0);
        $post = Post::find($id);
        header('Content-Type: application/json');
        if ($post) {
            echo json_encode([
                'success' => true,
                'post' => [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'category_id' => $post->category_id,
                    'content' => $post->content,
                    'featured_image' => $post->featured_image,
                    'status' => $post->status
                ]
            ]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }



    /**
     * CRUD: Eliminar Entrada
     */
    public function deletePost(): void {
        $this->checkAuth();
        $id = (int)($_GET['id'] ?? 0);
        $post = Post::find($id);

        if ($post) {
            // Eliminar imagen del servidor
            if ($post->featured_image && file_exists(dirname(dirname(__DIR__)) . '/public/uploads/' . $post->featured_image)) {
                @unlink(dirname(dirname(__DIR__)) . '/public/uploads/' . $post->featured_image);
            }
            Post::delete($id);
        }

        header("Location: /?route=admin/posts");
        exit;
    }

    /**
     * CRUD: Gestionar Categorías (Lista y Creación en una sola pantalla)
     */
    public function categories(): void {
        $this->checkAuth();
        
        $error = null;
        
        // Manejar envío de nueva categoría
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_category'])) {
            $name = trim($_POST['name'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $parentIdInput = $_POST['parent_id'] ?? '';
            $parentId = (!empty($parentIdInput) && $parentIdInput !== 'none') ? (int)$parentIdInput : null;

            if (empty($slug)) {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
            }

            if (empty($name)) {
                $error = 'El nombre de la categoría es obligatorio.';
            } else {
                $success = Category::create($name, $slug, $description, $parentId);
                if ($success) {
                    header("Location: /?route=admin/categories");
                    exit;
                } else {
                    $error = 'Ocurrió un error al guardar la categoría. El nombre o slug podría estar duplicado.';
                }
            }
        }

        // Manejar actualización de categoría existente
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_category'])) {
            $id = (int)($_POST['id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $parentIdInput = $_POST['parent_id'] ?? '';
            $parentId = (!empty($parentIdInput) && $parentIdInput !== 'none') ? (int)$parentIdInput : null;

            if (empty($slug)) {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
            }

            if (empty($name)) {
                $error = 'El nombre de la categoría es obligatorio.';
            } elseif ($id > 0) {
                $success = Category::update($id, $name, $slug, $description, $parentId);
                if ($success) {
                    header("Location: /?route=admin/categories");
                    exit;
                } else {
                    $error = 'Ocurrió un error al actualizar la categoría. El nombre o slug podría estar duplicado.';
                }
            }
        }

        $categories = Category::all();

        $this->render('admin/categories/index', [
            'title' => 'Categorías - Admin Panel',
            'categories' => $categories,
            'error' => $error
        ]);
    }

    /**
     * CRUD: Eliminar Categoría
     */
    public function deleteCategory(): void {
        $this->checkAuth();
        $id = (int)($_GET['id'] ?? 0);
        Category::delete($id);
        header("Location: /?route=admin/categories");
        exit;
    }

    /**
     * AJAX: Reordenar y reestructurar jerarquía de categorías
     */
    public function reorderCategories(): void {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            if (is_array($data)) {
                foreach ($data as $item) {
                    $id = (int)($item['id'] ?? 0);
                    $parentId = isset($item['parent_id']) && $item['parent_id'] !== 'none' && $item['parent_id'] !== '' ? (int)$item['parent_id'] : null;
                    $order = (int)($item['order'] ?? 0);
                    if ($id > 0) {
                        \App\Models\Category::updateParentAndOrder($id, $parentId, $order);
                    }
                }
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            }
        }
        header('Content-Type: application/json');
        echo json_encode(['success' => false]);
        exit;
    }

    /**
     * CRUD: Listar y Moderar Comentarios
     */
    public function comments(): void {
        $this->checkAuth();
        $comments = Comment::all();

        $this->render('admin/comments/index', [
            'title' => 'Comentarios - Admin Panel',
            'comments' => $comments
        ]);
    }

    /**
     * CRUD: Aprobar Comentario
     */
    public function approveComment(): void {
        $this->checkAuth();
        $id = (int)($_GET['id'] ?? 0);
        Comment::approve($id);
        header("Location: /?route=admin/comments");
        exit;
    }

    /**
     * CRUD: Eliminar Comentario
     */
    public function deleteComment(): void {
        $this->checkAuth();
        $id = (int)($_GET['id'] ?? 0);
        Comment::delete($id);
        header("Location: /?route=admin/comments");
        exit;
    }

    /**
     * CRUD: Listar Páginas Estáticas
     */
    public function pages(): void {
        $this->checkAuth();
        $pages = \App\Models\Page::all();

        $this->render('admin/pages/index', [
            'title' => 'Páginas Estáticas - Admin Panel',
            'pages' => $pages
        ]);
    }

    /**
     * CRUD: Crear Página Estática
     */
    public function createPage(): void {
        $this->checkAuth();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $content = trim($_POST['content'] ?? '');

            if (empty($slug)) {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
            }

            if (empty($title) || empty($content)) {
                $error = 'Por favor, completa todos los campos obligatorios.';
            } else {
                $success = \App\Models\Page::create($title, $slug, $content);
                if ($success) {
                    header("Location: /?route=admin/pages");
                    exit;
                } else {
                    $error = 'Ocurrió un error al guardar la página. El slug podría estar duplicado.';
                }
            }
        }

        $this->render('admin/pages/create', [
            'title' => 'Nueva Página - Admin Panel',
            'error' => $error
        ]);
    }

    /**
     * CRUD: Editar Página Estática
     */
    public function editPage(): void {
        $this->checkAuth();
        $id = (int)($_GET['id'] ?? 0);
        $page = \App\Models\Page::find($id);

        if (!$page) {
            header("Location: /?route=admin/pages");
            exit;
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $content = trim($_POST['content'] ?? '');

            if (empty($slug)) {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
            }

            if (empty($title) || empty($content)) {
                $error = 'Por favor, completa todos los campos obligatorios.';
            } else {
                $success = \App\Models\Page::update($page->id, $title, $slug, $content);
                if ($success) {
                    header("Location: /?route=admin/pages");
                    exit;
                } else {
                    $error = 'Ocurrió un error al actualizar la página. El slug podría estar duplicado.';
                }
            }
        }

        $this->render('admin/pages/edit', [
            'title' => 'Editar Página - Admin Panel',
            'page' => $page,
            'error' => $error
        ]);
    }

    /**
     * CRUD: Eliminar Página Estática
     */
    public function deletePage(): void {
        $this->checkAuth();
        $id = (int)($_GET['id'] ?? 0);
        \App\Models\Page::delete($id);
        header("Location: /?route=admin/pages");
        exit;
    }

    /**
     * Bandeja de Entrada: Listar Mensajes de Contacto
     */
    public function messages(): void {
        $this->checkAuth();
        $messages = \App\Models\Message::all();

        $this->render('admin/messages/index', [
            'title' => 'Bandeja de Mensajes - Admin Panel',
            'messages' => $messages
        ]);
    }

    /**
     * Bandeja de Entrada: Eliminar Mensaje
     */
    public function deleteMessage(): void {
        $this->checkAuth();
        $id = (int)($_GET['id'] ?? 0);
        \App\Models\Message::delete($id);
        header("Location: /?route=admin/messages");
        exit;
    }

    /**
     * Muestra y procesa la configuración de la Identidad del Sitio.
     */
    public function settings(): void {
        $this->checkAuth();

        $error = null;
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $siteName = trim($_POST['site_name'] ?? '');
            
            $themeLight = trim($_POST['theme_light_primary'] ?? '');
            $themeLightSec = trim($_POST['theme_light_secondary'] ?? '');
            $themeDark = trim($_POST['theme_dark_primary'] ?? '');
            $themeDarkSec = trim($_POST['theme_dark_secondary'] ?? '');
            
            $themeLightBg = trim($_POST['theme_light_bg'] ?? '');
            $themeDarkBg = trim($_POST['theme_dark_bg'] ?? '');
            
            $themeLightHeader = trim($_POST['theme_light_header'] ?? '');
            $themeDarkHeader = trim($_POST['theme_dark_header'] ?? '');
            
            $themeLightFooter = trim($_POST['theme_light_footer'] ?? '');
            $themeDarkFooter = trim($_POST['theme_dark_footer'] ?? '');

            // Validar formato hexadecimal
            $hexPattern = '/^#[0-9a-fA-F]{6}$/';

            if (empty($siteName) || empty($themeLight) || empty($themeLightSec) || empty($themeDark) || empty($themeDarkSec) || 
                empty($themeLightBg) || empty($themeDarkBg) || empty($themeLightHeader) || empty($themeDarkHeader) || 
                empty($themeLightFooter) || empty($themeDarkFooter)) {
                $error = 'Por favor, completa todos los campos.';
            } elseif (!preg_match($hexPattern, $themeLight) || !preg_match($hexPattern, $themeLightSec) || 
                      !preg_match($hexPattern, $themeDark) || !preg_match($hexPattern, $themeDarkSec) || 
                      !preg_match($hexPattern, $themeLightBg) || !preg_match($hexPattern, $themeDarkBg) || 
                      !preg_match($hexPattern, $themeLightHeader) || !preg_match($hexPattern, $themeDarkHeader) || 
                      !preg_match($hexPattern, $themeLightFooter) || !preg_match($hexPattern, $themeDarkFooter)) {
                $error = 'Los colores deben tener un formato hexadecimal válido (ej. #7C3AED).';
            } else {
                // Guardar configuraciones
                \App\Models\Setting::set('site_name', $siteName);
                \App\Models\Setting::set('theme_light_primary', $themeLight);
                \App\Models\Setting::set('theme_light_secondary', $themeLightSec);
                \App\Models\Setting::set('theme_dark_primary', $themeDark);
                \App\Models\Setting::set('theme_dark_secondary', $themeDarkSec);
                \App\Models\Setting::set('theme_light_bg', $themeLightBg);
                \App\Models\Setting::set('theme_dark_bg', $themeDarkBg);
                \App\Models\Setting::set('theme_light_header', $themeLightHeader);
                \App\Models\Setting::set('theme_dark_header', $themeDarkHeader);
                \App\Models\Setting::set('theme_light_footer', $themeLightFooter);
                \App\Models\Setting::set('theme_dark_footer', $themeDarkFooter);
                $success = true;
            }
        }

        // Cargar valores actuales
        $siteName = \App\Models\Setting::get('site_name', 'ModernBlog');
        
        $themeLight = \App\Models\Setting::get('theme_light_primary', '#7c3aed');
        $themeLightSec = \App\Models\Setting::get('theme_light_secondary', '#4f46e5');
        $themeDark = \App\Models\Setting::get('theme_dark_primary', '#a78bfa');
        $themeDarkSec = \App\Models\Setting::get('theme_dark_secondary', '#6366f1');
        
        $themeLightBg = \App\Models\Setting::get('theme_light_bg', '#f8fafc');
        $themeDarkBg = \App\Models\Setting::get('theme_dark_bg', '#020617');
        
        $themeLightHeader = \App\Models\Setting::get('theme_light_header', '#ffffff');
        $themeDarkHeader = \App\Models\Setting::get('theme_dark_header', '#020617');
        
        $themeLightFooter = \App\Models\Setting::get('theme_light_footer', '#ffffff');
        $themeDarkFooter = \App\Models\Setting::get('theme_dark_footer', '#0f172a');

        $this->render('admin/settings', [
            'title' => 'Identidad del Sitio - Admin Panel',
            'siteName' => $siteName,
            'themeLight' => $themeLight,
            'themeLightSec' => $themeLightSec,
            'themeDark' => $themeDark,
            'themeDarkSec' => $themeDarkSec,
            'themeLightBg' => $themeLightBg,
            'themeDarkBg' => $themeDarkBg,
            'themeLightHeader' => $themeLightHeader,
            'themeDarkHeader' => $themeDarkHeader,
            'themeLightFooter' => $themeLightFooter,
            'themeDarkFooter' => $themeDarkFooter,
            'error' => $error,
            'success' => $success
        ]);
    }

    /**
     * Renderiza vistas pasando variables extractadas.
     */
    private function render(string $viewName, array $data = []): void {
        extract($data);
        $viewFile = dirname(__DIR__) . "/Views/{$viewName}.php";
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo "Error: La vista administrativa '{$viewName}' no existe.";
        }
    }
}

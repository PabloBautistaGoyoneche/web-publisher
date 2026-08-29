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
        $user = User::findByUsername($_SESSION['admin_user']);
        if ($user) {
            $_SESSION['admin_id'] = $user->id;
            $_SESSION['admin_name'] = $user->display_name;
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

        $githubOwner = 'PabloBautistaGoyoneche';
        $githubRepo = 'web-publisher';
        $githubBranch = 'main';
        $githubToken = '';
        $currentCommit = \App\Models\Setting::get('current_commit', 'initial');

        $updateAvailable = false;
        $latestCommitSha = null;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['github_update_available']) && isset($_SESSION['github_latest_commit']) && isset($_SESSION['github_last_checked']) && ($_SESSION['github_last_checked'] > time() - 3600)) {
            $updateAvailable = $_SESSION['github_update_available'];
            $latestCommitSha = $_SESSION['github_latest_commit'];
        } else {
            $latestCommitSha = $this->fetchLatestCommitSha($githubOwner, $githubRepo, $githubBranch, $githubToken);
            if ($latestCommitSha && $latestCommitSha !== $currentCommit) {
                $updateAvailable = true;
            }
            $_SESSION['github_update_available'] = $updateAvailable;
            $_SESSION['github_latest_commit'] = $latestCommitSha;
            $_SESSION['github_last_checked'] = time();
        }

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
            'recentPosts' => $recentPosts,
            'updateAvailable' => $updateAvailable,
            'latestCommitSha' => $latestCommitSha
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
            $seo_title = isset($_POST['seo_title']) ? trim($_POST['seo_title']) : null;
            $seo_description = isset($_POST['seo_description']) ? trim($_POST['seo_description']) : null;
            $seo_keywords = isset($_POST['seo_keywords']) ? trim($_POST['seo_keywords']) : null;

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
                        $status,
                        $seo_title,
                        $seo_description,
                        $seo_keywords
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
                $seo_title = isset($_POST['seo_title']) ? trim($_POST['seo_title']) : null;
                $seo_description = isset($_POST['seo_description']) ? trim($_POST['seo_description']) : null;
                $seo_keywords = isset($_POST['seo_keywords']) ? trim($_POST['seo_keywords']) : null;

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
                            $status,
                            $seo_title,
                            $seo_description,
                            $seo_keywords
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
                    'status' => $post->status,
                    'seo_title' => $post->seo_title,
                    'seo_description' => $post->seo_description,
                    'seo_keywords' => $post->seo_keywords
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
     * CRUD: Duplicar Entrada
     */
    public function duplicatePost(): void {
        $this->checkAuth();
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $post = Post::find($id);
            if ($post) {
                // Generar un slug único para el duplicado
                $baseSlug = $post->slug . '-copia';
                $slug = $baseSlug;
                $counter = 1;
                
                $db = \App\Database::getConnection();
                $stmt = $db->prepare("SELECT COUNT(*) FROM posts WHERE slug = :slug");
                
                do {
                    $stmt->execute(['slug' => $slug]);
                    $exists = (int)$stmt->fetchColumn() > 0;
                    if ($exists) {
                        $slug = $baseSlug . '-' . $counter;
                        $counter++;
                    }
                } while ($exists);

                // Crear el post duplicado. El título será "Título (Copia)"
                $title = $post->title . ' (Copia)';
                
                Post::create(
                    $post->user_id,
                    $post->category_id,
                    $title,
                    $slug,
                    $post->excerpt,
                    $post->content,
                    $post->featured_image,
                    'draft', // Por seguridad y UX, se crea como Borrador
                    $post->seo_title ? $post->seo_title . ' (Copia)' : null,
                    $post->seo_description,
                    $post->seo_keywords
                );
            }
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

            // Evitar anidamiento de 3 niveles en el backend (colapsar a abuelo)
            if ($parentId !== null) {
                $parentCategory = Category::find($parentId);
                if ($parentCategory && $parentCategory->parent_id !== null) {
                    $parentId = $parentCategory->parent_id;
                }
            }

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

            // Evitar anidamiento de 3 niveles en el backend (colapsar a abuelo)
            if ($parentId !== null) {
                $parentCategory = Category::find($parentId);
                if ($parentCategory && $parentCategory->parent_id !== null) {
                    $parentId = $parentCategory->parent_id;
                }
            }

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
                        // Evitar anidamiento de 3 niveles en el backend (colapsar a abuelo)
                        if ($parentId !== null) {
                            $parentCategory = Category::find($parentId);
                            if ($parentCategory && $parentCategory->parent_id !== null) {
                                $parentId = $parentCategory->parent_id;
                            }
                        }
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
     * CRUD: Activar/Desactivar Caja de Comentarios en las Entradas
     */
    public function toggleComments(): void {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $enable = isset($_POST['enable_comments']) && $_POST['enable_comments'] === '1' ? '1' : '0';
            \App\Models\Setting::set('enable_comments', $enable);
        }
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

            if ($page->slug === 'sobre-el-autor') {
                $authorName = trim($_POST['author_name'] ?? '');
                if (empty($authorName)) {
                    $error = 'El nombre del autor es obligatorio.';
                } else {
                    $adminId = $_SESSION['admin_id'] ?? null;
                    $author = null;
                    if ($adminId) {
                        $author = User::find($adminId);
                    }
                    if (!$author && isset($_SESSION['admin_user'])) {
                        $author = User::findByUsername($_SESSION['admin_user']);
                    }
                    if ($author) {
                        $oldName = $author->display_name;
                        if ($authorName !== $oldName) {
                            User::updateDisplayName($author->id, $authorName);
                            $_SESSION['admin_name'] = $authorName;
                            $content = str_replace($oldName, $authorName, $content);
                        }
                    }
                }
            }

            if (!$error) {
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
        }

        $adminId = $_SESSION['admin_id'] ?? null;
        $author = null;
        if ($adminId) {
            $author = User::find($adminId);
        }
        if (!$author && isset($_SESSION['admin_user'])) {
            $author = User::findByUsername($_SESSION['admin_user']);
        }

        $this->render('admin/pages/edit', [
            'title' => 'Editar Página - Admin Panel',
            'page' => $page,
            'author' => $author,
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

            $ctaEbookTitle = trim($_POST['cta_ebook_title'] ?? '');
            $ctaEbookDesc = trim($_POST['cta_ebook_desc'] ?? '');
            $ctaEbookButton = trim($_POST['cta_ebook_button'] ?? '');
            $ctaEbookLink = trim($_POST['cta_ebook_link'] ?? '');

            $socialFacebook = trim($_POST['social_facebook'] ?? '');
            $socialInstagram = trim($_POST['social_instagram'] ?? '');
            $socialTwitter = trim($_POST['social_twitter'] ?? '');
            $socialLinkedin = trim($_POST['social_linkedin'] ?? '');
            $socialYoutube = trim($_POST['social_youtube'] ?? '');
            $socialGithub = trim($_POST['social_github'] ?? '');

            // Validar formato hexadecimal
            $hexPattern = '/^#[0-9a-fA-F]{6}$/';

            if (empty($siteName) || empty($themeLight) || empty($themeLightSec) || empty($themeDark) || empty($themeDarkSec) || 
                empty($themeLightBg) || empty($themeDarkBg) || empty($themeLightHeader) || empty($themeDarkHeader) || 
                empty($themeLightFooter) || empty($themeDarkFooter) || empty($ctaEbookTitle) || empty($ctaEbookDesc) || empty($ctaEbookButton) || empty($ctaEbookLink)) {
                $error = 'Por favor, completa todos los campos obligatorios.';
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
                \App\Models\Setting::set('cta_ebook_title', $ctaEbookTitle);
                \App\Models\Setting::set('cta_ebook_desc', $ctaEbookDesc);
                \App\Models\Setting::set('cta_ebook_button', $ctaEbookButton);
                \App\Models\Setting::set('cta_ebook_link', $ctaEbookLink);

                \App\Models\Setting::set('social_facebook', $socialFacebook);
                \App\Models\Setting::set('social_instagram', $socialInstagram);
                \App\Models\Setting::set('social_twitter', $socialTwitter);
                \App\Models\Setting::set('social_linkedin', $socialLinkedin);
                \App\Models\Setting::set('social_youtube', $socialYoutube);
                \App\Models\Setting::set('social_github', $socialGithub);

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

        $ctaEbookTitle = \App\Models\Setting::get('cta_ebook_title', 'Descarga nuestro eBook Gratuito');
        $ctaEbookDesc = \App\Models\Setting::get('cta_ebook_desc', 'Aprende los fundamentos del desarrollo web moderno con nuestra guía completa.');
        $ctaEbookButton = \App\Models\Setting::get('cta_ebook_button', 'Descargar eBook');
        $ctaEbookLink = \App\Models\Setting::get('cta_ebook_link', '#');

        $socialFacebook = \App\Models\Setting::get('social_facebook', '');
        $socialInstagram = \App\Models\Setting::get('social_instagram', '');
        $socialTwitter = \App\Models\Setting::get('social_twitter', '');
        $socialLinkedin = \App\Models\Setting::get('social_linkedin', '');
        $socialYoutube = \App\Models\Setting::get('social_youtube', '');
        $socialGithub = \App\Models\Setting::get('social_github', '');

        $currentCommit = \App\Models\Setting::get('current_commit', 'initial');

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
            'ctaEbookTitle' => $ctaEbookTitle,
            'ctaEbookDesc' => $ctaEbookDesc,
            'ctaEbookButton' => $ctaEbookButton,
            'ctaEbookLink' => $ctaEbookLink,
            'socialFacebook' => $socialFacebook,
            'socialInstagram' => $socialInstagram,
            'socialTwitter' => $socialTwitter,
            'socialLinkedin' => $socialLinkedin,
            'socialYoutube' => $socialYoutube,
            'socialGithub' => $socialGithub,
            'currentCommit' => $currentCommit,
            'error' => $error,
            'success' => $success
        ]);
    }

    /**
     * Muestra y procesa el módulo CTA eBook.
     */
    public function ctaEbook(): void {
        $this->checkAuth();

        $error = null;
        $success = false;

        $uploadDir = dirname(dirname(__DIR__)) . '/public/uploads/';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? 'save';

            if ($action === 'delete_file') {
                $currentLink = \App\Models\Setting::get('cta_ebook_link', '#');
                if (!empty($currentLink) && $currentLink !== '#') {
                    $filePath = $uploadDir . $currentLink;
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                    \App\Models\Setting::set('cta_ebook_link', '#');
                    $success = 'El archivo del eBook ha sido eliminado con éxito.';
                }
            } else {
                $ctaEbookTitle = trim($_POST['cta_ebook_title'] ?? '');
                $ctaEbookDesc = trim($_POST['cta_ebook_desc'] ?? '');
                $ctaEbookButton = trim($_POST['cta_ebook_button'] ?? '');
                $ctaEbookLink = trim($_POST['cta_ebook_link'] ?? '#');
                $ctaEbookDelay = trim($_POST['cta_ebook_delay'] ?? '5');

                if (empty($ctaEbookTitle) || empty($ctaEbookDesc) || empty($ctaEbookButton) || empty($ctaEbookDelay)) {
                    $error = 'Por favor, completa los campos requeridos.';
                } else {
                    // Procesar subida de archivo si existe
                    if (isset($_FILES['cta_ebook_file']) && $_FILES['cta_ebook_file']['error'] === UPLOAD_ERR_OK) {
                        $tmpName = $_FILES['cta_ebook_file']['tmp_name'];
                        $origName = basename($_FILES['cta_ebook_file']['name']);
                        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
                        
                        // Validar extensiones de eBook permitidas (pdf, epub, zip, docx, etc.)
                        $allowedExts = ['pdf', 'epub', 'zip', 'rar', 'mobi', 'docx'];
                        if (!in_array($ext, $allowedExts)) {
                            $error = 'Formato de archivo no permitido. Solo se permiten: PDF, EPUB, MOBI, ZIP, RAR, DOCX.';
                        } else {
                            // Borrar el archivo viejo si existe
                            $oldLink = \App\Models\Setting::get('cta_ebook_link', '#');
                            if (!empty($oldLink) && $oldLink !== '#' && file_exists($uploadDir . $oldLink)) {
                                @unlink($uploadDir . $oldLink);
                            }

                            // Subir el nuevo archivo
                            $filename = 'ebook_' . time() . '_' . uniqid() . '.' . $ext;
                            if (!is_dir($uploadDir)) {
                                mkdir($uploadDir, 0755, true);
                            }
                            if (move_uploaded_file($tmpName, $uploadDir . $filename)) {
                                $ctaEbookLink = $filename;
                            }
                        }
                    }

                    if (!$error) {
                        \App\Models\Setting::set('cta_ebook_title', $ctaEbookTitle);
                        \App\Models\Setting::set('cta_ebook_desc', $ctaEbookDesc);
                        \App\Models\Setting::set('cta_ebook_button', $ctaEbookButton);
                        \App\Models\Setting::set('cta_ebook_link', $ctaEbookLink);
                        \App\Models\Setting::set('cta_ebook_delay', $ctaEbookDelay);
                        $success = '¡Módulo CTA eBook guardado con éxito!';
                    }
                }
            }
        }

        // Cargar valores actuales
        $ctaEbookTitle = \App\Models\Setting::get('cta_ebook_title', 'Descarga nuestro eBook Gratuito');
        $ctaEbookDesc = \App\Models\Setting::get('cta_ebook_desc', 'Aprende los fundamentos del desarrollo web moderno con nuestra guía completa.');
        $ctaEbookButton = \App\Models\Setting::get('cta_ebook_button', 'Descargar eBook');
        $ctaEbookLink = \App\Models\Setting::get('cta_ebook_link', '#');
        $ctaEbookDelay = \App\Models\Setting::get('cta_ebook_delay', '5');

        $this->render('admin/cta_ebook/index', [
            'title' => 'Módulo CTA eBook - Admin Panel',
            'ctaEbookTitle' => $ctaEbookTitle,
            'ctaEbookDesc' => $ctaEbookDesc,
            'ctaEbookButton' => $ctaEbookButton,
            'ctaEbookLink' => $ctaEbookLink,
            'ctaEbookDelay' => $ctaEbookDelay,
            'error' => $error,
            'success' => $success
        ]);
    }

    public function checkUpdate(): void {
        $this->checkAuth();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['github_update_available']);
        unset($_SESSION['github_latest_commit']);
        unset($_SESSION['github_last_checked']);
        header("Location: /?route=admin/dashboard");
        exit;
    }

    public function update(): void {
        $this->checkAuth();
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $githubOwner = 'PabloBautistaGoyoneche';
        $githubRepo = 'web-publisher';
        $githubBranch = 'main';
        $githubToken = '';
        
        $currentCommit = \App\Models\Setting::get('current_commit', 'initial');
        
        // Obtener el último commit de la sesión si está guardado, o consultarlo
        $latestCommit = $_SESSION['github_latest_commit'] ?? null;
        $updateAvailable = $_SESSION['github_update_available'] ?? false;
        
        if (!$latestCommit) {
            $latestCommit = $this->fetchLatestCommitSha($githubOwner, $githubRepo, $githubBranch, $githubToken);
            if ($latestCommit) {
                $_SESSION['github_latest_commit'] = $latestCommit;
                $updateAvailable = ($latestCommit !== $currentCommit);
                $_SESSION['github_update_available'] = $updateAvailable;
                $_SESSION['github_last_checked'] = time();
            }
        }
        
        $this->render('admin/update', [
            'title' => 'Actualización de Sistema - Admin Panel',
            'currentCommit' => $currentCommit,
            'latestCommit' => $latestCommit,
            'updateAvailable' => $updateAvailable
        ]);
    }

    public function updateApi(): void {
        $this->checkAuth();
        header('Content-Type: application/json');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $githubOwner = 'PabloBautistaGoyoneche';
        $githubRepo = 'web-publisher';
        $githubBranch = 'main';
        $githubToken = '';

        $action = $_GET['action'] ?? '';

        try {
            switch ($action) {
                case 'download':
                    $latestCommitSha = $this->fetchLatestCommitSha($githubOwner, $githubRepo, $githubBranch, $githubToken);
                    if (!$latestCommitSha) {
                        throw new \Exception("No se pudo conectar a GitHub para verificar el commit.");
                    }

                    $zipUrl = "https://api.github.com/repos/{$githubOwner}/{$githubRepo}/zipball/{$githubBranch}";
                    $tmpZip = tempnam(sys_get_temp_dir(), 'update_') . '.zip';

                    if (!$this->downloadZip($zipUrl, $tmpZip, $githubToken)) {
                        @unlink($tmpZip);
                        throw new \Exception("Error al descargar el paquete ZIP desde GitHub.");
                    }

                    $_SESSION['update_zip_path'] = $tmpZip;
                    $_SESSION['update_latest_commit'] = $latestCommitSha;

                    echo json_encode([
                        'success' => true,
                        'commit_short_sha' => substr($latestCommitSha, 0, 7)
                    ]);
                    break;

                case 'extract':
                    $tmpZip = $_SESSION['update_zip_path'] ?? '';
                    if (empty($tmpZip) || !file_exists($tmpZip)) {
                        throw new \Exception("No se encontró el archivo ZIP descargado en la sesión.");
                    }

                    $extractDir = sys_get_temp_dir() . '/update_extracted_' . time();
                    $zip = new \ZipArchive();
                    if ($zip->open($tmpZip) === true) {
                        $zip->extractTo($extractDir);
                        $zip->close();
                    } else {
                        @unlink($tmpZip);
                        throw new \Exception("No se pudo extraer el ZIP descargado.");
                    }

                    $_SESSION['update_extract_dir'] = $extractDir;

                    echo json_encode(['success' => true]);
                    break;

                case 'install':
                    $extractDir = $_SESSION['update_extract_dir'] ?? '';
                    if (empty($extractDir) || !is_dir($extractDir)) {
                        throw new \Exception("No se encontró el directorio de extracción temporal.");
                    }

                    $subdirs = glob($extractDir . '/*', GLOB_ONLYDIR);
                    if (empty($subdirs)) {
                        throw new \Exception("La estructura del ZIP de GitHub es incorrecta.");
                    }
                    $repoDir = $subdirs[0];

                    // Copiar archivos
                    $appRoot = dirname(__DIR__);
                    $this->copyFolder($repoDir, $appRoot);

                    // Ejecutar migraciones SQL
                    $migrationsCount = 0;
                    $migrationsDir = $appRoot . '/src/Migrations';
                    if (is_dir($migrationsDir)) {
                        $db = \App\Database::getConnection();
                        
                        $db->exec("CREATE TABLE IF NOT EXISTS migrations (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            migration VARCHAR(100) NOT NULL UNIQUE,
                            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                        )");

                        $files = glob($migrationsDir . '/*.sql');
                        sort($files);

                        foreach ($files as $file) {
                            $filename = basename($file);
                            $stmt = $db->prepare("SELECT COUNT(*) FROM migrations WHERE migration = :name");
                            $stmt->execute(['name' => $filename]);
                            if ($stmt->fetchColumn() == 0) {
                                $sql = file_get_contents($file);
                                if (!empty(trim($sql))) {
                                    $db->exec($sql);
                                }
                                $stmt = $db->prepare("INSERT INTO migrations (migration) VALUES (:name)");
                                $stmt->execute(['name' => $filename]);
                                $migrationsCount++;
                            }
                        }
                    }

                    echo json_encode([
                        'success' => true,
                        'migrations_count' => $migrationsCount
                    ]);
                    break;

                case 'cleanup':
                    $tmpZip = $_SESSION['update_zip_path'] ?? '';
                    $extractDir = $_SESSION['update_extract_dir'] ?? '';
                    $latestCommitSha = $_SESSION['update_latest_commit'] ?? '';

                    if (!empty($tmpZip) && file_exists($tmpZip)) {
                        @unlink($tmpZip);
                    }
                    if (!empty($extractDir) && is_dir($extractDir)) {
                        $this->removeFolder($extractDir);
                    }

                    if (!empty($latestCommitSha)) {
                        \App\Models\Setting::set('current_commit', $latestCommitSha);
                    }

                    unset($_SESSION['update_zip_path']);
                    unset($_SESSION['update_extract_dir']);
                    unset($_SESSION['update_latest_commit']);
                    unset($_SESSION['github_update_available']);
                    unset($_SESSION['github_latest_commit']);
                    unset($_SESSION['github_last_checked']);

                    echo json_encode(['success' => true]);
                    break;

                default:
                    throw new \Exception("Acción de actualización no válida.");
            }
        } catch (\Exception $e) {
            // Intentar limpiar en caso de error
            $tmpZip = $_SESSION['update_zip_path'] ?? '';
            $extractDir = $_SESSION['update_extract_dir'] ?? '';
            if (!empty($tmpZip) && file_exists($tmpZip)) {
                @unlink($tmpZip);
            }
            if (!empty($extractDir) && is_dir($extractDir)) {
                $this->removeFolder($extractDir);
            }
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    public function logs(): void {
        $this->checkAuth();
        
        $db = \App\Database::getConnection();
        
        // Asegurar que la tabla exista
        $db->exec("CREATE TABLE IF NOT EXISTS system_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            level VARCHAR(20) NOT NULL,
            message TEXT NOT NULL,
            file VARCHAR(255) DEFAULT NULL,
            line INT DEFAULT NULL,
            trace TEXT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        $stmt = $db->query("SELECT * FROM system_logs ORDER BY created_at DESC LIMIT 150");
        $logs = $stmt->fetchAll();

        $this->render('admin/logs', [
            'title' => 'Bitácora de Errores - Admin Panel',
            'logs' => $logs
        ]);
    }

    public function clearLogs(): void {
        $this->checkAuth();
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        try {
            $db = \App\Database::getConnection();
            $db->exec("DELETE FROM system_logs");
            $_SESSION['logs_success'] = "El historial de errores ha sido vaciado correctamente.";
        } catch (\Throwable $e) {
            $_SESSION['logs_error'] = "No se pudo vaciar la bitácora: " . $e->getMessage();
        }

        header("Location: /?route=admin/logs");
        exit;
    }

    private function fetchLatestCommitSha(string $owner, string $repo, string $branch, string $token): ?string {
        $url = "https://api.github.com/repos/$owner/$repo/commits/$branch";
        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => [
                    'User-Agent: Web-Publisher-App',
                    'Accept: application/vnd.github.v3+json'
                ]
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ];
        if (!empty($token)) {
            $opts['http']['header'][] = "Authorization: token $token";
        }
        $context = stream_context_create($opts);
        try {
            $response = @file_get_contents($url, false, $context);
            if ($response !== false) {
                $data = json_decode($response, true);
                return $data['sha'] ?? null;
            }
        } catch (\Exception $e) {}
        return null;
    }

    private function downloadZip(string $url, string $destPath, string $token): bool {
        $fp = fopen($destPath, 'w+');
        if (!$fp) return false;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Web-Publisher-App');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($token)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: token $token"]);
        }
        $success = curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        return $success !== false;
    }

    private function copyFolder(string $src, string $dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    if ($file === 'uploads' || $file === '.git' || $file === '.agents' || $file === '.gemini' || $file === 'node_modules') {
                        continue;
                    }
                    $this->copyFolder($src . '/' . $file, $dst . '/' . $file);
                } else {
                    if ($file === 'database.php' && basename(dirname($src . '/' . $file)) === 'config') {
                        continue;
                    }
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    private function removeFolder(string $dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . "/" . $object) && !is_link($dir . "/" . $object))
                        $this->removeFolder($dir . "/" . $object);
                    else
                        unlink($dir . "/" . $object);
                }
            }
            rmdir($dir);
        }
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

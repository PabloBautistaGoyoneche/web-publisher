<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use App\Helpers;

class BlogController {
    /**
     * Muestra la página de inicio.
     */
    public function home(): void {
        $posts = Post::allPublished();
        $categories = Category::all();
        
        // El primer post es el destacado (si existe)
        $featuredPost = !empty($posts) ? $posts[0] : null;
        
        // El resto va al grid principal
        $gridPosts = !empty($posts) ? array_slice($posts, 1) : [];

        $this->render('home', [
            'title' => 'Inicio - Modern Blog',
            'featuredPost' => $featuredPost,
            'posts' => $gridPosts,
            'categories' => $categories
        ]);
    }

    /**
     * Muestra un post individual por su slug y maneja el envío de comentarios.
     */
    public function post(string $slug): void {
        $post = Post::findBySlug($slug);
        
        if (!$post) {
            $this->error404();
            return;
        }

        // Incrementar vistas
        $post->incrementViews();

        $comments = $post->getComments();
        $relatedPosts = $post->getRelated(6);
        $categories = Category::all();

        // Procesar comentario enviado (POST)
        $commentSuccess = false;
        $commentError = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
            if (\App\Models\Setting::get('enable_comments', '1') !== '1') {
                $commentError = 'Los comentarios están deshabilitados temporalmente.';
            } else {
                $name = trim($_POST['author_name'] ?? '');
                $email = trim($_POST['author_email'] ?? '');
                $content = trim($_POST['content'] ?? '');

                if (empty($name) || empty($email) || empty($content)) {
                    $commentError = 'Todos los campos son obligatorios para comentar.';
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $commentError = 'Por favor, introduce un correo electrónico válido.';
                } else {
                    $success = Comment::create($post->id, $name, $email, $content);
                    if ($success) {
                        // Recargar comentarios
                        $comments = $post->getComments();
                        $commentSuccess = true;
                    } else {
                        $commentError = 'Ocurrió un error al guardar tu comentario. Inténtalo de nuevo.';
                    }
                }
            }
        }

        $this->render('post', [
            'title' => $post->title . ' - Modern Blog',
            'post' => $post,
            'comments' => $comments,
            'relatedPosts' => $relatedPosts,
            'categories' => $categories,
            'commentSuccess' => $commentSuccess,
            'commentError' => $commentError
        ]);
    }

    /**
     * Muestra el archivo de una categoría.
     */
    public function archive(string $categorySlug): void {
        $category = Category::findBySlug($categorySlug);
        
        if (!$category) {
            $this->error404();
            return;
        }

        $posts = Post::findByCategory($category->id);
        $categories = Category::all();

        $this->render('archive', [
            'title' => 'Categoría: ' . $category->name . ' - Modern Blog',
            'category' => $category,
            'posts' => $posts,
            'categories' => $categories
        ]);
    }

    /**
     * Muestra los resultados de búsqueda.
     */
    public function search(): void {
        $query = trim($_GET['s'] ?? '');
        $posts = [];
        if (!empty($query)) {
            $posts = Post::search($query);
        }

        $categories = Category::all();

        $this->render('search', [
            'title' => 'Buscar: "' . Helpers::sanitize($query) . '" - Modern Blog',
            'query' => $query,
            'posts' => $posts,
            'categories' => $categories
        ]);
    }

    /**
     * Muestra una página estática y maneja el formulario de contacto.
     */
    public function page(string $slug): void {
        $page = \App\Models\Page::findBySlug($slug);
        
        if (!$page) {
            $this->error404();
            return;
        }

        $categories = Category::all();
        
        $contactSuccess = false;
        $contactError = null;

        if ($slug === 'contacto' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $subject = trim($_POST['subject'] ?? '');
            $message = trim($_POST['message'] ?? '');

            if (empty($name) || empty($email) || empty($subject) || empty($message)) {
                $contactError = 'Todos los campos son obligatorios.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $contactError = 'Por favor, ingresa un correo electrónico válido.';
            } else {
                $success = \App\Models\Message::create($name, $email, $subject, $message);
                if ($success) {
                    $contactSuccess = true;
                } else {
                    $contactError = 'Ocurrió un error al enviar el mensaje. Inténtalo de nuevo.';
                }
            }
        }

        $this->render('page', [
            'title' => $page->title . ' - Modern Blog',
            'page' => $page,
            'categories' => $categories,
            'contactSuccess' => $contactSuccess,
            'contactError' => $contactError
        ]);
    }

    /**
     * Página de error 404.
     */
    public function error404(): void {
        header("HTTP/1.0 404 Not Found");
        $categories = Category::all();
        $this->render('404', [
            'title' => 'Página No Encontrada - 404',
            'categories' => $categories
        ]);
    }

    /**
     * Renderiza una vista pasando datos extractados.
     */
    private function render(string $viewName, array $data = []): void {
        extract($data);
        $viewFile = dirname(__DIR__) . "/Views/{$viewName}.php";
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo "Error: La vista '{$viewName}' no existe.";
        }
    }
}

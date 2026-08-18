<?php
use App\Helpers;
?>
<!DOCTYPE html>
<html lang="es" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <?php
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
    
    // Reemplazar nombre del sitio en el título de la página
    $pageTitle = isset($title) ? $title : $siteName;
    $pageTitle = str_ireplace(['Modern Blog', 'ModernBlog'], $siteName, $pageTitle);
    
    // SEO Meta Title, Description & Keywords Auto-generation for Articles
    $seoDescription = \App\Models\Setting::get('site_description', 'Un blog moderno e interactivo.');
    $seoKeywords = 'tecnologia, blog, programacion, desarrollo web';
    
    if (isset($post) && $post instanceof \App\Models\Post) {
        // Meta Title: Use custom seo_title if set, otherwise autogenerate (max 60 characters)
        if (!empty($post->seo_title)) {
            $pageTitle = $post->seo_title;
        } else {
            $rawTitle = $post->title;
            if (mb_strlen($rawTitle) > 60) {
                $pageTitle = mb_substr($rawTitle, 0, 57) . '...';
            } else {
                $pageTitle = $rawTitle;
            }
        }
        
        // Meta Description: Use custom seo_description if set, otherwise autogenerate (max 155 characters ending in "...")
        if (!empty($post->seo_description)) {
            $seoDescription = $post->seo_description;
        } else {
            $cleanContent = strip_tags($post->content);
            $cleanContent = preg_replace('/\s+/', ' ', $cleanContent);
            $cleanContent = trim($cleanContent);
            
            if (mb_strlen($cleanContent) > 155) {
                $seoDescription = mb_substr($cleanContent, 0, 152) . '...';
            } else {
                if (mb_strlen($cleanContent) > 152) {
                    $seoDescription = mb_substr($cleanContent, 0, 152) . '...';
                } else {
                    $seoDescription = $cleanContent;
                }
            }
        }
        
        // Meta Keywords: Use custom seo_keywords if set, otherwise autogenerate from title and content
        if (!empty($post->seo_keywords)) {
            $seoKeywords = $post->seo_keywords;
        } else {
            $keywordsList = [];
            
            // Title words (length >= 4)
            $titleClean = strtolower(preg_replace('/[^a-záéíóúüñ\s]/u', '', $post->title));
            $titleWords = explode(' ', $titleClean);
            foreach ($titleWords as $word) {
                $word = trim($word);
                if (mb_strlen($word) >= 4) {
                    $keywordsList[] = $word;
                }
            }
            
            // Content frequent words (excluding stop words)
            $contentClean = strtolower(preg_replace('/[^a-záéíóúüñ\s]/u', '', strip_tags($post->content)));
            $contentWords = explode(' ', $contentClean);
            
            $stopWords = [
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
            
            $wordCounts = [];
            foreach ($contentWords as $word) {
                $word = trim($word);
                if (mb_strlen($word) >= 4 && !in_array($word, $stopWords)) {
                    $wordCounts[$word] = isset($wordCounts[$word]) ? $wordCounts[$word] + 1 : 1;
                }
            }
            
            arsort($wordCounts);
            $topContentWords = array_slice(array_keys($wordCounts), 0, 10);
            $keywordsList = array_merge($keywordsList, $topContentWords);
            
            $keywordsList = array_unique($keywordsList);
            $seoKeywords = implode(', ', array_slice($keywordsList, 0, 15));
        }
    }

    // Configuración dinámica de OpenGraph, Twitter Cards y JSON-LD
    $currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $ogTitle = $pageTitle;
    $ogDescription = $seoDescription;
    $ogImage = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . Helpers::asset('favicon.ico');
    $ogType = 'website';

    if (isset($post) && $post instanceof \App\Models\Post) {
        $ogType = 'article';
        if ($post->featured_image) {
            $ogImage = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . Helpers::asset('uploads/' . $post->featured_image);
        }
    }

    // Generar JSON-LD de Breadcrumbs
    $breadcrumbSchema = null;
    if (isset($post) && $post instanceof \App\Models\Post) {
        $cat = $post->getCategory();
        $itemList = [[
            "@type" => "ListItem",
            "position" => 1,
            "name" => "Inicio",
            "item" => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/"
        ]];
        $pos = 2;
        if ($cat) {
            if ($cat->parent_id) {
                $parentCat = \App\Models\Category::find($cat->parent_id);
                if ($parentCat) {
                    $itemList[] = [
                        "@type" => "ListItem",
                        "position" => $pos++,
                        "name" => $parentCat->name,
                        "item" => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/" . $parentCat->slug
                    ];
                }
            }
            $itemList[] = [
                "@type" => "ListItem",
                "position" => $pos++,
                "name" => $cat->name,
                "item" => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/" . $cat->slug
            ];
        }
        $itemList[] = [
            "@type" => "ListItem",
            "position" => $pos,
            "name" => $post->title,
            "item" => $currentUrl
        ];
        $breadcrumbSchema = [
            "@context" => "https://schema.org",
            "@type" => "BreadcrumbList",
            "itemListElement" => $itemList
        ];
    } elseif (isset($category) && $category instanceof \App\Models\Category) {
        $itemList = [[
            "@type" => "ListItem",
            "position" => 1,
            "name" => "Inicio",
            "item" => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/"
        ]];
        $pos = 2;
        if ($category->parent_id) {
            $parentCat = \App\Models\Category::find($category->parent_id);
            if ($parentCat) {
                $itemList[] = [
                    "@type" => "ListItem",
                    "position" => $pos++,
                    "name" => $parentCat->name,
                    "item" => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/" . $parentCat->slug
                ];
            }
        }
        $itemList[] = [
            "@type" => "ListItem",
            "position" => $pos,
            "name" => $category->name,
            "item" => $currentUrl
        ];
        $breadcrumbSchema = [
            "@context" => "https://schema.org",
            "@type" => "BreadcrumbList",
            "itemListElement" => $itemList
        ];
    } elseif (isset($page) && $page instanceof \App\Models\Page) {
        $itemList = [
            [
                "@type" => "ListItem",
                "position" => 1,
                "name" => "Inicio",
                "item" => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/"
            ],
            [
                "@type" => "ListItem",
                "position" => 2,
                "name" => $page->title,
                "item" => $currentUrl
            ]
        ];
        $breadcrumbSchema = [
            "@context" => "https://schema.org",
            "@type" => "BreadcrumbList",
            "itemListElement" => $itemList
        ];
    }

    // Generar JSON-LD de Artículo
    $articleSchema = null;
    if (isset($post) && $post instanceof \App\Models\Post) {
        $articleSchema = [
            "@context" => "https://schema.org",
            "@type" => "BlogPosting",
            "headline" => $post->title,
            "description" => $seoDescription,
            "datePublished" => date('c', strtotime($post->created_at)),
            "dateModified" => date('c', strtotime($post->created_at)),
            "author" => [
                "@type" => "Person",
                "name" => $post->getAuthor()->display_name
            ],
            "publisher" => [
                "@type" => "Organization",
                "name" => $siteName,
                "logo" => [
                    "@type" => "ImageObject",
                    "url" => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . Helpers::asset('favicon.ico')
                ]
            ],
            "mainEntityOfPage" => [
                "@type" => "WebPage",
                "@id" => $currentUrl
            ]
        ];
        if ($post->featured_image) {
            $articleSchema["image"] = [
                (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . Helpers::asset('uploads/' . $post->featured_image)
            ];
        }
    }

    // Generar JSON-LD general para Organización y Sitio
    $siteSchema = [
        "@context" => "https://schema.org",
        "@type" => "WebSite",
        "name" => $siteName,
        "url" => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/"
    ];
    $orgSchema = [
        "@context" => "https://schema.org",
        "@type" => "Organization",
        "name" => $siteName,
        "url" => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/",
        "logo" => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . Helpers::asset('favicon.ico')
    ];
    ?>
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($seoDescription); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($seoKeywords); ?>">

    <!-- Metadatos OpenGraph (Redes Sociales) -->
    <meta property="og:title" content="<?php echo htmlspecialchars($ogTitle); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($ogDescription); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($ogImage); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($currentUrl); ?>">
    <meta property="og:type" content="<?php echo htmlspecialchars($ogType); ?>">
    <meta property="og:site_name" content="<?php echo htmlspecialchars($siteName); ?>">

    <!-- Metadatos Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($ogTitle); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($ogDescription); ?>">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($ogImage); ?>">

    <!-- Datos Estructurados JSON-LD (Motores de Búsqueda) -->
    <script type="application/ld+json">
        <?php echo json_encode($siteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
    </script>
    <script type="application/ld+json">
        <?php echo json_encode($orgSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
    </script>
    <?php if ($breadcrumbSchema): ?>
    <script type="application/ld+json">
        <?php echo json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
    </script>
    <?php endif; ?>
    <?php if ($articleSchema): ?>
    <script type="application/ld+json">
        <?php echo json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
    </script>
    <?php endif; ?>
    
    <!-- Tailwind Styles (Compilado) -->
    <link rel="stylesheet" href="<?php echo Helpers::asset('css/styles.css'); ?>">

    <!-- Estilos de Identidad del Sitio Dinámicos -->
    <style>
        :root {
            /* Colores de Acento Principal */
            --brand-50: <?php echo Helpers::adjustBrightnessRgb($themeLight, 0.95); ?>;
            --brand-100: <?php echo Helpers::adjustBrightnessRgb($themeLight, 0.9); ?>;
            --brand-200: <?php echo Helpers::adjustBrightnessRgb($themeLight, 0.75); ?>;
            --brand-300: <?php echo Helpers::adjustBrightnessRgb($themeLight, 0.55); ?>;
            --brand-400: <?php echo Helpers::adjustBrightnessRgb($themeLight, 0.35); ?>;
            --brand-500: <?php echo Helpers::adjustBrightnessRgb($themeLight, 0.15); ?>;
            --brand-600: <?php echo Helpers::hexToRgbValues($themeLight); ?>;
            --brand-700: <?php echo Helpers::adjustBrightnessRgb($themeLight, -0.15); ?>;
            --brand-800: <?php echo Helpers::adjustBrightnessRgb($themeLight, -0.3); ?>;
            --brand-900: <?php echo Helpers::adjustBrightnessRgb($themeLight, -0.45); ?>;
            --brand-950: <?php echo Helpers::adjustBrightnessRgb($themeLight, -0.6); ?>;

            /* Colores Secundarios (Degradado) */
            --secondary-50: <?php echo Helpers::adjustBrightnessRgb($themeLightSec, 0.95); ?>;
            --secondary-100: <?php echo Helpers::adjustBrightnessRgb($themeLightSec, 0.9); ?>;
            --secondary-200: <?php echo Helpers::adjustBrightnessRgb($themeLightSec, 0.75); ?>;
            --secondary-300: <?php echo Helpers::adjustBrightnessRgb($themeLightSec, 0.55); ?>;
            --secondary-400: <?php echo Helpers::adjustBrightnessRgb($themeLightSec, 0.35); ?>;
            --secondary-500: <?php echo Helpers::adjustBrightnessRgb($themeLightSec, 0.15); ?>;
            --secondary-600: <?php echo Helpers::hexToRgbValues($themeLightSec); ?>;
            --secondary-700: <?php echo Helpers::adjustBrightnessRgb($themeLightSec, -0.15); ?>;
            --secondary-800: <?php echo Helpers::adjustBrightnessRgb($themeLightSec, -0.3); ?>;
            --secondary-900: <?php echo Helpers::adjustBrightnessRgb($themeLightSec, -0.45); ?>;
            --secondary-950: <?php echo Helpers::adjustBrightnessRgb($themeLightSec, -0.6); ?>;

            /* Fondos del Sitio */
            --sitebg: <?php echo Helpers::hexToRgbValues($themeLightBg); ?>;
            --siteheader: <?php echo Helpers::hexToRgbValues($themeLightHeader); ?>;
            --sitefooter: <?php echo Helpers::hexToRgbValues($themeLightFooter); ?>;
        }
        .dark {
            /* Colores de Acento Principal */
            --brand-50: <?php echo Helpers::adjustBrightnessRgb($themeDark, 0.95); ?>;
            --brand-100: <?php echo Helpers::adjustBrightnessRgb($themeDark, 0.9); ?>;
            --brand-200: <?php echo Helpers::adjustBrightnessRgb($themeDark, 0.75); ?>;
            --brand-300: <?php echo Helpers::adjustBrightnessRgb($themeDark, 0.55); ?>;
            --brand-400: <?php echo Helpers::hexToRgbValues($themeDark); ?>;
            --brand-500: <?php echo Helpers::adjustBrightnessRgb($themeDark, -0.15); ?>;
            --brand-600: <?php echo Helpers::adjustBrightnessRgb($themeDark, -0.3); ?>;
            --brand-700: <?php echo Helpers::adjustBrightnessRgb($themeDark, -0.45); ?>;
            --brand-800: <?php echo Helpers::adjustBrightnessRgb($themeDark, -0.6); ?>;
            --brand-900: <?php echo Helpers::adjustBrightnessRgb($themeDark, -0.75); ?>;
            --brand-950: <?php echo Helpers::adjustBrightnessRgb($themeDark, -0.85); ?>;

            /* Colores Secundarios (Degradado) */
            --secondary-50: <?php echo Helpers::adjustBrightnessRgb($themeDarkSec, 0.95); ?>;
            --secondary-100: <?php echo Helpers::adjustBrightnessRgb($themeDarkSec, 0.9); ?>;
            --secondary-200: <?php echo Helpers::adjustBrightnessRgb($themeDarkSec, 0.75); ?>;
            --secondary-300: <?php echo Helpers::adjustBrightnessRgb($themeDarkSec, 0.55); ?>;
            --secondary-400: <?php echo Helpers::hexToRgbValues($themeDarkSec); ?>;
            --secondary-500: <?php echo Helpers::adjustBrightnessRgb($themeDarkSec, -0.15); ?>;
            --secondary-600: <?php echo Helpers::adjustBrightnessRgb($themeDarkSec, -0.3); ?>;
            --secondary-700: <?php echo Helpers::adjustBrightnessRgb($themeDarkSec, -0.45); ?>;
            --secondary-800: <?php echo Helpers::adjustBrightnessRgb($themeDarkSec, -0.6); ?>;
            --secondary-900: <?php echo Helpers::adjustBrightnessRgb($themeDarkSec, -0.75); ?>;
            --secondary-950: <?php echo Helpers::adjustBrightnessRgb($themeDarkSec, -0.85); ?>;

            /* Fondos del Sitio */
            --sitebg: <?php echo Helpers::hexToRgbValues($themeDarkBg); ?>;
            --siteheader: <?php echo Helpers::hexToRgbValues($themeDarkHeader); ?>;
            --sitefooter: <?php echo Helpers::hexToRgbValues($themeDarkFooter); ?>;
        }
    </style>
    
    <!-- Script de Inicialización de Modo Oscuro Rápido (Previene parpadeo) -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-sitebg text-slate-900 transition-colors duration-300 dark:text-slate-100 flex flex-col min-h-screen">

    <!-- Navbar flotante con Glassmorphism -->
    <header class="sticky top-0 z-50 glass-navbar transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="flex items-center gap-2 group">
                        <span class="font-extrabold text-xl tracking-tight text-white">
                            <?php echo htmlspecialchars($siteName); ?>
                        </span>
                    </a>
                </div>

                <!-- Enlaces de navegación desktop -->
                <nav class="hidden xl:flex items-center space-x-8 font-medium">
                    <a href="/" class="text-white hover:text-white/80 transition-colors">Inicio</a>
                    <a href="/?route=page&slug=sobre-el-autor" class="text-white hover:text-white/80 transition-colors">Sobre Nosotros</a>
                    
                    <!-- Menú dinámico de categorías -->
                    <div class="relative group">
                        <button class="flex items-center gap-1 text-white hover:text-white/80 transition-colors focus:outline-none">
                            Categorías
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        
                        <!-- Dropdown de categorías -->
                        <div class="absolute left-0 mt-2 w-56 rounded-xl bg-white dark:bg-slate-900 shadow-xl border border-slate-100 dark:border-slate-800 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <?php if (isset($categories)): ?>
                                <?php 
                                $parentCategories = array_filter($categories, function($c) { return $c->parent_id === null; });
                                foreach($parentCategories as $cat): 
                                    $subcategories = array_filter($categories, function($c) use ($cat) { return $c->parent_id === $cat->id; });
                                ?>
                                    <?php if (!empty($subcategories)): ?>
                                        <!-- Categoría con Subcategorías -->
                                        <div class="relative group/sub">
                                            <a href="/?route=category&slug=<?php echo $cat->slug; ?>" class="flex items-center justify-between px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">
                                                <span><?php echo htmlspecialchars($cat->name); ?></span>
                                                <svg class="w-3.5 h-3.5 transform -rotate-90 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </a>
                                            <!-- Sub-dropdown lateral -->
                                            <div class="absolute left-full top-0 ml-1 w-48 rounded-xl bg-white dark:bg-slate-900 shadow-xl border border-slate-100 dark:border-slate-800 py-2 opacity-0 invisible group-hover/sub:opacity-100 group-hover/sub:visible transition-all duration-200">
                                                <?php foreach($subcategories as $sub): ?>
                                                    <a href="/?route=category&slug=<?php echo $sub->slug; ?>" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">
                                                        <?php echo htmlspecialchars($sub->name); ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <!-- Categoría simple -->
                                        <a href="/?route=category&slug=<?php echo $cat->slug; ?>" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">
                                            <?php echo htmlspecialchars($cat->name); ?>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <a href="/?route=page&slug=contacto" class="text-white hover:text-white/80 transition-colors">Contacto</a>
                </nav>

                <!-- Buscador y Botón de Modo Oscuro -->
                <div class="flex items-center gap-4">
                    
                    <!-- Formulario de Búsqueda -->
                    <form action="/" method="GET" class="hidden sm:block relative">
                        <input type="hidden" name="route" value="search">
                        <input type="text" name="s" placeholder="Buscar posts..." value="<?php echo isset($query) ? htmlspecialchars($query) : ''; ?>" class="w-48 focus:w-64 px-4 py-2 pl-10 text-sm bg-white/10 text-white border border-transparent focus:border-white/30 rounded-xl focus:outline-none transition-all duration-300 placeholder:text-white/60">
                        <div class="absolute left-3 top-2.5 text-white/60">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </form>

                    <!-- Interruptor Modo Oscuro -->
                    <button id="theme-toggle" class="p-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white transition-colors focus:outline-none" aria-label="Cambiar tema">
                        <!-- Icono Sol -->
                        <svg id="theme-toggle-light-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.46 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 100 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                        <!-- Icono Luna -->
                        <svg id="theme-toggle-dark-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    </button>
                    
                    <!-- Hamburguesa menú móvil -->
                    <button id="mobile-menu-button" class="xl:hidden p-2 rounded-xl bg-white/10 hover:bg-white/20 text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Menú Móvil -->
        <div id="mobile-menu" class="hidden xl:hidden border-t border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-950 px-6 py-6 flex flex-col items-center gap-2">
            <a href="/" class="w-full text-center font-semibold py-2.5 text-slate-700 dark:text-slate-200 hover:text-brand-600 dark:hover:text-brand-400 transition-colors text-base">Inicio</a>
            <a href="/?route=page&slug=sobre-el-autor" class="w-full text-center font-semibold py-2.5 text-slate-700 dark:text-slate-200 hover:text-brand-600 dark:hover:text-brand-400 transition-colors text-base">Sobre Nosotros</a>
            
            <!-- Acordeón de Categorías -->
            <div class="w-full flex flex-col items-center">
                <button id="mobile-categories-toggle" class="w-full text-center font-semibold py-2.5 text-slate-700 dark:text-slate-200 hover:text-brand-600 dark:hover:text-brand-400 flex items-center justify-center gap-1 focus:outline-none text-base">
                    Categorías
                    <svg id="mobile-categories-arrow" class="w-4 h-4 transform transition-transform duration-200 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div id="mobile-categories-list" class="hidden w-full max-w-xs space-y-1.5 py-2 bg-slate-50 dark:bg-slate-900/40 rounded-2xl border border-slate-100 dark:border-slate-800/50 mt-1 transition-all duration-300">
                    <?php if (isset($categories)): ?>
                        <?php 
                        $parentCategories = array_filter($categories, function($c) { return $c->parent_id === null; });
                        foreach($parentCategories as $cat): 
                            $subcategories = array_filter($categories, function($c) use ($cat) { return $c->parent_id === $cat->id; });
                        ?>
                            <?php if (!empty($subcategories)): ?>
                                <!-- Categoría Padre con Subcategorías -->
                                <div class="w-full flex flex-col items-center">
                                    <button class="mobile-sub-toggle w-full text-center text-sm py-2 text-slate-700 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 font-semibold flex items-center justify-center gap-1 focus:outline-none">
                                        <?php echo htmlspecialchars($cat->name); ?>
                                        <svg class="mobile-sub-arrow w-3.5 h-3.5 transform transition-transform duration-200 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    <div class="mobile-sub-list hidden w-full bg-slate-100/50 dark:bg-slate-950/30 py-1 space-y-1 rounded-xl">
                                        <?php foreach($subcategories as $sub): ?>
                                            <a href="/?route=category&slug=<?php echo $sub->slug; ?>" class="block text-center text-xs py-1.5 text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 font-medium">
                                                <?php echo htmlspecialchars($sub->name); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- Categoría Simple -->
                                <a href="/?route=category&slug=<?php echo $cat->slug; ?>" class="block text-center text-sm py-2 text-slate-700 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 font-semibold">
                                    <?php echo htmlspecialchars($cat->name); ?>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <a href="/?route=page&slug=contacto" class="w-full text-center font-semibold py-2.5 text-slate-700 dark:text-slate-200 hover:text-brand-600 dark:hover:text-brand-400 transition-colors text-base">Contacto</a>
         </div>
    </header>

    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10">

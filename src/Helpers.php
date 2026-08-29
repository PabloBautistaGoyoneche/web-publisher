<?php

namespace App;

class Helpers {
    /**
     * Sanitiza cadenas para prevenir ataques XSS.
     */
    public static function sanitize(string $data): string {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Convierte una fecha SQL en un formato amigable en español.
     */
    public static function formatDate(string $dateStr): string {
        $timestamp = strtotime($dateStr);
        $months = [
            1 => 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        
        $day = date('j', $timestamp);
        $monthNum = (int)date('n', $timestamp);
        $year = date('Y', $timestamp);
        
        return "{$day} de {$months[$monthNum]}, {$year}";
    }

    /**
     * Calcula el tiempo aproximado de lectura de un texto.
     */
    public static function readTime(string $content): string {
        $cleanContent = strip_tags($content);
        $wordCount = str_word_count($cleanContent);
        $minutes = ceil($wordCount / 200); // 200 palabras por minuto promedio
        return "{$minutes} min de lectura";
    }

    /**
     * Genera un extracto recortado de un contenido.
     */
    public static function excerpt(string $content, int $limit = 150): string {
        $cleanContent = strip_tags($content);
        if (mb_strlen($cleanContent) <= $limit) {
            return $cleanContent;
        }
        return mb_substr($cleanContent, 0, $limit) . '...';
    }

    /**
     * Retorna la ruta relativa correcta para recursos estáticos con control de caché.
     */
    public static function asset(string $path): string {
        $realPath = dirname(__DIR__) . '/public/' . ltrim($path, '/');
        $version = file_exists($realPath) ? filemtime($realPath) : time();
        return '/' . ltrim($path, '/') . '?v=' . $version;
    }

    /**
     * Ajusta el brillo de un color hexadecimal.
     * $percent: de -1.0 (negro) a 1.0 (blanco).
     */
    public static function adjustBrightness(string $hex, float $percent): string {
        $hex = ltrim($hex, '#');
        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        if ($percent > 0) {
            $r = round($r + (255 - $r) * $percent);
            $g = round($g + (255 - $g) * $percent);
            $b = round($b + (255 - $b) * $percent);
        } else {
            $r = round($r * (1 + $percent));
            $g = round($g * (1 + $percent));
            $b = round($b * (1 + $percent));
        }
        
        $r = max(0, min(255, $r));
        $g = max(0, min(255, $g));
        $b = max(0, min(255, $b));
        
        return sprintf("#%02x%02x%02x", $r, $g, $b);
    }

    /**
     * Convierte un color hexadecimal en valores RGB separados por espacio.
     */
    public static function hexToRgbValues(string $hex): string {
        $hex = ltrim($hex, '#');
        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        return "{$r} {$g} {$b}";
    }

    /**
     * Ajusta el brillo de un color hexadecimal y retorna sus valores RGB separados por espacio.
     */
    public static function adjustBrightnessRgb(string $hex, float $percent): string {
        $hexColor = self::adjustBrightness($hex, $percent);
        return self::hexToRgbValues($hexColor);
    }

    /**
     * Optimiza y redimensiona una imagen subida al servidor, convirtiéndola a WebP
     * o comprimiendo un JPEG a un tamaño máximo para mejorar el rendimiento de carga (LCP).
     */
    public static function optimizeAndResizeImage(string $sourcePath, string $destPath, int $maxWidth = 1200, int $maxHeight = 1200, int $quality = 82): bool {
        if (!extension_loaded('gd')) {
            return copy($sourcePath, $destPath);
        }

        list($width, $height, $type) = getimagesize($sourcePath);
        
        switch ($type) {
            case IMAGETYPE_JPEG:
                $srcImage = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $srcImage = imagecreatefrompng($sourcePath);
                break;
            case IMAGETYPE_WEBP:
                $srcImage = imagecreatefromwebp($sourcePath);
                break;
            default:
                return copy($sourcePath, $destPath);
        }

        if (!$srcImage) {
            return copy($sourcePath, $destPath);
        }

        // Calcular nuevas dimensiones manteniendo la relación de aspecto
        $ratio = $width / $height;
        if ($width > $maxWidth || $height > $maxHeight) {
            if ($width / $maxWidth > $height / $maxHeight) {
                $newWidth = $maxWidth;
                $newHeight = round($maxWidth / $ratio);
            } else {
                $newHeight = $maxHeight;
                $newWidth = round($maxHeight * $ratio);
            }
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }

        // Crear lienzo vacío con color verdadero
        $dstImage = imagecreatetruecolor($newWidth, $newHeight);

        // Preservar transparencia para PNG y WebP
        if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_WEBP) {
            imagealphablending($dstImage, false);
            imagesavealpha($dstImage, true);
            $transparent = imagecolorallocatealpha($dstImage, 255, 255, 255, 127);
            imagefilledrectangle($dstImage, 0, 0, $newWidth, $newHeight, $transparent);
        }

        // Redimensionar
        imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Guardar la imagen optimizada
        $ext = strtolower(pathinfo($destPath, PATHINFO_EXTENSION));
        $success = false;

        if ($ext === 'webp' && function_exists('imagewebp')) {
            $success = imagewebp($dstImage, $destPath, $quality);
        } elseif ($ext === 'png') {
            // PNG usa un factor de compresión de 0 a 9
            $pngQuality = 9 - round(($quality * 9) / 100);
            $success = imagepng($dstImage, $destPath, $pngQuality);
        } else {
            $success = imagejpeg($dstImage, $destPath, $quality);
        }

        // Liberar memoria
        imagedestroy($srcImage);
        imagedestroy($dstImage);

        return $success;
    }
}

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
     * Retorna la ruta relativa correcta para recursos estáticos (CSS, JS, imágenes).
     */
    public static function asset(string $path): string {
        // En nuestro caso, la raíz es la carpeta public
        return '/' . ltrim($path, '/');
    }
}

<?php

namespace App\Helpers;

class SvgHelper
{
    /**
     * Validate SVG content
     */
    public static function isValidSvg($content)
    {
        if (empty($content)) {
            return false;
        }
        
        // Check if it's valid SVG
        $isValid = preg_match('/<svg[^>]*>/', $content);
        
        // Check for malicious content
        $hasScript = preg_match('/<script|<iframe|javascript:/i', $content);
        
        return $isValid && !$hasScript;
    }
    
    /**
     * Sanitize SVG content
     */
    public static function sanitizeSvg($content)
    {
        // Remove scripts and dangerous tags
        $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $content);
        $content = preg_replace('/onload\s*=\s*["\'][^"\']*["\']/i', '', $content);
        $content = preg_replace('/onclick\s*=\s*["\'][^"\']*["\']/i', '', $content);
        
        return $content;
    }
    
    /**
     * Get SVG icon HTML
     */
    public static function getIconHtml($icon, $svg)
    {
        if ($svg && self::isValidSvg($svg)) {
            return self::sanitizeSvg($svg);
        }
        return $icon ?? '🏠';
    }
}

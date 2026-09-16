<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PropertyType extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'icon_svg', 'is_active', 'sort_order'];
    
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
    
    public function properties()
    {
        return $this->hasMany(Property::class);
    }
    
    public function getDisplayNameAttribute()
    {
        return $this->name;
    }
    
    // Get icon HTML (supports both emoji and SVG)
    public function getIconHtmlAttribute()
    {
        if ($this->icon_svg) {
            return $this->icon_svg;
        }
        return $this->icon ?? '🏠';
    }
    
    // Get icon as safe HTML
    public function getSafeIconAttribute()
    {
        if ($this->icon_svg) {
            // Clean SVG to prevent XSS
            return clean($this->icon_svg);
        }
        return $this->icon ?? '🏠';
    }
}
<?php

namespace App\Models;

use Database\Factories\SitePageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SitePage extends Model
{
    /** @use HasFactory<SitePageFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['published' => 'boolean', 'show_in_footer' => 'boolean', 'system' => 'boolean', 'position' => 'integer'];
    }
}

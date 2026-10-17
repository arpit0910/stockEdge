<?php

namespace App\Models;

use Database\Factories\SiteSectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSection extends Model
{
    /** @use HasFactory<SiteSectionFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['item_limit' => 'integer', 'position' => 'integer', 'published' => 'boolean'];
    }
}

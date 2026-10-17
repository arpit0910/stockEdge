<?php

namespace App\Models;

use Database\Factories\TaxonomyFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taxonomy extends Model
{
    /** @use HasFactory<TaxonomyFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['position' => 'integer', 'published' => 'boolean'];
    }

    public function scopeCategories(Builder $query): void
    {
        $query->where('kind', 'category');
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('published', true);
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('position')->orderBy('id');
    }
}

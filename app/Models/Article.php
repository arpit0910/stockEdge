<?php

namespace App\Models;

use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Article extends Model
{
    /** @use HasFactory<ArticleFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public static function slugFromTitle(string $title, ?int $articleId = null): string
    {
        $baseSlug = Str::limit(Str::slug($title), 190, '');
        if ($baseSlug === '') {
            throw ValidationException::withMessages(['title' => 'Use a title that can generate a URL slug.']);
        }
        $slug = $baseSlug;
        $suffix = 2;
        while (static::query()->where('slug', $slug)->when($articleId !== null, fn ($query) => $query->where('id', '!=', $articleId))->exists()) {
            $slug = $baseSlug.'-'.$suffix++;
        }

        return $slug;
    }
}

<?php

namespace App\Models;

use Database\Factories\StockFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stock extends Model
{
    /** @use HasFactory<StockFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:4',
            'day_high' => 'decimal:4',
            'day_low' => 'decimal:4',
            'change' => 'decimal:2',
            'yield' => 'decimal:2',
            'buy_price' => 'decimal:4',
            'target_price' => 'decimal:4',
            'show_on_homepage' => 'boolean',
            'top_asx_pick' => 'boolean',
            'homepage_position' => 'integer',
        ];
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}

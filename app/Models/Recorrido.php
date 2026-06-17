<?php

namespace App\Models;

use Database\Factories\RecorridoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recorrido extends Model
{
    /** @use HasFactory<RecorridoFactory> */
    use HasFactory;

    protected $table = 'recorridos';

    protected $fillable = [
        'category_id',
        'created_by',
        'nombre',
        'codigo',
        'origen',
        'destino',
        'descripcion',
        'tarifa_bs',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'tarifa_bs' => 'decimal:2',
            'activo' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function paradas(): HasMany
    {
        return $this->hasMany(Parada::class)->orderBy('orden');
    }
}

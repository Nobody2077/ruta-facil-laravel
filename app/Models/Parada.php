<?php

namespace App\Models;

use Database\Factories\ParadaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Parada extends Model
{
    /** @use HasFactory<ParadaFactory> */
    use HasFactory;

    protected $table = 'paradas';

    protected $fillable = [
        'recorrido_id',
        'orden',
        'nombre',
        'referencia',
        'latitud',
        'longitud',
    ];

    protected function casts(): array
    {
        return [
            'orden' => 'integer',
            'latitud' => 'decimal:7',
            'longitud' => 'decimal:7',
        ];
    }

    public function recorrido(): BelongsTo
    {
        return $this->belongsTo(Recorrido::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * Atributos asignables masivamente
     *
     * @var array
     */
    protected $fillable = [
        'category_id',   // ID de la categoría (opcional)
        'product_name',  // Nombre del producto
        'details',       // Descripción/detalles
        'status',       // Estado (activo/inactivo)
        'sku'           // Código único de identificación
    ];

    /**
     * Atributos que deberían ser convertidos a tipos nativos
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean' // Convierte status a booleano
    ];

    /**
     * Relación con la categoría del producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relación con los despachos del producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dispatchProducts()
    {
        return $this->hasMany(DispatchProduct::class);
    }

    /**
     * Scope para productos activos
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope para productos inactivos
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive($query)
    {
        return $query->where('status', false);
    }

    /**
     * Obtiene el stock disponible (si aplica)
     *
     * @return int
     */
    public function getStockAttribute()
    {
        // Implementar lógica de stock según necesidades
        return 0;
    }
}
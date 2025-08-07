<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DispatchProduct extends Model
{
    protected $fillable = ['dispatch_id', 'product_id', 'quantity_out', 'quantity_returned'];

    public function product()
    {
        return $this->belongsTo(\App\Product::class);
    }

    public function dispatch()
    {
        return $this->belongsTo(\App\Dispatch::class);
    }

}

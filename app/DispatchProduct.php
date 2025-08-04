<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DispatchProduct extends Model
{
    protected $table = 'dispatch_products';
    protected $fillable = ['dispatch_id', 'product_id', 'quantity_out', 'quantity_returned'];
    
    public function dispatch()
    {
        return $this->belongsTo('App\Dispatch');
    }
    
    public function product()
    {
        return $this->belongsTo('App\Product');
    }
    
    public function getMissingAttribute()
    {
        return $this->quantity_out - $this->quantity_returned;
    }
}
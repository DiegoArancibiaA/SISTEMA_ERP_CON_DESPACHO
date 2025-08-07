<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dispatch extends Model
{
    protected $fillable = ['user_id', 'dispatch_date', 'return_date', 'status'];

    public function products()
    {
        return $this->hasMany(\App\DispatchProduct::class);
    }
}

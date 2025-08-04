<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dispatch extends Model
{
    protected $table = 'dispatches';
    protected $fillable = ['user_id', 'dispatch_date', 'return_date', 'status'];
    protected $dates = ['dispatch_date', 'return_date', 'created_at', 'updated_at'];
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function products()
    {
        return $this->hasMany('App\DispatchProduct');
    }
    
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
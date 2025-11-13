<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rate extends Model
{
    

    protected $fillable = [
        'name',
        'code',
        'rate',
        'sellingrate',
        'currency',
        'effective_date',
        'expiry_date',
        'is_active',
        'description'
    ];

    protected $casts = [
        'rate' => 'decimal:4',
        'sellingrate' => 'decimal:4',
        'effective_date' => 'date',
        'expiry_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrent($query)
    {
        return $query->where('effective_date', '<=', now())
                    ->where(function($q) {
                        $q->where('expiry_date', '>=', now())
                          ->orWhereNull('expiry_date');
                    });
    }

    public function getFormattedRateAttribute()
    {
        return number_format($this->rate, 2);
    }
    
    public function getFormattedSellingRateAttribute()
{
    return number_format($this->sellingrate, 2);
}

    public function isCurrent()
    {
        return $this->effective_date <= now() && 
               ($this->expiry_date === null || $this->expiry_date >= now());
    }
}
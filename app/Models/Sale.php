<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;
        protected $fillable = [
        'productable_id',
        'productable_type',
        'quantity',
        'price',
        'payment_id'
    ];
    public function productable()
    {
        return $this->morphTo();
    }
    public function payment()
{
    return $this->belongsTo(Payment::class);
}
}

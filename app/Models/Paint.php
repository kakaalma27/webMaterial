<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paint extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'stock',
        'price',
        'status',
        'description',
        'color',
        'path'
    ];
      public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }
    public function sales()
{
    return $this->morphMany(Sale::class, 'productable');
}
}

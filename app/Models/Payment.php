<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;
        protected $fillable = ['name', 'number', 'status', 'user_id'];

        protected $casts = [
    'status' => 'boolean',
];

 public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }
}

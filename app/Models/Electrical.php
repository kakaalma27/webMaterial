<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Electrical extends Model
{
    use HasFactory;
        protected $fillable = [
        'name',
        'stock',
        'price',
        'status',
        'path'
    ];
}

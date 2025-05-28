<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plumbing extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'stock',
        'price',
        'status',
        'description',
        'path'
    ];
}

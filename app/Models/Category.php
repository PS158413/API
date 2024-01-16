<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'user_id',
        'product_id',
    ];

    public function user(): object
    {
        return $this->belongsTo(User::class)->select('id', 'name', 'email');
    }

    public function products(): object
    {
        return $this->belongsToMany(Product::class)->orderBy('id', 'desc');
    }
}

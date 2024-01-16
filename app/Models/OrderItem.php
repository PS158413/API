<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'orderitems';

    protected $fillable = [
        'order_id',
        'product_id',
        'user_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): object
    {
        return $this->belongsTo(User::class)->select('id', 'name', 'email');
    }

    public function products(): object
    {
        return $this->belongsToMany(Product::class, 'order_product', 'orderitems_id', 'product_id')->orderBy('id', 'desc');
    }
}

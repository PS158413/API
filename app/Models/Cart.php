<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';

    protected $fillable = [
        'product_id',
        'quantity',
        'price',
    ];

    //products
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    //user
     public function user()
     {
         return $this->belongsTo(User::class);
     }

    //total price
       public function getTotalPriceAttribute()
       {
           return $this->price * $this->quantity;
       }
}

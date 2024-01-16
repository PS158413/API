<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'zipcode',
        'huisnummer',
        'address',
        'city',
        'total',
    ];

    public function OrderItem()
    {
        return $this->hasMany(OrderItem::class);
    }
}

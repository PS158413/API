<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_number',
        'name',
        'description',
        'price',
        'image',
        'color',
        'height_cm',
        'width_cm',
        'depth_cm',
        'weight_gr',
        'barcode',
        'stock',
        'user_id',
        'category_id',
        'sku',
    ];

    public function user(): object
    {
        return $this->belongsTo(User::class)->select('id', 'name', 'last_name', 'email');
    }

    public function category()
    {
        return $this->belongsToMany(Category::class)->orderBy('id', 'desc');
    }

    public function orderItems()
    {
        return $this->belongsToMany(OrderItem::class, 'order_product', 'orderitems_id', 'product_id')->orderBy('id', 'desc');
    }

    public function receiptProduct(): object
    {
        return $this->belongsTo(ReceiptProduct::class)->select('id', 'name', 'email');
    }
}

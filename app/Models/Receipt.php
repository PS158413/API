<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'article_number',
        'name',
        'price',
        'customer_id',
        'user_id',
    ];

    public function receipt_products(): object
    {
        return $this->hasMany(ReceiptProduct::class);
    }

    public function user(): object
    {
        return $this->belongsTo(User::class)->select('id', 'name', 'email');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptProduct extends Model
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
    ];

    public function user(): object
    {
        return $this->belongsTo(User::class)->select('id', 'name', 'email');
    }

    public function receipt(): object
    {
        return $this->belongsTo(Receipt::class)->select('id', 'name', 'email');
    }
}

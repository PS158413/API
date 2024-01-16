<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    use HasFactory;

    protected $table = 'availability';

    protected $fillable = [
        'absence',
        'start_time',
        'finish_time',
        'user_id',

    ];

    public function user(): object
    {
        return $this->belongsTo(User::class)->select('id', 'name', 'last_name', 'email');
    }
}

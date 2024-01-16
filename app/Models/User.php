<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'city',
        'phone',
        'birthday',
        'cashier_number',
        'role_id',
        'customernumber',
        'password',
        'password_confirmation',
    ];



    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): string
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * Products
     *
     * Get All products uploaded by user
     *
     * @return object Eloquent product object
     */
    public function products()
    {
        return $this->hasMany(Product::class)->orderBy('id', 'desc');
    }

    public function availability()
    {
        return $this->hasMany(Availability::class)->orderBy('id', 'desc');
    }

    public function categorys()
    {
        return $this->hasMany(Category::class)->orderBy('id', 'desc');
    }

    public function OrderItem()
    {
        return $this->hasMany(OrderItem::class)->orderBy('id', 'desc');
    }

    public function roles()
    {
        return $this->hasMany(Role::class)->orderBy('id', 'desc');
    }

    public function role()
    {
        return $this->belongsToMany(Role::class)->orderBy('id', 'desc');
    }

    public function user(): object
    {
        return $this->belongsTo(User::class)->select('id', 'name', 'email');
    }

    public function ReceiptProduct()
    {
        return $this->hasMany(ReceiptProduct::class)->orderBy('id', 'desc');
    }
}

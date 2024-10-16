<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Database\Factories\ProductsFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Orders extends Model
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'customer_id',
        'order_no',
        'order_date',
        'status_id',
        'shipment_address',
    ];


    public function customer()
    {
        return $this->hasOne(Customers::class, 'id', 'customer_id');
    }

    public function status()
    {
        return $this->hasOne(OrderStatuses::class, 'id', 'status_id');
    }

    public function products()
    {
        return $this->hasMany(OrderProducts::class, 'order_id', 'id');
    }

    /**
     * The roles that belong to the Orders
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orderProducts(): BelongsToMany
    {
        return $this->belongsToMany(Products::class, 'order_products', 'order_id', 'product_id');
    }


    protected static function newFactory(): Factory
    {
        return OrderFactory::new();
    }
}

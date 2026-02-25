<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'offer_id',
        'quantity'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function productOffer()
    {
        return $this->belongsTo(ProductOffer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}

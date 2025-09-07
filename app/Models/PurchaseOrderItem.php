<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderItem extends Model
{
    use HasFactory;
protected $table = 'purchase_order_items'; // only if not default

   protected $fillable = [
    'purchase_order_id',
    'material_id',
    'item_name',
    'unit_price',
    'quantity',
    'gst_rate',
    'total_price',
    'available_qty',
    'remaining_qty',
];

// app/Models/PurchaseOrderItem.php
public function material()
{
    return $this->belongsTo(Material::class, 'material_id');
}


public function purchaseOrder()
{
    return $this->belongsTo(\App\Models\PurchaseOrder::class, 'purchase_order_id');
}


}

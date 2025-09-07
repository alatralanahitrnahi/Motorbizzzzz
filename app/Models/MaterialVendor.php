<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialVendor extends Model
{
    protected $table = 'material_vendor'; // important since it's not plural

    protected $fillable = [
        'vendor_id',
        'material_id',
        'unit_price',
        'quantity',
        'material_name',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}

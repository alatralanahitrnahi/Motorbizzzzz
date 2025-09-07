<?php

// app/Models/Invoice.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'invoice_type',
        'invoiceable_type',
        'invoiceable_id',
        'vendor_id',
        'invoice_date',
        'due_date',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'status',
        'file_path',
        'generated_by'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2'
    ];

    // Relationships
    public function invoiceable()
    {
        return $this->morphTo();
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
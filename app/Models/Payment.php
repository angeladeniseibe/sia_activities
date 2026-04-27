<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\ElectricBill;

class Payment extends Model
{
    protected $fillable = [
        'customer_id',
        'bill_id',
        'amount_paid',
        'date_paid',
    ];

    // 🔗 Payment belongs to a customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // 🔗 Payment belongs to a bill
    public function bill()
    {
        return $this->belongsTo(ElectricBill::class, 'bill_id');
    }
}

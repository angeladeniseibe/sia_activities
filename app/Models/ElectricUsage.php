<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ElectricBill;

class ElectricUsage extends Model
{
    protected $fillable = [
    'customer_id',
    'kilowatts_used',
    'rate_per_kwh',
    'month',
    'year',
];

public function customer()
{
    return $this->belongsTo(Customer::class);
}


    // 👤 Owner
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 💰 Bill
    public function bill()
    {
        return $this->hasOne(ElectricBill::class, 'usage_id');
    }
 
}

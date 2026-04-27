<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ElectricUsage;
use App\Models\Payment;
use App\Models\User;

class ElectricBill extends Model
{
    protected $fillable = [
        'user_id',     // 🔥 ADD THIS (IMPORTANT)
        'usage_id',
        'bill_amount',
        'due_date',
    ];

    /*
    |-----------------------------------
    | RELATIONSHIPS
    |-----------------------------------
    */

    // Bill belongs to User (DIRECT ACCESS - IMPORTANT)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Bill belongs to Usage
    public function usage()
    {
        return $this->belongsTo(ElectricUsage::class, 'usage_id');
    }

    // Bill has many payments
    public function payments()
    {
        return $this->hasMany(Payment::class, 'bill_id');
    }

    /*
    |-----------------------------------
    | ACCESSORS
    |-----------------------------------
    */

    // TOTAL PAYMENTS
    public function getTotalPaidAttribute()
    {
        return $this->payments->sum('amount_paid');
    }

    // AUTO STATUS
    public function getStatusAttribute()
    {
        $paid = $this->total_paid;

        if ($paid <= 0) {
            return 'Unpaid';
        } elseif ($paid < $this->bill_amount) {
            return 'Partial';
        } else {
            return 'Paid';
        }
    }

    // REMAINING BALANCE
    public function getBalanceAttribute()
    {
        return $this->bill_amount - $this->total_paid;
    }
}

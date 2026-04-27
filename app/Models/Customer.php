<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'address',
        'gender',
        'dob',
        'user_id',
    ];

    // RELATIONSHIPS
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function usages()
    {
        return $this->hasMany(ElectricUsage::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}

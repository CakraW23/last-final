<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $guarded = [];

    public function user() {
        return $this->hasOne(User::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }
}

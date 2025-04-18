<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function wallet() {
        return $this->belongsTo(Wallet::class);
    }

    public function receiver() {
        return $this->belongsTo(User::class,'receiver_id');
    }
}

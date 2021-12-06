<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function from_user()
    {
        return $this->belongsTo(User::class, 'from');
    }

    public function to_user()
    {
        return $this->belongsTo(User::class, 'to');
    }
}

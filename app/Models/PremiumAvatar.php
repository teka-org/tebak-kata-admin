<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class Transaction extends Model
{
    protected $connection = 'mongodb';
    protected $table = "premiumAvatar";
    protected $primarykey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = ['image', 'avatar_name', 'price'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

}
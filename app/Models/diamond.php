<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;



class Diamond extends Model 
{
    // protected $connection = 'mysql';
    protected $connection = 'mongodb';
    protected $table = "diamond";
    protected $primarykey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;
    
    protected $fillable = ['image', 'quantity', 'price'];
}


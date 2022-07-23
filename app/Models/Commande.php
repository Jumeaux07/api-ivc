<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;
    protected $fillable = [
        'numero',
        'model',
        'description',
        'mesure',
        'delais',
        'total',
        'reste',
        'user_id',
        'client_id',
    ];
}

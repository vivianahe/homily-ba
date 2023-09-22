<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homilie extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'citation',
        'title',
        'reading',
        'gospel',
        'img',
        'audio',
        'user_id'
    ];

}

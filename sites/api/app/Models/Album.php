<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'name',
        'slug',
        'artist_name',
        'artist_id',
        'released'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrawlerPagination extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'to_crawl'
    ];
}

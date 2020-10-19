<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Posts extends Model
{
    protected $fillable = [
        'title', 'main_content', 'price', 'image_path'
    ];
}

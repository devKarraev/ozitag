<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlatPost extends Model
{
    protected $fillable = [
        'title', 'main_content', 'price', 'image_path'
    ];
}

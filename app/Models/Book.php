<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'author',
        'publisher',
        'isbn',
        'year',
        'image',
        'stock',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

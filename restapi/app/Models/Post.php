<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'content', 'image', 'category_id', 'user_id',
    ];

    /**
     * The Post that belong to Category.
     */
    public function CategoriesBelongsTo(){
        return $this->belongsTo(Category::class, 'id');
    }
}

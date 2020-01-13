<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\BlogCategories\Entities\BlogCategories;

class Blog extends Model
{
    protected $table = 'blog';

    protected $fillable = ['title', 'description', 'category_id', 'user_id', 'featured_image', 'is_featured'];

    /**
     * Get blog categories.
     */
    public function blogCategories()
    {
        return $this->belongsToMany(BlogCategories::class, 'cat_blog', 'blog_id', 'cat_id');
    }
}

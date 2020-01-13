<?php

namespace Modules\BlogCategories\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Blog\Entities\Blog;

class BlogCategories extends Model
{
    protected $fillable = [ 'name', 'description', 'user_id' ];
    protected $table = 'blog_categories';

    /**
     * Get blog of this categories.
     */
    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'cat_blog', 'cat_id', 'blog_id');
    }
}

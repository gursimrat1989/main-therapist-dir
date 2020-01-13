<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $appends = array('slug');

    public function getSlugAttribute()
    {
        return $this->slugify( $this->name );  
    }

    /**
     * Create Slug.
     *
     * @var array
     */
    public static function slugify($text)
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        $text = preg_replace('~[^-\w]+~', '', $text);

        $text = trim($text, '-');

        $text = preg_replace('~-+~', '-', $text);

        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * App\Role relation.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * App\Profile relation.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    /**
     * If user is Admin
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function isAdmin()
    {
       return $this->roles()->where('name', 'admin')->exists();
    }
}
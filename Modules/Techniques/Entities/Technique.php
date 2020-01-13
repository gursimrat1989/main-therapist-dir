<?php

namespace Modules\Techniques\Entities;

use Illuminate\Database\Eloquent\Model;

class Technique extends Model
{
    protected $fillable = [ 'name', 'description', 'user_id' ];
    protected $table = 'techniques';
}

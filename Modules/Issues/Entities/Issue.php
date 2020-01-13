<?php

namespace Modules\Issues\Entities;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $fillable = ['name', 'description', 'user_id'];
    protected $table = 'issues';
}

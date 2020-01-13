<?php

namespace Modules\Techniques\Entities;

use Illuminate\Database\Eloquent\Model;

class TechniqueDescription extends Model
{
    protected $fillable = ['user_id', 'technique_id', 'technique_description'];
    protected $table = 'technique_users';
}

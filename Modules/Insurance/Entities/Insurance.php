<?php

namespace Modules\Insurance\Entities;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    protected $table = 'insurance';
    protected $fillable = ['name', 'description', 'user_id'];
}

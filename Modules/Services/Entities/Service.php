<?php

namespace Modules\Services\Entities;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'description', 'user_id'];
    protected $table = 'services';
}

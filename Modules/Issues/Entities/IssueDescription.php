<?php

namespace Modules\Issues\Entities;

use Illuminate\Database\Eloquent\Model;

class IssueDescription extends Model
{
    protected $fillable = ['user_id','issue_id','issue_description'];
    protected $table = 'issue_users';
}

<?php

namespace Modules\Newsletter\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Newsletter\Entities\Subscribers;

class SubscribersList extends Model
{
    protected $fillable = ['list_name', 'status'];
    protected $table = 'subscribers_list';
    public $timestamps = true;

    /**
     * Get blog of this categories.
     */
    public function subscribers()
    {
        return $this->belongsToMany(Subscribers::class, 'slists_subs', 'slists_id', 'subs_id');
    }
}

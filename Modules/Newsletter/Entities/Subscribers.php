<?php

namespace Modules\Newsletter\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Newsletter\Entities\SubscribersList;

class Subscribers extends Model
{
    protected $fillable = ['email'];
    protected $table = 'subscribers';
    public $timestamps = true;

    /**
     * Get blog categories.
     */
    public function subscribersList()
    {
        return $this->belongsToMany(SubscribersList::class, 'slists_subs', 'subs_id', 'slists_id');
    }
}

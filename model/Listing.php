<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Listing extends Model
{
    use SoftDeletes;

    public $timestamps = true;

    public function getCarMeta()
    {
        return $this->hasOne('App\CarListingMeta', 'listing_id');
    }


    public function getContact()
    {
        return $this->hasOne('App\ListingContact', 'listing_id');
    }

    public function getImage()
    {
        return $this->hasMany('App\ListingImage', 'listing_id');
    }
}

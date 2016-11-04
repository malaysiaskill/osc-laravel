<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Projek extends Model
{
    protected $table = 'projek';

    public function devteam()
    {
    	return $this->belongsTo('App\DevTeam', 'devteam_id', 'id');
    }
}

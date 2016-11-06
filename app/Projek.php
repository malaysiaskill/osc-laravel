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

    public function tasks()
    {
    	return $this->hasMany('App\ProjekTask', 'projek_id', 'id');
    }
}

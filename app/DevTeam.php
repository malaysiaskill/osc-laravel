<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DevTeam extends Model
{
    protected $table = 'devteam';

    public function ketua()
    {
    	return $this->belongsTo('App\User', 'ketua_kumpulan', 'id');
    }

    public function projek()
    {
    	return $this->hasMany('App\Projek', 'devteam_id', 'id');
    }

	public function getJumlahAhliAttribute()
    {
    	return count(explode(',', $this->senarai_jtk));
    }
}

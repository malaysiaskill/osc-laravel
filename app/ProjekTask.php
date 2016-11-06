<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjekTask extends Model
{
    protected $table = 'projek_task';

    public function jtk()
    {
    	return $this->belongsTo('App\User', 'assigned', 'id');
    }

    public function getCreatedAtAttribute($date)
	{
	    return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y h:i A');
	}

	public function getUpdatedAtAttribute($date)
	{
	    return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y h:i A');
	}
}

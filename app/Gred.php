<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gred extends Model
{
    protected $table = 'gred';

    public function getGredTitleAttribute()
    {
    	return $this->gred . " - " . $this->nama_jawatan;
    }
}

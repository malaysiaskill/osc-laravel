<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SenaraiSemakHarian extends Model
{
    protected $table = 'semakan_harian';

    public function getTarikhSemakanFormattedAttribute()
	{
	    $date = $this->tarikh_semakan;
	    if (strlen($date) != 0) {
	    	return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
	    } else {
	    	return '-';
		}
	}
}

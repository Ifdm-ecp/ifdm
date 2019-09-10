<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class drilling extends Model
{
	protected $table = 'drilling';

	public function generalData()
	{
		return $this->hasMany('App\d_general_data');
	}

	public function escenario()
	{
		return $this->belongsTo('App\escenario', 'scenario_id');
	}

}

?>
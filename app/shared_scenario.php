<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class shared_scenario extends Model
{
	protected $table = 'shared_scenario';

	public function user()
	{
	    return $this->belongsTo('App\User');
	}
}



?>
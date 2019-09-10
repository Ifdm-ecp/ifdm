<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sessions extends Model
{

	 /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sessions';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'last_activity', 'first_activity'];
}

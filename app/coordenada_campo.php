<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class coordenada_campo extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    public $timestamps = false;
    use Authenticatable, Authorizable, CanResetPassword;

    public function campo()
    {
        return $this->belongsTo('App\campo');
    }
    public function formacion()
    {
        return $this->belongsTo('App\formacion');
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'coordenada_campos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['lat', 'lon', 'orden', 'campo_id','formacion_id','id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
}

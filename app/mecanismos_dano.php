<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class mecanismos_dano extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    public $timestamps = false;
    use Authenticatable, Authorizable, CanResetPassword;

    public function VariableDano()
    {
        return $this->hasMany('App\variable_dano');
    }
    public function Subparametros()
    {
        return $this->hasMany('App\subparametro');
    }
    public function Configuracion_dano()
    {
        return $this->hasMany('App\configuracion_dano');
    }
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'Mecanismos_dano';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'nombre'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
 }

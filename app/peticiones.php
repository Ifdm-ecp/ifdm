<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class peticiones extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    public $timestamps = false;
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public function pozos()
    {
        return $this->belongsTo('App\pozo', 'pozo_id');
    }

    public function mecdans()
    {
        return $this->belongsTo('App\mecanismos_dano', 'mecdan_id');
    }

    public function interv()
    {
        return $this->belongsTo('App\formacionxpozo', 'intervalo_id');
    }

    public function damag_1()
    {
        return $this->belongsTo('App\subparametro', 'subparametro_id');
    }

    public function damag_2()
    {
        return $this->belongsTo('App\variable_dano', 'subparametro_id', 'nombre');
    }

    public function damag_conf()
    {
        return $this->belongsTo('App\configuracion_dano', 'configuracion_daño');
    }

        public function usuario()
    {
        return $this->belongsTo('App\User', 'usuario_id');
    }

    protected $table = 'peticiones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'pozo_id', 'intervalo_id', 'mecdan_id', 'subparametro_id', 'variable', 'valor', 'fecha_monitoreo', 'fecha_creacion', 'comentario', 'usuario_id', 'tipo_peticion', 'configuracion_daño'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
}

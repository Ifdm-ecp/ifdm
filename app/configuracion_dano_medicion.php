<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class configuracion_dano_medicion extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    public $timestamps = false;
    use Authenticatable, Authorizable, CanResetPassword;

    public function pozo()
    {
        return $this->belongsTo('App\pozo');
    }

    public function ConfiguracionDano()
    {
        return $this->belongsTo('App\configuracion_dano');
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cd_mediciones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'pozo_id', 'cd_id', 'valor','fecha','comentario'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
}

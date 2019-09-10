<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class fluidoxpozos extends Model implements AuthenticatableContract,
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
    protected $table = 'fluidoxpozos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'tipo', 'api', 'lgr', 'gwr' ,'wor', 'saturation_pressure', 'gor', 'cgr', 'specific_gas', 'water_viscosity', 'oil_viscosity', 'gas_viscosity', 'fvfw', 'fvfo', 'fvfg', 'pozo_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
}

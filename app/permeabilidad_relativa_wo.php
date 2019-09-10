<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class permeabilidad_relativa_wo extends Model implements AuthenticatableContract,
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
    protected $table = 'permeabilidad_relativa_wo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sw', 'krw', 'kro', 'pcwo', 'formacion_id', 'id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
}

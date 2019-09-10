<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Traits\PvtTraits;

class pvt extends Model implements AuthenticatableContract,
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
    protected $table = 'pvt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'campo_id', 'uo', 'ug', 'uw', 'bo', 'bg', 'bw', 'rs', 'rv'];



    static function store($request)
    {
        $store = new Pvt;
        $pvt = new PvtTraits();        
        $pvt->saveAndUpdate($request, $store);
    } 

    static function edit($data)
    {
        $pvt = new PvtTraits();    
        return $pvt->editPvt($data);
        
    }


    static function actualizarPvtGlobal($request, $interval)
    { 
        if($interval->pvt != null) {
            $update = $interval->pvt;      
        } else {
            $update = new pvt;
            $request->formacion_id = $interval->id;
        }

        

        $pvt = new PvtTraits();  
        $pvt->saveAndUpdate($request, $update);
    }
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
}

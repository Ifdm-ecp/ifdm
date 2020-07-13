<?php

namespace App\Models\MultiparametricAnalysis;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\medicion;
use App\pozo;

class Statistical extends Model
{
    protected $table = 'multiparametric_analysis_statistical';
    public $timestamps = false;

    protected $fillable = ['statistical', 'basin_statistical', 'field_statistical', 'ms1', 'ms2', 'ms3', 'ms4', 'ms5', 'fb1', 'fb2', 'fb3', 'fb4', 'fb5', 'os1', 'os2', 'os3', 'os4', 'os5', 'rp1', 'rp2', 'rp3', 'rp4', 'id1', 'id2', 'id3', 'id4', 'gd1', 'gd2', 'gd3', 'gd4', 'kd', 'date_ms1', 'date_ms2', 'date_ms3', 'date_ms4', 'date_ms5', 'comment_ms1', 'comment_ms2', 'comment_ms3', 'comment_ms4', 'comment_ms5', 'date_fb1', 'date_fb2', 'date_fb3', 'date_fb4', 'date_fb5', 'comment_fb1', 'comment_fb2', 'comment_fb3', 'comment_fb4', 'comment_fb5', 'date_os1', 'date_os2', 'date_os3', 'date_os4', 'comment_os1', 'comment_os2', 'comment_os3', 'comment_os4', 'date_rp1', 'date_rp2', 'date_rp3', 'date_rp4', 'comment_rp1', 'comment_rp2', 'comment_rp3', 'comment_rp4', 'date_id1', 'date_id2', 'date_id3', 'date_id4', 'comment_id1', 'comment_id2', 'comment_id3', 'comment_id4', 'date_gd1', 'date_gd2', 'date_gd3', 'date_gd4', 'comment_gd1', 'comment_gd2', 'comment_gd3', 'comment_gd4','p10_ms1', 'p10_ms2', 'p10_ms3', 'p10_ms4', 'p10_ms5', 'p10_fb1', 'p10_fb2', 'p10_fb3', 'p10_fb4', 'p10_fb5', 'p10_os1', 'p10_os2', 'p10_os3', 'p10_os4', 'p10_rp1', 'p10_rp2', 'p10_rp3', 'p10_rp4', 'p10_id1', 'p10_id2', 'p10_id3', 'p10_id4', 'p10_gd1', 'p10_gd2', 'p10_gd3', 'p10_gd4','p90_ms1', 'p90_ms2', 'p90_ms3', 'p90_ms4', 'p90_ms5', 'p90_fb1', 'p90_fb2', 'p90_fb3', 'p90_fb4', 'p90_fb5', 'p90_os1', 'p90_os2', 'p90_os3', 'p90_os4', 'p90_rp1', 'p90_rp2', 'p90_rp3', 'p90_rp4', 'p90_id1', 'p90_id2', 'p90_id3', 'p90_id4', 'p90_gd1', 'p90_gd2', 'p90_gd3', 'p90_gd4', 'available', 'escenario_id','status_wr'
    ];

    public function escenario()
    {
    	return $this->belongsTo('App\escenario');
    }


    static function subparametro($sub, $statistical){

        if(!strcmp($statistical->statistical, "Colombia")){
            //dd(DB::table('mediciones')->join('Pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', $sub)->orderBy('fecha', 'desc')->first());
            return DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', $sub)->orderBy('fecha', 'desc')->first();
            //return medicion::with('pozo')->where('subparametro_id', $sub)->orderBy('fecha', 'desc')->first();
        }else{
            //dd(DB::table('mediciones')->join('Pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$statistical->campos))->where('subparametro_id', 1)->orderBy('fecha', 'desc')->first());
            return DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$statistical->campos))->where('subparametro_id', $sub)->orderBy('fecha', 'desc')->first();     
        }
    } 


    public function subparameters()
    {
        return $this->hasOne('App\subparameters_weight', 'multiparametric_id');
    }

    static function updatecampos($request, $id){
        //se modifica el array del campo field_statistical con implode
        if($request->field_statistical)
        {
            $input['field_statistical'] = implode(",",$request->field_statistical);
            $request->statistical = null;
        }else{
            $input['field_statistical'] = null;
            $request->basin_statistical = null;
        }
        //se guardan solo los campos field_statistical y statistical en la bbdd;
        $statistical = Statistical::find($id);
        $statistical->escenario_id = $request->id_scenary;
        $statistical->field_statistical = $input['field_statistical'];
        $statistical->basin_statistical = $request->basin_statistical;
        $statistical->statistical = $request->statistical;
        $statistical->status_wr = $request->status_wr;
        $statistical->save();

        return $statistical;
    }

    static function updateTodos($request, $id){
        //se conviertelos arrays en cadenas
        if($request->msAvailable)
        {
            $input['msAvailable'] = implode(",",$request->msAvailable);
        }else{
            $input['msAvailable'] = null;
        }

        if($request->fbAvailable)
        {
            $input['fbAvailable'] = implode(",",$request->fbAvailable);
        }else{
            $input['fbAvailable'] = null;
        }

        if($request->osAvailable)
        {
            $input['osAvailable'] = implode(",",$request->osAvailable);
        }else{
            $input['osAvailable'] = null;
        }

        if($request->rpAvailable)
        {
            $input['rpAvailable'] = implode(",",$request->rpAvailable);
        }else{
            $input['rpAvailable'] = null;
        }

        if($request->idAvailable)
        {
            $input['idAvailable'] = implode(",",$request->idAvailable);
        }else{
            $input['idAvailable'] = null;
        }

        if($request->gdAvailable)
        {
            $input['gdAvailable'] = implode(",",$request->gdAvailable);
        }else{
            $input['gdAvailable'] = null;
        }


        //se ingresa los datos de la tabla statistical
        $statistical = Statistical::find($id);
        $statistical->status_wr = $request->button_wr;
        $statistical->ms1 = $request->ms1;
        $statistical->ms2 = $request->ms2;
        $statistical->ms3 = $request->ms3;
        $statistical->ms4 = $request->ms4;
        $statistical->ms5 = $request->ms5;
        $statistical->fb1 = $request->fb1;
        $statistical->fb2 = $request->fb2;
        $statistical->fb3 = $request->fb3;
        $statistical->fb4 = $request->fb4;
        $statistical->fb5 = $request->fb5;
        $statistical->os1 = $request->os1;
        $statistical->os2 = $request->os2;
        $statistical->os3 = $request->os3;
        $statistical->os4 = $request->os4;
        $statistical->rp1 = $request->rp1;
        $statistical->rp2 = $request->rp2;
        $statistical->rp3 = $request->rp3;
        $statistical->rp4 = $request->rp4;
        $statistical->id1 = $request->id1;
        $statistical->id2 = $request->id2;
        $statistical->id3 = $request->id3;
        $statistical->id4 = $request->id4;
        $statistical->gd1 = $request->gd1;
        $statistical->gd2 = $request->gd2;
        $statistical->gd3 = $request->gd3;
        $statistical->gd4 = $request->gd4;
        $statistical->kd = $request->kd;
        $statistical->date_ms1 = $request->date_ms1;
        $statistical->date_ms2 = $request->date_ms2;
        $statistical->date_ms3 = $request->date_ms3;
        $statistical->date_ms4 = $request->date_ms4;
        $statistical->date_ms5 = $request->date_ms5;
        $statistical->comment_ms1 = $request->comment_ms1;
        $statistical->comment_ms2 = $request->comment_ms2;
        $statistical->comment_ms3 = $request->comment_ms3;
        $statistical->comment_ms4 = $request->comment_ms4;
        $statistical->comment_ms5 = $request->comment_ms5;
        $statistical->date_fb1 = $request->date_fb1;
        $statistical->date_fb2 = $request->date_fb2;
        $statistical->date_fb3 = $request->date_fb3;
        $statistical->date_fb4 = $request->date_fb4;
        $statistical->date_fb5 = $request->date_fb5;
        $statistical->comment_fb1 = $request->comment_fb1;
        $statistical->comment_fb2 = $request->comment_fb2;
        $statistical->comment_fb3 = $request->comment_fb3;
        $statistical->comment_fb4 = $request->comment_fb4;
        $statistical->comment_fb5 = $request->comment_fb5;
        $statistical->date_os1 = $request->date_os1;
        $statistical->date_os2 = $request->date_os2;
        $statistical->date_os3 = $request->date_os3;
        $statistical->date_os4 = $request->date_os4;
        $statistical->comment_os1 = $request->comment_os1;
        $statistical->comment_os2 = $request->comment_os2;
        $statistical->comment_os3 = $request->comment_os3;
        $statistical->comment_os4 = $request->comment_os4;
        $statistical->date_rp1 = $request->date_rp1;
        $statistical->date_rp2 = $request->date_rp2;
        $statistical->date_rp3 = $request->date_rp3;
        $statistical->date_rp4 = $request->date_rp4;
        $statistical->comment_rp1 = $request->comment_rp1;
        $statistical->comment_rp2 = $request->comment_rp2;
        $statistical->comment_rp3 = $request->comment_rp3;
        $statistical->comment_rp4 = $request->comment_rp4;
        $statistical->date_id1 = $request->date_id1;
        $statistical->date_id2 = $request->date_id2;
        $statistical->date_id3 = $request->date_id3;
        $statistical->date_id4 = $request->date_id4;
        $statistical->comment_id1 = $request->comment_id1;
        $statistical->comment_id2 = $request->comment_id2;
        $statistical->comment_id3 = $request->comment_id3;
        $statistical->comment_id4 = $request->comment_id4;
        $statistical->date_gd1 = $request->date_gd1;
        $statistical->date_gd2 = $request->date_gd2;
        $statistical->date_gd3 = $request->date_gd3;
        $statistical->date_gd4 = $request->date_gd4;
        $statistical->comment_gd1 = $request->comment_gd1;
        $statistical->comment_gd2 = $request->comment_gd2;
        $statistical->comment_gd3 = $request->comment_gd3;
        $statistical->comment_gd4 = $request->comment_gd4;
        $statistical->p10_ms1 = $request->p10_ms1;
        $statistical->p10_ms2 = $request->p10_ms2;
        $statistical->p10_ms3 = $request->p10_ms3;
        $statistical->p10_ms4 = $request->p10_ms4;
        $statistical->p10_ms5 = $request->p10_ms5;
        $statistical->p10_fb1 = $request->p10_fb1;
        $statistical->p10_fb2 = $request->p10_fb2;
        $statistical->p10_fb3 = $request->p10_fb3;
        $statistical->p10_fb4 = $request->p10_fb4;
        $statistical->p10_fb5 = $request->p10_fb5;
        $statistical->p10_os1 = $request->p10_os1;
        $statistical->p10_os2 = $request->p10_os2;
        $statistical->p10_os3 = $request->p10_os3;
        $statistical->p10_os4 = $request->p10_os4;
        $statistical->p10_rp1 = $request->p10_rp1;
        $statistical->p10_rp2 = $request->p10_rp2;
        $statistical->p10_rp3 = $request->p10_rp3;
        $statistical->p10_rp4 = $request->p10_rp4;
        $statistical->p10_id1 = $request->p10_id1;
        $statistical->p10_id2 = $request->p10_id2;
        $statistical->p10_id3 = $request->p10_id3;
        $statistical->p10_id4 = $request->p10_id4;
        $statistical->p10_gd1 = $request->p10_gd1;
        $statistical->p10_gd2 = $request->p10_gd2;
        $statistical->p10_gd3 = $request->p10_gd3;
        $statistical->p10_gd4 = $request->p10_gd4;
        $statistical->p90_ms1 = $request->p90_ms1;
        $statistical->p90_ms2 = $request->p90_ms2;
        $statistical->p90_ms3 = $request->p90_ms3;
        $statistical->p90_ms4 = $request->p90_ms4;
        $statistical->p90_ms5 = $request->p90_ms5;
        $statistical->p90_fb1 = $request->p90_fb1;
        $statistical->p90_fb2 = $request->p90_fb2;
        $statistical->p90_fb3 = $request->p90_fb3;
        $statistical->p90_fb4 = $request->p90_fb4;
        $statistical->p90_fb5 = $request->p90_fb5;
        $statistical->p90_os1 = $request->p90_os1;
        $statistical->p90_os2 = $request->p90_os2;
        $statistical->p90_os3 = $request->p90_os3;
        $statistical->p90_os4 = $request->p90_os4;
        $statistical->p90_rp1 = $request->p90_rp1;
        $statistical->p90_rp2 = $request->p90_rp2;
        $statistical->p90_rp3 = $request->p90_rp3;
        $statistical->p90_rp4 = $request->p90_rp4;
        $statistical->p90_id1 = $request->p90_id1;
        $statistical->p90_id2 = $request->p90_id2;
        $statistical->p90_id3 = $request->p90_id3;
        $statistical->p90_id4 = $request->p90_id4;
        $statistical->p90_gd1 = $request->p90_gd1;
        $statistical->p90_gd2 = $request->p90_gd2;
        $statistical->p90_gd3 = $request->p90_gd3;
        $statistical->p90_gd4 = $request->p90_gd4;
        $statistical->msAvailable = $input['msAvailable'];
        $statistical->fbAvailable = $input['fbAvailable'];
        $statistical->osAvailable = $input['osAvailable'];
        $statistical->rpAvailable = $input['rpAvailable'];
        $statistical->idAvailable = $input['idAvailable'];
        $statistical->gdAvailable = $input['gdAvailable'];
        $statistical->status_wr = $request->button_wr;

        $statistical->escenario_id = $request->id_scenary;

        $statistical->save();

        return $statistical;
    }
}

<?php 
namespace App\Http\Controllers;

use DB;
use App\Http\Controllers\Controller;


	class DatosController extends Controller
	{
		/* Devuelve la vista geor con los datos de las cuencas para popular el select */
		public function Cuencas()
		{
			$cuencas = DB::table('Campos')->get();

			return view('Geor',['cuencas2' => $cuencas]);
		}
		/* Devuelve la vista geor con los datos de los campos para popular el select */
		public function Campos($id)
		{
			$campos = DB::table('Campos')->where('Cuenca_id', $id)->get();
			return view('Geor',['Campos' => $campos]);
		}
	}

 ?>

<?php
namespace App\Http\Controllers;
 
use  App\Http\Controllers\Gmaps;

class MapController extends Controller 
{
    public function __construct() 
    {
    }

    #Devuelve la vista inicial georreference. 
    public function maps() 
    {
        return view('Geor');
    }
 
}

?>
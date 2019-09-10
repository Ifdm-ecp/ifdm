<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TagManual extends Model
{
    protected $fillable = [ 'id', 'tittle'];

    static function borrar($id)
    {
    	$destroy = TagManual::find($id);
    	if($destroy->manual->count() > 0)
        {
            Manual::borrar($destroy->id);
        }
        $destroy->delete();
    }

    public function manual()
    {
    	return $this->hasMany('App\Manual', 'tag_manuals_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manual extends Model
{
    protected $fillable = [ 'id', 'tittle', 'body', 'orde', 'tag_manuals_id'];

    static function borrar($tagManualId)
    {
    	Manual::where('tag_manuals_id', $tagManualId)->delete();
    }

    public function tagManual()
    {
    	return $this->belongsTo('App\TagManual', 'tag_manuals_id');
    }
}

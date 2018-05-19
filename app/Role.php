<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function users()
	{
	    return $this
	        ->belongsToMany('App\User')
	        ->withTimestamps();
	}

	public function user2()
        {
            return $this->belongsToMany('App\User','role_user')
             ->withPivot('user_id');;
        }
}

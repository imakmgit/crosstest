<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class News extends Authenticatable
{
	public function newsCreator()
	{
		return $this->belongsTo('App\Models\User', 'created_by', 'id');
	}
}

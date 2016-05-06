<?php

namespace App\Http\Controllers;
use Session;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

	protected $data = array();

    public function  __construct() {
		$this->data = array();
		$controller = explode('\\', get_called_class());
		$controller = str_replace('Controller', '', end($controller));
		$this->data['controller'] = strtolower($controller);
		if(Session::has('logged_in_user')) {
			$this->data['user'] = Session::get('user');
		}
	}
}

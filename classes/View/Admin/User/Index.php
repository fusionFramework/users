<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_User_Index extends View_Admin {
	public $header = 'Users';
	public $icon = 'fa fa-user';

	public $typeAhead = '';

	public function typeAhead()
	{
		return json_encode($this->typeAhead, JSON_PRETTY_PRINT);
	}
}

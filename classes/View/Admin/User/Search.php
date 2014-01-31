<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_User_Search extends View_Admin {
	public $header = 'Users';
	public $icon = 'fa fa-user';

	public $users = [];

	public function users()
	{
		$out = [];

		foreach($this->users as $user)
		{
			$out[] = [
				'id' => $user->id,
				'username' => $user->username,
				'email' => $user->email
			];
		}
	}
}
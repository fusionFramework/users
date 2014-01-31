<?php defined('SYSPATH') OR die('No direct script access.');

abstract class View_Admin_User_Tab {
	/**
	 * @var Model_User user to edit.
	 */
	public $user;

	public function csrf()
	{
		return Security::token();
	}

	public $button = false;

	public function has_button()
	{
		return $this->button != false;
	}
}

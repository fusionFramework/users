<?php defined('SYSPATH') OR die('No direct script access.');

abstract class Sessions extends Kohana_Session
{
	public function regenerate()
	{
		Plug::listen('user.session', [$this->id(), $this->_data]);
		return parent::regenerate();
	}
}
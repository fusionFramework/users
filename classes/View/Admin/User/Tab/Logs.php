<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_User_Tab_Logs extends View_Admin_User_Tab {

	/**
	 * @var array Different types of logs
	 */
	public $types = [];

	/**
	 * @var array Collection of routes
	 */
	public $routes = [];

	/**
	 * @var string Generated table HTML
	 */
	public $table = '';

	public function types() {
		$list = [];

		foreach ($this->types as $k => $v)
		{
			if (is_int($k))
			{
				$list[]['path'] = $v;
			}
			else
			{
				$list = $this->_flatten($v, $k, $list);
			}
		}

		sort($list);

		return $list;
	}

	protected function _flatten($types, $parent, $list)
	{
		foreach ($types as $k => $v)
		{
			if (is_int($k))
			{
				$list[]['path'] = $parent . '.' . $v;
			}
			else
			{
				$id = $parent . '.' . $k;
				$list = $this->_flatten($v, $id, $list);
			}
		}
		return $list;
	}

	public function routes()
	{
		return json_encode($this->routes, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
	}
}

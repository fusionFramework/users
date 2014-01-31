<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_User_Tab_Notes extends View_Admin_User_Tab {

	/**
	 * @var Model_User_Note All notes for this user
	 */
	public $notes;

	/**
	 * @var array containing routes to manage notes
	 */
	public $routes = [];

	/**
	 * Get a list of note types
	 *
	 * @return array
	 */
	public function options()
	{
		$options = Kohana::$config->load('notes.types');

		$list = [];

		foreach($options as $type => $name)
		{
			$list[] = ['type' => $type, 'value' => $name];
		}
		return $list;
	}

	/**
	 * @return array
	 */
	public function notes() {
		$list = [];

		foreach($this->notes as $note)
		{
			$list[] = array_merge($note->as_array(), ['created' => Fusion::date($note->created_at)]);
		}

		return $list;
	}

	public $button = [
		'modal_id' => '',
		'class' => 'btn-primary nolink note-refresh',
		'icon' => 'fa fa-refresh',
		'text' => 'Refresh'
	];

	public function routes()
	{
		return json_encode($this->routes, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	}
}

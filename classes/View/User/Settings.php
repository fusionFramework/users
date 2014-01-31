<?php defined('SYSPATH') OR die('No direct script access.');

class View_User_Settings extends Views {

	public $title = 'Settings';

	/**
	 * @var string Link to submit the settings form to
	 */
	public $submit_link = '';

	/**
	 * @var array A list defining the navigation tabs
	 */
	public $tabs = array();

	/**
	 * @var string Active tab
	 */
	public $active_tab = '';

	/**
	 * Standardise the tabs
	 *
	 * @return array
	 */
	public function tabs()
	{
		$list = array();
		foreach($this->tabs as $name => $tab)
		{
			$list[] = array(
				'title' => $tab['title'],
				'url' => $tab['route'],
				'active' => ($name == $this->active_tab)
			);
		}
		return $list;
	}
}

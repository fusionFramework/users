<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_User_View extends View_Admin {
	public $header = 'Users';
	public $icon = 'fa fa-user';

	/**
	 * @var Model_User user to edit.
	 */
	public $user;

	public $game_info = array();

	public $tabs;

	/**
	 * Renders any tab-specific HTML
	 * @return string
	 */
	public function tab_containers()
	{
		$html = '';
		$tabs = $this->tabs;

		foreach($tabs as $tab)
		{
			if(isset($tab['render']))
			{
				$tpl = $tab['render']();
				if(is_object($tpl))
				{
					$renderer = Kostache_Layout::factory();
					$renderer->set_layout('admin/user/tab');
					$tpl->id = $tab['id'];

					if(!isset($tpl->title))
					{
						$tpl->title = $tab['title'];
					}

					$html .= $renderer->render($tpl);
				}
				else
				{
					$html .= $tpl;
				}
			}
		}

		return $html;
	}

	public function groups_available() {
		$list = array();

		foreach($this->groups as $group) {
			if($this->user->has('groups', $group->id) == false)
			{
				$list[] = array(
					'id' => $group->id,
					'name' => ucfirst($group->name)
				);
			}
		}
		return $list;
	}

	public function groups() {
		$list = array();

		foreach($this->user->getGroups() as $group) {
			$list[] = array(
				'id' => $group->id,
				'name' => ucfirst($group->name)
			);
		}
		return $list;
	}

	public function timezones() {
		$zones = DateTimeZone::listIdentifiers();
		$list = array();

		foreach($zones as $zone)
		{
			$list[] = array(
				'value' => $zone,
				'selected' => $this->user->timezone == $zone
			);
		}

		return $list;
	}
}

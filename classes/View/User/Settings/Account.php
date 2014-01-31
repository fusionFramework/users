<?php defined('SYSPATH') OR die('No direct script access.');

class View_User_Settings_Account extends View_User_Settings {

	public function timezones()
	{
		$zones = DateTimeZone::listIdentifiers();
		$list = array();

		foreach($zones as $zone)
		{
			$list[] = array(
				'value' => $zone,
				'selected' => Fusion::$user->timezone == $zone
			);
		}

		return $list;
	}
}

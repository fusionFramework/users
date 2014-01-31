<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_User_Tab_Avatars extends View_Admin_User_Tab {

	/**
	 * @var Model_Avatar Non-default avatars
	 */
	public $avatars;

	/**
	 * @var string Link to submit data to
	 */
	public $submit_link;

	/**
	 * Avatars the user does not have
	 * @return array
	 */
	public function avatars_available() {
		$list = array();

		$avatars = $this->avatars;

		foreach($avatars as $avatar) {
			if($this->user->has('avatars', $avatar->id) == false)
			{
				$list[] = array(
					'id' => $avatar->id,
					'name' => ucfirst($avatar->title)
				);
			}
		}
		return $list;
	}

	/**
	 * All avatars the user already has
	 * @return array
	 */
	public function avatars() {
		$list = array();

		$avatars = $this->user->avatars->find_all();
		foreach($avatars as $avatar) {
			$list[] = array(
				'id' => $avatar->id,
				'name' => ucfirst($avatar->title)
			);
		}
		return $list;
	}
}

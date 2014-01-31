<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * User group admin
 *
 * @package    fusionFramework/user
 * @category   Admin
 * @author     Maxim Kerstens
 * @copyright  (c) happydemon.org
 */
class Admin_User_Groups extends Admin
{
	public  $resource = "user.groups";
	public $icon = 'fa fa-flag';
	public $track_changes = TRUE;

	/**
	 * Set up the dataTable definition for this controller.
	 *
	 * @see Table
	 *
	 * @param Table $table
	 *
	 * @return Table A fully configured dataTable definition
	 */
	public function setup_table($table)
	{
		$table->add_column('name', array('head' => 'Name'));

		return $table;
	}

	protected function _setup()
	{
		$this->model = ORM::factory('Group');

		// a wider modal is needed for the permissions
		$this->modal['width'] = 550;

		$this->_assets['set'][] = 'moveselect';
		$this->_assets['js'][] = 'admin/user_groups.js';
	}

	public function modal(Array $data)
	{
		return View::factory('admin/modal/user_groups', $data);
	}
}
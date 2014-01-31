<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * Notification admin
 *
 * @package    fusionFramework/user
 * @category   Admin
 * @author     Maxim Kerstens
 * @copyright  (c) happydemon.org
 */
class Admin_User_Notifications extends Admin
{
	public  $resource = "user.notifications";
	public $icon = 'fa fa-rss-square';
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
		$dim = Kohana::$config->load('notifications');

		$table->add_column('img', [
			'head' => 'Image',
			'retrieve' => function(Model_Noticication $record){
				return $record->icon();
			},
			'format' => 'image',
			'param' => [$dim['width'], $dim['height']],
			'class' => 'col-sm-1'
		], false, false);
		$table->add_column('alias', array('head' => 'Alias'));

		return $table;
	}

	protected function _setup()
	{
		$this->model = ORM::factory('Notification');

		// a wider modal is needed for the permissions
		$this->modal['width'] = 550;

		$this->_assets['set'][] = 'uploadify';

		$this->images = [
			'icon' => [
				'web' => function($model){
						return URL::site('m/notifications/' . $model->img, true, false);
					},
				'move' => function($record, $image) {
						$cfg = Kohana::$config->load('notifications')->as_array();
						$record->img = strtolower(Inflector::underscore(str_replace('.', ' ', $record->alias))).'.png';
						rename($image, $cfg['path'].$record->img);
					}
			]
		];
	}

	public function modal(Array $data)
	{
		$form = $data['model']->get_form(['alias', 'icon', 'title', 'message', 'url']);
		return $form;
	}
}
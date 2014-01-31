<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * Avatar admin
 *
 * @package    fusionFramework/user
 * @category   Admin
 * @author     Maxim Kerstens
 * @copyright  (c) happydemon.org
 */
class Admin_User_Avatars extends Admin
{
	public  $resource = "user.avatars";
	public $icon = 'fa fa-picture-o';
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
		$dim = Kohana::$config->load('avatar')->as_array();

		$table->add_column('img', [
			'head' => 'Image',
			'retrieve' => function(Model_Avatar $record){
				return $record->img();
			},
			'format' => 'image',
			'param' => [$dim['width'], $dim['height']],
			'class' => 'col-sm-1'
		], false, false);
		$table->add_column('title', array('head' => 'Title'));
		$table->add_column('default', array('head' => 'Default?'));

		return $table;
	}

	protected function _setup()
	{
		$avatar = Kohana::$config->load('avatar')->as_array();
		$this->model = ORM::factory('Avatar');

		// a wider modal is needed for the permissions
		$this->modal['width'] = 550;

		$this->_assets['set'][] = 'uploadify';

		$this->images = [
			'img' => [
				'web' => function($model){
						return URL::site('m/avatars/' . $model->img, true, false);
					},
				'move' => function($record, $image) use($avatar) {
						$record->img = strtolower(Inflector::underscore($record->title)).'.png';
						rename($image, $avatar['path'].$record->img);
					}
			]
		];
	}

	public function modal(Array $data)
	{
		$form = $data['model']->get_form(['img', 'title', 'default', 'status']);
		return $form;
	}
}
<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * User Property model, key/value storage.
 *
 * @package    fusionFramework/user
 * @category   Model
 * @author     Maxim Kerstens
 * @copyright  (c) happydemon.org
 */
class Model_User_Property extends ORM {

	protected $_table_columns = array(
		'id' => NULL,
		'user_id' => NULL,
		'key' => NULL,
		'value' => NULL
	);

	protected $_belongs_to = array(
		'user' => array(
			'model' => 'User'
		)
	);

	protected $_serialize_columns = array(
		'value'
	);

}

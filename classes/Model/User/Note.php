<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * User Note Model
 *
 * Used in the admin to keep track on a user's history with admins on the site.
 *
 * @package    fusionFramework/user
 * @category   Model
 * @author     Maxim Kerstens
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class Model_User_Note extends ORM {

	protected $_belongs_to = array(
		'user' => array(
			'model' => 'User',
		)
	);

	protected $_created_column = ['column' => 'created_at', 'format' => true];
}

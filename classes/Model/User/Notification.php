<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * User Notification Model.
 *
 * @package    fusionFramework/user
 * @category   Model
 * @author     Maxim Kerstens
 * @copyright  (c) happydemon.org
 */
class Model_User_Notification extends ORM {

	protected $_belongs_to = array(
		'log' => array(
			'model' => 'Log',
		),
		'user' => array(
			'model' => 'User',
		),
		'notification' => array(
			'model' => 'Notification',
		)
	);

	protected $_load_with = ['notification'];
	protected $_created_column = ['column' => 'created_at', 'format' => true];
	protected $_serialize_columns = ['param'];

	/**
	 * Prepare notification data for a view.
	 *
	 * @return array
	 */
	public function parse()
	{
		return array(
			'message' => __($this->notification->message, $this->params),
			'url' => ($this->notification->url != '') ? array('href' => Route::url('user.notifications.route', array('id' => $this->id), true)) : false,
			'icon' => $this->notification->icon(),
			'title' => $this->notification->title,
			'type' => $this->notification->typ,
			'link_unset' => Route::url('user.notifications.unset', array('id' => $this->id), true),
			'date' => Fusion::date($this->created_at)
		);
	}
}

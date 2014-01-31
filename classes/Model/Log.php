<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Log model
 *
 * @package    fusionFramework/user
 * @category   Model
 * @author     Maxim Kerstens
 * @copyright  (c) 2013-2014 Maxim Kerstens
 * @license    BSD
 */
class Model_Log extends ORM {

	protected $_created_column = array(
		'column' => 'time',
		'format' => TRUE
	);

	protected $_belongs_to = array('user' => array());

	protected $_serialize_columns = array('params');

	/**
	 * Send a notification to a user based on a log.
	 *
	 * @param Model_User $user         User instance we'll be notifying
	 * @param string     $notification A string that can be parsed through __()
	 * @param array      $param        Params to parse the notification with (combined with $log->params)
	 *
	 * @return Model_User_Notification
	 */
	public function notify(Model_User $user, $notification, $param = array())
	{
		$notify = Kohana::$config->load('notify.' . $notification);

		$notify = ORM::factory('Notification')
			->where('alias', '=', $notification)
			->find();

		if(!$notify->loaded())
		{
			throw new Kohana_Exception('User could not be notified, no notification called ":notification" exists.', [':notification' => $notification]);
		}

		$values = array(
			'log_id' => $this->id,
			'user_id' => $user->id,
			'notification_id' => $notify->id,
			'param' => array_merge($this->params, $param),
			'read' => '0',
			'alias' => $notification
		);

		//fire a notify event
		Plug::fire('log.notify', $values);

		return ORM::factory('User_Notification')
			->values($values, ['log_id', 'user_id', 'notification_id', 'param', 'read'])
			->create();
	}
}

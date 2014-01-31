<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * User model
 *
 * @package    fusionFramework/user
 * @category   Model
 * @author     Maxim Kerstens
 * @copyright  (c) happydemon.org
 */
class Model_User extends \Cartalyst\Sentry\Users\Kohana\User {

	/**
	 * The login attribute.
	 *
	 * @var string
	 */
	protected static $loginAttribute = 'username';

	/**
	 * Columns which have an array as content
	 * @var array
	 */
	protected $_serialize_columns = array('settings', 'permissions');

	/**
	 * auto-set the updated_at column
	 * @var array
	 */
	protected $_updated_column = array ('column' => 'updated_at', 'format' => true);

	/**
	 * Auto-set the created_at column
	 * @var array
	 */
	protected $_created_column = array ('column' => 'created_at', 'format' => true);

	/**
	 * Define the has many relation(s)
	 * @var array
	 */
	protected $_has_many = array (
		'groups' => array ('model' => 'Group', 'through' => 'users_groups'),
		'properties' => array('model' => 'User_Property'),
		'notifications' => array('model' => 'User_Notification'),
		'avatars' => array('model' => 'Avatar', 'through' => 'users_avatars')
	);

	/**
	 * Validation rules
	 * @return array
	 */
	public function rules()
	{
		return array_merge(parent::rules(), array(
			'username' => array(
				array('not_empty'),
				array('alpha_dash'),
				array('min_length', array(':value', 4)),
				array('max_length', array(':value', 32)),
				array (array ($this, 'unique_key_exists'), array (':value', 'username'))
			)
		));
	}

	/**
	 * Modify the user's points
	 *
	 * @param integer $amount >= 0
	 * @param string $modifier (+,-,=)
	 * @return bool
	 */
	public function points($amount, $modifier = '+')
	{
		if ($modifier != '=')
		{
			$points = $this->setting('points', 0);
		}

		switch ($modifier)
		{
			case '+':
				$points = $points + $amount;
				break;
			case '-':
				$points = $points - $amount;

				if ($points < 0)
				{
					return false;
				}
				break;
			case '=':
				$points = $amount;
				break;
		}

		$this->config('points', $points, true);
		return true;
	}

	/**
	 * Retrieve the value of one of the user's setting
	 *
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	public function setting($name, $default = null, $local_only = false)
	{
		if ($this->settings != null && array_key_exists($name, $this->settings))
		{
			return $this->settings[$name];
		}

		if ($local_only == false)
		{
			$property = $this->properties->where('key', '=', $name)->find();

			if ($property->loaded())
			{
				return $property->value;
			}
		}

		return $default;
	}

	/**
	 * Change a user's setting.
	 *
	 * @param      $setting Key name of the setting
	 * @param      $value   Value of the setting
	 * @param bool $local   If the setting is called a lot set this to true
	 */
	public function config($setting, $value, $local = false)
	{
		if($this->settings == null)
		{
			$this->settings = array();
		}
		if (array_key_exists($setting, $this->settings))
		{
			$settings = $this->settings;
			$settings[$setting] = $value;
			$this->settings = $settings;
			$this->save();
		}
		else if ($local == true)
		{
			$settings = $this->settings;
			$settings[$setting] = $value;
			$this->settings = $settings;
			$this->save();
		}
		else
		{
			$prop = $this->properties->where('key', '=', $setting)->find();

			if ($prop->loaded())
			{
				$prop->value = $value;
				$prop->save();
			}
			else
			{
				ORM::factory('User_Property')
					->values(
					array(
						'user_id' => $this->id,
						'key' => $setting,
						'value' => $value
					)
				)
				->create();
			}
		}

		return $this;
	}

	/**
	 * Set the user's groups (deletes previously stored ones).
	 *
	 * We're using Kohana's add method, not Sentry's addGroup
	 * @see ORM::add
	 */
	public function set_groups(Array $groups)
	{
		//remove all related groups
		$this->remove('groups');

		//add all the new groups
		$this->add('groups', $groups);

		return $this;
	}

	/**
	 * Return a list groups this user does not have.
	 *
	 * @param bool $return_local Also return the groups this user has?
	 * @return array
	 */
	public function not_in_groups($return_local=false)
	{
		$owned = $this->groups->find_all();
		$groups = Sentry::getGroupProvider()->createModel();

		if(count($owned) > 0) {
			$user_group_ids = array();

			foreach($owned as $group) {
				$user_group_ids[] = $group->id;
			}

			if(count($user_group_ids) > 1)
			{
				$groups->where('id', 'NOT_IN', $user_group_ids);
			}
			else
			{
				$groups->where('id', '!=', $user_group_ids[0]);
			}

			if($return_local == false)
				return $groups->find_all();
			else
			{
				return array('joined' => $owned, 'free' => $groups->find_all());
			}
		}

		return array();
	}

	/**
	 * Retrieve a user's avatar image url
	 * @return string
	 */
	public function avatar()
	{
		$avatar = $this->setting('avatar', null, true);

		return ($avatar == null) ? Kohana::$config->load('avatar.default') : $this->avatars->where('id', '=', $avatar)->img();
	}

	/**
	 * Retrieve a user's avatar image url and return an image tag
	 * @return string
	 */
	public function avatar_img()
	{
		return '<img src="'.$this->avatar().'" width="64" height="64" />';
	}

	/**
	 * Used to represent in belongs_to relations when changes are tracked
	 * @return bool|string
	 */
	public function candidate_key()
	{
		if (!$this->loaded()) return FALSE;
		return $this->username;
	}

} // End User Model
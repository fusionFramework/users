<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Promote a user to owner.
 *
 * There are 2 ways of doing this,
 * either by passing an id or a username as parameter:
 *  - php minion user:owner --id=x
 *  - php minion user:owner --username=x
 *
 * Where x would be a proper value.
 *
 * @package    fusionFramework
 * @category   Admin
 * @author     Maxim Kerstens
 */
class Task_User_Owner extends Minion_Task
{
	protected $_options = [
		'username' => null,
		'id' => null
	];


	public function build_validation(Validation $validation)
	{
		$check = function(Validation $validation, $value, $param) {
			$params = [
				'id' => 'username',
				'username' => 'id'
			];

			if($value != null)
			{
				try
				{
					Sentry::findUserByCredentials(array(
						$param => $value
					));
				}
				catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
				{
					//only return false if the other parameter also wasn't supplied
					if($validation[$params[$param]] == null)
						return false;
				}
			}
			return true;
		};

		return parent::build_validation($validation)
			->rule('username', $check, [':validation', ':value', ':field'])
			->rule('id', $check, [':validation', ':value', ':field']);
	}

	protected function _get_user($param, $value)
	{
		try
		{
			$user = Sentry::findUserByCredentials(array(
				$param => $value
			));
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			$user = null;
		}

		return $user;
	}

	protected function _execute(array $params)
	{
		$user = $this->_get_user('id', $params['id']);

		if($user == null)
		{
			$user = $this->_get_user('username', $params['username']);
		}

		if ($user == null)
		{
			Minion_CLI::write('No user was found!');
		}
		else {
			// Add the owner group to the user's groups
			$user->add('groups', 2);
			$user->save();

			Minion_CLI::write($user->username . ' has become an owner.');
		}
	}
}
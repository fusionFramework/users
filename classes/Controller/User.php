<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User extends Controller_Fusion_Site {

	protected $_login_required = false;
	protected $_dump_all_alerts = true;

	// Show the registration form
	public function action_register() {
		$form_values = array('username' => '', 'email' => '', 'password' => '', 'first_name' => '', 'last_name' => '');

		if($this->request->method() == Request::POST)
		{
			$this->register($form_values);
		}

		$this->_tpl = new View_User_Register;
		$this->_tpl->values = $form_values;
	}
	
	// Try to register the user
	public function register(&$form_values) {
		try
		{
			$form_values = $this->request->post();
			// Let's register a user.
			$user = Sentry::register(array_merge($form_values, array('settings' => array(), 'permissions' => array())));

			$user->config('points', Fusion::$config['currency']['initial_budget'], true);

			//Add the user the 'user' group
			$user->add('groups', 1);

			Plug::fire('user.register', [$user]);

			// Went fine, let's send him an activation mail
			$mail_tpl = Kostache::factory()->render(array(
				'site_name' => Fusion::$config['name'],
				'username' => $user->username,
				'activation_link' => Route::url('user.activate', array('username' => $user->username, 'code' => $user->getActivationCode())),
				'activation_code' => $user->getActivationCode()
			), 'mails/user/register');

			$msg = Fusion::$mail
				->message($form_values['email'], Fusion::$config['name'].'\'s account activation.', $mail_tpl)
				->setContentType('text/html');

			Fusion::$mail->send($msg);

			RD::set(RD::SUCCESS, 'Your have registered successfully, check your email for your activation code.');

			//everything went successful, send the user to the index page
			$this->redirect(Route::url('default', null, true));
		}
		catch (ORM_Validation_Exception $e)
		{
			$errors = $e->errors('orm');

			//make hints out of the errors
			foreach($errors as $error)
			{
				RD::set(RD::ERROR, $error);
			}
		}
	}

	// Try to activate the user
	public function action_activate() {
		try {
			$user = Sentry::getUserProvider()->findByCredentials(array('username' => $this->request->param('username')));

			// Attempt to activate the user
			if ($user->attemptActivation($this->request->param('code')))
			{
				// User activation passed
				RD::set(RD::SUCCESS, 'Your account has been activated.');

				//everything went successful, send the user to the login page
				$this->redirect(Route::url('user.login', null, true));
			}
			else
			{
				// User activation failed
				RD::set(RD::ERROR, 'You seem to have entered the wrong activation code.');
			}
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			RD::set(RD::ERROR, 'There\'s no record of the user you want to activate.');

		}
		catch (Cartalyst\SEntry\Users\UserAlreadyActivatedException $e)
		{
			RD::set(RD::ERROR, 'This user is already activated.');
		}

		// Something went wrong, send the user to the index
		$this->redirect(Route::url('default', null, true));

	}

	// Log the user out
	public function action_logout()
	{
		if(Fusion::$user != null)
		{
			Plug::fire('user.logout', [Fusion::$user]);

			Sentry::logout();

			RD::set(RD::WARNING, 'Sad to see you go, but take care.');
		}

		$this->redirect(Route::url('default', null, true));
	}

	// Show the login form
	public function action_login() {
		$throttle = '';

		if($this->request->method() == REquest::POST)
		{
			$throttle = $this->login();
		}

		$this->_tpl = new View_User_Login;
		$this->_tpl->throttle = $throttle;
	}

	// Try to log a user in
	public function login() {
		$view = '';
		try
		{
			$throttle_provider = Sentry::getThrottleProvider();

			//if the login throttler is enabled set it up
			if(Kohana::$config->load('sentry.throttle') == true) {
				$throttle_provider->enable();

				$throttle = $throttle_provider->findByUserLogin($this->request->post('username'));

				//set the limit of consecutive failed login attempts
				$throttle->setAttemptLimit(Kohana::$config->load('sentry.throttle_attempts'));

				//set the suspension time in minutes
				$throttle->setSuspensionTime(Kohana::$config->load('sentry.throttle_suspension_time'));
			}
			else
				$throttle_provider->disable();

			$user = Sentry::getUserProvider()->findByCredentials(array(
				'username'      => $this->request->post('username'),
				'password'   => $this->request->post('password')
			));

			// Log the user in
			if($this->request->post('remember', false)) {
				Sentry::loginAndRemember($user);
			}
			else {
				Sentry::login($user, false);
			}

			//if the login throttler is enabled clear failed login attempts
			if($throttle_provider->isEnabled())
				$throttle->clearLoginAttempts();

			RD::set(RD::SUCCESS, 'Welcome back '.$user->username.'!');

			//everything went successful, send the user to the index page
			$this->redirect(Route::url('default', null, true));
		}
		catch (Cartalyst\Sentry\Users\WrongPasswordException $e)
		{
			RD::set(RD::ERROR, 'You seem to have have provided an incorrect password.');

			//if throttles are enabled add an attempt and show how many attempts are left
			if($throttle_provider->isEnabled()) {
				$throttle->addLoginAttempt();

				$view = $throttle->getLoginAttempts().'/'.$throttle->getAttemptLimit().' attempts left. <br />';
			}
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			RD::set(RD::ERROR, 'There\'s no user with that login.');
		}
		catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
		{
			RD::set(RD::ERROR, 'We need to know who\'s logging in.');
		}
		catch (Cartalyst\Sentry\Users\UserNotActivatedException $e)
		{
			RD::set(RD::ERROR, 'Your account hasn\'t been activated yet.');
		}
		catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e)
		{
			$time = $throttle->getSuspensionTime();
			RD::set(RD::ERROR, 'You have tried logging in too much, wait '.$time.' minutes before trying again.');
		}
		catch (Cartalyst\Sentry\Throttling\UserBannedException $e)
		{
			RD::set(RD::ERROR, 'You are banned.');
		}

		return $view;
	}

	// Show the reset password form
	public function action_reset() {
		if($this->request->method() == Request::POST)
		{
			$this->reset();
		}

		$this->_tpl = new View_User_Reset;
	}

	// Generate a reset token
	public function reset() {
		try
		{
			// Find the user using the user email address
			$user = Sentry::getUserProvider()->findByCredentials(array('username' => $this->request->post('username')));

			// Went fine, let's send him an activation mail
			$mail_tpl = Kostache::factory()->render(array(
				'site_name' => Fusion::$config['name'],
				'username' => $user->username,
				'reset_link' => Route::url('user.reset_valid', array('username' => $user->username, 'code' => $user->getResetPasswordCode())),
				'reset_code' => $user->getActivationCode()
			), 'mails/user/reset');

			$msg = Fusion::$mail->message($user->email, Fusion::$config['name'].' password reset.', $mail_tpl)
				->setContentType('text/html');

			Fusion::$mail->send($msg);

			RD::set(RD::SUCCESS, 'We have sent you a mail with a reset token and further instructions.');

			//everything went successful, send the user somewhere else
			$this->redirect(Route::url('sentry.users.reset_valid', null, true));
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			RD::set(RD::ERROR, 'There\'s no user with that login credential.');
		}
	}

	// Show the reset code validation form
	public function action_reset_validate() {
		if($this->request->method() == Request::POST)
		{
			$this->reset_valid();
		}

		$this->_tpl = new View_User_Reset_Valid;
		if($this->request->param('code') != null)
		{
			$this->_tpl->token = $this->request->param('code');
		}
	}

	// Try to reset a user's password
	public function reset_valid() {
		try
		{
			// Find the user using the user id
			$user = Sentry::getUserProvider()->findByCredentials(array(
				'username' => $this->request->param('username')
			));

			if($this->request->post('password') == '')
				RD::set(RD::ERROR, 'Please provide a password.');
			// Check if the reset password code is valid
			else if ($user->checkResetPasswordCode($this->request->post('code')))
			{
				// Attempt to reset the user password
				if ($user->attemptResetPassword($this->request->post('code'), $this->request->post('password')))
				{
					// Password reset passed
					RD::set(RD::SUCCESS, 'You have successfully reset your password');

					//everything went successful, send the user somewhere else
					$this->redirect(Route::url('user.login', null, true));
				}
				else
				{
					RD::set(RD::ERROR, 'Resetting your password has failed.');
				}
			}
			else
			{
				// The provided password reset code is Invalid
				RD::set(RD::ERROR, 'The provided reset code is invalid.');
			}
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			RD::set(RD::ERROR, 'There\'s no user with that login credential.');
		}
	}

	// @todo Show the provided user's profile
	public function action_profile()
	{
		$id = $this->request->param('id');

		$user = ORM::factory('User', $id);
	}

} // End User controller

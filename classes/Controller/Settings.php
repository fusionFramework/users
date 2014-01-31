<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Settings extends Controller_Fusion_Site {

	protected $_login_required = true;
	protected $_current_tab = '';

	public  function after()
	{
		// If a View was assigned add the tab navigation
		if($this->_tpl != null)
		{
			$tabs = Plug::fire('user.settings.nav');
			$list = array();

			foreach($tabs as $tab)
			{
				$list = array_merge($list, $tab);
			}
			$this->_tpl->tabs = $list;

			$this->_tpl->active_tab = $this->_current_tab;
		}
		parent::after();
	}

	public function action_index()
	{
		$this->_current_tab = 'account';
		$this->_tpl = new View_User_Settings_Account;
		$this->_tpl->submit_link = Route::url('user.settings.account.submit', null , true);
	}

	public function action_account_submit()
	{
		if($this->request->method() == Request::POST)
		{
			if(!in_array($_POST['timezone'], DateTimeZone::listIdentifiers()))
			{
				RD::set(RD::ERROR, 'No valid timezone supplied.');
			}
			else if(!Valid::email($_POST['email']))
			{
				RD::set(RD::ERROR, 'No valid email address supplied.');
			}
			else if(isset($_POST['new_password']) && $_POST['new_password'] != $_POST['confirm_password'])
			{
				RD::set(RD::ERROR, 'The supplied passwords don\'t match.');
			}
			else
			{
				try {
					if(isset($_POST['new_password']) && !empty($_POST['password']))
					{
						Fusion::$user->password = $_POST['new_password'];
					}
					Fusion::$user->values($_POST, array('timezone', 'email'))
						->save();
					RD::set(RD::SUCCESS, 'You\'ve successfully updated your account details.');
				}
				catch(ORM_Validation_Exception $e)
				{
					$errors = $e->errors('model');

					foreach($errors as $error)
					{
						RD::set(RD::WARNING, $error);
					}
				}
			}
		}
		$this->redirect(Route::url('user.settings', null, true));
	}

	public function action_preferences()
	{
		$this->_current_tab = 'prefs';
		$this->_tpl = new View_User_Settings_Preferences;
		$this->_tpl->submit_link = Route::url('settings.preferences.submit', null , true);
	}

} // End User controller

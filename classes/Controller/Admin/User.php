<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Admin dashboard
 *
 * @package    fusionFramework
 * @category   Admin
 * @author     Maxim Kerstens
 * @copyright  (c) 2013-2014 Maxim Kerstens
 * @license    BSD
 */
class Controller_Admin_User extends Controller_Fusion_Admin {

	public function action_index()
	{
		$this->_tpl = new View_Admin_User_Index;
		$this->_tpl->locate_link = Route::url('admin.user.search', null, true);
		$this->_tpl->typeAhead = Admin::typeAhead_tpl('username');
		Fusion::$assets->add_set('typeahead');
		Fusion::$assets->add_js('admin/users.js');
	}

	public function action_search()
	{
		if($this->request->method() != Request::POST || !isset($_POST['username']))
			throw new HTTP_Exception_404('No user found');

		$user = ORM::factory('User')
			->where('username', '=', $_POST['username'])
			->find();

		if($user->loaded())
		{
			$this->redirect(Route::url('admin.user.view', array('id' => $user->id), true));
		}
		else
		{
			$users = ORM::factory('User')
				->where('username', 'LIKE', '%'.$_POST['username'].'%')
				->find_all();

			if($users->count() == 0)
			{
				throw new HTTP_Exception_404('No user found');
			}
			else
			{
				//@todo implement!!
				$this->_tpl = new View_Admin_User_Search;
				$this->_tpl->users = $users;
			}
		}
	}

	public function action_view()
	{
		$id = $this->request->param('id');

		$user = ORM::factory('User', $id);

		if ( ! $user->loaded())
		{
			throw HTTP_Exception::factory('404', 'No such user');
		}

		Fusion::$assets->add_set('moveselect');
		Fusion::$assets->add_js('admin/users/edit.js');

		$this->_tpl = new View_Admin_User_View;
		$this->_tpl->user = $user;

		$provided_tabs = array_reverse(Plug::fire('admin.user.tabs', array($this->_tpl->user)));
		$tabs = [];

		foreach($provided_tabs as $tab)
		{
			$tabs = array_merge($tabs, $tab);
		}

		foreach($tabs as $ind => $tab)
		{
			if(!Fusion::$user->hasAccess('admin.user.'.$tab['id']))
			{
				unset($tabs[$ind]);
			}
		}

		$this->_tpl->tabs = $tabs;

		//add assets
		foreach($tabs as $tab)
		{
			if(isset($tab['assets']))
			{
				foreach($tab['assets'] as $type => $assets)
				{
					Fusion::$assets->add($type, $assets);
				}
			}
		}
		//$this->_tpl->link_pwd = Route::url('admin.user.modal.password', array('user_id' => $id));
		$this->_tpl->link_submit = Route::url('admin.user.edit', array('id' => $id));
		$this->_tpl->groups = ORM::factory('Group')->find_all();
	}

	public function action_edit()
	{
		$id = $this->request->param('id');

		$user = ORM::factory('User', $id);

		if ( ! $user->loaded())
		{
			throw HTTP_Exception::factory('404', 'No such user');
		}

		$data = $this->request->post();

		try {
			//handle the groups
			Database::instance()->query(null, 'DELETE FROM users_groups WHERE user_id="'.$user->id.'"');

			$user->add('groups', $data['groups']);

			//handle the rest
			$user->values($data, array('email', 'timezone'))
				->save();

			RD::success($user->username. ' edited successfully!');
		}
		catch(ORM_Validation_Exception $e) {
			RD::alert('Error(s) for '.$user->username.': '.implode($e->errors('orm'), ', '));
		}
		$this->redirect(Route::url('admin.user.view', array('id' => $user->id), true));
	}

} // End Admin Users

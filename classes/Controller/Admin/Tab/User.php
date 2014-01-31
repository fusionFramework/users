<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * User tab request controller
 *
 * @package    fusionFramework
 * @category   Admin/User
 * @author     Maxim Kerstens
 * @copyright  (c) 2013-2014 Maxim Kerstens
 * @license    BSD
 */
class Controller_Admin_Tab_User extends Controller_Admin_Tab {

	// Process a list of avatars
	public function action_avatars() {
		$this->access('admin.user.tab-avatars.load');

		if($this->request->method() != Request::POST)
			throw new HTTP_Exception_404;

		$id = $this->request->param('id');

		$user = ORM::factory('User', $id);

		if ( ! $user->loaded())
		{
			throw HTTP_Exception::factory('404', 'No such user');
		}

		$data = $this->request->post();

		try {
			//handle the avatars
			Database::instance()->query(null, 'DELETE FROM users_avatars WHERE user_id="'.$user->id.'"');

			$user->add('avatars', $data['avatars']);

			RD::success($user->username. '\'s avatars were edited successfully!');
		}
		catch(ORM_Validation_Exception $e) {
			RD::alert('Error(s) for '.$user->username.': '.implode($e->errors('orm'), ', '));
		}
	}

	public function action_log_js()
	{
		$table = new Table();
		Plug::fire('admin.user.tab.logs', [$table]);

		$this->response->headers('Content-Type','application/x-javascript');
		$this->response->body($table->js(Route::url('admin.user.tab.logs.fill', null, true)));
	}

	public function action_log_fill() {
		$this->access('admin.user.logs.view');

		$this->_handle_ajax = false;

		if (DataTables::is_request())
		{
			$table = new Table();
			Plug::fire('admin.user.tab.logs', [$table]);

			//set a model and render
			$model = ORM::factory('Log')->where('user_id', '=', $this->request->param('id'));

			if(!empty($_GET['date_start']))
			{
				$model->where('time', '>=', date_create_from_format('Y-m-d', $_GET['date_start'])->getTimestamp());
			}
			if(!empty($_GET['date_end']))
			{
				$model->and_where('time', '<=', date_create_from_format('Y-m-d', $_GET['date_end'])->getTimestamp());
			}
			$data = $table->model($model)->request();

			$this->response
				->headers('content-type', 'application/json')
				->body($data->render());
		}
		else
			throw new HTTP_Exception_500();
	}

	public function action_log_modal()
	{
		$this->access('admin.user.tab-logs.load');

		$id = $this->request->param('id');

		$log = ORM::factory('Log', $id);

		if(!$log->loaded())
			throw new HTTP_Exception_404;

		RD::set(RD::SUCCESS, 'Log loaded', null, [
			'alias' => $log->alias,
			'time' => Fusion::date($log->time),
			'message' => __($log->message, $log->params),
			'location' => $log->location,
			'ip' => $log->ip,
			'browser' => $log->agent,
			'params' => $log->params
		]);
	}

	// Handle note creation
	public function action_create_note() {
		$this->access('admin.user.tab-notes.submit');

		if($this->request->method() != Request::POST)
			throw new HTTP_Exception_404;

		$id = $this->request->param('id');

		$user = ORM::factory('User', $id);

		if ( ! $user->loaded())
		{
			throw HTTP_Exception::factory('404', 'No such user');
		}

		$data = $this->request->post();
		$data['created_by'] = Fusion::$user->username;
		$data['user_id'] = $id;

		try {
			$note = ORM::factory('User_Note')
				->values($data)
				->save();

			RD::success($user->username. ' had a note added', null, array_merge($note->as_array(), ['created' => Fusion::date($note->created_at)]));
		}
		catch(ORM_Validation_Exception $e) {
			RD::alert('Error(s) for '.$user->username.': '.implode($e->errors('orm'), ', '));
		}
	}

	// Process a list of avatars
	public function action_refresh_notes() {
		$this->access('admin.user.tab-notes.reresh');

		if($this->request->method() != Request::GET)
			throw new HTTP_Exception_404;

		$id = $this->request->param('id');

		$user = ORM::factory('User', $id);

		if ( ! $user->loaded())
		{
			throw HTTP_Exception::factory('404', 'No such user');
		}

		try {
			$notes = ORM::factory('User_Note')
				->where('user_id', '=', $id)
				->find_all();

			$list = [];

			if(count($notes) > 0)
			{
				foreach($notes as $note)
				{
					$list[] = array_merge($note->as_array(), ['created' => Fusion::date($note->created_at)]);
				}
			}

			RD::success('Notes refreshed', null, $list);
		}
		catch(ORM_Validation_Exception $e) {
			RD::alert('Error(s) for notes from '.$user->username.': '.implode($e->errors('orm'), ', '));
		}
	}
} // End Admin Users Tab

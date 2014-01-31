<?php defined('SYSPATH') OR die('No direct script access.');

Plug::listen('admin.nav_list', function() {
	return [
		'title' => 'Users',
		'icon'  => 'fa fa-users',
		'items' => array(
			[
				'title' => 'Manage',
				'route'  => 'admin.user.index',
				'icon'  => 'fa fa-user'
			],
			array(
				'title' => 'Avatars',
				'route' => 'admin.user.avatars.index',
				'icon'  => 'fa fa-square',
			),
			array(
				'title' => 'Groups',
				'route' => 'admin.user.groups.index',
				'icon'  => 'fa fa-flag',
			),
			[
				'title' => 'Notifications',
				'route' => 'admin.user.notifications.index',
				'icon'  => 'fa fa-rss-square'
			]
		)
	];
});

Plug::listen('admin.search', function($type, $term, $handle){
	$return = [];

	switch($type)
	{
		case 'email':
		case 'username':
			if($handle == 'data')
			{
				$users = ORM::factory('User')
					->where($type, 'LIKE', '%'.$term.'%')
					->find_all();

				if($users->count() > 0)
				{
					foreach($users as $user)
					{
						$return[] = array('id' => $user->id, 'value' => $user->username);
					}
				}
			}
			else
				return '<p><small>#<%id%></small> <strong><%value%></strong></p>';

			break;
		case 'avatar':
			if($handle == 'data')
			{
				$avatars = ORM::factory('Avatar')
					->where('title', 'LIKE', '%'.$term.'%')
					->find_all();

				if($avatars->count() > 0)
				{
					foreach($avatars as $avatar)
					{
						$return[] = array('id' => $avatar->id, 'value' => $avatar->title, 'img' => $avatar->img());
					}
				}
			}
			else
			{
				return '<div class="row"><div class="col-sm-2"><img src="<%img%>" width="20" height="20"/></div><div class="col-sm-8">#<%id%> <%value%></div></div>';
			}

			break;
	}

	if(count($return) == 0)
		return null;

	return $return;
});

Plug::listen('admin.user.tabs', function($user) {
	return [
		[
			'title' => 'Notes',
			'id' => 'tab-notes',
			'permissions' => ['submit', 'refresh'],
			'render' => function() use ($user) {
					$tpl = new View_Admin_User_Tab_Notes;
					$tpl->user = $user;
					$tpl->notes = ORM::factory('User_Note')->where('user_id', '=', $user->id)->find_all();
					$tpl->routes = [
						'submit' => Route::url('admin.user.tab.notes.add', ['id' => $user->id], true),
						'refresh' => Route::url('admin.user.tab.notes.refresh', ['id' => $user->id], true)
					];

					return $tpl;
				},
			'assets' => [
				'js' => ['plugins/jquery.slimscroll.js', 'admin/users/tab/notes.js']
			]
		],
		[
			'title' => 'Avatars',
			'id' => 'tab-avatars',
			'permissions' => ['load'],
			'render' => function() use ($user) {
					$tpl = new View_Admin_User_Tab_Avatars;
					$tpl->user = $user;
					$tpl->avatars = ORM::factory('Avatar')->where('default', '=', '0')->find_all();
					$tpl->submit_link = Route::url('admin.user.tab.avatars', array('id' => $user->id), true);

					return $tpl;
				},
			'assets' => [
				'js' => 'admin/users/tab/avatars.js'
			]
		],
		[
			'title' => 'Logs',
			'id' => 'tab-logs',
			'permissions' => ['load'],
			'render' => function() use ($user) {
					$tpl = new View_Admin_User_Tab_Logs;
					$tpl->user = $user;

					$tpl->routes = [
						'modal' => Route::url('admin.user.tab.logs.modal', array('id' => 0), true)
					];

					$table = new Table();
					Plug::fire('admin.user.tab.logs', [$table]);

					$tpl->table = $table->template_table($user->id);
					return $tpl;
				},
			'assets' => [
				'set' => ['datatables', 'datepicker'],
				'js' => [Route::url('admin.user.tab.logs.js', null, true), 'admin/users/tab/logs.js']
			]
		],
	];
});

Plug::listen('admin.user.tab.logs', function(Kohana_Table $table){
	$table->name('logs');
	$table->show_buttons(true);

	$table->add_column('alias', ['head' => 'Alias']);

	// Show the log's creation date
	$table->add_column('date', ['head' => 'Date', 'field' => 'time', 'retrieve' => function($model){
			return date('Y-m-d', $model->time);
		}]);

	// Show the log's creation time
	$table->add_column('time', ['head' => 'Time', 'retrieve' => function($model){
			return date('h:i:s a', $model->time);
		}]);

	// If the log has an other_user defined in params, show username
	$table->add_column('params', ['head' => 'Other user', 'retrieve' => function($model){
			if(isset($model->params[':other_username']))
			{
				return $model->params[':other_username'];
			}
			else if(isset($model->params[':other_user_id']))
			{
				$o_user = ORM::factory('User', $model->params[':other_user_id']);
				return $o_user->username;
			}
			return '';
		}]);

	$table->remove_button('edit')
		->remove_button('remove')
		->add_button('show', 'fa-search-plus', 'primary');

	return $table;
});

Plug::listen('admin.task.stats', function($cache){
	$cache->set('stats.registrations', [
			'today' => ORM::factory('User')->where('created_at', '<', strtotime('-1 day'))->count_all(),
			'last_week' => ORM::factory('User')->where('created_at', '<', strtotime('-1 week'))->count_all(),
			'last_month' => ORM::factory('User')->where('created_at', '<', strtotime('-1 month'))->count_all()
		], 60*60*24);

	Minion_CLI::write('User stats were cached.');
});

Plug::listen('admin.dashboard.stats', function($cache){
	$registrations = $cache->get('stats.registrations', ['today' => 'n/a', 'last_week' => 'n/a', 'last_month' => 'n/a']);

	return [
		'title' => 'Registrations',
		'id' => 'stat-users',
		'icon' => 'fa fa-user',
		'items' => [
			[
				'title' => 'Registrations today',
				'value' => $registrations['today']
			],
			[
				'title' => 'Registrations last week',
				'value' => $registrations['last_week']
			],
			[
				'title' => 'Registrations last month',
				'value' => $registrations['last_month']
			]
		]
	];
});
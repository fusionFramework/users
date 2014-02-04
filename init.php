<?php defined('SYSPATH') OR die('No direct script access.');
class_alias('\Cartalyst\Sentry\Facades\Kohana\Sentry', 'Sentry');

Route::set('user.register', 'user/register')
	->defaults(array(
	'controller' => 'User',
	'action'     => 'register',
	)
);
Route::set('user.activate', 'user/activate/<username>/<code>')
	->defaults(array(
		'controller' => 'User',
		'action'     => 'activate',
		'code' => null
	)
);
Route::set('user.login', 'user/login')
	->defaults(array(
		'controller' => 'User',
		'action'     => 'login',
	)
);
Route::set('user.logout', 'user/logout')
	->defaults(array(
		'controller' => 'User',
		'action'     => 'logout',
	)
);
Route::set('user.reset_valid', 'user/reset/validate/<username>/<code>')
	->defaults(array(
		'controller' => 'User',
		'action'     => 'reset_validate',
	)
);
Route::set('user.reset', 'user/reset')
	->defaults(array(
		'controller' => 'User',
		'action'     => 'reset',
	)
);

Route::set('user.profile', 'u/<name>', array('name' => '([-a-zA-Z0-9_]+)'))
	->defaults(array(
		'controller' => 'User',
		'action'     => 'profile',
	)
);

// User settings
Plug::listen('user.settings.nav', function(){
	return array(
		'account' => array(
			'title' => 'Account',
			'route' => Route::url('user.settings')
		),
		'prefs' => array(
			'title' => 'Preferences',
			'route' => Route::url('user.settings.prefs')
		)
	);
});

Route::set('user.settings', 'settings')
	->defaults(array(
		'controller' => 'Settings',
		'action'     => 'index',
	)
);
Route::set('user.settings.account.submit', 'settings/account/submit')
	->defaults(array(
		'controller' => 'Settings',
		'action'     => 'account_submit',
	)
);
Route::set('user.settings.prefs', 'settings/preferences')
	->defaults(array(
		'controller' => 'Settings',
		'action'     => 'preferences',
	)
);
Route::set('user.settings.prefs.submit', 'settings/preferences/submit')
	->defaults(array(
		'controller' => 'Settings',
		'action'     => 'preferences_submit',
	)
);


Route::set('admin.user.index', 'admin/user')
	->defaults(array(
			'controller' => 'Admin_User',
			'action'     => 'index',
		)
	);
Route::set('admin.user.search', 'admin/user/search')
	->defaults(array(
			'controller' => 'Admin_User',
			'action'     => 'search',
		)
	);
Route::set('admin.user.view', 'admin/user/view/<id>', array('id' => '([0-9]+)'))
	->defaults(array(
			'controller' => 'Admin_User',
			'action'     => 'view',
		)
	);
Route::set('admin.user.edit', 'admin/user/edit/<id>', array('id' => '([0-9]+)'))
	->defaults(array(
			'controller' => 'Admin_User',
			'action'     => 'edit',
		)
	);

/**
 * User tab routes
 */
Route::set('admin.user.tab.avatars', 'admin/user/tab/avatars/<id>', array('id' => '([0-9]+)'))
	->defaults(array(
			'controller' => 'Admin_Tab_User',
			'action'     => 'avatars',
		)
	);

Route::set('admin.user.tab.logs.fill', 'admin/user/tab/logs/fill(/<id>)', array('id' => '([0-9]+)'))
	->defaults(array(
			'controller' => 'Admin_Tab_User',
			'action'     => 'log_fill',
		)
	);
Route::set('admin.user.tab.logs.js', 'admin/user/tab/logs.js')
	->defaults(array(
			'controller' => 'Admin_Tab_User',
			'action'     => 'log_js',
		)
	);
Route::set('admin.user.tab.logs.modal', 'admin/user/tab/logs/modal/<id>', array('id' => '([0-9]+)'))
	->defaults(array(
			'controller' => 'Admin_Tab_User',
			'action'     => 'log_modal',
		)
	);

Route::set('admin.user.tab.notes.add', 'admin/user/tab/notes/add/<id>', array('id' => '([0-9]+)'))
	->defaults(array(
			'controller' => 'Admin_Tab_User',
			'action'     => 'create_note',
		)
	);
Route::set('admin.user.tab.notes.refresh', 'admin/user/tab/notes/refresh/<id>', array('id' => '([0-9]+)'))
	->defaults(array(
			'controller' => 'Admin_Tab_User',
			'action'     => 'refresh_notes',
		)
	);
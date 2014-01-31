<?php 

/**
 *	User avatars admin routes
 */
//set the js file route
Route::set('admin.user.avatars.js', 'admin/user/avatars/table.js')
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action'     => 'js',
		'master'     => 'Admin_User_Avatars'
	)
);

//set the actions js file route
Route::set('admin.user.avatars.actions.js', 'admin/user/avatars/actions.js')
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action'     => 'js_actions',
		'master'     => 'Admin_User_Avatars',
	)
);

//set the fill table route
Route::set('admin.user.avatars.fill', 'admin/user/avatars/fill(/<id>)', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'fill_table',
		'id' => 0,
		'master' => 'Admin_User_Avatars'
	)
);

//set the delete record route
Route::set('admin.user.avatars.remove', 'admin/user/avatars/<id>/remove', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'remove',
		'master' => 'Admin_User_Avatars'
	)
);


//set the record history route
Route::set('admin.user.avatars.history', 'admin/user/avatars/<id>/history', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'history',
		'master' => 'Admin_User_Avatars'
	)
);

//set the upload route
Route::set('admin.user.avatars.upload', 'admin/user/avatars/upload')
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'upload',
		'master' => 'Admin_User_Avatars'
	)
);

//set the load record route
Route::set('admin.user.avatars.modal', 'admin/user/avatars/<id>/load', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'modal',
		'master' => 'Admin_User_Avatars'
	)
);

//set the save record route
Route::set('admin.user.avatars.save', 'admin/user/avatars/save')
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'save',
		'master' => 'Admin_User_Avatars'
	)
);


//set the index route
Route::set('admin.user.avatars.index', 'admin/user/avatars(/<id>)', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'table',
		'id' => null,
		'master' => 'Admin_User_Avatars'
	)
);

/**
 *	User groups admin routes
 */
//set the js file route
Route::set('admin.user.groups.js', 'admin/user/groups/table.js')
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action'     => 'js',
		'master'     => 'Admin_User_Groups'
	)
);

//set the actions js file route
Route::set('admin.user.groups.actions.js', 'admin/user/groups/actions.js')
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action'     => 'js_actions',
		'master'     => 'Admin_User_Groups',
	)
);

//set the fill table route
Route::set('admin.user.groups.fill', 'admin/user/groups/fill(/<id>)', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'fill_table',
		'id' => 0,
		'master' => 'Admin_User_Groups'
	)
);

//set the delete record route
Route::set('admin.user.groups.remove', 'admin/user/groups/<id>/remove', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'remove',
		'master' => 'Admin_User_Groups'
	)
);


//set the record history route
Route::set('admin.user.groups.history', 'admin/user/groups/<id>/history', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'history',
		'master' => 'Admin_User_Groups'
	)
);


//set the load record route
Route::set('admin.user.groups.modal', 'admin/user/groups/<id>/load', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'modal',
		'master' => 'Admin_User_Groups'
	)
);

//set the save record route
Route::set('admin.user.groups.save', 'admin/user/groups/save')
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'save',
		'master' => 'Admin_User_Groups'
	)
);


//set the index route
Route::set('admin.user.groups.index', 'admin/user/groups(/<id>)', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'table',
		'id' => null,
		'master' => 'Admin_User_Groups'
	)
);

/**
 *	User notifications admin routes
 */
//set the js file route
Route::set('admin.user.notifications.js', 'admin/user/notifications/table.js')
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action'     => 'js',
		'master'     => 'Admin_User_Notifications'
	)
);

//set the actions js file route
Route::set('admin.user.notifications.actions.js', 'admin/user/notifications/actions.js')
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action'     => 'js_actions',
		'master'     => 'Admin_User_Notifications',
	)
);

//set the fill table route
Route::set('admin.user.notifications.fill', 'admin/user/notifications/fill(/<id>)', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'fill_table',
		'id' => 0,
		'master' => 'Admin_User_Notifications'
	)
);

//set the delete record route
Route::set('admin.user.notifications.remove', 'admin/user/notifications/<id>/remove', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'remove',
		'master' => 'Admin_User_Notifications'
	)
);


//set the record history route
Route::set('admin.user.notifications.history', 'admin/user/notifications/<id>/history', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'history',
		'master' => 'Admin_User_Notifications'
	)
);

//set the upload route
Route::set('admin.user.notifications.upload', 'admin/user/notifications/upload')
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'upload',
		'master' => 'Admin_User_Notifications'
	)
);

//set the load record route
Route::set('admin.user.notifications.modal', 'admin/user/notifications/<id>/load', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'modal',
		'master' => 'Admin_User_Notifications'
	)
);

//set the save record route
Route::set('admin.user.notifications.save', 'admin/user/notifications/save')
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'save',
		'master' => 'Admin_User_Notifications'
	)
);


//set the index route
Route::set('admin.user.notifications.index', 'admin/user/notifications(/<id>)', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'table',
		'id' => null,
		'master' => 'Admin_User_Notifications'
	)
);
<?php defined('SYSPATH') OR die('No direct script access.');

Plug::listen('admin.task.stats', function($cache){
	$cache->set('stats.registrations', [
			'today' => ORM::factory('User')->where('created_at', '<', strtotime('-1 day'))->count_all(),
			'last_week' => ORM::factory('User')->where('created_at', '<', strtotime('-1 week'))->count_all(),
			'last_month' => ORM::factory('User')->where('created_at', '<', strtotime('-1 month'))->count_all()
		], 60*60*24);

	Minion_CLI::write('User stats were cached.');
});
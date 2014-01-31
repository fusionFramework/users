<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Generate permission files for your user admin tab definitions.
 *
 * @package    fusionFramework
 * @category   Admin
 * @author     Maxim Kerstens
 */
class Task_Admin_Tabs extends Minion_Task
{
	protected function _execute(array $params)
	{
		$dirs = new DirectoryIterator(FUSIONPATH);
		foreach ($dirs as $fileInfo) {
			if($fileInfo->isDir())
			{
				$admin = $fileInfo->getRealPath().DIRECTORY_SEPARATOR.'module'.DIRECTORY_SEPARATOR.'admin.php';
				if(file_exists($admin))
				{

					require_once $admin;
				}
			}
		}

		$provided_tabs = array_reverse(Plug::fire('admin.user.tabs', array(ORM::factory('User', 1))));
		$tabs = [];
		$perms = [];

		foreach($provided_tabs as $tab)
		{
			$tabs = array_merge($tabs, $tab);
		}

		$cfg = new Config();
		$cfg = $cfg->attach(new Config_File('permissions'))->load('admin');

		// empty the config object
		foreach($cfg->as_array() as $key => $value)
		{
			unset($cfg[$key]);
		}

		$original_perms = Kohana::load(FUSIONPATH.'user' .DIRECTORY_SEPARATOR.'permissions'.DIRECTORY_SEPARATOR.'admin'.EXT);

		foreach($original_perms as $key => $content)
		{
			if($key == 'user')
			{
				$perms = $content;
			}
			else
			{
				$cfg->set($key, $content);
			}
		}

		foreach($tabs as $ind => $tab)
		{
			if(isset($tab['permissions']))
			{
				foreach($tab['permissions'] as $p)
				{
					if(!in_array($tab['id'].'.'.$p, $perms))
					{
						$perms[] = $tab['id'].'.'.$p;
						Minion_CLI::write('admin.user.'.$tab['id'].'.'.$p. ' was added to permissions');
					}
				}
			}
			if(!in_array($tab['id'], $perms))
			{
				$perms[] = $tab['id'];
				Minion_CLI::write('admin.user.'.$tab['id']. ' was added to permissions');
			}
		}

		$cfg->set('user', $perms);


		$cfg->export(FUSIONPATH.'user'.DIRECTORY_SEPARATOR.'permissions'.DIRECTORY_SEPARATOR);
		Minion_CLI::write('Permissions were saved.');
	}
}
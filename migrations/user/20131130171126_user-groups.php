<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Fill user groups with starter groups
 */
class Migration_User_20131130171126 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		$db->query(NULL, "INSERT INTO `groups` VALUES
			(1, 'user', '[]', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
			(2, 'owner', '[]', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
			(3, 'programmer', '[]', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
			(4, 'artist', '[]', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
			(5, 'moderator', '[]', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
			(6, 'writer', '[]', '0000-00-00 00:00:00', '0000-00-00 00:00:00');");

		// Give the owner all existing permissions
		$owner = ORM::factory('Group', 2);
		$owner->permissions = Permissions::instance()->all();
		$owner->save();
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		$db->query(NULL, 'DELETE FROM `groups` WHERE id IN (1, 2, 3, 4, 5, 6);');
	}

}

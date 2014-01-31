<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * User avatars
 */
class Migration_User_20131116171126 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		$db->query(NULL, "CREATE TABLE IF NOT EXISTS `avatars` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `title` varchar(30) NOT NULL,
			  `img` varchar(120) NOT NULL,
			  `status` ENUM(  'active',  'retired',  'draft' ) NOT NULL DEFAULT  'draft',
			  `default` tinyint(1) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

		$db->query(NULL, "CREATE TABLE IF NOT EXISTS `users_avatars` (
		  `user_id` int(11) unsigned NOT NULL,
		  `avatar_id` int(11) unsigned NOT NULL,
		  PRIMARY KEY (`user_id`,`avatar_id`),
		  KEY `k_user` (`user_id`),
		  KEY `k_avatar` (`avatar_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

		$db->query(NULL, "ALTER TABLE `users_avatars`
		  ADD CONSTRAINT `users_avatars_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
		  ADD CONSTRAINT `users_avatars_ibfk_2` FOREIGN KEY (`avatar_id`) REFERENCES `avatars` (`id`) ON DELETE CASCADE;");
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		$db->query(NULL, 'DROP TABLE `avatars`;');
		$db->query(NULL, 'DROP TABLE `users_avatars`;');
	}

}

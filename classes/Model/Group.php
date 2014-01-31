<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Group model
 *
 * @package    fusionFramework/user
 * @category   Model
 * @author     Maxim Kerstens
 * @copyright  (c) happydemon.org
 */
class Model_Group extends \Cartalyst\Sentry\Groups\Kohana\Group {
	use Formo_ORM;

	protected $_primary_val = 'name';

	/**
	 * Define form fields based on model properties.
	 *
	 * @param Formo $form
	 */
	public function formo(Formo $form)
	{
		if($form->find('name') != null)
		{
			$form->name->set('label', 'name')
				->set('driver', 'input');
		}

		if($form->find('permissions') != null)
		{
			$form->permissions->set('label', 'Permissions')
				->set('driver', 'transfer');
		}
	}

	/**
	 * Used to represent in belongs_to relations when changes are tracked
	 * @return bool|string
	 */
	public function candidate_key()
	{
		if (!$this->loaded()) return FALSE;
		return $this->name;
	}

} // End Group Model
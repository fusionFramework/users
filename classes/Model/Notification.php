<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Notification model
 *
 * @package    fusionFramework/user
 * @category   Model
 * @author     Maxim Kerstens
 * @copyright  (c) happydemon.org
 */
class Model_Notification extends ORM {

	use Formo_ORM;
	protected $_primary_val = 'title';

	public function rules()
	{
		return array(
			'alias' => array(
				array('not_empty'),
				array('max_length', array(':value', 120)),
				array('min_length', array(':value', 4)),
			),
			'icon' => array(
				array('not_empty'),
				array('max_length', array(':value', 80)),
			),
			'title' => array(
				array('max_length', array(':value', 48)),
			),
			'message' => array(
				array('max_length', array(':value', 500)),
			),
			'url' => array(
				array('max_length', array(':value', 255)),
			),
		);
	}

	public function icon()
	{
		return URL::site('m/notifications/'.$this->img, true, false);
	}

	/**
	 * Define form fields based on model properties.
	 *
	 * @param Formo $form
	 */
	public function formo(Formo $form)
	{
		if($form->find('title') != null)
		{
			$form->title->set('label', 'Title')
				->set('driver', 'input')
				->set('attr.class', 'form-control');
		}

		if($form->find('alias') != null)
		{
			$form->alias->set('label', 'Alias')
				->set('driver', 'input')
				->set('attr.class', 'form-control');
		}

		if($form->find('icon') != null)
		{
			$form->icon->set('label', 'Icon')
				->set('driver', 'image')
				->set('attr.class', 'form-control')
				->set('dim', ['width' => Kohana::$config->load('notifications.width'), 'height' => Kohana::$config->load('notifications.height')]);

		}

		if($form->find('url') != null)
		{
			$form->url->set('label', 'URL')
				->set('driver', 'input')
				->set('attr.class', 'form-control');
		}

		if($form->find('message') != null)
		{
			$form->message->set('label', 'Message')
				->set('driver', 'textarea')
				->set('attr.class', 'form-control');
		}
	}

	/**
	 * Used to represent in belongs_to relations when changes are tracked
	 * @return bool|string
	 */
	public function candidate_key()
	{
		if (!$this->loaded()) return FALSE;
		return $this->alias;
	}

} // End Notification Model

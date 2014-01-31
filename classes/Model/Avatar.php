<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Avatar model
 *
 * @package    fusionFramework/user
 * @category   Model
 * @author     Maxim Kerstens
 * @copyright  (c) happydemon.org
 */
class Model_Avatar extends ORM {

	use Formo_ORM;
	protected $_primary_val = 'title';

	protected $_belongs_to = array(
		'user' => array(
			'model' => 'User',
		)
	);

	public function rules()
	{
		return array(
			'title' => array(
				array('not_empty'),
				array('max_length', array(':value', 30)),
				array('min_length', array(':value', 4)),
			),
			'img' => array(
				array('not_empty'),
				array('max_length', array(':value', 120)),
			),
			'default' => array(
				array('in_array', array(':value', array(0,1))),
			),
		);
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

		if($form->find('img') != null)
		{
			$form->img->set('label', 'Image')
				->set('driver', 'image')
				->set('attr.class', 'form-control')
				->set('dim', ['width' => Kohana::$config->load('avatar.width'), 'height' => Kohana::$config->load('avatar.height')]);

		}

		if($form->find('default') != null)
		{
			$form->default->set('label', 'Default?')
				->set('driver', 'radios')
				->set('opts', array (
					'0' => 'Yes',
					'1' => 'No'
				))
				->set('attr.class', 'form-control');
		}

		if($form->find('status') != null)
		{
			$form->status->set('label', 'Status')
				->set('driver', 'radios')
				->set('opts', array (
					'active' => 'Active',
					'retired' => 'Retired',
					'draft' => 'Draft'
				))
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
		return $this->title;
	}

	/**
	 * @return string URL to avatar image
	 */
	public function img()
	{
		return URL::site('m/avatars/'.$this->img, true, false);
	}

} // End Avatar Model

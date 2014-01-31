<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Admin tab controller base
 *
 * @package    fusionFramework
 * @category   Admin/User
 * @author     Maxim Kerstens
 * @copyright  (c) 2013-2014 Maxim Kerstens
 * @license    BSD
 */
abstract class Controller_Admin_Tab extends Controller_Fusion_Admin {
	public function after()
	{
		if (Fusion::$user != null)
		{
			if(!$this->request->is_ajax() && is_object($this->_tpl))
			{
				$renderer = Kostache_Layout::factory();
				$renderer->set_layout('empty');
				$this->response->body($renderer->render($this->_tpl));
			}
		}

		parent::after();
	}

} // End Admin Tab base

<?php defined('SYSPATH' OR die('No direct access allowed.'));
/**
 * Minimalistic menu config example.
 * Renders a simple list (<li>) of links.
 *
 * @see https://github.com/anroots/kohana-menu/wiki/Configuration-files
 * @author Ando Roots <ando@sqroot.eu>
 */
return array(
	'items'             => array(
		array(
			'route'   => 'forum',
			'title'   => 'Forum',
			'icon'    => 'icon-comment-alt',
			'items' => array(
				array(
					'route'     => 'forum.category',
					'title'   => null,
					'param' => array('id' => null, 'page' => null),
					'items' => array(
						array(
							'route'     => 'forum.topic',
							'title'   => null,
							'param' => array('id' => null, 'topic' => null),
							'items' => array(
								array(
									'route'     => 'forum.topic.reply',
									'title'   => null,
									'param' => array('id' => null, 'topic' => null),
								),
								array(
									'route'     => 'forum.topic.edit',
									'title'   => null,
									'param' => array('id' => null, 'topic' => null),
								),
								array(
									'route'     => 'forum.topic.reply.edit',
									'title'   => null,
									'param' => array('id' => null, 'topic' => null),
								)
							)
						),
						array(
							'route'     => 'forum.topic.create',
							'title'   => null,
							'param' => array('id' => null),
						)
					)
				)
			)
		)
	)
);
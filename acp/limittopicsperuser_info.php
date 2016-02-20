<?php
/**
*
* @package Limit Topics Per User
* @copyright (c) 2015 frankingermann (info@frankingermann.de)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace frankingermann\limittopicsperuser\acp;

class limittopicsperuser_info
{
	function module()
	{
		return array(
			'filename'	=> '\frankingermann\limittopicsperuser\acp\limittopicsperuser_module',
			'title'		=> 'LIMIT_TOPICS_PER_USER',
			'modes'		=> array(
				'main'	=> array('title' => 'LTPU_SETTINGS', 
								'auth' => 'ext_frankingermann/limittopicsperuser && acl_a_board', 
								'cat' => array('LIMIT_TOPICS_PER_USER')),
			),
		);
	}
}

<?php
/**
*
* @package Limit Topics Per User
* @copyright (c) 2015 frankingermann (info@frankingermann.de)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace frankingermann\limittopicsperuser\acp;

class limittopicsperuser_module
{
	public $u_action;

	function main($id, $mode)
	{
		global $phpbb_container, $user;

		$this->tpl_name		= 'limittopicsperuser';
		$this->page_title	= $user->lang('LIMIT_TOPICS_PER_USER');

		// Get an instance of the admin controller
		$admin_controller = $phpbb_container->get('frankingermann.limittopicsperuser.admin.controller');

		// Make the $u_action url available in the admin controller
		$admin_controller->set_page_url($this->u_action);

		$admin_controller->display_options();
	}
}

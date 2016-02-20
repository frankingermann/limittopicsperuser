<?php
/**
*
* @package Limit Topics Per User
* @copyright (c) 2015 frankingermann (info@frankingermann.de)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace frankingermann\limittopicsperuser\migrations;

class version_0_9_0 extends \phpbb\db\migration\migration
{
	public function update_data()
	{
		return array(
			// all parameters that go into the phpbb_config table (short ones, max 252 chars)
			array('config.add', array('ltpu_enable', '0')),
			array('config.add', array('ltpu_per_day_check', '0')),
			array('config.add', array('ltpu_per_week_check', '0')),
			array('config.add', array('ltpu_per_month_check', '0')),
			array('config.add', array('ltpu_per_day_settings', '')),
			array('config.add', array('ltpu_per_week_settings', '')),
			array('config.add', array('ltpu_per_month_settings', '')),
			array('config.add', array('ltpu_excluded_rank_ids', '')),
			array('config.add', array('ltpu_included_group_ids', '')),

			// Add the main ACP module
			array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'LIMIT_TOPICS_PER_USER')),

			// Add sub-entries to main ACP module
			array('module.add', array(
				'acp', 'LIMIT_TOPICS_PER_USER', array(
					'module_basename'	=> '\frankingermann\limittopicsperuser\acp\limittopicsperuser_module',
					'modes'				=> array('main'),
				),
			)),
		);
	}
}

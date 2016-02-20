<?php
/**
*
* @package Limit Topics Per User
* @copyright (c) 2015 frankingermann (info@frankingermann.de)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace frankingermann\limittopicsperuser\controller;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* Admin controller
*/
class admin_controller implements admin_interface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var ContainerInterface */
	protected $container;

	/** @var string Custom form action */
	protected $u_action;

	/**
	* Constructor for admin controller
	*
	* @param \phpbb\config\config		$config		Config object
	* @param \phpbb\request\request		$request	Request object
	* @param \phpbb\template\template	$template	Template object
	* @param \phpbb\user				$user		User object
	* @param ContainerInterface			$container	Service container interface
	* @access public
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user, ContainerInterface $container)
	{
		$this->config		= $config;
		$this->request		= $request;
		$this->template		= $template;
		$this->user			= $user;
		$this->container	= $container;
	}

	/**
	* Display the options a user can configure for this extension
	*
	* @return null
	* @access public
	*/
	public function display_options()
	{
		// Create a form key for preventing CSRF attacks
		$form_key = 'limittopicsperuser';
		add_form_key($form_key);

		$this->config_text = $this->container->get('config_text');

		// Is the form being submitted
		if ($this->request->is_set_post('submit'))
		{
			// Is the submitted form valid
			if (!check_form_key($form_key))
			{
				trigger_error($this->user->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			// If no errors, process the form data
			// Set the options the user configured
			$this->set_options();

			// Add option settings change action to the admin log
			$phpbb_log = $this->container->get('log');
			$phpbb_log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LTPU_LOG');

			// Option settings have been updated and logged
			// Confirm this to the user and provide link back to previous page
			trigger_error($this->user->lang('CONFIG_UPDATED') . adm_back_link($this->u_action));
		}

		// ToDo: provide a better way to find out the forum IDs. Maybe a tooltip hint
		// on the edit fields or the like?
		
		// Set output vars for display in the template
		$this->template->assign_vars(array(
			'LTPU_ENABLE'				=> isset($this->config['ltpu_enable']) ? $this->config['ltpu_enable'] : '',
			'LTPU_PER_DAY_CHECK'		=> isset($this->config['ltpu_per_day_check']) ? $this->config['ltpu_per_day_check'] : '',
			'LTPU_PER_DAY_SETTINGS'		=> isset($this->config['ltpu_per_day_settings']) ? $this->config['ltpu_per_day_settings'] : '',
			'LTPU_PER_WEEK_CHECK'		=> isset($this->config['ltpu_per_week_check']) ? $this->config['ltpu_per_week_check'] : '',
			'LTPU_PER_WEEK_SETTINGS'	=> isset($this->config['ltpu_per_week_settings']) ? $this->config['ltpu_per_week_settings'] : '',
			'LTPU_PER_MONTH_CHECK'		=> isset($this->config['ltpu_per_month_check']) ? $this->config['ltpu_per_month_check'] : '',
			'LTPU_PER_MONTH_SETTINGS'	=> isset($this->config['ltpu_per_month_settings']) ? $this->config['ltpu_per_month_settings'] : '',
			'LTPU_EXCLUDED_RANK_IDS'	=> isset($this->config['ltpu_excluded_rank_ids']) ? $this->config['ltpu_excluded_rank_ids'] : '',
			'LTPU_INCLUDED_GROUP_IDS'	=> isset($this->config['ltpu_included_group_ids']) ? $this->config['ltpu_included_group_ids'] : '',
			'U_ACTION' 					=> $this->u_action,
		));
	}

	/**
	* Set the options a user can configure
	*
	* @return null
	* @access protected
	*/
	protected function set_options()
	{
		$this->config->set('ltpu_enable', $this->request->variable('ltpu_enable', 0));
		$this->config->set('ltpu_per_day_check', $this->request->variable('ltpu_per_day_check', 0));
		$this->config->set('ltpu_per_day_settings', $this->request->variable('ltpu_per_day_settings', ''));
		$this->config->set('ltpu_per_week_check', $this->request->variable('ltpu_per_week_check', 0));
		$this->config->set('ltpu_per_week_settings', $this->request->variable('ltpu_per_week_settings', ''));
		$this->config->set('ltpu_per_month_check', $this->request->variable('ltpu_per_month_check', 0));
		$this->config->set('ltpu_per_month_settings', $this->request->variable('ltpu_per_month_settings', ''));
		$this->config->set('ltpu_excluded_rank_ids', $this->request->variable('ltpu_excluded_rank_ids', ''));
		$this->config->set('ltpu_included_group_ids', $this->request->variable('ltpu_included_group_ids', ''));
	}


	/**
	* Set page url
	*
	* @param string $u_action Custom form action
	* @return null
	* @access public
	*/
	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
	}
}

<?php
/*
*
* @package Limit Topics Per User
* @copyright (c) frankingermann (info@frankingermann.de)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* 
*/

namespace frankingermann\limittopicsperuser\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var ContainerInterface */
	protected $container;

	/** @var string Custom form action */
	protected $u_action;

	/**
	* Constructor for listener
	*
	* @param \phpbb\config\config				$config		Config object
	* @param \phpbb\template\template			$template	Template object
	* @param \phpbb\auth\auth 					$auth
	* @param \phpbb\user						$user		User object
	* @param \phpbb\db\driver\driver_interface	$db
	* @param ContainerInterface					$container	Service container interface
	* @access public
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\template\template $template, \phpbb\auth\auth $auth, \phpbb\user $user, \phpbb\db\driver\driver_interface $db, $container)
	{
		$this->config		= $config;
		$this->template		= $template;
		$this->auth			= $auth;
		$this->user			= $user;
		$this->db			= $db;
		$this->container	= $container;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		// 'core.modify_posting_parameters' is to early, it's before
		// the user setup so load_language doesn't get called there.
		
		return array(
			'core.modify_posting_auth'	=> 'check_limit_topics_per_user',
			'core.user_setup' => 'load_language',
		);
	}

	/**
	* load the language file only if we may need it
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	
	public function load_language($event)
	{
		// only load the language file if there's any chance we may need it:
		// are we in posting.php? do we have a registered user ?
		// is the extension configured to do anything?
		
		$data = $event->get_data();
		
		if (( ($data['lang_set']) && ($data['lang_set'][0]=='posting')) &&
			( $this->user->data['is_registered'] ) &&
			( $this->config['ltpu_enable'] ) &&					
		 	(	($this->config['ltpu_per_day_check']) || 
		     	($this->config['ltpu_per_week_check']) || 
				($this->config['ltpu_per_month_check']) ) )
		{			
			$this->user->add_lang_ext('frankingermann/limittopicsperuser', 'common');
		}	
		return;
	}
	
	/**
	* Check whether a rule applies to the forum the user is trying to start a new topic in. 
	* If so, construct "WHERE forum_id IN <forumids>" clause.
	*
	* @param object $event The event object
	* @return array 
	* @access public
	*/
	public function check_limit_rules_to_apply($event, $forum_id, $settings)
	{
		$out['do_check'] = false;
		$out['forum_ids_to_check'] = '';
		$out['max_topics'] = 0;
		
		// settings rule parser: example setting: "1+2:10; 3:5" (that's _two_ rules)
		
		$max_topics_for_all = 0;
		$forum_match_found = false;
		$catch_all_match_found = false;
		
		if (trim($settings)>'') // empty settings never apply
		{
			$rules = explode(";",trim($settings)); // "1+2,3:10; 3:5" --> [1+2,3:10] [3:5]
			
			foreach ($rules as $rule)   // [1+2,3:10] [3:5]
			{ 
			
				$rule_parts = explode(":",trim($rule));		   		// rule_parts: [1+2,3] [10]
				
				$rule_forum_parts = explode(",",trim($rule_parts[0])); // [1+2] [3]
				
				foreach ($rule_forum_parts as $rule_forum_part)
				{
					$rule_forums = explode("+",trim($rule_forum_part)); 	 // rule_forums: [1] [2]
					$max_topics_for_rule = intval(trim($rule_parts[1])); // 10 ...or [10] ?? does intval() help here??
					
					foreach ($rule_forums as $rule_forum) 
					{
						$rule_forum = trim($rule_forum);
						
						// a rule like "*:5" sets a "catch all" rule for ALL forums:
						if ($rule_forum == '*') 
						{
							$max_topics_for_all = $max_topics_for_rule;
							$catch_all_match_found = true;
						}
							
						if ($rule_forum == $forum_id)	// gotcha - found a rule to apply to this forum
						{  
							$forum_match_found = true;
							
							$out['do_check'] = true;
							
							$out['max_topics'] = $max_topics_for_rule; 
							
							// make IN-clause from all forum-ids to check, so [1+2] becomes "1,2"
							$out['forum_ids_to_check'] = implode(",",$rule_forums);
							
							return $out;
							
							// there's "room for improvement" here: currently only the first matched 
							// rule just "wins". Should the same forum id be present in any OTHER rules
							// as well, these will currently simply be ignored (!)
						}
					}
				}
			}	
		}
		
		// if we dind't find a specific setting for this forum, but a "*" setting for all: use that
		if ( ($catch_all_match_found) && (!$forum_match_found) )
		{
			$out['do_check'] = true;						
			$out['max_topics'] = $max_topics_for_all; 		
            $out['forum_ids_to_check'] = $forum_id;
		}

		return $out;
	}
	
	/**
	* count the topics a user started in (a combination of) forum_id(s) since date $since
	*
	* @param object $event The event object
	* @return number of posted topics
	* @access public
	*/
	
	public function count_topics_by_user_since($event, $forum_ids_to_check, $user_id, $since)
	{
		$sql = "SELECT count(distinct topic_id) as topic_count FROM ". TOPICS_TABLE. 
				" WHERE forum_id IN (".$forum_ids_to_check.")".
				" AND `topic_poster` =" .$user_id.
				" AND `topic_time` > " .$since; //.";";
			
		$result = $this->db->sql_query($sql);
		
		if ( !($result) )
		{
			trigger_error('Could not obtain topics this month count for this forum. sql='.$sql);
		}

		$row = $this->db->sql_fetchrow($result);
		$topic_count = $row['topic_count'];
		
		return $topic_count;	
	}
	
	
	/**
	* Perform checks for day, week or month ($time_mode)
	*
	* @param object $event The event object, $forum_id, $user_id, $time_mode, $settings, $since, &$results
	* @return array
	* @access public
	*/
	
    public function do_timeframed_check($event, $forum_id, $user_id, $time_mode, $settings, $since, &$results)
	{
		$check_ary = $this->check_limit_rules_to_apply($event, $forum_id, $settings);
	  			
		if ($check_ary['do_check'])
		{
			$forum_ids_to_check = $check_ary['forum_ids_to_check'];
			$max_topics = intval($check_ary['max_topics']);
	  
			$topic_count = intval( $this->count_topics_by_user_since($event, $forum_ids_to_check, $user_id, $since) );
	  
			if ($topic_count >= $max_topics) 
			{				
				$results['time_mode'] = $time_mode;
				$results['limit_hit'] = $max_topics;
				
				return false;
			}
		}
		return true;
	}

	/**
	* Check topics per user limit (main event handler function)
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function check_limit_topics_per_user($event)
	{
		// it's all about creating new topics here - if the "mode" is anything else than "post",
		// let the core engine handle it and simply return:
		
		if ( ( !($event['mode']=='post') ) ||				
		     ( !$this->user->data['is_registered'] ) || // not a registered user? we can't check anything as we can't count posts for guests PER GUEST
			 ( !$this->config['ltpu_enable'] )) // is checking globally disabled? - if so: nothing to do, just return.
		{
			return;
		}					
		
		// Ok, global checking is ENabled. Is any of the time-bound checks enabled?
		// In case not: there's nothing to do here, just return.
		
		if (   (!$this->config['ltpu_per_day_check']) 
		    && (!$this->config['ltpu_per_week_check']) 
			&& (!$this->config['ltpu_per_month_check']) )
		{
			// this code "smells" ;) 
			// what if the next version also checks posts per YEAR? If someone would
			// only enable checks per YEAR, and nothing else, this will very probably fail...
			// ...and we could have some "side-effects" below. Not NOW, but then...
			return;
		}

		// if only certain user groups shall be checked: test if the user is in one of these groups.
		$included_group_ids = Trim($this->config['ltpu_included_group_ids']);
		
		if ($included_group_ids)
		{
			$user_main_group = $this->user->data['group_id'];
		
			$group_ids = explode(',',$included_group_ids);
			
			if (!in_array($user_main_group,$group_ids)) {
				return;
			}		
		}
		
		// does the user have a rank that should NOT be checked? (Admins, VIP's...)
		$excluded_rank_ids = Trim($this->config['ltpu_excluded_rank_ids']);
		
		if ($excluded_rank_ids)
		{
			$user_rank = $this->user->data['user_rank'];
		
			$rank_ids = explode(',',$excluded_rank_ids);
			
			if (in_array($user_rank,$rank_ids)) {
				return;
			}
		}
		
		// assume approval, until proven otherwise by the checks below...
		$allow_new_topic = true;
		
		$forum_id = $event['forum_id']; 
		$user_id = $this->user->data['user_id'];
		
		$results = array('limit_hit'=>0, 'time_mode'=>'');
		
		// is checking new topics per DAY enabled? then do daily checks.
		
		if ($allow_new_topic && ($this->config['ltpu_per_day_check'])) 
		{			
			$allow_new_topic = $this->do_timeframed_check(
				$event, 
				$forum_id, $user_id,
				'day',
				$this->config['ltpu_per_day_settings'],
				strtotime('today midnight'),
				$results); // var param
		}
		
		// is checking new topics per WEEK enabled? then do weekly checks.
		
		if ($allow_new_topic && ($this->config['ltpu_per_week_check'])) 
		{
			$allow_new_topic = $this->do_timeframed_check(
				$event, 
				$forum_id, $user_id,
				'week',
				$this->config['ltpu_per_week_settings'],
			    strtotime('Last Monday', time()),
				$results); // var param
		}
		
		// is checking new topics per MONTH enabled? then do monthly checks.
		
		if ($allow_new_topic && ($this->config['ltpu_per_month_check'])) 
		{
			$allow_new_topic = $this->do_timeframed_check(
				$event, 
				$forum_id, $user_id,
				'month',
				$this->config['ltpu_per_month_settings'],
				strtotime( 'first day of ' . date( 'F Y')),
				$results); // var param
		}
		
		if ($allow_new_topic) 
		{
			return;		
		}
	
        // this would be the perfect place to load the language file...
        // but then trigger_error doesn't work any more :-(
        
		$limit_hit = $results['limit_hit'];
		$time_mode = $results['time_mode'];
		
		$message = '';
		
		switch ($time_mode) {
			case 'day';
				$message = $this->user->lang['LTPU_ERROR_DAY'];
				break;
			case 'week';
				$message = $this->user->lang['LTPU_ERROR_WEEK'];
				break;
			case 'month';
				$message = $this->user->lang['LTPU_ERROR_MONTH'];
				break;
		}

		$message = sprintf($message,$limit_hit);
		
		$back_link = $this->user->lang['LTPU_ERROR_BACK_TO_FORUM'];		
		$back_link = sprintf($back_link,$forum_id);
		
		// we have to use "brute force" here: simply setting $event['cancel'] 
		// kind of "works", but we can't show an error msg to the user then - 
		// we should tell him/her what went wrong, or what limit he/she hit:

		trigger_error($message.$back_link);
	}
}
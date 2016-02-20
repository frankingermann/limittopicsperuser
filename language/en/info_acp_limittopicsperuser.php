<?php
/**
*
* @package Limit Topics Per User
* @copyright (c) 2015 frankingermann (info@frankingermann.de)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

/// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'LIMIT_TOPICS_PER_USER'				=> 'Limit Topics Per User',
	'LIMIT_TOPICS_PER_USER_TITLE'		=> 'Limit topics per user, forum and day/week/month',
	'LIMIT_TOPICS_PER_USER_EXPLAIN'		=> 'This extension lets you limit the number of new topics a user can start each day, week or month per (sub-)forum.',
	'LTPU_LOG'							=> '<strong>Limit Topics Per User settings updated</strong>',
	'LTPU_SETTINGS'						=> 'Settings',
	'LTPU_GLOBAL_SETTINGS'				=> 'Global settings',
	'LTPU_ENABLE'						=> 'Enable checking',
	'LTPU_ENABLE_EXPLAIN'				=> '“Main Switch” to globally turn all checks below on or off.',
	'LTPU_PER_DAY_CHECK_EXPLAIN'		=> 'Enable checking the limit of topics per user and forum on one <strong>DAY</strong>?',
	'LTPU_PER_WEEK_CHECK_EXPLAIN'		=> 'Enable checking the limit of topics per user and forum in one <strong>WEEK</strong>?',
	'LTPU_PER_MONTH_CHECK_EXPLAIN'		=> 'Enable checking the limit of topics per user and forum in one <strong>MONTH</strong>?',
	'LTPU_SETTINGS_PER_DAY_TITLE'		=> 'Check max. new topics per day',
	'LTPU_SETTINGS_PER_WEEK_TITLE'		=> 'Check max. new topics per week',
	'LTPU_SETTINGS_PER_MONTH_TITLE'		=> 'Check max. new topics per month',
	'LTPU_PER_DAY_CHECK'				=> 'Check topics per day',
	'LTPU_PER_WEEK_CHECK'				=> 'Check topics per week',
	'LTPU_PER_MONTH_CHECK'				=> 'Check topics per month',
	'LTPU_PER_DAY_SETTINGS'				=> 'Forum settings for checks per day',
	'LTPU_PER_WEEK_SETTINGS'			=> 'Forum settings for checks per week',
	'LTPU_PER_MONTH_SETTINGS'			=> 'Forum settings for checks per month',
	'LTPU_PER_DAY_SETTINGS_EXPLAIN'		=> '(see below for an explanation on what to enter here.)',
	'LTPU_PER_WEEK_SETTINGS_EXPLAIN'	=> '(see below for an explanation on what to enter here.)',
	'LTPU_PER_MONTH_SETTINGS_EXPLAIN'	=> '(see below for an explanation on what to enter here.)',
	'LTPU_EXCLUDED_RANKS_TITLE'			=> 'Excluded user ranks',
	'LTPU_EXCLUDED_RANKS'				=> 'Excluded rank IDs',
	'LTPU_EXCLUDED_RANKS_EXPLAIN'		=> 'Here you can enter a comma separated list of user ranks that bypass all of the above checks. Admins have rank ID 1.',	
	'LTPU_INCLUDED_GROUPS_TITLE' 		=> 'User groups to check',
	'LTPU_INCLUDED_GROUPS'				=> 'Group IDs to check',
	'LTPU_INCLUDED_GROUPS_EXPLAIN'		=> 'If you want only certain user groups to be checked, enter their group IDs here (comma-separated). Leaving this blank will check ALL user groups, even admins(!)',	
	'LTPU_INCLUDED_GROUP_IDS_EXPLAIN' 	=> 'Standard group IDs: Guests: 1, Registered: 2, Registered (COPPA): 3, Global Mods: 4, Admins: 5, Bots: 6, Newly registered: 7',	
	'LTPU_FORUM_SETTINGS_EXPLAINED'		=> 'Settings format explained',
	'LTPU_FORUM_SETTINGS_EXPLAINED_TEXT'=> 
'<p><strong>Syntax</strong><br />Enter Forum settings in the following format: ForumID[,ForumID]:[maxtopics] [; [ForumID[,ForumID]]:[maxtopics]].</p>
<p><strong>Examples</strong><br />“1:2” allows 2 new topics per user in the forum with ID 1 per day/week/month. Entering “1:5; 2,3:10” will allow 5 topics in forum with ID 1 and 10 topics in the forums with IDs 2 and 3.
Forums with no matching entry are not checked and remain unlimited.</p>
<p><strong>Global setting for all forums</strong><br />To have a common value for all forums, use an Asterisk “*” as forum ID placeholder like so: “*:2” - this will allow 2 new topics per user and (sub-) forum in ALL forums.</p>
<p><strong>Combinations</strong><br />To set a limit for several forums combined, use a Plus sign like so: “1+2:5” - users can post max. 5 Topics in forums 1 and 2 <i>combined</i>.</p>',

));

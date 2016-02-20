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

// DEVELOPERS PLEASE NOTE
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
	'LTPU_ERROR_DAY'	=> 'Du hast das Tages-Limit von %1$s für neue Themen in diesem Forum erreicht.<br />Du musst bis morgen warten, bevor Du ein weiteres Thema eröffnen kannst.',
	'LTPU_ERROR_WEEK'	=> 'Du hast das Wochen-Limit von %1$s für neue Themen in diesem Forum erreicht.<br />Du musst bis nächste Woche warten, bevor Du ein weiteres Thema eröffnen kannst.',
	'LTPU_ERROR_MONTH'	=> 'Du hast das Monats-Limit von %1$s für neue Themen in diesem Forum erreicht.<br />Du musst bis nächste Woche warten, bevor Du ein weiteres Thema eröffnen kannst.',
    'LTPU_ERROR_BACK_TO_FORUM'	=> '<br /><br /><a href="viewforum.php?f=%1$s">Klicke hier, um zum Forum zurückzukehren.</a>',
));

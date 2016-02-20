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
	'LIMIT_TOPICS_PER_USER'				=> 'Anzahl Themen pro Benutzer beschränken',
	'LIMIT_TOPICS_PER_USER_TITLE' 		=> 'Anzahl Themen pro Benutzer, Forum und Tag/Woche/Monat beschränken',
	'LIMIT_TOPICS_PER_USER_EXPLAIN'		=> 'Mit dieser Erweiterung kannst Du die max. Anzahl neuer Themen begrenzen, die ein Benutzer pro Tag, Woche oder Monat in (Unter-) Foren eröffnen kann.',
	'LTPU_LOG'							=> '<strong>Extension "Anzahl Themen pro Benutzer beschränken": Einstellungen aktualisiert</strong>',
	'LTPU_SETTINGS'						=> 'Einstellungen',
	'LTPU_GLOBAL_SETTINGS'				=> 'Globale Einstellungen',
	'LTPU_ENABLE'						=> 'Überprüfung aktivieren',
	'LTPU_ENABLE_EXPLAIN'				=> 'Der “Hauptschalter”, um alle Prüfungen komplett ein- oder auszuschalten.',
	'LTPU_PER_DAY_CHECK_EXPLAIN'		=> 'Prüfung der Themen-Anzahl pro Benutzer, Forum und <strong>TAG</strong> aktivieren?',
	'LTPU_PER_WEEK_CHECK_EXPLAIN'		=> 'Prüfung der Themen-Anzahl pro Benutzer, Forum und <strong>WOCHE</strong> aktivieren?',
	'LTPU_PER_MONTH_CHECK_EXPLAIN'		=> 'Prüfung der Themen-Anzahl pro Benutzer, Forum und <strong>MONAT</strong> aktivieren?',
	'LTPU_SETTINGS_PER_DAY_TITLE'		=> 'max. Themen-Anzahl pro Tag prüfen',
	'LTPU_SETTINGS_PER_WEEK_TITLE'		=> 'max. Themen-Anzahl pro Woche prüfen',
	'LTPU_SETTINGS_PER_MONTH_TITLE'		=> 'max. Themen-Anzahl pro Monat prüfen',
	'LTPU_PER_DAY_CHECK'				=> 'Tagesweise Prüfung',
	'LTPU_PER_WEEK_CHECK'				=> 'Wochenweise Prüfung',
	'LTPU_PER_MONTH_CHECK'				=> 'Monatsweise Prüfung',
	'LTPU_PER_DAY_SETTINGS'				=> 'Forum-Einstellungen für tagesweise Prüfung',
	'LTPU_PER_WEEK_SETTINGS'			=> 'Forum-Einstellungen für wochenweise Prüfung',
	'LTPU_PER_MONTH_SETTINGS'			=> 'Forum-Einstellungen für monatsweise Prüfung',
	'LTPU_PER_DAY_SETTINGS_EXPLAIN'		=> '(siehe unten für eine Erklärung des Formats für diesen Eintrag.)',
	'LTPU_PER_WEEK_SETTINGS_EXPLAIN'	=> '(siehe unten für eine Erklärung des Formats für diesen Eintrag.)',
	'LTPU_PER_MONTH_SETTINGS_EXPLAIN'	=> '(siehe unten für eine Erklärung des Formats für diesen Eintrag.)',
	'LTPU_EXCLUDED_RANKS_TITLE'			=> 'Ausgenommene Benutzer-Ränge',
	'LTPU_EXCLUDED_RANKS'				=> 'Nicht zu prüfende Rang-IDs',
	'LTPU_EXCLUDED_RANKS_EXPLAIN'		=> 'Hier kannst Du eine Komma-getrennte Liste von Rang-IDs eintragen, die NICHT überprüft werden. Administratoren haben die Rang-ID 1.',	
	'LTPU_INCLUDED_GROUPS_TITLE' 		=> 'Zu prüfende Benutzer-Gruppen',
	'LTPU_INCLUDED_GROUPS'				=> 'Zu prüfende Gruppen-IDs',
	'LTPU_INCLUDED_GROUPS_EXPLAIN'		=> 'Wenn nur bestimmte Benutzer-Gruppen geprüft werden sollen, trage hier Komma-getrennt die Gruppen-IDs ein. Wenn nichts eingetragen wird, werden ALLE Benutzer-Gruppen geprüft, sogar Admins(!).',	
	'LTPU_INCLUDED_GROUP_IDS_EXPLAIN' 	=> 'Standard-Gruppen-IDs: Gäste: 1, Registriert: 2, Registriert (COPPA): 3, Globale Mods: 4, Admins: 5, Bots: 6, Neu registrierte: 7',	
	'LTPU_FORUM_SETTINGS_EXPLAINED'		=> 'Erklärung der Forum-Einstellungen',
	'LTPU_FORUM_SETTINGS_EXPLAINED_TEXT'=> 
'<p><strong>Syntax</strong><br />Alle Forum-Einstellungen haben das folgende Format: ForumID[,ForumID]:[maxthemen] [; [ForumID[,ForumID]]:[maxthemen]].</p>
<p><strong>Beispiele</strong><br />“1:2” erlaubt max. 2 Themen im Forum mit der ID 1 pro Tag/Woche/Monat. Die Einstellung “1:5; 2,3:10” erlaubt 5 Themen im Forum mit der ID 1 sowie 10 im Forum mit der ID 2 bzw. 3.
Foren, für die es keinen passenden Eintrag gibt, werden nicht geprüft und bleiben unbeschränkt.</p>
<p><strong>Gleiche Einstellung für alle Foren</strong><br />Um die Anzahl für alle Foren gleich zu beschränken, benutze einen Stern “*” als Platzhalter für die Foren-ID: “*:2” - erlaubt 2 Themen pro (Unter-) Forum in ALLEN Foren.</p>
<p><strong>Kombinationen</strong><br />Um die Anzahl in mehreren Foren in Kombination zu beschränken, benutze ein Plus: “1+2:5” - Benutzer können 5 Themen in den Foren #1 und #2 <i>zusammen</i> schreiben.</p>',

));

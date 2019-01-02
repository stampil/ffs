<?php
/**
*
* @package hjw calendar Extension
* @copyright (c) 2019 hjw
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

// Bot settings

$lang = array_merge($lang, array(
	'BIRTHDAY'					=> 'Geburtstag',
	'CALENDAR'					=> 'Kalender',
	'CALENDAR_ASK'				=> 'Werden Sie an dieser Veranstaltung teilnehmen?',
	'CALENDAR_CANCELED'			=> 'Termin als abgesagt kennzeichnen',
	'CALENDAR_COMMENTS'			=> 'Bemerkungen',
	'CALENDAR_DATE'				=> 'Eingetragen am',
	'CALENDAR_DATE_FORM'		=> 'j.n.y',
	'CALENDAR_ENTER'			=> 'Eintragen',
	'CALENDAR_ENTRY'			=> 'Kalender-Eintrag',
	'CALENDAR_EVENT'			=> 'Art',
	'CALENDAR_EVENT_NAME'		=> 'Name',
	'CALENDAR_EVERY_WEEKDAY'	=> 'in jedem',
	'CALENDAR_FROM'				=> 'vom',
	'CALENDAR_GROUP'			=> 'Gesamtzahl (1 wenn Sie alleine kommen)',
	'CALENDAR_MB'				=> 'Eventuell',
	'CALENDAR_NO'				=> 'Nein',
	'CALENDAR_NO_ITEMS'			=> 'Bisher keine Anmeldungen',
	'CALENDAR_NUMBER'			=> 'Anzahl',
	'CALENDAR_PART'				=> 'Teilnahme',
	'CALENDAR_RESET'			=> 'Zurücksetzen',
	'CALENDAR_REPEAT'			=> 'Wiederkehrender Termin',
	'CALENDAR_REPEAT_DAYS_1'	=> 'Wiederholung alle ',
	'CALENDAR_REPEAT_DAYS_2'	=> ' Tage',
	'CALENDAR_REPEAT_MONTH_1'	=> 'Gleicher Tag, jeder ',
	'CALENDAR_REPEAT_MONTH_2'	=> '. Monat',
	'CALENDAR_SEND'				=> 'Absenden',
	'CALENDAR_TITLE'			=> 'Kalender',
	'CALENDAR_T_OFF'			=> 'Anzeige der Termine für heute ausschalten',
	'CALENDAR_T_ON'				=> 'Termine anzeigen',
	'CALENDAR_TO'				=> 'bis',
	'CALENDAR_UPCOMING_DATES'	=> 'Die nächsten Termine',
	'CALENDAR_USERS'			=> 'Teilnehmer',
	'CALENDAR_WEEKLY_OVERVIEW'	=> 'Wochen-Übersicht',
	'CALENDAR_YES'				=> 'Ja',
	'PARTICIPANTS_LIST'			=> 'Teilnehmerliste',
	'VIEWING_CALENDAR'			=> 'Betrachtet den Kalender',
));
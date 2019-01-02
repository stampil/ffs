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
	'BIRTHDAY'					=> 'Birthday',
	'CALENDAR'					=> 'Calendar',
	'CALENDAR_ASK'				=> 'Will you attend this event?',
	'CALENDAR_CANCELED'			=> 'Indicate appointment as cancelled',
	'CALENDAR_COMMENTS'			=> 'Comments',
	'CALENDAR_DATE'				=> 'Registered',
	'CALENDAR_DATE_FORM'		=> 'M jS, \'y',
	'CALENDAR_ENTER'			=> 'Enter',
	'CALENDAR_ENTRY'			=> 'Calendar Entry',
	'CALENDAR_EVENT'			=> 'Type',
	'CALENDAR_EVENT_NAME'		=> 'Name',
	'CALENDAR_EVERY_WEEKDAY'	=> 'in every',
	'CALENDAR_FROM'				=> 'from',
	'CALENDAR_GROUP'			=> 'Total number (1 if you come alone)',
	'CALENDAR_MB'				=> 'Maybe',
	'CALENDAR_NO'				=> 'No',
	'CALENDAR_NO_ITEMS'			=> 'Until now no registrations',
	'CALENDAR_NUMBER'			=> 'Number',
	'CALENDAR_PART'				=> 'Participant',
	'CALENDAR_RESET'			=> 'Reset',
	'CALENDAR_REPEAT'			=> 'Recurring appointment',
	'CALENDAR_REPEAT_DAYS_1'	=> 'Repeat every',
	'CALENDAR_REPEAT_DAYS_2'	=> ' days',
	'CALENDAR_REPEAT_MONTH_1'	=> 'Same day, every',
	'CALENDAR_REPEAT_MONTH_2'	=> 'month',
	'CALENDAR_SEND'				=> 'Submit',
	'CALENDAR_TITLE'			=> 'Calendar',
	'CALENDAR_T_OFF'			=> 'Events switch off for today ',
	'CALENDAR_T_ON'				=> 'Events switch on',
	'CALENDAR_TO'				=> 'to',
	'CALENDAR_UPCOMING_DATES'	=> 'Upcoming Dates',
	'CALENDAR_USERS'			=> 'Name',
	'CALENDAR_WEEKLY_OVERVIEW'	=> 'Weekly Overview',
	'CALENDAR_YES'				=> 'Yes',
	'PARTICIPANTS_LIST'			=> 'Participants List',
	'VIEWING_CALENDAR'			=> 'Viewing calendar',
));
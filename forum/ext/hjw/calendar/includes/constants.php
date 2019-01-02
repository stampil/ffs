<?php
/**
*
* @package hjw calendar Extension
* @copyright (c) 2019 hjw
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

global $table_prefix;

// Config constants
if(!defined('CALENDAR_TABLE'))					define('CALENDAR_TABLE',				$table_prefix . 'calendar');
if(!defined('CALENDAR_PARTICIPANTS_TABLE'))		define('CALENDAR_PARTICIPANTS_TABLE',	$table_prefix . 'calendar_participants');
if(!defined('CALENDAR_EVENT_TABLE'))			define('CALENDAR_EVENT_TABLE',			$table_prefix . 'calendar_event');
if(!defined('CALENDAR_EVENT_LIST_TABLE'))		define('CALENDAR_EVENT_LIST_TABLE',		$table_prefix . 'calendar_event_list');
if(!defined('CALENDAR_SPECIAL_DAYS_TABLE'))		define('CALENDAR_SPECIAL_DAYS_TABLE',	$table_prefix . 'calendar_special_days');
if(!defined('CALENDAR_FORUMS_TABLE'))			define('CALENDAR_FORUMS_TABLE',			$table_prefix . 'calendar_forums');
if(!defined('FOOTB_MATCHES_TABLE'))				define('FOOTB_MATCHES_TABLE',			$table_prefix . 'footb_matches');
if(!defined('FOOTB_TEAMS_TABLE'))				define('FOOTB_TEAMS_TABLE',				$table_prefix . 'footb_teams');
<?php
/**
*
* @package hjw calendar Extension
* @copyright (c) 2019 hjw
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if(!defined('IN_PHPBB'))
{
	exit;
}
$leap_year = date("L",mktime(0,0,0,1,1,$year));
if (!$leap_year && $month == 2 && $day == 28)
{
$sql = 'SELECT *
	FROM ' . CALENDAR_EVENT_LIST_TABLE . "
		WHERE date_from = '" . date("Y-m-d", mktime(0, 0, 0, $month, $day, $year)) . "' 
			OR
		date_from <= '" . date("Y-m-d", mktime(0, 0, 0, $month, $day, $year)) . "' AND 
			date_to >= '" . date("Y-m-d", mktime(0, 0, 0, $month, $day, $year)) . "' 
			OR
		date_from = '0000-" . date("m-d", mktime(0, 0, 0, $month, $day, $year)) . "' OR 
			date_from <= '0000-" . date("m-d", mktime(0, 0, 0, $month, $day, $year)) . "' AND 
				date_to >= '0000-" . date("m-d", mktime(0, 0, 0, $month, $day, $year)) . "' 
					OR
		date_from LIKE '%-02-28'  AND 
			anniversary = 1 
				OR
		date_from LIKE '%-02-29'  AND 
			anniversary = 1 
				ORDER by id";
}
else
{
$sql = 'SELECT *
	FROM ' . CALENDAR_EVENT_LIST_TABLE . "
		WHERE date_from = '" . date("Y-m-d", mktime(0, 0, 0, $month, $day, $year)) . "' 
			OR
		date_from <= '" . date("Y-m-d", mktime(0, 0, 0, $month, $day, $year)) . "' AND 
			date_to >= '" . date("Y-m-d", mktime(0, 0, 0, $month, $day, $year)) . "' 
			OR
		date_from = '0000-" . date("m-d", mktime(0, 0, 0, $month, $day, $year)) . "' OR 
			date_from <= '0000-" . date("m-d", mktime(0, 0, 0, $month, $day, $year)) . "' AND 
				date_to >= '0000-" . date("m-d", mktime(0, 0, 0, $month, $day, $year)) . "' 
					OR
		date_from LIKE '%-" . date("m-d", mktime(0, 0, 0, $month, $day, $year)) . "'  AND 
			anniversary = 1 
				ORDER by id";
}
$event_result = $this->db->sql_query($sql);
while($event_row = $this->db->sql_fetchrow($event_result))
{
	$show = true;
	$age = '';
	if ($event_row['anniversary'])
	{
		$from		= $event_row['date_from'];
		$r			= explode('-',$from);
		$from_year	= (int)$r[0];
		if ($year >= $from_year)
		{
			if ($year > $from_year)
			{
				$age .= ' ('.($year - $from_year).')';
			}
		}
		else
		{
			$show = false;
		}
	}
	$appointment	= $event_row['appointment'].$age;
	$description	= $event_row['appointment'].$age;
	if($event_row['description'] != '')
	{
		$description	.= ' - ' . $event_row['description'];
	}

	if ($show)
	{
		$this->template->assign_block_vars('day.cdh', array(
			'LINK'			=> append_sid($event_row['link']),
			'EVENT_NAME' 	=> $appointment,
			'EVENT_TITLE'	=> $description,
			'TR'			=> chr(10).str_repeat('-',strlen(utf8_decode($appointment))),
			'NR'			=> '',
			'PART'			=> '',
			'COLOR' 		=> $event_row['color'],
			'BCOLOR' 		=> $event_row['bcolor'],
			'BIG'			=> $event_row['big'],
		));
	}
}
$sql = 'SELECT *
	FROM ' . CALENDAR_TABLE . "
		WHERE date_from = '" . date("Y-m-d", mktime(0, 0, 0, $month, $day, $year)) . "' OR 
			date_from <= '" . date("Y-m-d", mktime(0, 0, 0, $month, $day, $year)) . "' AND 
				date_to >= '" . date("Y-m-d", mktime(0, 0, 0, $month, $day, $year)) . "' OR 
					date_from <= '" . date("Y-m-d", mktime(0, 0, 0, $month, $day, $year)) . "' AND 
						date_to = '0000-00-00' AND calendar_repeat > 0
							ORDER by event_id";
$event_result = $this->db->sql_query($sql);
while($event_row = $this->db->sql_fetchrow($event_result))
{
	$event_id 	= $event_row['event_id'];
	$event_name = $event_row['event_name'];
	$post_id 	= $event_row['post_id'];
	$link		= '';
	$sql = 'SELECT *
		FROM ' . POSTS_TABLE . '
			WHERE post_id = ' . $post_id;
	$result = $this->db->sql_query($sql);
	while($row = $this->db->sql_fetchrow($result))
	{
		if ($row['post_visibility'] == 1)
		{
			$user_id = $this->user->data['user_id'];
			$auth_array = $this->auth->acl_raw_data($user_id, 'f_read', $row['forum_id']);
			if (isset($auth_array[$user_id][$row['forum_id']]['f_read']) && $auth_array[$user_id][$row['forum_id']]['f_read'])
			{
				$link 	= 'p=' . $row['post_id'] . '#p' . $row['post_id'];
				$subject = $row['post_subject'];
			}
		}
	}
	$adate	= mktime(11,0,0,$month,$day,$year);
	$bdate	= mktime(12,0,0,$month,$day,$year);
	$cdate	= mktime(13,0,0,$month,$day,$year);

	if ($link)
	{
		if($event_row['calendar_repeat'])
		{
			$f		= explode('-',$event_row['date_from']);
			$e='';
			if($event_row['date_to'] != 0000-00-00)
			{
				$e		= explode('-',$event_row['date_to']);
			}
			if($event_row['repeat_dm'] == 1)
			{
				
				$start	= mktime(12,0,0,$f[1],$f[2],$f[0]);
				$adiff	= ($adate - $start)/($event_row['repeat_day_number']*60*60*24);
				$bdiff	= ($bdate - $start)/($event_row['repeat_day_number']*60*60*24);
				$cdiff	= ($cdate - $start)/($event_row['repeat_day_number']*60*60*24);
				if($adiff != (int)($adiff) && $bdiff != (int)($bdiff) && $cdiff != (int)($cdiff))
				{
					$link='';
				}
			}

			if($event_row['repeat_dm'] == 2)
			{
				
				$start	= mktime(12,0,0,$f[1],$f[2],$f[0]);
				$dm	= ($month - $f[1]) + (($year - $f[0]) * 12);
				if($dm/$event_row['repeat_month_number'] == (int)($dm/$event_row['repeat_month_number']))
				{	
					if(date("N",$bdate) == $event_row['weekday'] && (date("j",$bdate) - ($event_row['repeat_day_number']-1)*7)>0 && (date("j",$bdate) - ($event_row['repeat_day_number']-1)*7)<8)
					{
					}
					else
					{
						$link='';
					}
				}
				else
				{
					$link='';
				}
			}
		
			if($event_row['repeat_dm'] == 0)
			{
				$dm	= ($month - $f[1]) + (($year - $f[0]) * 12);
				if($dm/$event_row['repeat_month_number'] != (int)($dm/$event_row['repeat_month_number']) || $day != $f[2])
				{
					$link = '';
				}
			}	
			
		}
	}

	if ($link)
	{
		$sql = 'SELECT *
			FROM ' . CALENDAR_EVENT_TABLE . '
				WHERE id = ' . $event_id;
		$result = $this->db->sql_query($sql);
		while($row = $this->db->sql_fetchrow($result))
		{
			$number = 0;
			$nr = '';
			$part='';
			$event_date = '';
			if ($event_row['calendar_repeat'])
			{
				$event_date = date("Y-m-d", mktime(12, 0, 0, $month, $day, $year));
			}
			
			if($this->config['hjw_calendar_number_participating'])
			{
				$sql = 'SELECT user_id, participants, number
					FROM ' . CALENDAR_PARTICIPANTS_TABLE . '
						WHERE post_id = ' . $post_id . "
							AND event_date ='" . $event_date  . "'";
				$result = $this->db->sql_query($sql);
				while($part_row = $this->db->sql_fetchrow($result))
				{
					$sql = 'SELECT username
						FROM ' . USERS_TABLE . '
							WHERE user_id = ' . $part_row['user_id'];
					$user_result = $this->db->sql_query($sql);
					$user_row = $this->db->sql_fetchrow($user_result);
				
					if ($part_row['participants'] == 'yes')
					{
						$number += (int)$part_row['number'];
						if($this->config['hjw_calendar_participants_name'])
						{
							$part .= chr(10) . $user_row['username'] . ' [' . (int)$part_row['number'] . ']';
						}
					}
				}
			}
			if($bdate)
			{
				$bdate = 'd=' . $bdate . '&';
			}
			if($event_row['canceled'] == 0)
			{
				$eventbg = 'eventbg';
			}
			else
			{
				$eventbg = 'no-eventbg';
			}
			
			$this->template->assign_block_vars('day.cdh', array(
				'LINK'			=> append_sid($this->phpbb_root_path . 'viewtopic.php?' . $bdate . $link),
				'EVENT_TITLE'	=> $subject,
				'EVENT_NAME' 	=> $event_name,
				'TR'			=> chr(10).str_repeat('-',strlen(utf8_decode($subject))),
				'NR'			=> $number,
				'PART'			=> $part,
				'COLOR' 		=> $row['color'],
				'BCOLOR' 		=> $row['bcolor'],
				'BIG'			=> $row['big'],
				'EVENTBG'		=> $eventbg,
			));
		}
	}
}

if ($this->config['hjw_calendar_birthday_on_calendar'] == 1)
{	
	if (!$leap_year && $month == 2 && $day == 28)
	{
		$sql = 'SELECT *
			FROM ' . USERS_TABLE . "
				WHERE user_birthday LIKE '28- 2-%' 
					OR user_birthday LIKE '29- 2-%' 
						ORDER by user_birthday";
	}
	else
	{
		$b_day = str_pad(date("j", mktime(0, 0, 0, $month, $day, $year)), 2, ' ', STR_PAD_LEFT).'-'.str_pad(date("n", mktime(0, 0, 0, $month, $day, $year)), 2, ' ', STR_PAD_LEFT);
		$sql = 'SELECT *
			FROM ' . USERS_TABLE . "
				WHERE user_birthday LIKE '" . $b_day . "-%' 
					ORDER by user_birthday";
	}
	$result = $this->db->sql_query($sql);
	while($row = $this->db->sql_fetchrow($result))
	{
		$user_name	= $row['username'];
		$birthday = $this->user->lang['BIRTHDAY'].' '.$user_name;
		$age = explode ('-',$row['user_birthday'].'-00-00-00');
		if($age[2] == ' 0')
		{
			$age[2] = $year;
		}
			if ( checkdate($age[1], $age[0], $age[2]) )
			{
				$user_age = $year - $age[2];
				if ($user_age >= 0)
				{
					if ($user_age > 0)
					{
						$birthday = $user_age.'. '.$birthday;
						$user_age = ' ('.$user_age.')';
					}
					else
					{
						$user_age = '';
					}
					$this->template->assign_block_vars('day.cdh', array(
						'LINK'			=> append_sid($this->phpbb_root_path . 'memberlist.php?mode=viewprofile&u='.$row['user_id']),
						'EVENT_TITLE' 	=> $birthday,
						'EVENT_NAME' 	=> $user_name.$user_age,
						'COLOR' 		=> $row['user_colour'],
					));
				}
			}
	}
}
if ($football == true)
{
	include($this->root_path . 'includes/football_event' . $this->phpex);
}
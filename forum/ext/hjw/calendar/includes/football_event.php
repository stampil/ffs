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
if ($football)
{
	$sql = 'SELECT *
		FROM ' . FOOTB_MATCHES_TABLE . "
			WHERE match_datetime like '" . date("Y-m-d", mktime(0, 0, 0, $month, $day, $year)) . "%' 
				ORDER by match_datetime";
	$match_result = $this->db->sql_query($sql);
	while($match_row = $this->db->sql_fetchrow($match_result))
	{
		if($match_row['team_id_home'] > 0)
		{
			$sql = 'SELECT *
				FROM ' . FOOTB_TEAMS_TABLE . '
					WHERE season = ' . $match_row['season'] . ' 
						AND league = ' . $match_row['league'] . ' 
							AND team_id = ' . $match_row['team_id_home'];
			$team_result =  $this->db->sql_query($sql);
			$team_row = $this->db->sql_fetchrow($team_result);
			$home_short = $team_row['team_name_short'];
			$home =  $team_row['team_name'];
		}
		else
		{
			$home_short = '...';
			$home =  '...';
		}

		if($match_row['team_id_guest'] > 0)
		{
			$sql = 'SELECT *
			FROM ' . FOOTB_TEAMS_TABLE . '
				WHERE season = ' . $match_row['season'] . ' 
					AND league = ' . $match_row['league'] . '
						AND team_id = ' . $match_row['team_id_guest'];
			$team_result =  $this->db->sql_query($sql);
			$team_row = $this->db->sql_fetchrow($team_result);
			$guest_short = $team_row['team_name_short'];
			$guest =  $team_row['team_name'];
		}
		else
		{
			$guest_short = '...';
			$guest =  '...';
		}
	$t = explode(' ',$match_row['match_datetime']);
	$ti = str_split($t[1],5);
	$time = $ti[0];
		$this->template->assign_block_vars('day.cdh', array(
			'LINK'			=> '',
			'EVENT_NAME' 	=> $time . ' ' . $home_short . ' : ' . $guest_short,
			'COLOR' 		=> '',
			'BCOLOR' 		=> '',
			'EVENT_TITLE'	=> $time . ' ' . $home . ' : ' . $guest,
		));
	}
}
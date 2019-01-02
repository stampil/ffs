<?php
/**
*
* @package hjw calendar Extension
* @copyright (c) 2019 hjw
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace hjw\calendar\controller;

/**
* @ignore
*/

class main
{
	var $u_action;
	
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\request\request_interface */
	protected $request;
	
	/** @var string php file extension */
	protected $php_ext;

	/** @var string phpbb root path */
	protected $phpbb_root_path;
	
	/**
	* Constructor
	*
	* @param \phpbb\config\config		$config
	* @param \phpbb\controller\helper	$helper
	* @param \phpbb\template\template	$template
	* @param \phpbb\user				$this->user
	*/

	public function __construct(\phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\controller\helper $helper, 
								\phpbb\auth\auth $auth, \phpbb\template\template $template, \phpbb\user $user, \phpbb\request\request $request,
								$phpbb_root_path, $phpEx)
	{
		$this->config = $config;
		$this->db = $db;
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
		$this->auth = $auth;
		$this->request = $request;
		$this->phpex = $phpEx;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->root_path = $phpbb_root_path . 'ext/hjw/calendar/';
	}

	public function display()
	{
		$this->template->assign_vars(array(
			'S_IN_CALENDAR'				=> true,
		));
		include($this->root_path . 'includes/constants' . $this->phpex);

		$this->user->add_lang_ext('hjw/calendar', 'calendar');

		$football = 0;
		$sql = 'SELECT *
			FROM ' . EXT_TABLE . '
				ORDER by ext_name';
		$result = $this->db->sql_query($sql);
		while($row = $this->db->sql_fetchrow($result))
		{
			if($row['ext_name'] == 'football/football' &&	$row['ext_active'] == 1)
			{
				$football = $this->config['hjw_calendar_football'];
			}
		}

		$month_name = array(
			1 => $this->user->lang['datetime']['January'],
			2 => $this->user->lang['datetime']['February'],
			3 => $this->user->lang['datetime']['March'],
			4 => $this->user->lang['datetime']['April'],
			5 => $this->user->lang['datetime']['May'],
			6 => $this->user->lang['datetime']['June'],
			7 => $this->user->lang['datetime']['July'],
			8 => $this->user->lang['datetime']['August'],
			9 => $this->user->lang['datetime']['September'],
		   10 => $this->user->lang['datetime']['October'],
		   11 => $this->user->lang['datetime']['November'],
		   12 => $this->user->lang['datetime']['December'],
		);

		$weekstart = $this->config['hjw_calendar_week_start'];

		$ws = 0 - intval($weekstart);
		if ($ws < 0)$ws += 7;
		$day[$ws] = $this->user->lang['datetime']['Sunday'];
		$ws++;
		if ($ws > 6)$ws -= 7;
		$day[$ws] = $this->user->lang['datetime']['Monday'];
		$ws++;
		if ($ws > 6)$ws -= 7;
		$day[$ws] = $this->user->lang['datetime']['Tuesday'];
		$ws++;
		if ($ws > 6)$ws -= 7;
		$day[$ws] = $this->user->lang['datetime']['Wednesday'];
		$ws++;
		if ($ws > 6)$ws -= 7;
		$day[$ws] = $this->user->lang['datetime']['Thursday'];
		$ws++;
		if ($ws > 6)$ws -= 7;
		$day[$ws] = $this->user->lang['datetime']['Friday'];
		$ws++;
		if ($ws > 6)$ws -= 7;
		$day[$ws] = $this->user->lang['datetime']['Saturday'];
		
		$this->template->assign_vars( array(
			'DAY1' 	=> $day[0],
			'DAY2' 	=> $day[1],
			'DAY3'	=> $day[2],
			'DAY4' 	=> $day[3],
			'DAY5' 	=> $day[4],
			'DAY6' 	=> $day[5],
			'DAY7' 	=> $day[6],
		));
	
		if($this->user->data['user_timezone'])
		{
			date_default_timezone_set($this->user->data['user_timezone']);
		}
		else
		{
			date_default_timezone_set($this->config['board_timezone']);
		}
		$today = date("Y-n-j");

		$month = date("n"); 
		$year = date("Y"); 
		if ($this->request->variable('month', ''))
		{
			$month	=	(int)$this->request->variable('month', '');
		}
		if ($this->request->variable('year', ''))
		{
			$year	=	(int)$this->request->variable('year', '');
		}
		$submit	= (isset($_POST['newmonth'])) ? true : false;
		if ($submit)
		{
			$month	=	$this->request->variable('newmonth', '');
		}
		$submit	= (isset($_POST['newyear'])) ? true : false;
		if ($submit)
		{
			$year	=	$this->request->variable('newyear', '');
		}
		
		$t_month = $month;
		$t_year  = $year;

		include($this->root_path . 'includes/special_days' . $this->phpex);
		
		$previous_year = $year;
		$previous_month = $month-1;
		if ($previous_month == 0) 
		{
			$previous_month = 12;
			$previous_year--;
		}
		$next_year = $year;
		$next_month = $month+1;
		if ($next_month == 13)
		{
			$next_month = 1;
			$next_year++;
		}
		for ($i=1;$i<=12;$i++)
		{
			$s='';if ($i == $month) $s=' selected="selected"';  
			$this->template->assign_block_vars('month', array(
				'SELECT' =>'<option' . $s . ' value="' . $i . '">' . $month_name[$i] . '</option>',
			));	
		}
			
		for ($i=$year-2;$i<$year+8;$i++)
		{
			$s='';if ($i == $year) $s=' selected="selected"';  
			$this->template->assign_block_vars('year', array(
				'SELECT' =>'<option' . $s . ' value="' . $i . '">' . $i . '</option>',
			));	
		}

		$wd = date("N", mktime(12, 0, 0, $month, 1, $year));
		$ml = date("t", mktime(12, 0, 0, $month, 1, $year));
		
		$a=$wd-intval($weekstart);

		$end = $ml+$a;
		if ($end/7 > (intval($end/7)))
		{
			$end=(intval($end/7)+1)*7;
		}
		for ($i=1;$i<=$end;$i++)
		{
			$hday ='';
			$day_count = $i-$a;
			$day   = date("j", mktime(12, 0, 0, $t_month, $day_count, $t_year));
			$month = date("n", mktime(12, 0, 0, $t_month, $day_count, $t_year));
			$year  = date("Y", mktime(12, 0, 0, $t_month, $day_count, $t_year));

			$noday= '';
			if (!($day_count>0 and $day_count<=$ml))
			{
				$noday='noday';
			}
			$today_f='';
			if ($today == $year.'-'.$month.'-'.$day)
			{
				$today_f = 'today';
			}
			$d=$i-1;
			while ($d > 7)
			{
				$d=$d-7;
			}

			include($this->root_path . 'includes/special_day' . $this->phpex);
			$week = false;
			if($this->config['hjw_calendar_week_display'] == true)
			{
				$week = date("W", mktime(12, 0, 0, $month, $day, $year));
			}
			$this->template->assign_block_vars('day', array(
				'WEEK'	=> $week,
				'INDEX'	=> false,
				'DAY'	=> $this->user->lang['datetime'][date("l", mktime(12, 0, 0, $month, $day, $year))],
				'DATE'	=> date("j. ", mktime(12, 0, 0, $month, $day, $year)) . $this->user->lang['datetime'][date("l", mktime(12, 0, 0, $month, $day, $year))] . ' ' . $hday,
				'NODAY' => $noday,
				'TODAY'	=> $today_f,
				'HDAY'	=> date("j. ", mktime(12, 0, 0, $month, $day, $year)) . $hday,
				'BG'	=> strtolower(date("D", mktime(12, 0, 0, $month, $day, $year))),
				'I'		=> $d,
			));

			include($this->root_path . 'includes/calendar_event' . $this->phpex);
		}	

		$this->template->assign_vars(array(
			'S_CALENDAR'		=> true,
			'CALENDAR'			=> false,
			'PREVIOUS'			=> append_sid('?month='.$previous_month.'&amp;year='.$previous_year),
			'NEXT'				=> append_sid('?month='.$next_month.'&amp;year='.$next_year),
			'U_ACTION'			=> $this->u_action,
			'WEEK'				=> $week,
			'EVENT_LEGEND'		=> $this->config['hjw_calendar_legend_display'],
		));
		$i =0;
		$sql = 'SELECT *
			FROM ' . CALENDAR_EVENT_TABLE . '
				ORDER by sort';
		$result = $this->db->sql_query($sql);
		while($row = $this->db->sql_fetchrow($result))
		{	
			if ($row['big'] == 1)
			{
				$b = '<strong>';
				$nb= '</strong>';
			}
			else
			{
				$b = '';
				$nb= '';
			}
			$this->template->assign_block_vars('calendar_events', array(
				'EVENT' 		=> $b.$row['event'].$nb,
				'COLOR' 		=> $row['color'],
				'BCOLOR'		=> $row['bcolor'],
				'WEEK'			=> $week,
			));	
		}

		return $this->helper->render('calendar_body.html', $this->user->lang['CALENDAR_TITLE']);
		return $this->helper->render('posting_options_after.html', $this->user->lang['CALENDAR_TITLE']);
	}
}
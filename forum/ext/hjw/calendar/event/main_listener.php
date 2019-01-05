<?php
/**
*
* @package hjw calendar Extension
* @copyright (c) 2019 hjw
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace hjw\calendar\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class main_listener implements EventSubscriberInterface
{
	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'								=> 'load_language_on_setup',
			'core.posting_modify_template_vars'   			=> 'calendar',
			'core.page_header'     							=> 'calendar_on_header',
			'core.viewonline_overwrite_location'			=> 'viewonline_page',
			'core.submit_post_end'							=> 'send_data_to_table',
			'core.viewtopic_assign_template_vars_before'	=> 'modify_participants_list',
			'core.viewtopic_modify_post_row'				=> 'display_participants_list',
		);
	}

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
 
	/** @var \phpbb\event\dispatcher_interface */
    protected $phpbb_dispatcher;	
	
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
								\phpbb\event\dispatcher_interface $phpbb_dispatcher, $phpbb_root_path, $phpEx )
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
        $this->phpbb_dispatcher = $phpbb_dispatcher;
	}

	public function viewonline_page($event)
	{
		switch ($event['on_page'][1])
		{
			case 'app':
				if (strrpos($event['row']['session_page'], '/calendar'))
				{
					$event['location'] = $this->user->lang('VIEWING_CALENDAR');
					$event['location_url'] = $this->helper->route('hjw_calendar_controller');
				}
			break;
		}
	}

	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'hjw/calendar',
			'lang_set' => 'calendar',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function display_participants_list($event)
	{ 
		$post_id = $event['row']['post_id'];
		$forum_id = $event['row']['forum_id'];
		$topic_id = $event['row']['topic_id'];
		$d = $this->request->variable('d',0);
		$p = $this->request->variable('p','');
		$event_date = $this->request->variable('event_date','');
		if($p != '' && $d != 0)
		{
			if($p == $post_id)
			{
				$event_date = date("Y-m-d",$d);
			}
		}
		$date_format  = $this->user->data['user_dateformat'];

		include_once($this->root_path . 'includes/constants' . $this->phpex);

		$p_id = $event['cp_row'];
		$sql = 'SELECT *
			FROM ' . CALENDAR_TABLE . '
			WHERE post_id = ' . $post_id;

		$result = $this->db->sql_query($sql);
		$event_row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);
		if ($event_row)
		{
			$e_id					=	$event_row['event_id'];
			$e_n					=	$event_row['event_name'];
			$calendar_repeat		=	$event_row['calendar_repeat'];
			$repeat_dm				=	$event_row['repeat_dm'];
			$repeat_day_number		=	$event_row['repeat_day_number'];
			$repeat_month_number	=	$event_row['repeat_month_number'];

			$f		=	explode('-',$event_row['date_from']);
			$t		=	explode('-',$event_row['date_to']);
			$cal_date = $f[2]. '.' . $f[1] . '.' . $f[0];
			if ($t[0] >= $f[0] && $t[1] >=$f[1] && $t[2] > $t[2])
			{
				$cal_date .= ' - ' . $t[2]. '.' . $t[1] . '.' . $t[0];
			}
			$pd = '';
			
			$sql = 'SELECT *
				FROM ' . CALENDAR_EVENT_TABLE . '
					WHERE id = ' . $event_row['event_id'];

			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			if ($row['participants'])
			{
				$sql = 'SELECT *
					FROM ' . CALENDAR_FORUMS_TABLE . '
					WHERE forum_id = ' . $forum_id;
				$result = $this->db->sql_query($sql);
				$forum_row = $this->db->sql_fetchrow($result);
				$this->db->sql_freeresult($result);
				if ($forum_row)
				{
					if ($forum_row['allowed'] == 1)
					{
						if($calendar_repeat)
						{
							$e		= explode('-',$event_row['date_to']);
							$today 	= mktime(12,0,0,date("m"),date("d"),date("Y"));
							if($event_date)
							{
								if($repeat_dm)
								{
									$today = mktime(12,0,0,date("m",$d),date("d",$d)-(10*$repeat_day_number),date("Y",$d));
								}
								if($repeat_dm == 0)
								{
									$today = mktime(12,0,0,date("m",$d)-(10*$repeat_month_number),date("d",$d),date("Y",$d));
								}
							}
							if($event_row['date_to'] != '0000-00-00')
							{
								$end	= mktime(12,0,0,$e[1],$e[2],$e[0]);
							}
							else
							{
								$end = mktime(12,0,0,12,31,2037);
							}
							$i = 0;
							$j = 0;
							$nr = 0;
							$option = '';
							
							if($repeat_dm)
							{
								while (mktime(12,0,0,$f[1],$f[2]+($i*$repeat_day_number),$f[0]) <= $end && $j<20)
								{
									if($repeat_dm == 1)
									{
										$d = mktime(12,0,0,$f[1],$f[2]+($i*$repeat_day_number),$f[0]);
									}
									if($repeat_dm == 2)
									{
										$new_month = $f[1] + ($i*$repeat_month_number);
										$begin_weekday = date("N",mktime(12,0,0,$new_month,1,$f[0]));
										$wd=$event_row['weekday']-$begin_weekday+1;
										if($wd<=0)
										{
											$wd=$wd+7;
										}
										$wd = $wd+($repeat_day_number-1)*7;
										if($wd > date("t",mktime(12,0,0,$new_month,1,$f[0])))
										{
											$d=0;
										}
										else
										{
											$d = mktime(12,0,0,$new_month,$wd,$f[0]);
										}
									}
									if($d >= $today)
									{
										$j++;
										if ($j == 1)
										{
											$pd = date("Y-m-d",$d);
										}
										$m = date("d. ", $d) . $this->user->lang['datetime'][date("F", $d)] . date(" Y", $d);
										$selected='';
										if ($event_date == date("Y-m-d",$d))
										{
											$selected = ' selected="selected"';
											$pd = date("Y-m-d",$d);
										}
										$number['yes']	= 0;
										$number['no']	= 0;
										$number['mb']	= 0;
										$sql = 'SELECT participants, number
											FROM ' . CALENDAR_PARTICIPANTS_TABLE . '
												WHERE post_id = ' . $post_id . "
													AND event_date = '" . date("Y-m-d",$d) . "'";
										$result = $this->db->sql_query($sql);
										while($part_row = $this->db->sql_fetchrow($result))
										{
											$number[''.$part_row['participants'].''] += (int)$part_row['number'];
										}
										$nr = '';
										if($number['yes'])
										{
										$nr = ' [' . $number['yes'] . ']';
										}
										$option .='<option value="' . $d . '"' . $selected . '>' . $m . $nr . '</option>';
									}
									$i++;
								}
								$p_id['row']['EVENT_LIST'][] = array(
									'EVENT_DATE_LIST'			=> $option,
								);
								$p_id['row']['ACTION'] = array(
									'U_ACTION'				=>	'./viewtopic.php?p=' . $post_id . '#p' . $post_id,
								);
							}
		
							if($repeat_dm == 0)
							{
								while (mktime(12,0,0,$f[1]+($i*$repeat_month_number),$f[2],$f[0]) <= $end && $j<20)
								{
									$d = mktime(12,0,0,$f[1]+($i*$repeat_month_number),$f[2],$f[0]);
									if($d > $today)
									{
										$j++;
										if ($j == 1)
										{
											$pd = date("Y-m-d",$d);
										}
										$m = date("d. ", $d) . $this->user->lang['datetime'][date("F", $d)] . date(" Y", $d);
										$selected='';
										if ($event_date == date("Y-m-d",$d))
										{
											$selected = ' selected="selected"';
											$pd = date("Y-m-d",$d);
										}
										$number['yes']	= 0;
										$number['no']	= 0;
										$number['mb']	= 0;
										$sql = 'SELECT participants, number
											FROM ' . CALENDAR_PARTICIPANTS_TABLE . '
												WHERE post_id = ' . $post_id . "
													AND event_date = '" . date("Y-m-d",$d) . "'";
										$result = $this->db->sql_query($sql);
										while($part_row = $this->db->sql_fetchrow($result))
										{
											$number[''.$part_row['participants'].''] += (int)$part_row['number'];
										}
										$nr = '';
										if($number['yes'])
										{
										$nr = ' [' . $number['yes'] . ']';
										}
										$option .='<option value="' . $d . '"' . $selected . '>' . $m . $nr . '</option>';
									}
									$i++;
								}
								$p_id['row']['EVENT_LIST'][] = array(
									'EVENT_DATE_LIST'			=> $option,
								);
								$p_id['row']['ACTION'] = array(
									'U_ACTION'				=>	'./viewtopic.php?p=' . $post_id . '#p' . $post_id,
								);
							}
							$cal_date = date("d.m.Y", strtotime($pd));
						}

						$number['yes']	= 0;
						$number['no']	= 0;
						$number['mb']	= 0;
						if($pd)
						{	
							$d = explode('-',$pd);
							$link = 'calendar/?month=' . $d[1] . '&year=' . $d[0];
						}
						else
						{
							$link = 'calendar/?month=' . $f[1] . '&year=' . $f[0];
						
						}
						if(!$this->config['enable_mod_rewrite'])
						{
							$link = 'app.php/' . $link;
						}
						$link = append_sid($link);

						$p_id['row']['CALENDAR_ENTRY'] = array(
							'ENTRY'				=>	$cal_date,
							'LINK'				=>	$link,
							'PARTICIPANTS_ID'	=>	true,
							'EVENT_DATE'		=>	$pd,
							'U_PARTICIPANTS'	=>	append_sid($this->phpbb_root_path . 'viewtopic.php?f='.$forum_id.'&amp;p='.$post_id.'#p'.$post_id),
						);
						$sql = 'SELECT *
							FROM ' . CALENDAR_PARTICIPANTS_TABLE . '
								WHERE post_id = ' . $post_id . "
									AND event_date = '" . $pd . "'";
						$result = $this->db->sql_query($sql);
						while($part_row = $this->db->sql_fetchrow($result))
						{
							$sql = 'SELECT user_colour, username, user_timezone, user_id
								FROM ' . USERS_TABLE . '
									WHERE user_id = ' . $part_row['user_id'];
							$user_result = $this->db->sql_query($sql);
							while($user_row = $this->db->sql_fetchrow($user_result))
							{
								$number[''.$part_row['participants'].''] += (int)$part_row['number'];
								$r	= explode('-',$part_row['date'].'-0-0');
								date_default_timezone_set($this->config['board_timezone']);
								$d = mktime($r[3], $r[4], $r[5], $r[1], $r[2], $r[0]);
								$a = date("Y-m-j-H-i-s", $d);					
								date_default_timezone_set($this->user->data['user_timezone']);
								$m = date("Y-m-j-H-i-s", $d);					
								date_default_timezone_set($this->config['board_timezone']);

								$r	= explode('-',$m);

								$p_date = $this->user->create_datetime()
									->setDate($r[0], $r[1], $r[2])
									->setTime($r[3],$r[4],$r[5])
									->format($date_format, true);

								$FG='black';
								// <!-- ModifA.Giraudi: New classe for calendar entry Yes No Maybe -->
								if (strtoupper($part_row['participants'])=='YES') $FG='green';
								if (strtoupper($part_row['participants'])=='NO') $FG='red';
								//	<!-- end of modif -->
								
								
								$p_id['row']['LIST'][] = array(
									'PARTICIPANTS_USER'			=> $user_row['username'],
									'PARTICIPANTS_USER_LINK'	=> append_sid($this->phpbb_root_path . 'memberlist.php?mode=viewprofile&u=' . $user_row['user_id']),
									'PARTICIPANTS_COLOUR'		=> $user_row['user_colour'],
									'PARTICIPANTS_NUMBER'		=> $part_row['number'],
									'PARTICIPANTS_PART'			=> $this->user->lang['CALENDAR_'.strtoupper($part_row['participants']).''],
									'PARTICIPANTS_COMMENTS'		=> $part_row['comments'],
									'PARTICIPANTS_DATE'			=> $p_date,
									// <!-- ModifA.Giraudi: New classe for calendar entry Yes No Maybe -->
									'PARTICIPANTS_FG_COLOUR'	=> $FG,
									//	<!-- end of modif -->
								);
							}
							$this->db->sql_freeresult($user_result);
						}
						$p_id['row']['COUNT'] = array(
						'PARTICIPANTS_COUNT'	=> $number['yes'] . '&nbsp;/&nbsp;' . $number['mb'] . '&nbsp;/&nbsp;' . $number['no'],
						);
					}
				}
			}
			else
			{
				$link = 'calendar/?month=' . $f[1] . '&year=' . $f[0];
				if(!$this->config['enable_mod_rewrite'])
				{
					$link = 'app.php/' . $link;
				}
				$link = append_sid($link);
				$p_id['row']['CALENDAR_ENTRY'] = array(
					'ENTRY'				=>	$cal_date,
					'LINK'				=>	$link,
				);

			}
			$event['cp_row'] = $p_id;
		}
	}

	public function calendar_on_header($event)
	{ 
		global $weekday, $wday, $c_event, $c_date, $user, $c_event, $c_c, $c_from, $c_to, $c_name, $c_link, $c_part, $c_nr, $c_title, $c_color, $c_bcolor, $c_big, $c_canceled;

		$dc = $this->request->variable('dc','');
		$sid = $this->request->variable('sid','');
		$c_c = -1;
		
		$c_from		= array();
		$c_to		= array();
		$c_name		= array();
		$c_link		= array();				
		$c_part		= array();
		$c_nr		= array();
		$c_title	= array();
		$c_color	= array();
		$c_bcolor	= array();
		$c_big		= array();
		$c_canceled	= array();
		
		$calendar	= true;

		if($this->config['hjw_calendar_on_index_off'])
		{
			$url 	= $this->request->variable('REQUEST_URI', '', false,\phpbb\request\request_interface::SERVER);
			$cookie = $this->request->variable($this->config['cookie_name'] . '_calendar_on_header', '', false,\phpbb\request\request_interface::COOKIE);
			if($sid)
			{
				$s = '\?sid=' . $sid;
				$url = preg_replace ('/' . $s . '/', '', $url);
				$s = '\&amp;sid=' . $sid;
				$url = preg_replace ('/' . $s . '/', '', $url);
			}	
			$url = preg_replace('/\?dc=off/','',$url);
			$url = preg_replace('/\?dc=on/','',$url);
			$url = preg_replace('/&amp;dc=off/','',$url);
			$url = preg_replace('/&amp;dc=on/','',$url);
			$i = strpos($url, '?');
			if ($i)
			{
				$url	.= '&';
			}
			else
			{
				$url	.= '?';
			}

			if ($dc == '' && $cookie <> date("j"))
			{
				$dc = 'on';
			}
		
			if ($dc == 'on')
			{
				$calendar	= true;
				$d_action	= append_sid($url . 'dc=off');
				$cookie = 0;
				setcookie($this->config['cookie_name'] . '_calendar_on_header', 0,  time()+60*60*24, '/', $this->config['cookie_domain'], '');
			}

			if ($cookie == date("j"))
			{
				$calendar	= false;
				$d_action	= append_sid($url . 'dc=on');
			}

			if ($dc == 'off')
			{
				$calendar	= false;
				$d_action	= append_sid($url . 'dc=on');
				setcookie($this->config['cookie_name'] . '_calendar_on_header', date("j"),  time()+60*60*24, '/', $this->config['cookie_domain'], '');
			}
			$this->template->assign_vars(array(
				'DISPLAY_ACTION'		=> $d_action,
				'CALENDAR_ON_INDEX_OFF'	=> $this->config['hjw_calendar_on_index_off'],
			));
		}

		$this->template->assign_vars(array(
			'CALENDAR'				=> $calendar,
		));
		
		include($this->root_path . 'includes/constants' . $this->phpex);
		$calendar_link	=	$this->helper->route('hjw_calendar_controller');

		if($this->config['version'] > '3.1')
		{
			$version = '3.1';
		}
		if($this->config['version'] > '3.2')
		{
			$version = '3.2';
		}
		
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
		
		$this->template->assign_vars(array(
				'U_CALENDAR'				=> $calendar_link,	
				'S_WEEK_OR_NEXT'			=> $this->config['hjw_calendar_week_or_next'],
				'S_WEEK_ON_INDEX'			=> $this->config['hjw_calendar_week_on_index'],
				'S_BIRTHDAY_ON_CALENDAR'	=> $this->config['hjw_calendar_birthday_on_calendar'],
				'S_CALENDAR_FOR_GUESTS'		=> $this->config['hjw_calendar_for_guests'],
				'VERSION'					=> $version,
			));	

		if ($calendar == true)
		{
			if($this->config['hjw_calendar_week_or_next'] == 1 || $this->config['hjw_calendar_week_or_next'] == 3)
			{
				if($this->user->data['user_timezone'])
				{
					date_default_timezone_set($this->user->data['user_timezone']);
				}
				else
				{
					date_default_timezone_set($this->config['board_timezone']);
				}
				$t_day   = $day   = date("j"); 
				$t_month = $month = date("n"); 
				$t_year  = $year  = date("Y"); 

				include($this->root_path . 'includes/special_days' . $this->phpex);
				$c_days = ($this->config['hjw_calendar_number_of_weeks']*7)-1;
				for ($y=0;$y<=$c_days;$y++)
				{
					$hday = '';
					$i=$y;
					while ($i > 6)
					{
						$i -= 7;
					}
					$d = mktime(12, 0, 0, $t_month, $t_day+$y, $t_year);
					$day   = date("j", $d);
					$month = date("n", $d);
					$year  = date("Y", $d);
					include($this->root_path . 'includes/special_day' . $this->phpex);
					$this->template->assign_block_vars('day', array(
						'INDEX'	=> true,
						'WD'	=> $this->user->lang['datetime'][date("l", $d)],
						'DATE'	=> date("d.", $d),
						'HDAY'	=> $hday,
						'BG'	=> strtolower(date("D", $d)),
						'I'		=> $i,
					));

					include($this->root_path . 'includes/calendar_event' . $this->phpex);
				}
			}
			if($this->config['hjw_calendar_week_or_next'] == 2 || $this->config['hjw_calendar_week_or_next'] == 3)
			{
				$count_event = 0;
				$count_event_max = 10 *  $this->config['hjw_calendar_number_of_weeks'];
				$sql = 'SELECT *
					FROM ' . CALENDAR_TABLE . " 
						WHERE date_from >= '" . date("Y-m-d") . "' 
							OR 
								date_to >= '" . date("Y-m-d") . "'
									OR 
										date_from <= '" . date("Y-m-d") . "'
											AND 
												date_to = '0000-00-00' AND calendar_repeat = 1 
					ORDER by date_from";

				$event_result = $this->db->sql_query($sql);
				while (($event_row = $this->db->sql_fetchrow($event_result)) && ($count_event < $count_event_max))
				{
					$hday = '';
					$upcoming = $event_row;
					$post_id 	= $event_row['post_id'];
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
								if($event_row['calendar_repeat'])
								{
									$start	= strtotime($event_row['date_from'])+43200;
									$date	= strtotime(date("Y-m-d"))+43200;
									$count = 0;
									for($i=0;$count<$count_event_max ;$i++)
									{
										if($event_row['repeat_dm'] == 0)
										{
											$next	= mktime(12,0,0,date("m",strtotime($event_row['date_from']))+$i*$event_row['repeat_month_number'],date("d",strtotime($event_row['date_from'])),date("Y",strtotime($event_row['date_from'])));
										}
										if($event_row['repeat_dm'] == 1)
										{
											$next	= mktime(12,0,0,date("m",strtotime($event_row['date_from'])),date("d",strtotime($event_row['date_from']))+$i*$event_row['repeat_day_number'],date("Y",strtotime($event_row['date_from'])));
										}
										if($event_row['repeat_dm'] == 2)
										{
											$new_month = date("m",$start) + ($i*$event_row['repeat_month_number']);
											$begin_weekday = date("N",mktime(12,0,0,$new_month,1,date("Y",$start)));
											$wd=$event_row['weekday']-$begin_weekday+1;
											if($wd<=0)
											{
												$wd=$wd+7;
											}
											$wd = $wd+($event_row['repeat_day_number']-1)*7;
											if($wd > date("t",mktime(12,0,0,$new_month,1,date("Y",$start))))
											{
												$next=0;
											}
											else
											{
												$next	= mktime(12,0,0,$new_month,$wd,date("Y",$start));
											}
										}
										if (($next) >= date($date)) 
										{
											$upcoming['date_from'] =  date("Y-m-d", $next);
											upcoming_events($upcoming);
											$count++;
										}
							
							
									}
								}
								else
								{		
									upcoming_events($upcoming);
								}
							}
						}
					}
				}
				$sql = 'SELECT *
					FROM ' . CALENDAR_EVENT_LIST_TABLE . "
						WHERE date_from >= '" . date("Y-m-d") . "'
							OR 
								date_to >= '" . date("Y-m-d") . "' 
									ORDER by date_from";
				$event_result = $this->db->sql_query($sql);
				while (($event_row = $this->db->sql_fetchrow($event_result)) && ($count_event < $count_event_max))
				{
					$appointment	= $event_row['appointment'];
					$description	= $event_row['description'];
					$link 			= $event_row['link'];
					$color			= $event_row['color'];
					$big			= $event_row['big'];
					$bcolor			= $event_row['bcolor'];
					$anniversary	= $event_row['anniversary'];

					$c_c++;
					$c_from[$c_c]  = $event_row['date_from'];
					if ($event_row['date_to'] <> '0000-00-00' && $event_row['date_to'] > $event_row['date_from'])
					{
						$c_to[$c_c]  = $event_row['date_to'];
					}
					else
					{
						$c_to[$c_c]  = '';
					}
					$c_name[$c_c]	= $appointment;
					$c_title[$c_c]	= $appointment;
					if($description != '')
					{
						$c_title[$c_c]	.= ' - ' . $description;
					}
					
					$c_link[$c_c]	= $link;
					$c_part[$c_c]	= '';
					$c_nr[$c_c]		= '';
					$c_color[$c_c]	= $color;
					$c_big[$c_c]	= $big;
					$c_bcolor[$c_c]	= $bcolor;
					$c_canceled[$c_c]		= 0;
				}
			
				$sql = 'SELECT *
					FROM ' . CALENDAR_EVENT_LIST_TABLE . "
						WHERE date_from LIKE '0000-%'
							OR anniversary = 1";
				$event_result = $this->db->sql_query($sql);
				while ($event_row = $this->db->sql_fetchrow($event_result)) 
				{
					$appointment	= $event_row['appointment'];
					$description	= $event_row['description'];
					$link 			= $event_row['link'];
					$color			= $event_row['color'];
					$big			= $event_row['big'];
					$bcolor			= $event_row['bcolor'];
					$anniversary	= $event_row['anniversary'];

					$r				= explode('-',$event_row['date_from']);
					$from_year		= str_pad($r[0], 4, '0', STR_PAD_LEFT);
					$from_month 	= str_pad($r[1], 2, '0', STR_PAD_LEFT);
					$from_day		= str_pad($r[2], 2, '0', STR_PAD_LEFT);
					$r				= explode('-',$event_row['date_to']);
					$to_year		= str_pad($r[0], 4, '0', STR_PAD_LEFT);
					$to_month 		= str_pad($r[1], 2, '0', STR_PAD_LEFT);
					$to_day			= str_pad($r[2], 2, '0', STR_PAD_LEFT);

					if($from_year =='0000' || $anniversary)
					{
						if($anniversary)
						{
							$age = $from_year;
						}

						$from_year = date("Y");
						if($from_month . $from_day < date("md"))
						{
							$from_year++;
						}

						if($anniversary)
						{
							$age = $from_year - $age;
							$appointment	=	$appointment . ' (' . $age . ')';
						}

						if($to_year > '0000')
						{
							$to_year = $from_year - $r[0] + $to_year;
						}
					}
					$c_c++;
					$c_from[$c_c]  = $from_year . '-' . $from_month . '-' . $from_day;
					$date_to = $to_year . '-' . $to_month . '-' . $to_day ;
					if ($date_to <> '0000-00-00' && $date_to > $event_row['date_from'])
					{
						$c_to[$c_c] = $to_year . '-' . $to_month . '-' . $to_day;
					}
					else
					{
						$c_to[$c_c] = '';
					}
					$c_name[$c_c]		= $appointment;
					$c_title[$c_c]		= $appointment;
					if($description)
					{
						$c_title[$c_c]	.= ' - ' . $description;
					}
					$c_link[$c_c]		= $link;
					$c_part[$c_c]		= '';
					$c_nr[$c_c]			= '';
					$c_color[$c_c]		= $color;
					$c_big[$c_c]		= $big;
					$c_bcolor[$c_c]		= $bcolor;
					$c_canceled[$c_c]	= 0;
				}

				if($this->config['hjw_calendar_birthday_on_calendar'])
				{
					$sql = 'SELECT user_id, username, user_birthday, user_colour
						FROM ' . USERS_TABLE . '
							ORDER by user_birthday';

					$event_result = $this->db->sql_query($sql);
					while (($event_row = $this->db->sql_fetchrow($event_result)) && ($count_event < $count_event_max))
					{
						if($event_row['user_birthday'] && $event_row['user_birthday'] != ' 0- 0-   0')
						{
							$username	= $event_row['username'];
							$birthday	= $this->user->lang['BIRTHDAY'].' '.$username;

							$r			= explode('-',$event_row['user_birthday'].'-00-00-00');
							$from_day	= str_pad(trim($r[0]), 2, '0', STR_PAD_LEFT);
							$from_month = str_pad(trim($r[1]), 2, '0', STR_PAD_LEFT);
							$from_year	= str_pad(trim($r[2]), 4, '0', STR_PAD_LEFT);

							if ( checkdate($from_month, $from_day, $from_year) )
							{
								$user_age = $from_year;
								$from_year = date("Y");
								if($from_month . $from_day < date("md"))
								{
									$from_year++;
								}
								$user_age = $from_year - $user_age;
								if ($user_age >= 0)
								{
									$birthday = $user_age.'. '.$birthday;
									$user_age = ' ('.$user_age.')';

									$c_c++;
									$c_from[$c_c]		= $from_year . '-' . $from_month . '-' . $from_day ;
									$c_to[$c_c]			= '';
									$c_name[$c_c]		= $username.$user_age;
									$c_title[$c_c]		= $birthday;
									$c_link[$c_c]		= append_sid($this->phpbb_root_path . 'memberlist.php?mode=viewprofile&u='.$event_row['user_id']);
									$c_part[$c_c]		= '';
									$c_nr[$c_c]			= '';
									$c_color[$c_c]		= $event_row['user_colour'];
									$c_bcolor[$c_c]		= '';
									$c_big[$c_c]		= '';
									$c_canceled[$c_c]	= 0;
								}
							}
						}
					}
				}

				if (isset($c_from))
				{
					array_multisort($c_from, $c_to, $c_name, $c_link, $c_part, $c_nr, $c_title, $c_color, $c_bcolor, $c_big, $c_canceled);
					$hd = false;
					$c_count = min(count($c_from),$count_event_max);
					for($i=0;$i<$c_count;$i++)
					{
						$hday ='';
						$day   = date("j", strtotime($c_from[$i])+43200);
						$month = date("n", strtotime($c_from[$i])+43200);
						$year  = date("Y", strtotime($c_from[$i])+43200);
						include($this->root_path . 'includes/special_day' . $this->phpex);
						$break = false;
						if ((int)(($i+1)/10) == ($i+1)/10)
						{
							$break = true;
						}
						$date_to = '';
						if($c_to[$i] > $c_from[$i])
						{
							$date_to = date($this->user->lang['CALENDAR_DATE_FORM'], strtotime($c_to[$i])+43200);
						}
						if($hday)
						{
							$hd = true;
						}
						if($c_canceled[$i] == 0)
			{
				$eventbg = 'eventbg';
			}
			else
			{
				$eventbg = 'no-eventbg';
			}
						$this->template->assign_block_vars('event', array(
							'LINK'			=> $c_link[$i],
							'PART'			=> $c_part[$i],
							'NR'			=> $c_nr[$i],
							'EVENT_TITLE'	=> $c_title[$i],
							'TR'			=> chr(10).str_repeat('-',strlen(utf8_decode($c_title[$i]))),
							'EVENT_NAME' 	=> $c_name[$i],
							'COLOR'			=> $c_color[$i],
							'BCOLOR'		=> $c_bcolor[$i],
							'BIG'			=> $c_big[$i],
							'WD'			=> $this->user->lang['datetime'][date("l", strtotime($c_from[$i])+43200)],
							'WD_TO'			=> $this->user->lang['datetime'][date("l", strtotime($c_to[$i])+43200)],
							'DATE'			=> date($this->user->lang['CALENDAR_DATE_FORM'], strtotime($c_from[$i])+43200),
							'DATE_TO'		=> $date_to,
							'DAY'			=> $this->user->lang['datetime'][date("l", strtotime($c_from[$i])+43200)],
							'HDAY'			=> $hday,
							'BG'			=> strtolower(date("D", strtotime($c_from[$i])+43200)),
							'BREAK'			=> $break,
							'EVENTBG'		=> $eventbg,
						));
					}
						$this->template->assign_vars(array(
							'HD'			=> $hd,
						));
				}
			}
		}
	}
	

	public function modify_participants_list()
	{
		$user_id  = $this->user->data['user_id'];
		include($this->root_path . 'includes/constants' . $this->phpex);
		if ($this->request->variable('part', ''))
		{
			if ($user_id)
			{
				$pd	= $this->request->variable('event_date', '', true);
				$sql_ary = array(
					'POST_ID'		=> $this->request->variable('participants_id', '', true),
					'EVENT_DATE'	=> $pd,
					'USER_ID'		=> $user_id,
					'NUMBER'		=> (int)($this->request->variable('group', '', true)),
					'PARTICIPANTS'	=> utf8_normalize_nfc($this->request->variable('part', '', true)),
					'COMMENTS'		=> utf8_normalize_nfc($this->request->variable('comments', '', true)),
					'DATE'			=> date("Y-n-j-H-i"),
				);

				$vars = array(
					'sql_ary',
					'user_id',
				);
				extract($this->phpbb_dispatcher->trigger_event('hjw.calendar.viewtopic.modify_participants_list', compact($vars)));
				
				$sql = 'SELECT * from ' . CALENDAR_PARTICIPANTS_TABLE . "
					WHERE post_id = " . $sql_ary['POST_ID'] . "
						AND event_date = '" . $pd . "'
							AND user_id = " . $user_id; 
				$result = $this->db->sql_query($sql);
				$row = $this->db->sql_fetchrow($result);
				if ($row)
				{
					$sql = 'UPDATE ' . CALENDAR_PARTICIPANTS_TABLE . '
						SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . "
							WHERE post_id = " . $sql_ary['POST_ID'] . "
								AND event_date = '" . $pd . "'
									AND user_id = " . $user_id;

					$notify = 'calendar_participants_change';
				}
				else
				{
					$sql = 'INSERT INTO ' . CALENDAR_PARTICIPANTS_TABLE . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);	
					
					$notify = 'calendar_participants';
				}
				$result = $this->db->sql_query($sql);
				
				if($this->config['hjw_calendar_notify_participating'] == true)

				{
					calendar_messenger($notify, $user_id, $sql_ary['POST_ID'], 'd=' . mktime(12,0,0,date("n",strtotime($pd)),date("j",strtotime($pd)),date("Y",strtotime($pd))) . '&');
				}
			}
		}
	}

	public function calendar($event)
	{ 
		$mode = $this->request->variable('mode','');
		if($mode != 'quote')
		{
			include($this->root_path . 'includes/constants' . $this->phpex);
			$post_id = $event['post_id'];
			$forum_id = $event['forum_id'];
					
			$post = $event['page_data'];
			$post['SUBJECT'] = $this->request->variable('subject', $post['SUBJECT'], true);
			$post['MESSAGE'] = $this->request->variable('message', $post['MESSAGE'], true);
			$event['page_data'] = $post;

			$quote = false;
			if ((substr($event['post_data']['post_text'],1,5) == 'quote'))
			{
				$quote = true;
			}

			$first_post_id = 0;
			if (isset($event['post_data']['topic_first_post_id']))
			{
				$first_post_id = $event['post_data']['topic_first_post_id'];
			}
			$first_post = false;
			if ($first_post_id == 0)
			{
				$first_post = true;
			}
			else
			{
				if($first_post_id == $event['post_id'] && !$quote)
				{
					$first_post = true;
				}
			}

			if ($this->config['hjw_calendar_only_first_post'] && !$first_post)
			{
			}
			else
			{
				$sql = 'SELECT *
					FROM ' . CALENDAR_FORUMS_TABLE . '
						WHERE forum_id = ' . $forum_id;
				$result = $this->db->sql_query($sql);
				$forum_row = $this->db->sql_fetchrow($result);
				$this->db->sql_freeresult($result);
				if ($forum_row)
				{
					if ($forum_row['allowed'] == 1)
					{
						$this->template->assign_vars( array(
							'CALENDAR_ALLOWED'			=> true,
						));	

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
						$day_name = array(
							1 => $this->user->lang['datetime']['Monday'],
							2 => $this->user->lang['datetime']['Tuesday'],
							3 => $this->user->lang['datetime']['Wednesday'],
							4 => $this->user->lang['datetime']['Thursday'],
							5 => $this->user->lang['datetime']['Friday'],
							6 => $this->user->lang['datetime']['Saturday'],
							7 => $this->user->lang['datetime']['Sunday'],
						);

						$event_id 	= '';
						$event_name	= '';
						$from 		= '';
						$r			= '';
						$from_year	= '';
						$from_month = '';
						$from_day	= '';
						$to 		= '';
						$r			= '';
						$to_year	= '';
						$to_month 	= '';
						$to_day		= '';
						$calendar_repeat				= 0;
						$calendar_repeat_dm				= 0;
						$calendar_repeat_day_number		= 7;
						$calendar_repeat_month_number	= 1;
						$calendar_weekday				= 1;
						$calendar_canceled				= 0;
						if ($post_id)
						{
							$sql = 'SELECT *
								FROM ' . CALENDAR_TABLE . '
									WHERE post_id = ' . $post_id;
							$result = $this->db->sql_query($sql);
							$row = $this->db->sql_fetchrow($result);
							if($row)
							{
								$present	= true;
								$event_id 	= $row['event_id'];
								$event_name	= $row['event_name'];
								$from 		= $row['date_from'];
								$r			= explode('-',$from);
								$from_year	= $r[0];
								$from_month = $r[1];
								$from_day	= $r[2];
								$to 		= $row['date_to'];
								$r			= explode('-',$to);
								$to_year	= $r[0];
								$to_month 	= $r[1];
								$to_day		= $r[2];
							
								$calendar_repeat				= $row['calendar_repeat'];
								$calendar_repeat_dm				= $row['repeat_dm'];
								$calendar_repeat_day_number		= $row['repeat_day_number'];
								$calendar_repeat_month_number	= $row['repeat_month_number'];
								$calendar_weekday				= $row['weekday'];
								$calendar_canceled				= $row['canceled'];
							}
						}

						$event_id	= $this->request->variable('event', $event_id);
						$event_name	= $this->request->variable('event_name', $event_name);
					
						$from_day	= str_pad($this->request->variable('from_day',	$from_day),		2 ,'0', STR_PAD_LEFT);
						$from_month	= str_pad($this->request->variable('from_month',$from_month),	2 ,'0', STR_PAD_LEFT);
						$from_year	= str_pad($this->request->variable('from_year',	$from_year),	4 ,'0', STR_PAD_LEFT);
						$to_day		= str_pad($this->request->variable('to_day',	$to_day),		2 ,'0', STR_PAD_LEFT);
						$to_month	= str_pad($this->request->variable('to_month',	$to_month),		2 ,'0', STR_PAD_LEFT);
						$to_year	= str_pad($this->request->variable('to_year',	$to_year),		4 ,'0', STR_PAD_LEFT);
						$from		= $from_year.'-'.$from_month.'-'.$from_day;
						$to			= $to_year.'-'.$to_month.'-'.$to_day;
						
						$calendar_repeat				= $this->request->variable('calendar_repeat', $calendar_repeat);
						$calendar_repeat_dm				= $this->request->variable('calendar_repeat_dm', $calendar_repeat_dm);
						$calendar_repeat_day_number		= $this->request->variable('calendar_repeat_day_number', $calendar_repeat_day_number);
						$calendar_repeat_month_number	= $this->request->variable('calendar_repeat_month_number', $calendar_repeat_month_number);
						$calendar_repeat_day_name		= $this->request->variable('calendar_every_weekday_name', $calendar_weekday);
						$calendar_canceled				= $this->request->variable('calendar_canceled', $calendar_canceled);

						if($calendar_repeat_day_number < 1)
						{
							$calendar_repeat_day_number = 7;
						}
						if($calendar_repeat_month_number <1)
						{
							$calendar_repeat_month_number = 1;
						}

						$cr0 = '';
						$cr1 = '';
						if(!$calendar_repeat)
						{
							$cr0 = ' checked="checked" ';
						}
						else
						{
							$cr1 = ' checked="checked" ';
						}
						$crd  = '';
						$crm  = '';
						$crwd = '';
						if($calendar_repeat_dm == 0)
						{
							$crm = ' checked="checked" ';
						}
						if($calendar_repeat_dm == 1)
						{
							$crd = ' checked="checked" ';
						}
						if($calendar_repeat_dm == 2)
						{
							$crwd = ' checked="checked" ';
							$calendar_repeat_day_number		= $this->request->variable('calendar_every_weekday_number', $calendar_repeat_day_number);
							$calendar_repeat_day_name		= $this->request->variable('calendar_every_weekday_name', $calendar_weekday);
							$calendar_repeat_month_number	= $this->request->variable('calendar_every_month_number', $calendar_repeat_month_number);
						}
						$cc0 = '';
						$cc1 = '';
						if($calendar_canceled == 0)
						{
							$cc0 = ' checked="checked" ';
						}
						else
						{
							$cc1 = ' checked="checked" ';
						}
						
						$this->template->assign_vars(array(
							'CALENDAR_TAB'					=> $this->config['hjw_calendar_tab'],
							'EVENT_NAME' 					=> $event_name,
							'CR0'							=> $cr0,
							'CR1'							=> $cr1,
							'CRD'							=> $crd,
							'CALENDAR_REPEAT_DAY_NUMBER'	=> $calendar_repeat_day_number,
							'CRM'							=> $crm,
							'CALENDAR_REPEAT_MONTH_NUMBER'	=> $calendar_repeat_month_number,
							'CRWD'							=> $crwd,
							'CC0'							=> $cc0,
							'CC1'							=> $cc1,
						));	

						$this->template->assign_block_vars('from_day', array(
							'SELECT' =>'<option value=" ">'.$this->user->lang['DAY'].'</option>',
						));	

						for ($i=1;$i<=31;$i++)
						{
							$s='';if ($i == $from_day) $s=' selected="selected"';  
							$this->template->assign_block_vars('from_day', array(
								'SELECT' =>'<option'.$s.' value="'.$i.'">'.$i.'</option>',
							));	
						}

						$this->template->assign_block_vars('to_day', array(
							'SELECT' =>'<option value=" ">'.$this->user->lang['DAY'].'</option>',
						));	

						for ($i=1;$i<=31;$i++)
						{
							$s='';if ($i == $to_day) $s=' selected="selected"';  
							$this->template->assign_block_vars('to_day', array(
								'SELECT' =>'<option'.$s.' value="'.$i.'">'.$i.'</option>',
							));	
						}
	
						$this->template->assign_block_vars('from_month', array(
							'SELECT' =>'<option value=" ">'.$this->user->lang['MONTH'].'</option>',
						));	

						for ($i=1;$i<=12;$i++)
						{
							$s='';if ($i == $from_month) $s=' selected="selected"';  
							$this->template->assign_block_vars('from_month', array(
								'SELECT' =>'<option'.$s.' value="'.$i.'">'.$month_name[$i].'</option>',
							));	
						}

						$this->template->assign_block_vars('to_month', array(
							'SELECT' =>'<option value=" ">'.$this->user->lang['MONTH'].'</option>',
						));	

						for ($i=1;$i<=12;$i++)
						{
							$s='';if ($i == $to_month) $s=' selected="selected"';  
							$this->template->assign_block_vars('to_month', array(
								'SELECT' =>'<option'.$s.' value="'.$i.'">'.$month_name[$i].'</option>',
							));	
						}

						$date = getdate();
						$year=$date['year']*1;
						if ($from_year > 0)
						{
							$year = $from_year;
						}
						$this->template->assign_block_vars('from_year', array(
							'SELECT' =>'<option value=" ">'.$this->user->lang['YEAR'].'</option>',
						));	
	
						for ($i=$year;$i<$year+10;$i++)
						{
							$s='';if ($i == $from_year) $s=' selected="selected"';  
							$this->template->assign_block_vars('from_year', array(
								'SELECT' =>'<option'.$s.' value="'.$i.'">'.$i.'</option>',
							));	
						}

						$this->template->assign_block_vars('to_year', array(
							'SELECT' =>'<option value=" ">'.$this->user->lang['YEAR'].'</option>',
						));	

						for ($i=$year;$i<$year+10;$i++)
						{
							$s='';if ($i == $to_year) $s=' selected="selected"';  
							$this->template->assign_block_vars('to_year', array(
								'SELECT' =>'<option'.$s.' value="'.$i.'">'.$i.'</option>',
							));	
						}
						for ($i=1;$i<6;$i++)
						{
							$s='';if ($i == $calendar_repeat_day_number) $s=' selected="selected"';  
							$this->template->assign_block_vars('every', array(
								'SELECT' =>'<option'.$s.' value="'.$i.'">'.$i.'</option>',
							));	
						}
						for ($i=1;$i<8;$i++)
						{
							$s='';if ($i == $calendar_repeat_day_name) $s=' selected="selected"';  
							$this->template->assign_block_vars('weekday', array(
								'SELECT' =>'<option'.$s.' value="'.$i.'">'.$day_name[$i].'</option>',
							));	
						}

						$this->template->assign_block_vars('eventselect', array(
							'SELECT' =>'<option style="color:#BBBBBB" value=" ">entfernen</option>',
						));	

						$sql = 'SELECT *
							FROM ' . CALENDAR_EVENT_TABLE . '
								ORDER by sort';
						$result = $this->db->sql_query($sql);
						while($row = $this->db->sql_fetchrow($result))
						{
							$s='';if (intval($row['id']) == intval($event_id)) $s=' selected="selected"';
							$this->template->assign_block_vars('eventselect', array(
								'SELECT' =>'<option' . $s . ' value="' . $row['id'] . '">' . $row['event'] . '</option>',
							));
						}
					}
				}
			}
		}
	}

	public function send_data_to_table($event)
	{
		$mode = $this->request->variable('mode','');
		if($mode != 'quote')
		{
			include($this->root_path . 'includes/constants' . $this->phpex);

			$post_id = $event['data']['post_id'];

			$present 	= false;
			$e_id		=	'';
			$e_n		=	'';
			$f[0]		=	'0000';
			$f[1]		=	'00';
			$f[2]		=	'00';
			$t[0]		=	'0000';
			$t[1]		=	'00';
			$t[2]		=	'00';
			$cr			= 0;
			$crd		= 0;
			$crdn		= 0;
			$crmn		= 0;
			$crwd		= 1;
			$cc			= 0;
			
			$sql = 'SELECT *
				FROM ' . CALENDAR_TABLE . '
					WHERE post_id = ' . $post_id;
			$result = $this->db->sql_query($sql);
			if($row = $this->db->sql_fetchrow($result))
			{
				$present	= true;
				$e_id		= $row['event_id'];
				$e_n		= $row['event_name'];
				$f			= explode('-',$row['date_from']);
				$t			= explode('-',$row['date_to']);
				$cr			= $row['calendar_repeat'];
				$crd		= $row['repeat_dm'];
				$crdn		= $row['repeat_day_number'];
				$crmn		= $row['repeat_month_number'];
				$crwd		= $row['weekday'];
				$cc			= $row['canceled'];
			}

			$event_id	= $this->request->variable('event', $e_id);
			$event_name	= utf8_normalize_nfc($this->request->variable('event_name', $e_n, true));
			$from_day	= str_pad($this->request->variable('from_day',		$f[2]),	2 ,'0', STR_PAD_LEFT);
			$from_month	= str_pad($this->request->variable('from_month',	$f[1]),	2 ,'0', STR_PAD_LEFT);
			$from_year	= str_pad($this->request->variable('from_year',		$f[0]),	4 ,'0', STR_PAD_LEFT);
			$to_day		= str_pad($this->request->variable('to_day',		$t[2]),	2 ,'0', STR_PAD_LEFT);
			$to_month	= str_pad($this->request->variable('to_month',		$t[1]),	2 ,'0', STR_PAD_LEFT);
			$to_year	= str_pad($this->request->variable('to_year',		$t[0]),	4 ,'0', STR_PAD_LEFT);
			$from		= $from_year.'-'.$from_month.'-'.$from_day;
			$to			= $to_year.'-'.$to_month.'-'.$to_day;

			$calendar_repeat				= $this->request->variable('calendar_repeat', $cr);
			$calendar_repeat_dm				= $this->request->variable('calendar_repeat_dm', $crd);
			$calendar_repeat_day_number		= $this->request->variable('calendar_repeat_day_number', $crdn);
			$calendar_repeat_month_number	= $this->request->variable('calendar_repeat_month_number', $crmn);
			$calendar_weekday				= $this->request->variable('calendar_every_weekday_name', $crwd);
			$calendar_canceled				= $this->request->variable('calendar_canceled', $cc);

			if($calendar_repeat_day_number == 0)
			{
				$calendar_repeat_day_number = 7;
			}
			if($calendar_repeat_month_number == 0)
			{
				$calendar_repeat_month_number = 1;
			}
			if($calendar_repeat_dm == 2)
			{
				$calendar_repeat_day_number		= $this->request->variable('calendar_every_weekday_number', $calendar_repeat_day_number);
				$calendar_repeat_month_number	= $this->request->variable('calendar_every_month_number', $calendar_repeat_month_number);
			}

			$sql_ary = array(
				'POST_ID'				=>	$post_id,
				'EVENT_ID'				=>	$event_id,
				'EVENT_NAME'			=>	$event_name,
				'DATE_FROM'				=>	$from,
				'DATE_TO'				=>	$to,
				'CALENDAR_REPEAT'		=>	$calendar_repeat,
				'REPEAT_DM'				=>	$calendar_repeat_dm,
				'REPEAT_DAY_NUMBER'		=>	$calendar_repeat_day_number,
				'REPEAT_MONTH_NUMBER'	=>	$calendar_repeat_month_number,
				'WEEKDAY'				=>	$calendar_weekday,
				'CANCELED'				=>	$calendar_canceled,
			);
	
			if ($present & $event_id == '')
			{
				$sql = 'DELETE FROM ' . CALENDAR_TABLE . ' 
					WHERE post_id = ' . $post_id; 
				$result = $this->db->sql_query($sql);
			}

			if ($event_id > 0)
			{
				if ($present)
				{
					// Calendar-Entry change
					$sql = 'Select *
						FROM ' . CALENDAR_TABLE . '
							WHERE post_id = ' . $post_id;
					$old_result = $this->db->sql_query($sql);
					$old_row = $this->db->sql_fetchrow($old_result);
					$old_ary = array(
						'POST_ID'				=>	$old_row['post_id'],
						'EVENT_ID'				=>	$old_row['event_id'],
						'EVENT_NAME'			=>	$old_row['event_name'],
						'DATE_FROM'				=>	$old_row['date_from'],
						'DATE_TO'				=>	$old_row['date_to'],
						'CALENDAR_REPEAT'		=>	$old_row['calendar_repeat'],
						'REPEAT_DM'				=>	$old_row['repeat_dm'],
						'REPEAT_DAY_NUMBER'		=>	$old_row['repeat_day_number'],
						'REPEAT_MONTH_NUMBER'	=>	$old_row['repeat_month_number'],
						'WEEKDAY'				=>	$old_row['weekday'],
						'CANCELED'				=>	$old_row['canceled'],
					);
					if($old_ary != $sql_ary)
					{			
						$sql = 'UPDATE ' . CALENDAR_TABLE . '
							SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
								WHERE post_id = ' . $post_id;
						$result = $this->db->sql_query($sql);
					
						$notify = 'calendar_notify_change';
					}
					else
					{
						$notify = false; 
					}
				}
				else
				{
					// New Calendar-Entry
					$sql = 'INSERT INTO ' . CALENDAR_TABLE . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);	
					$result = $this->db->sql_query($sql);
					
					$notify = 'calendar_notify';
					
				}
				if($notify && $this->config['hjw_calendar_notify'] == true)
				{
					calendar_messenger($notify, $event['data']['poster_id'], $post_id, '');
				}
			}
		}
	}
}

function calendar_messenger($mode, $author_id, $post_id, $pd)
{
	global $user, $db, $phpbb_root_path, $phpEx, $config;
	include_once($phpbb_root_path . 'includes/functions_messenger.' . $phpEx);

	$sql = 'SELECT poster_id, forum_id, post_subject
		FROM ' . POSTS_TABLE . '
			WHERE post_id = ' . $post_id;
	$post_result = $db->sql_query($sql);
	$post_row = $db->sql_fetchrow($post_result);

	$sql = 'SELECT username
		FROM ' . USERS_TABLE . '
			WHERE user_id = ' . $author_id;
	$user_result = $db->sql_query($sql);
	$user_row = $db->sql_fetchrow($user_result);
	$author = $user_row['username'];
	$part_user = array();
	$poster = false;

	if(($mode == 'calendar_participants' || $mode == 'calendar_participants_change') && $config['hjw_calendar_notify_participating'] == true && $config['hjw_calendar_notify_participating_adress'] != 1)
	{
		if($config['hjw_calendar_notify_participating_adress'] != 2)
		{
			$sql = 'SELECT user_id
				FROM ' . CALENDAR_PARTICIPANTS_TABLE . '
					WHERE post_id = ' . $post_id;
			$part_result = $db->sql_query($sql);
			while($part_row = $db->sql_fetchrow($part_result))
			{
				$part_user[] = $part_row['user_id'];
				if($part_row['user_id'] == $post_row['poster_id'])
				{
					$poster = true;
				}
			}
		}
		
		if($poster == false)
		{
			$part_user[] = $post_row['poster_id'];
		}

		for($i=0;$i<count($part_user);$i++)
		{

			$sql = 'SELECT username, user_email, user_lang, user_inactive_reason
				FROM ' . USERS_TABLE . '
					WHERE user_id = ' . $part_user[$i];
		
			$user_result = $db->sql_query($sql);
			$user_row = $db->sql_fetchrow($user_result);
			
			calendar_messenger_send($mode, $user_row['user_lang'], $user_row['user_email'], $config['sitename'], $author, $user_row['username'], $user_row['user_inactive_reason'], $post_row['post_subject'], $post_row['forum_id'], $post_id, $part_user[$i], $author_id, $pd);
		}
	}
	else
	{
		$sql = 'SELECT user_id, username, user_email, user_lang, user_inactive_reason
			FROM ' . USERS_TABLE;
		
		$user_result = $db->sql_query($sql);
		while($user_row = $db->sql_fetchrow($user_result))
		{
			calendar_messenger_send($mode, $user_row['user_lang'], $user_row['user_email'], $config['sitename'], $author, $user_row['username'], $user_row['user_inactive_reason'], $post_row['post_subject'], $post_row['forum_id'], $post_id, $user_row['user_id'] ,$author_id, $pd);
		}
	}
}

function calendar_messenger_send($mode, $lang, $email, $sitename, $author, $username, $inactive, $subject, $forum_id, $post_id, $user_id, $author_id, $pd)
{
	global $phpbb_root_path, $auth, $db;
	$url = generate_board_url();

	$sql = 'SELECT ban_userid
		FROM ' . BANLIST_TABLE . '
			WHERE ban_userid = ' . $user_id;
	$ban_result = $db->sql_query($sql);
	$ban_row = $db->sql_fetchrow($ban_result);
	if(!$ban_row)
	{
		if($username && $email && $lang && !$inactive && $user_id != $author_id)
		{
			$auth_array = $auth->acl_raw_data($user_id, 'f_read', $forum_id);
			if(isset($auth_array[$user_id][$forum_id]['f_read']) && $auth_array[$user_id][$forum_id]['f_read'])
			{
				$mail_template_path = $phpbb_root_path . 'ext/hjw/calendar/language/' . $lang . '/email/';

				$messenger = new \messenger();
				$messenger->template($mode, $lang , $mail_template_path);
				$messenger->to($email, $sitename);
				$messenger->assign_vars(array(
					'AUTHOR'   			=> $author,
					'USERNAME'   		=> $username,
					'EVENT_NAME' 		=> $subject,
					'U_CALENDAR_EVENT' 	=> $url . '/viewtopic.php?' . $pd . 'p=' . $post_id,
				));
				$messenger->send();
			}
		}
	}
}

function upcoming_events($upcoming)
{
	global $db, $user, $auth, $phpbb_root_path, $config, $wday, $c_event, $c_c, $c_from, $c_to, $c_name, $c_link, $c_part, $c_nr, $c_title, $c_color, $c_bcolor, $c_big, $c_canceled;

		$post_id	= $upcoming['post_id'];
		$event_id 	= $upcoming['event_id'];
		$event_name	= $upcoming['event_name'];
		$canceled	= $upcoming['canceled'];

		$sql = 'SELECT *
			FROM ' . CALENDAR_EVENT_TABLE . '
				WHERE id = ' . $event_id;
		$result = $db->sql_query($sql);
		$row	= $db->sql_fetchrow($result);
		
		$color	= $row['color'];
		$big	= $row['big'];
		$bcolor	= $row['bcolor'];
			
		$sql = 'SELECT *
			FROM ' . POSTS_TABLE . '
				WHERE post_id = ' . $post_id; 
		$post_result = $db->sql_query($sql);
		$post_row = $db->sql_fetchrow($post_result);
		$db->sql_freeresult($post_result);
		if ($post_row)
		{
			if ($post_row['post_visibility'] == 1)
			{
				$user_id = $user->data['user_id'];
				$auth_array = $auth->acl_raw_data($user_id, 'f_read', $post_row['forum_id']);
				if (isset($auth_array[$user_id][$post_row['forum_id']]['f_read']) && $auth_array[$user_id][$post_row['forum_id']]['f_read'])
				{	
					$bdate='';
					$event_date = '';
					if ($upcoming['calendar_repeat'])
					{
						$bdate = 'd=' . strtotime(($upcoming['date_from'].' 12:00:00')) . '&';
						$upcoming['date_to'] = '0000-00-00';
						$event_date = $upcoming['date_from'];
					}

					$link 	= append_sid($phpbb_root_path . 'viewtopic.php?' . $bdate . 'p=' . $post_row['post_id'] . '#p' . $post_row['post_id']);
					$subject = $post_row['post_subject'];
					$number = 0;
					$nr = '';
					$part='';
	
					if($config['hjw_calendar_number_participating'])
					{
						$sql = 'SELECT *
							FROM ' . CALENDAR_PARTICIPANTS_TABLE . '
								WHERE post_id = ' . $post_id . "
									AND event_date = '" . $event_date  . "'";
						$result = $db->sql_query($sql);
						while($part_row = $db->sql_fetchrow($result))
						{
							$sql = 'SELECT user_colour, username
								FROM ' . USERS_TABLE . '
									WHERE user_id = ' . $part_row['user_id'];
							$user_result = $db->sql_query($sql);
							$user_row = $db->sql_fetchrow($user_result);
		
							if ($part_row['participants'] == 'yes')
							{
								$number += (int)$part_row['number'];
								if($config['hjw_calendar_participants_name'])
								{
									$part .= chr(10) . $user_row['username'] . ' [' . (int)$part_row['number'] . ']';
								}
							}
						}
					}

					$c_c++;
					$c_from[$c_c]		= date("Y-m-d", strtotime($upcoming['date_from']));
					$c_to[$c_c]			= date("Y-m-d", strtotime($upcoming['date_to']));
					$c_name[$c_c]		= $event_name;
					$c_title[$c_c]		= $subject; 
					$c_part[$c_c]		= $part;
					$c_nr[$c_c]			= $number;
					$c_link[$c_c]		= $link;
					$c_color[$c_c]		= $color;
					$c_bcolor[$c_c]		= $bcolor;
					$c_big[$c_c]		= $big;
					$c_canceled[$c_c]	= $canceled;
				}
			}
		}
		else
		{
			$sql = 'DELETE FROM ' . CALENDAR_TABLE . ' 
				WHERE post_id = ' . $post_id; 
			$result = $db->sql_query($sql);
		}
	}

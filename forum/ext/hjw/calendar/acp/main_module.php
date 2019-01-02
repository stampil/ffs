<?php
/**
*
* @package hjw calendar Extension
* @copyright (c) 2019 hjw
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace hjw\calendar\acp;


class main_module
{
	var $u_action;
	public function main()
	{
		global $db, $user, $request, $template, $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		$this->db = $db;
		$this->user = $user;
		$this->template = $template;
		$this->config = $config;
		$this->request = $request;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->phpbb_admin_path = $phpbb_admin_path;
		$this->phpex = $phpEx;
		$this->root_path = $phpbb_root_path . 'ext/hjw/calendar/';
		$this->user->add_lang('acp/common');
		$this->page_title = $this->user->lang('ACP_CALENDAR_TITLE');

		include($this->root_path . 'includes/constants.' . $this->phpex);
		
		$football = false;
		$sql = 'SELECT *
			FROM ' . EXT_TABLE . '
				ORDER by ext_name';
		$result = $this->db->sql_query($sql);
		while($row = $this->db->sql_fetchrow($result))
		{
			if($row['ext_name'] == 'football/football' &&	$row['ext_active'] == 1)
			{
				$football = true;
				$this->template->assign_vars(array(
					'CALENDAR_FOOTBALL'	=> true,
				));
			}
		}

		$day_name = array(
			0 => $this->user->lang['datetime']['Sunday'],
			1 => $this->user->lang['datetime']['Monday'],
			2 => $this->user->lang['datetime']['Tuesday'],
			3 => $this->user->lang['datetime']['Wednesday'],
			4 => $this->user->lang['datetime']['Thursday'],
			5 => $this->user->lang['datetime']['Friday'],
			6 => $this->user->lang['datetime']['Saturday'],
		);

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

		$form_key = 'acp_calendar';
		add_form_key($form_key);

		$mode = $this->request->variable('mode', 'instructions');	

		switch($mode)
		{
			case 'instructions':
				$this->tpl_name = 'acp_calendar_instructions';
			break;
			
			case 'displayoptions':

				$this->config->set('hjw_calendar_tab', $this->request->variable('hjw_calendar_tab', $this->config['hjw_calendar_tab']));
				for ($i=0;$i<=1;$i++)
				{
					$s=''; 
					if ($this->config['hjw_calendar_tab'] == $i) $s =' selected="selected"'; 
					$this->template->assign_block_vars('tab', array(
						'SELECT' =>'<option' . $s . ' value="' . $i . '">' . $this->user->lang('ACP_CALENDAR_TAB_' . $i) . '</option>',
					));	
				}

				$this->config->set('hjw_calendar_week_start', $this->request->variable('hjw_calendar_week_start', $this->config['hjw_calendar_week_start']));
				for ($i=0;$i<=6;$i++)
				{
					$s=''; 
					if ($this->config['hjw_calendar_week_start'] == $i) $s =' selected="selected"'; 
					$this->template->assign_block_vars('weekstart', array(
						'SELECT' =>'<option' . $s . ' value="' . $i . '">' . $day_name[$i] . '</option>',
					));	
				}

				$this->config->set('hjw_calendar_week_display', $this->request->variable('hjw_calendar_week_display', $this->config['hjw_calendar_week_display']));
				$jn[0] = $jn[1] = '';
				$jn[$this->config['hjw_calendar_week_display']] =' checked="checked"';
				$this->template->assign_vars(array(
					'U_ACTION'		=> $this->u_action,
					'CWD1'			=> $jn[1],
					'CWD0'			=> $jn[0],
				));

				$this->config->set('hjw_calendar_legend_display', $this->request->variable('hjw_calendar_legend_display', $this->config['hjw_calendar_legend_display']));
				$jn[0] = $jn[1] = '';
				$jn[$this->config['hjw_calendar_legend_display']] =' checked="checked"';
				$this->template->assign_vars(array(
					'U_ACTION'		=> $this->u_action,
					'CLD1'			=> $jn[1],
					'CLD0'			=> $jn[0],
				));

				$this->config->set('hjw_calendar_for_guests', $this->request->variable('hjw_calendar_for_guests', $this->config['hjw_calendar_for_guests']));
				$jn[0] = $jn[1] = '';
				$jn[$this->config['hjw_calendar_for_guests']] =' checked="checked"';
				$this->template->assign_vars(array(
					'U_ACTION'		=> $this->u_action,
					'CFG1'			=> $jn[1],
					'CFG0'			=> $jn[0],
				));

				$this->config->set('hjw_calendar_birthday_on_calendar', $this->request->variable('hjw_calendar_birthday_on_calendar', $this->config['hjw_calendar_birthday_on_calendar']));
				$jn[0] = $jn[1] = '';
				$jn[$this->config['hjw_calendar_birthday_on_calendar']] =' checked="checked"';
				$this->template->assign_vars(array(
					'U_ACTION'		=> $this->u_action,
					'BOC1'			=> $jn[1],
					'BOC0'			=> $jn[0],
				));

				$this->config->set('hjw_calendar_only_first_post', $this->request->variable('hjw_calendar_only_first_post', $this->config['hjw_calendar_only_first_post']));
				$jn[0] = $jn[1] = '';
				$jn[$this->config['hjw_calendar_only_first_post']] =' checked="checked"';
				$this->template->assign_vars(array(
					'U_ACTION'		=> $this->u_action,
					'COFP1'			=> $jn[1],
					'COFP0'			=> $jn[0],
				));

				$this->config->set('hjw_calendar_week_or_next', $this->request->variable('hjw_calendar_week_or_next', $this->config['hjw_calendar_week_or_next']));
				for ($i=1;$i<=3;$i++)
				{
					$s=''; 
					if ($this->config['hjw_calendar_week_or_next'] == $i) $s =' selected="selected"'; 
					$this->template->assign_block_vars('weeknext', array(
						'SELECT' =>'<option' . $s . ' value="' . $i . '">' . $this->user->lang('ACP_WEEK_NEXT_' . $i) . '</option>',
					));	
				}
	
				$this->config->set('hjw_calendar_week_on_index', $this->request->variable('hjw_calendar_week_on_index', $this->config['hjw_calendar_week_on_index']));
				for ($i=0;$i<=3;$i++)
				{
					$s=''; 
					if ($this->config['hjw_calendar_week_on_index'] == $i) $s =' selected="selected"'; 
					$this->template->assign_block_vars('weekblock', array(
						'SELECT' =>'<option' . $s . ' value="' . $i . '">' . $this->user->lang('ACP_WEEKBLOCK_TEMPLATE_' . $i) . '</option>',
					));	
				}

				$this->config->set('hjw_calendar_number_of_weeks', $this->request->variable('hjw_calendar_number_of_weeks', $this->config['hjw_calendar_number_of_weeks']));
				if ($this->config['hjw_calendar_number_of_weeks'] == 0)
				{
					$this->config->set('hjw_calendar_number_of_weeks', 1);
				}
				$this->template->assign_vars(array(
					'NUMBER_OF_WEEKS' => $this->config['hjw_calendar_number_of_weeks'],
				));	

				$this->config->set('hjw_calendar_on_index_off', $this->request->variable('hjw_calendar_on_index_off', $this->config['hjw_calendar_on_index_off']));
				$jn[0] = $jn[1] = '';
				$jn[$this->config['hjw_calendar_on_index_off']] =' checked="checked"';
				$this->template->assign_vars(array(
					'U_ACTION'		=> $this->u_action,
					'COIO1'			=> $jn[1],
					'COIO0'			=> $jn[0],
				));

				$this->config->set('hjw_calendar_number_participating', $this->request->variable('hjw_calendar_number_participating', $this->config['hjw_calendar_number_participating']));
				$jn[0] = $jn[1] = '';
				$jn[$this->config['hjw_calendar_number_participating']] =' checked="checked"';
				$this->template->assign_vars(array(
					'U_ACTION'		=> $this->u_action,
					'CNP1'			=> $jn[1],
					'CNP0'			=> $jn[0],
				));

				$this->config->set('hjw_calendar_participants_name', $this->request->variable('hjw_calendar_participants_name', $this->config['hjw_calendar_participants_name']));
				$jn[0] = $jn[1] = '';
				$jn[$this->config['hjw_calendar_participants_name']] =' checked="checked"';
				$this->template->assign_vars(array(
					'U_ACTION'		=> $this->u_action,
					'CPN1'			=> $jn[1],
					'CPN0'			=> $jn[0],
				));

				$this->config->set('hjw_calendar_notify', $this->request->variable('hjw_calendar_notify', $this->config['hjw_calendar_notify']));
				$jn[0] = $jn[1] = '';
				$jn[$this->config['hjw_calendar_notify']] =' checked="checked"';
				$this->template->assign_vars(array(
					'U_ACTION'		=> $this->u_action,
					'CN1'			=> $jn[1],
					'CN0'			=> $jn[0],
				));

				$this->config->set('hjw_calendar_notify_participating', $this->request->variable('hjw_calendar_notify_participating', $this->config['hjw_calendar_notify_participating']));
				$jn[0] = $jn[1] = '';
				$jn[$this->config['hjw_calendar_notify_participating']] =' checked="checked"';
				$this->template->assign_vars(array(
					'U_ACTION'		=> $this->u_action,
					'CNPP1'			=> $jn[1],
					'CNPP0'			=> $jn[0],
				));

				$this->config->set('hjw_calendar_notify_participating_adress', $this->request->variable('hjw_calendar_notify_participating_adress', $this->config['hjw_calendar_notify_participating_adress']));
				$jn[0] = $jn[1] = $jn[2] = '';
				$jn[$this->config['hjw_calendar_notify_participating_adress']] =' checked="checked"';
				$this->template->assign_vars(array(
					'U_ACTION'		=> $this->u_action,
					'CNPPA2'			=> $jn[2],
					'CNPPA1'			=> $jn[1],
					'CNPPA0'			=> $jn[0],
				));
				if($football)
				{
					$this->config->set('hjw_calendar_football', $this->request->variable('hjw_calendar_football', $this->config['hjw_calendar_football']));
					$jn[0] = $jn[1] = '';
					$jn[$this->config['hjw_calendar_football']] =' checked="checked"';
					$this->template->assign_vars(array(
						'U_ACTION'		=> $this->u_action,
						'CFB1'			=> $jn[1],
						'CFB0'			=> $jn[0],
					));
				}
				
				$this->tpl_name = 'acp_calendar_displayoptions';
			break;

			case 'settings':
				$this->tpl_name = 'acp_calendar_event_settings';
				$action	= $this->request->variable('action', '');
				$id 	= $this->request->variable('id', 0);
				switch ($action)
				{
					case 'add':
						$this->template->assign_vars(array(
							'ID' 					=> '',
							'EVENT' 				=> '',
							'COLOR' 				=> '',
							'PARTICIPANTS'			=> 0,
							'BIG'					=> 0,
							'BCOLOR' 				=> '',
							'U_MODIFY'				=> $this->u_action . '&amp;action=create&amp;id=' . $id,
							'S_EDIT_CALENDAR_EVENT'	=> true,
						));
					break;

					case 'edit':
						$sql = 'SELECT *
							FROM ' . CALENDAR_EVENT_TABLE . '
							WHERE id = ' . $id;
						$result = $this->db->sql_query($sql);
						$row = $this->db->sql_fetchrow($result);
						$this->db->sql_freeresult($result);

						$this->template->assign_vars(array(
							'ID' 					=> $row['id'],
							'EVENT' 				=> $row['event'],
							'COLOR' 				=> $row['color'],
							'PARTICIPANTS'			=> $row['participants'],
							'BIG'					=> $row['big'],
							'BCOLOR' 				=> $row['bcolor'],
							'U_MODIFY'				=> $this->u_action . '&amp;action=modify&amp;id=' . $row['id'],
							'S_EDIT_CALENDAR_EVENT'	=> true,
							));
					break;

					case 'delete':
						$sql = 'DELETE
							FROM ' . CALENDAR_EVENT_TABLE . '
							WHERE id = ' . $id;
						$result = $this->db->sql_query($sql);
						$row = $this->db->sql_fetchrow($result);
						$this->db->sql_freeresult($result);
					break;

					case 'modify':
						$sql_ary = array(
							'EVENT'				=> utf8_normalize_nfc($this->request->variable('event', '', true)),
							'COLOR'				=> $this->request->variable('color', ''),
							'PARTICIPANTS'		=> $this->request->variable('participants', 0),
							'BIG'				=> $this->request->variable('big', 0),
							'BCOLOR'			=> $this->request->variable('bcolor', ''),
						);
						$sql = 'UPDATE
							' . CALENDAR_EVENT_TABLE . '
							SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
							WHERE id = ' . $id;
						$this->db->sql_query($sql);
					break;
			
					case 'create':
						$sql_ary = array(
							'EVENT'				=> utf8_normalize_nfc($this->request->variable('event', '', true)),
							'COLOR'				=> $this->request->variable('color', ''),
							'PARTICIPANTS'		=> $this->request->variable('participants', 0),
							'BIG'				=> $this->request->variable('big', 0),
							'BCOLOR'			=> $this->request->variable('bcolor', ''),
						);
						$sql = 'INSERT INTO ' . CALENDAR_EVENT_TABLE . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
						$this->db->sql_query($sql);
					break;

					case 'up':
						$sort = $this->request->variable('sort', '');
						$sql = 'SELECT *
							FROM ' . CALENDAR_EVENT_TABLE . '
								WHERE sort =' . ($sort-1);
						$result = $this->db->sql_query($sql);
						$row = $this->db->sql_fetchrow($result);
						$sql = 'UPDATE
							' . CALENDAR_EVENT_TABLE . '
								SET sort = ' . $sort . '
									WHERE id = ' . $row['id'];
						$this->db->sql_query($sql);
						$sql = 'UPDATE
							' . CALENDAR_EVENT_TABLE . '
								SET sort = ' . ($sort-1) . '
									WHERE id = ' . $id;
						$this->db->sql_query($sql);
					break;

					case 'down':
						$sort = $this->request->variable('sort', '');
						$sql = 'SELECT *
							FROM ' . CALENDAR_EVENT_TABLE . '
								WHERE sort =' . ($sort+1);
						$result = $this->db->sql_query($sql);
						$row = $this->db->sql_fetchrow($result);
						$sql = 'UPDATE
							' . CALENDAR_EVENT_TABLE . '
								SET sort = ' . $sort . '
									WHERE id = ' . $row['id'];
						$this->db->sql_query($sql);
						$sql = 'UPDATE
							' . CALENDAR_EVENT_TABLE . '
								SET sort = ' . ($sort+1) . '
									WHERE id = ' . $id;
						$this->db->sql_query($sql);
					break;
			
				}
				// Set sort for existing forums
				sort:
				$sql = 'SELECT *
					FROM ' . CALENDAR_EVENT_TABLE . '
					ORDER by sort';
				$result = $this->db->sql_query($sql);
				while($row = $this->db->sql_fetchrow($result))
				{	
					if ($row['sort'] == 0) 
					{
						$row['sort'] = $row['id'];
						$sql = 'UPDATE
							' . CALENDAR_EVENT_TABLE . '
								SET sort = ' . $row['sort'] . '
									WHERE id = ' . $row['id'];
						$this->db->sql_query($sql);
						goto sort;
					}
					// End set sort for existing forums
				
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
						'ID' 			=> $row['id'],
						'EVENT' 		=> $b.$row['event'].$nb,
						'COLOR' 		=> $row['color'],
						'PARTICIPANTS'	=> $b.$this->user->lang['ACP_CALENDAR_'.$row['participants'].''].$nb,
						'BCOLOR'		=> $row['bcolor'],
						'U_EDIT'		=> $this->u_action . '&amp;action=edit&amp;id=' . $row['id'],
						'U_DELETE'		=> $this->u_action . '&amp;action=delete&amp;id=' . $row['id'],
						'U_MOVE_UP'		=> $this->u_action . '&amp;action=up&amp;id=' . $row['id'] . '&amp;sort=' . $row['sort'],
						'U_MOVE_DOWN'	=> $this->u_action . '&amp;action=down&amp;id=' . $row['id'] . '&amp;sort=' . $row['sort'],
					));	
				}
				$this->template->assign_vars(array(
					'U_ACTION'				=> $this->u_action . '&amp;action=add',
					'S_CALENDAR_VERSION'	=> $this->user->lang['ACP_CALENDAR_TITLE'] . $this->user->lang['ACP_CALENDAR_VERSION'] . $this->config['hjw_calendar_version'],
				));
			break;

			case 'forums_settings':
				$this->tpl_name = 'acp_calendar_forums_settings';
				$action	= $this->request->variable('action', '');
				$this->template->assign_vars(array(
					'U_CALENDAR_FORUM'		=> $this->u_action . '&amp;action=forum',
				));
		
				if ($action == 'forum')
				{
					$forum_id = (int)$this->request->variable('forum', '0');
					$sql = 'SELECT *
						FROM ' . CALENDAR_FORUMS_TABLE . '
						WHERE forum_id = ' . $forum_id;
				
					$result = $this->db->sql_query($sql);
					$row = $this->db->sql_fetchrow($result);
					$this->db->sql_freeresult($result);

					$row['allowed'] ++;
					if ($row['allowed'] > 1) $row['allowed'] = 0 ;

					$sql_ary = array(
						'ALLOWED' 		=> $row['allowed'],
					);
				
					$sql = 'UPDATE
						' . CALENDAR_FORUMS_TABLE . '
						SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
						WHERE forum_id = ' . $forum_id;
					$this->db->sql_query($sql);

				}
				$forum_list = make_forum_select(false, false, true, true, true, false, true);

				foreach ($forum_list as $list_row)
				{
			
					$sql = 'SELECT *
						FROM ' . CALENDAR_FORUMS_TABLE . '
						WHERE forum_id = ' . $list_row['forum_id'];
					$result = $this->db->sql_query($sql);
					$row = $this->db->sql_fetchrow($result);
					$this->db->sql_freeresult($result);
					if (!$row)
					{
						$sql_ary = array(
							'FORUM_ID' 				=> $list_row['forum_id'],
							'ALLOWED' 				=> 0,
						);
						$sql = 'INSERT INTO ' . CALENDAR_FORUMS_TABLE . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
						$this->db->sql_query($sql);
						$color = 'red';
						$text = $this->user->lang['ACP_CALENDAR_ALLOWED_0'].'  &gt; '.$this->user->lang['ACP_CALENDAR_CHANGE'];
					}
					else
					{
						$color = 'green';
						if ($row['allowed'] == 0) 
						{
							$color = 'red';
						}
						$text = $this->user->lang['ACP_CALENDAR_ALLOWED_'.$row['allowed'].''].'  > '.$this->user->lang['ACP_CALENDAR_CHANGE'];
					}
					if ($list_row['forum_type'] == 0)	$fcolor="#BBBBBB";
					if ($list_row['forum_type'] == 1)	$fcolor=$color;
				
					$this->template->assign_block_vars('forum',array(
						'FORUM'	=> $list_row['padding'].$list_row['forum_name'],
						'COLOR'	=> $fcolor,
						'ID'	=> $this->u_action . '&amp;action=forum&amp;forum='.$list_row['forum_id'],
						'TEXT'	=> $text,
						'TYPE'	=> $list_row['forum_type'],
					));
					
				}

			break;
	
			case 'event_list':
				$this->tpl_name = 'acp_calendar_event_list';

				$action		= $this->request->variable('action', '');
				$id 		= $this->request->variable('id', 0);
				$from_year	= substr(str_pad($this->request->variable('from_year', ''), 4 ,'0', STR_PAD_LEFT),0,4);
				$from_month = str_pad($this->request->variable('from_month', ''), 2 ,'0', STR_PAD_LEFT);
				$from_day	= str_pad($this->request->variable('from_day', ''), 2 ,'0', STR_PAD_LEFT);
				$to_year	= substr(str_pad($this->request->variable('to_year', ''), 4 ,'0', STR_PAD_LEFT),0,4);
				$to_month 	= str_pad($this->request->variable('to_month', ''), 2 ,'0', STR_PAD_LEFT);
				$to_day		= str_pad($this->request->variable('to_day', ''), 2 ,'0', STR_PAD_LEFT);

				switch ($action)
				{
					case 'add':
						$this->template->assign_block_vars('to_day', array(
							'SELECT' =>'<option value="0"> </option>',
						));	
						for ($i=1;$i<=31;$i++)
						{
							$this->template->assign_block_vars('from_day', array(
								'SELECT' =>'<option value="' . $i . '">' . $i . '</option>',
							));	
							$this->template->assign_block_vars('to_day', array(
								'SELECT' =>'<option value="' . $i . '">' . $i . '</option>',
							));	
						}
						$this->template->assign_block_vars('to_month', array(
							'SELECT' =>'<option value="0"> </option>',
						));	
						for ($i=1;$i<=12;$i++)
						{
							$this->template->assign_block_vars('from_month', array(
								'SELECT' =>'<option value="'.$i.'">'.$month_name[$i].'</option>',
							));	
							$this->template->assign_block_vars('to_month', array(
								'SELECT' =>'<option value="'.$i.'">'.$month_name[$i].'</option>',
							));	
						}
						$this->template->assign_vars(array(
							'ID' 					=> '',
							'APPOINTMENT' 			=> '',
							'DESCRIPTION' 			=> '',
							'LINK'					=> '',
							'ANNIVERSARY' 			=> '',
							'DATE_FROM_YEAR' 		=> '',
							'DATE_FROM_MONTH' 		=> '',
							'DATE_FROM_DAY'			=> '',
							'DATE_TO_YEAR' 			=> '',
							'DATE_TO_MONTH' 		=> '',
							'DATE_TO_DAY' 			=> '',
							'COLOR'					=> '',
							'BIG'					=> 0,
							'BCOLOR'				=> '',
							'U_MODIFY'				=> $this->u_action . '&amp;action=create&amp;id=' . $id,
							'S_EDIT_CALENDAR_EVENT'	=> true,
						));
					break;

					case 'edit':
						$sql = 'SELECT *
							FROM ' . CALENDAR_EVENT_LIST_TABLE . '
							WHERE id = ' . $id;
						$result = $this->db->sql_query($sql);
						$row = $this->db->sql_fetchrow($result);
						$this->db->sql_freeresult($result);

						$from		= $row['date_from'];	
						$r			= explode('-',$from);
						$from_year	= $r[0];
						$from_month = $r[1];
						$from_day	= $r[2];
				
						$to 		= $row['date_to'];
						$r			= explode('-',$to);
						$to_year	= $r[0];
						$to_month 	= $r[1];
						$to_day		= $r[2];
						
						for ($i=1;$i<=31;$i++)
						{
							$s = '';if ($i == $from_day) $s=' selected="selected"';  
							$this->template->assign_block_vars('from_day', array(
								'SELECT' =>'<option' . $s . ' value="' . $i . '">' . $i . '</option>',
							));	
						}
						$this->template->assign_block_vars('to_day', array(
							'SELECT' =>'<option value="0"> </option>',
						));	
						for ($i=1;$i<=31;$i++)
						{
							$s = '';if ($i == $to_day) $s=' selected="selected"';  
							$this->template->assign_block_vars('to_day', array(
								'SELECT' =>'<option' . $s . ' value="' . $i . '">' . $i . '</option>',
							));	
						}
						for ($i=1;$i<=12;$i++)
						{
							$s='';if ($i == $from_month) $s=' selected="selected"';  
							$this->template->assign_block_vars('from_month', array(
								'SELECT' =>'<option'.$s.' value="'.$i.'">'.$month_name[$i].'</option>',
							));	
						}
						$this->template->assign_block_vars('to_month', array(
							'SELECT' =>'<option value="0"> </option>',
						));	
						for ($i=1;$i<=12;$i++)
						{
							$s='';if ($i == $to_month) $s=' selected="selected"';  
							$this->template->assign_block_vars('to_month', array(
								'SELECT' =>'<option'.$s.' value="'.$i.'">'.$month_name[$i].'</option>',
							));	
						}
						if($from_year == '0000')
						{
							$from_year ='';
						}
						if($to_year == '0000')
						{
							$to_year ='';
						}
						$this->template->assign_vars(array(
							'ID' 					=> $row['id'],
							'APPOINTMENT'			=> $row['appointment'],
							'DESCRIPTION' 			=> $row['description'],
							'LINK'					=> $row['link'],
							'ANNIVERSARY' 			=> $row['anniversary'],
							'DATE_FROM_YEAR' 		=> $from_year,
							'DATE_TO_YEAR' 			=> $to_year,
							'COLOR'					=> $row['color'],
							'BIG'					=> $row['big'],	
							'BCOLOR'				=> $row['bcolor'],
							'U_MODIFY'				=> $this->u_action . '&amp;action=modify&amp;id=' . $row['id'],
							'S_EDIT_CALENDAR_EVENT'	=> true,
						));
					break;

					case 'delete':
						$sql = 'DELETE
							FROM ' . CALENDAR_EVENT_LIST_TABLE . '
							WHERE id = ' . $id;
						$result = $this->db->sql_query($sql);
						$row = $this->db->sql_fetchrow($result);
						$this->db->sql_freeresult($result);
					break;

					case 'modify':
						$sql_ary = array(
							'APPOINTMENT'			=> utf8_normalize_nfc($this->request->variable('appointment', '', true)),
							'DESCRIPTION' 			=> utf8_normalize_nfc($this->request->variable('description', '', true)),
							'LINK'					=> utf8_normalize_nfc($this->request->variable('link', '', true)),
							'ANNIVERSARY' 			=> $this->request->variable('anniversary', 0),
							'DATE_FROM' 			=> $from_year . '-' . $from_month . '-' . $from_day,
							'DATE_TO' 				=> $to_year . '-' . $to_month . '-' . $to_day,
							'COLOR'					=> $this->request->variable('color', ''),
							'BIG'					=> $this->request->variable('big', 0),
							'BCOLOR'				=> $this->request->variable('bcolor', ''),
						);
						$sql = 'UPDATE
							' . CALENDAR_EVENT_LIST_TABLE . '
								SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
								WHERE id = ' . $id;
						$this->db->sql_query($sql);
					break;
			
					case 'create':
						$sql_ary = array(
							'APPOINTMENT'			=> utf8_normalize_nfc($this->request->variable('appointment', '', true)),
							'DESCRIPTION' 			=> utf8_normalize_nfc($this->request->variable('description', '', true)),
							'LINK'					=> utf8_normalize_nfc($this->request->variable('link', '', true)),
							'ANNIVERSARY' 			=> $this->request->variable('anniversary', 0),
							'DATE_FROM' 			=> $from_year . '-' . $from_month . '-' . $from_day,
							'DATE_TO' 				=> $to_year . '-' . $to_month . '-' . $to_day,
							'COLOR'					=> $this->request->variable('color', ''),
							'BIG'					=> $this->request->variable('big', 0),
							'BCOLOR'				=> $this->request->variable('bcolor', ''),
						);
						$sql = 'INSERT INTO ' . CALENDAR_EVENT_LIST_TABLE . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
						$this->db->sql_query($sql);
					break;
				}

				$sql = 'SELECT *
					FROM ' . CALENDAR_EVENT_LIST_TABLE . '
					ORDER by id';
				$result = $this->db->sql_query($sql);
				while($row = $this->db->sql_fetchrow($result))
				{
					$from		= $row['date_from'];	
					$r			= explode('-',$from);
					$from_year	= $r[0];
					if ($from_year == '0000')
					{
						$from_year ='';
					}
					$from_month = $r[1];
					$from_day	= $r[2];
					$date_from  = $from_day.'.'.$from_month.'.';
					if ($from_year != '') $date_from .= $from_year;
				
					$to 		= $row['date_to'];
					$r			= explode('-',$to);
					$to_year	= $r[0];
					if ($to_year == '0000')
					{
						$to_year ='';
					}
					$to_month 	= $r[1];
					$to_day		= $r[2];
					$date_to	= '';
					if ($to_day)
					{
						$date_to = $to_day.'.'.$to_month.'.';
						if ($to_year != '') $date_to .= $to_year;
					}
					if ($date_to == '00.00.')
					{
						$date_to ='';
					}
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
					$this->template->assign_block_vars('calendar_appointment', array(
						'ID' 			=> $row['id'],
						'APPOINTMENT'	=> $b.$row['appointment'].$nb,
						'DESCRIPTION' 	=> $b.$row['description'].$nb,
						'LINK'			=> $b.$row['link'].$nb,
						'ANNIVERSARY' 	=> $b.$this->user->lang['ACP_CALENDAR_'.$row['anniversary'].''].$nb,
						'DATE_FROM' 	=> $b.$date_from.$nb,
						'DATE_TO' 		=> $b.$date_to.$nb,
						'COLOR'			=> $row['color'],
						'BCOLOR'		=> $row['bcolor'],
						'U_EDIT'		=> $this->u_action . '&amp;action=edit&amp;id=' . $row['id'],
						'U_DELETE'		=> $this->u_action . '&amp;action=delete&amp;id=' . $row['id'],
					));	
				}
				$this->template->assign_vars(array(
					'U_ACTION'				=> $this->u_action . '&amp;action=add',
					'S_CALENDAR_VERSION'	=> $this->user->lang['ACP_CALENDAR_TITLE'] . $this->user->lang['ACP_CALENDAR_VERSION'] . $this->config['hjw_calendar_version'],
				));
			break;

			case 'special_days':
				$this->tpl_name = 'acp_calendar_special_days';

				$action		= $this->request->variable('action', '');
				$id 		= $this->request->variable('id', 0);
				switch ($action)
				{
					case 'add':
						$this->template->assign_block_vars('day', array(
							'SELECT' =>'<option></option>',
						));	
						for ($i=1;$i<=31;$i++)
						{
							$this->template->assign_block_vars('day', array(
								'SELECT' =>'<option value="' . $i . '">' . $i . '</option>',
							));	
						}
						$this->template->assign_block_vars('month', array(
							'SELECT' =>'<option></option>',
						));	
						for ($i=1;$i<=12;$i++)
						{
							$this->template->assign_block_vars('month', array(
								'SELECT' =>'<option value="'.$i.'">'.$month_name[$i].'</option>',
							));	
						}
						$this->template->assign_vars(array(
							'ID' 					=> '',
							'NAME' 					=> '',
							'EASTERN'				=> '',
							'SHOW_ON' 				=> 0,
							'COLOR'					=> '',
							'BIG'					=> 0,
							'BCOLOR'				=> '',
							'U_MODIFY'				=> $this->u_action . '&amp;action=create&amp;id=' . $id,
							'S_EDIT_CALENDAR_EVENT'	=> true,
						));
					break;

					case 'edit':
						$sql = 'SELECT *
							FROM ' . CALENDAR_SPECIAL_DAYS_TABLE . '
							WHERE id = ' . $id;
						$result = $this->db->sql_query($sql);
						$row = $this->db->sql_fetchrow($result);
						$this->db->sql_freeresult($result);
						
						$eastern = (int)$row['eastern'];
						if ($row['date'] == '.') $row['date'] = '';
						if ($row['date']) $eastern = '';
						if ($row['name'] == 'Advent')  $eastern = '';
						if ($row['name'] == 'Buß- und Bettag')  $eastern = '';

						$this->template->assign_vars(array(
							'ID' 					=> $row['id'],
							'NAME'					=> $row['name'],
							'EASTERN' 				=> $eastern,
							'SHOW_ON' 				=> $row['show_on'],
							'COLOR'					=> $row['color'],
							'BIG'					=> $row['big'],
							'BCOLOR'				=> $row['bcolor'],
							'U_MODIFY'				=> $this->u_action . '&amp;action=modify&amp;id=' . $row['id'],
							'S_EDIT_CALENDAR_EVENT'	=> true,
						));
						$r = explode('.',$row['date'] . '.');

						$this->template->assign_block_vars('day', array(
							'SELECT' =>'<option></option>',
						));	
						for ($i=1;$i<=31;$i++)
						{
							$s = '';if ($i == $r[0]) $s=' selected="selected"';  
							$this->template->assign_block_vars('day', array(
								'SELECT' =>'<option' . $s . ' value="' . $i . '">' . $i . '</option>',
							));	
						}
						$this->template->assign_block_vars('month', array(
							'SELECT' =>'<option></option>',
						));	
						for ($i=1;$i<=12;$i++)
						{
							$s='';if ($i == $r[1]) $s=' selected="selected"';  
							$this->template->assign_block_vars('month', array(
								'SELECT' =>'<option'.$s.' value="'.$i.'">'.$month_name[$i].'</option>',
							));	
						}

					break;
		
					case 'delete':
						$sql = 'DELETE
							FROM ' . CALENDAR_SPECIAL_DAYS_TABLE . '
							WHERE id = ' . $id;
						$result = $this->db->sql_query($sql);
						$row = $this->db->sql_fetchrow($result);
						$this->db->sql_freeresult($result);
					break;

					case 'modify':
					$sql_ary = array(
							'NAME'			=> utf8_normalize_nfc($this->request->variable('name', '', true)),
							'EASTERN' 		=> $this->request->variable('eastern', 0),
							'DATE'			=> $this->request->variable('from_day', '') . '.' . $this->request->variable('from_month', ''),
							'SHOW_ON' 		=> $this->request->variable('show_on', 0),
							'COLOR'			=> $this->request->variable('color', ''),
							'BIG'			=> $this->request->variable('big', 0),
							'BCOLOR'		=> $this->request->variable('bcolor', ''),
						);
						$sql = 'UPDATE
							' . CALENDAR_SPECIAL_DAYS_TABLE . '
								SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
								WHERE id = ' . $id;
						$this->db->sql_query($sql);
					break;
			
					case 'create':
						$sql_ary = array(
							'NAME'			=> utf8_normalize_nfc($this->request->variable('name', '', true)),
							'EASTERN' 		=> $this->request->variable('eastern', 0),
							'DATE'			=> $this->request->variable('from_day', '') . '.' . $this->request->variable('from_month', ''),
							'SHOW_ON' 		=> $this->request->variable('show_on', 0),
							'COLOR'			=> $this->request->variable('color', ''),
							'BIG'			=> $this->request->variable('big', 0),
							'BCOLOR'		=> $this->request->variable('bcolor', ''),
						);
						$sql = 'INSERT INTO ' . CALENDAR_SPECIAL_DAYS_TABLE . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
						$this->db->sql_query($sql);
					break;
				}

				$sql = 'SELECT *
					FROM ' . CALENDAR_SPECIAL_DAYS_TABLE . '
					ORDER by id';
				$result = $this->db->sql_query($sql);
				while($row = $this->db->sql_fetchrow($result))
				{
					$eastern = (int)$row['eastern'];
					if ($row['date'] == '.') $row['date'] = '';
					if ($row['date']) $eastern = '';
					if ($row['name'] == 'Advent')  $eastern = '';
					if ($row['name'] == 'Buß- und Bettag')  $eastern = '';
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
					$this->template->assign_block_vars('calendar_special_day', array(
						'ID' 			=> $row['id'],
						'NAME'			=> $b.$row['name'].$nb,
						'EASTERN' 		=> $b.$eastern.$nb,
						'DATE'			=> $b.$row['date'].$nb,
						'SHOW_ON' 		=> $b.$this->user->lang['ACP_CALENDAR_'.$row['show_on'].''].$nb,
						'COLOR'			=> $row['color'],
						'BCOLOR'		=> $row['bcolor'],
						'U_EDIT'		=> $this->u_action . '&amp;action=edit&amp;id=' . $row['id'],
						'U_DELETE'		=> $this->u_action . '&amp;action=delete&amp;id=' . $row['id'],
					));	
				}
				$this->template->assign_vars(array(
					'U_ACTION'				=> $this->u_action . '&amp;action=add',
					'S_CALENDAR_VERSION'	=> $this->user->lang['ACP_CALENDAR_TITLE'] . $this->user->lang['ACP_CALENDAR_VERSION'] . $this->config['hjw_calendar_version'],
				));
			break;
		}
	}
}

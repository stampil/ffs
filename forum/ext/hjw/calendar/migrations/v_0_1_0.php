<?php
/**
*
* @package hjw calendar Extension
* @copyright (c) 2015 hjw
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace hjw\calendar\migrations;

class v_0_1_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['calendar_version']) && version_compare($this->config['calendar_version'], '0.1.0', '>=');
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v320\dev');
	}

	public function update_schema()
	{
		return array(
			'add_tables'		=> array(
				$this->table_prefix . 'calendar_event'	=> array(
					'COLUMNS'				=> array(
						'id'				=> array('UINT', NULL, 'auto_increment'),
						'event'				=> array('VCHAR:255', ''),
						'color'				=> array('VCHAR:16', ''),
						'participants'		=> array('INT:1', 0),
					),					
					'PRIMARY_KEY'	=> 'id',
				),
				$this->table_prefix . 'calendar'	=> array(
					'COLUMNS'				=> array(
						'post_id'			=> array('UINT', 0),
						'event_id'			=> array('UINT', 0),
						'event_name'		=> array('VCHAR:255', ''),
						'date_from'			=> array('VCHAR:10', ''),
						'date_to'			=> array('VCHAR:10', ''),
					),					
					'PRIMARY_KEY'	=> 'post_id',
				),
				$this->table_prefix . 'calendar_participants'	=> array(
					'COLUMNS'				=> array(
						'post_id'			=> array('UINT', 0),
						'user_id'			=> array('UINT', 0),
						'number'			=> array('UINT', 0),
						'participants'		=> array('VCHAR:255', ''),
						'comments'			=> array('VCHAR:255', ''),
						'date'				=> array('VCHAR:10', ''),
					),					
				),
				$this->table_prefix . 'calendar_forums'	=> array(
					'COLUMNS'				=> array(
						'forum_id'			=> array('UINT', 0),
						'allowed'			=> array('INT:1', 0),
					),					
					'PRIMARY_KEY'	=> 'forum_id',
				),
			
			),
		);
	}
	public function update_data()
	{
		return array(
			array('config.add', array('calendar_version', '0.1.0')),
			array('config.add', array('week_on_index', 2, true)), 
			array('config.add', array('number_of_weeks', 1, true)), 
			array('config.add', array('birthday_on_calendar', 0, true)), 
			array('config.add', array('calendar_for_guests', 0, true)), 
			array('config.add', array('calendar_only_first_post', 1, true)), 
			array('config.add', array('calendar_number_participating', 1, true)), 
			array('config.add', array('calendar_participants_name', 1, true)), 
			array('config.add', array('calendar_tab', 1, true)), 
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_tables'		=> array(
				$this->table_prefix . 'calendar_event',
				$this->table_prefix . 'calendar',
				$this->table_prefix . 'calendar_participants',
				$this->table_prefix . 'calendar_forums',
			),
		);
	}
}
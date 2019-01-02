<?php
/**
*
* @package hjw calendar Extension
* @copyright (c) 2015 hjw
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace hjw\calendar\migrations;

class v_0_2_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['calendar_version']) && version_compare($this->config['calendar_version'], '0.2.0', '>=');
	}

	static public function depends_on()
	{
			return array('\hjw\calendar\migrations\v_0_1_0');
	}

	public function update_schema()
	{
		return array(
			'add_tables'		=> array(
				$this->table_prefix . 'calendar_event_list'	=> array(
					'COLUMNS'			=> array(
						'id'			=> array('UINT', null, 'auto_increment'),
						'appointment'	=> array('VCHAR:255', ''),
						'description'	=> array('VCHAR:255', ''),
						'link'			=> array('VCHAR:255', ''),
						'anniversary'	=> array('UINT', 0),
						'date_from'		=> array('VCHAR:10', ''),
						'date_to'		=> array('VCHAR:10', ''),
						'color'			=> array('VCHAR:10', ''),
					),					
					'PRIMARY_KEY'	=> 'id',
				),
				$this->table_prefix . 'calendar_special_days'	=> array(
					'COLUMNS'			=> array(
						'id'			=> array('UINT', null, 'auto_increment'),
						'name'			=> array('VCHAR:255', ''),
						'eastern'		=> array('INT:1', 0),
						'date'			=> array('VCHAR:10', ''),
						'show_on'		=> array('INT:1', 0),
						'color'			=> array('VCHAR:10', ''),
					),					
					'PRIMARY_KEY'	=> 'id',
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_tables'		=> array(
				$this->table_prefix . 'calendar_event_list',
				$this->table_prefix . 'calendar_special_days',
			),
		);
	}

	public function update_data()
	{
		return array(
			array('config.update', array('calendar_version', '0.2.0')),

			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_CALENDAR_TITLE'
			)),
			array('module.add', array(
				'acp',
				'ACP_CALENDAR_TITLE',
				array(
					'module_basename'	=> '\hjw\calendar\acp\main_module',
					'modes'				=> array('instructions','displayoptions','settings','forums_settings','event_list','special_days'),
				),
			)),
		);
	}
}
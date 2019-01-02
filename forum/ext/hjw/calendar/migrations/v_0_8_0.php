<?php
/**
*
* @package hjw calendar Extension
* @copyright (c) 2019 hjw
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace hjw\calendar\migrations;

class v_0_8_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['calendar_version']) && version_compare($this->config['calendar_version'], '0.8.0', '>=');
	}

	static public function depends_on()
	{
			return array('\hjw\calendar\migrations\v_0_7_7');
	}

	public function update_data()
	{
		return array(
			array('config.update', array('calendar_version', '0.8.0')),
			array('config.add', array('calendar_football', 0, true)), 
			array('config.add', array('calendar_on_header', 0, true)), 
		);
	}
	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'calendar'			=> array(
					'calendar_repeat'		=>	array('INT:1', 0),
					'repeat_dm'				=>	array('INT:1', 0),
					'repeat_day_number'		=>	array('UINT', 1),
					'repeat_month_number'	=>	array('UINT', 1),
				),
			),
		);
	}
}
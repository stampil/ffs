<?php
/**
*
* @package hjw calendar Extension
* @copyright (c) 2019 hjw
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace hjw\calendar\migrations;

class v_0_8_3 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['calendar_version']) && version_compare($this->config['calendar_version'], '0.8.3', '>=');
	}

	static public function depends_on()
	{
			return array('\hjw\calendar\migrations\v_0_8_2');
	}

	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'calendar_participants'			=> array(
					'event_date'		=> array('VCHAR:10', ''),
				),
			),
		);
	}
	
	public function update_data()
	{
		return array(
			array('config.update', array('calendar_version', '0.8.3')),
		);
	}
}
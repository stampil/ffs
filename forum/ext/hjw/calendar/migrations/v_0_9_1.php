<?php
/**
*
* @package hjw calendar Extension
* @copyright (c) 2019 hjw
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace hjw\calendar\migrations;

class v_0_9_1 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['hjw_calendar_version']) && version_compare($this->config['hjw_calendar_version'], '0.9.1', '>=');
	}

	static public function depends_on()
	{
			return array('\hjw\calendar\migrations\v_0_9_0');
	}
	public function update_schema()
	{
		return array(
			'add_columns'    => array(
				$this->table_prefix . 'calendar'    => array(
					'weekday'        => array('INT:1', 1),
				),
			),
		);
	} 
	public function update_data()
	{
		return array(
			array('config.add', array('hjw_calendar_legend_display', 0)),
		);
	}
}
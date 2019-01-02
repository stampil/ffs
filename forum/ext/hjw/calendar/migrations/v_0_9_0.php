<?php
/**
*
* @package hjw calendar Extension
* @copyright (c) 2019 hjw
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace hjw\calendar\migrations;

class v_0_9_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['calendar_version']) && version_compare($this->config['calendar_version'], '0.9.0', '>=');
	}

	static public function depends_on()
	{
			return array('\hjw\calendar\migrations\v_0_8_11');
	}
	public function update_data()
	{
		return array(
			array('config.add', array('hjw_calendar_version', '0.9.0')),
			array('config.add', array('hjw_calendar_week_on_index', $this->config['week_on_index'], true)),
			array('config.add', array('hjw_calendar_number_of_weeks', $this->config['number_of_weeks'], true)),
			array('config.add', array('hjw_calendar_birthday_on_calendar', $this->config['birthday_on_calendar'], true)),
			array('config.add', array('hjw_calendar_for_guests', $this->config['calendar_for_guests'], true)),
			array('config.add', array('hjw_calendar_only_first_post', $this->config['calendar_only_first_post'], true)),
			array('config.add', array('hjw_calendar_number_participating', $this->config['calendar_number_participating'], true)),
			array('config.add', array('hjw_calendar_participants_name', $this->config['calendar_participants_name'], true)),
			array('config.add', array('hjw_calendar_tab', $this->config['calendar_tab'], true)),
			array('config.add', array('hjw_calendar_week_or_next', $this->config['week_or_next'], true)),
			array('config.add', array('hjw_calendar_week_display', $this->config['calendar_week_display'], true)),
			array('config.add', array('hjw_calendar_week_start', $this->config['calendar_week_start'], true)),
			array('config.add', array('hjw_calendar_football', $this->config['calendar_football'], true)),
			array('config.add', array('hjw_calendar_on_header', $this->config['calendar_on_header'], true)),
			array('config.add', array('hjw_calendar_on_index_off', $this->config['calendar_on_index_off'], true)),
			array('config.add', array('hjw_calendar_notify', 0, true)),
			array('config.add', array('hjw_calendar_notify_participating', 0, true)),
			array('config.add', array('hjw_calendar_notify_participating_adress', 0, true)),
			array('config.remove', array('calendar_version')),
			array('config.remove', array('week_on_index')),
			array('config.remove', array('number_of_weeks')),
			array('config.remove', array('birthday_on_calendar')),
			array('config.remove', array('calendar_for_guests')),
			array('config.remove', array('calendar_only_first_post')),
			array('config.remove', array('calendar_number_participating')),
			array('config.remove', array('calendar_participants_name')),
			array('config.remove', array('calendar_tab')),
			array('config.remove', array('week_or_next')),
			array('config.remove', array('calendar_week_display')),
			array('config.remove', array('calendar_week_start')),
			array('config.remove', array('calendar_football')),
			array('config.remove', array('calendar_on_header')),
			array('config.remove', array('calendar_on_index_off')),
		);
	}
}
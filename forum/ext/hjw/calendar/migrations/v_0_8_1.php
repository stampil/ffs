<?php
/**
*
* @package hjw calendar Extension
* @copyright (c) 2019 hjw
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace hjw\calendar\migrations;

class v_0_8_1 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['calendar_version']) && version_compare($this->config['calendar_version'], '0.8.1', '>=');
	}

	static public function depends_on()
	{
			return array('\hjw\calendar\migrations\v_0_8_0');
	}

	public function update_data()
	{
		return array(
			array('config.update', array('calendar_version', '0.8.1')),
			array('config.add', array('calendar_on_index_off', 0, true)), 
		);
	}
}
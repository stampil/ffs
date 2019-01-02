<?php
/**
*
* @package hjw calendar Extension
* @copyright (c) 2015 hjw
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace hjw\calendar\migrations;

class v_0_7_4 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['calendar_version']) && version_compare($this->config['calendar_version'], '0.7.4', '>=');
	}

	static public function depends_on()
	{
			return array('\hjw\calendar\migrations\v_0_7_3');
	}

	public function update_data()
	{
		return array(
			array('config.update', array('calendar_version', '0.7.4')),
		);
	}
}
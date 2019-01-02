<?php
/**
*
* @package hjw calendar Extension
* @copyright (c) 2019 hjw
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace hjw\calendar\acp;

class main_info
{
	public function module()
	{
		return array(
			'filename'	=> '\hjw\calendar\acp\main_module',
			'title'		=> 'ACP_CALENDAR_TITLE',
			'modes'		=> array(
				'instructions'		=> array(
					'title' => 'ACP_CALENDAR_INSTRUCTIONS',
					'auth' => 'ext_hjw/calendar && acl_a_board',
					'cat' => array('ACP_CALENDAR_TITLE')
				),
				'displayoptions'	=> array(
					'title' => 'ACP_CALENDAR_DISPLAYOPTIONS',
					'auth' => 'ext_hjw/calendar && acl_a_board',
					'cat' => array('ACP_CALENDAR_TITLE')
				),
				'settings'			=> array(
					'title' => 'ACP_CALENDAR_EVENT_CONFIG',
					'auth' => 'ext_hjw/calendar && acl_a_board',
					'cat' => array('ACP_CALENDAR_TITLE')
				),
				'forums_settings'	=> array(
					'title' => 'ACP_CALENDAR_FORUMS_CONFIG',
					'auth' => 'ext_hjw/calendar && acl_a_board',
					'cat' => array('ACP_CALENDAR_TITLE')
				),
				'event_list'		=> array(
					'title' => 'ACP_CALENDAR_EVENT_LIST',
					'auth' => 'ext_hjw/calendar && acl_a_board',
					'cat' => array('ACP_CALENDAR_TITLE')
				),
				'special_days'		=> array(
					'title' => 'ACP_CALENDAR_SPECIAL_DAY',
					'auth' => 'ext_hjw/calendar && acl_a_board',
					'cat' => array('ACP_CALENDAR_TITLE')
				),
			),
		);
	}
}

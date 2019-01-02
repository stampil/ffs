<?php
/**
*
* @package hjw calendar Extension
* @copyright (c) 2019 hjw
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if(!defined('IN_PHPBB'))
{
	exit;
}
if (isset($special_day[$month][$day]))
{
	if ($sd_bcolor[$month][$day])
	{
		$bg = 'background-color:#' . $sd_bcolor[$month][$day] . ';';
	}
	else
	{
		$bg='';
	}
	$hday	.= '<span class="hday eventbg" style="color:#'.$sd_color[$month][$day].';' . $bg . '">'.$special_day[$month][$day].'</span>';
}
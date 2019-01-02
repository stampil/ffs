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
$sql = 'SELECT *
	FROM ' . CALENDAR_SPECIAL_DAYS_TABLE . '
	ORDER by id';
$result = $this->db->sql_query($sql);
while($row = $this->db->sql_fetchrow($result))
{
	if ($row['show_on'])
	{
		if ($row['date'] == '.') $row['date'] ='';
		if (!$row['date'] && $row['name'] <> 'Advent' && $row['name'] <> 'Buß- und Bettag')
		{
			$sp=easter_days($year)+21+(int)$row['eastern'];
			if ($row['big'] == 1)
			{
				$row['name'] = '<strong>'.$row['name'].'</strong>';
			}
			$special_day[date('n',mktime(12,0,0,3,$sp,$year))][date('j',mktime(12,0,0,3,$sp,$year))] = $row['name'];
			$sd_color[date('n',mktime(12,0,0,3,$sp,$year))][date('j',mktime(12,0,0,3,$sp,$year))] = $row['color'];
			$sd_bcolor[date('n',mktime(12,0,0,3,$sp,$year))][date('j',mktime(12,0,0,3,$sp,$year))] = $row['bcolor'];
		}
		if ($row['big'] == 1)
		{
			$row['name'] = '<strong>'.$row['name'].'</strong>';
		}
		if ($row['date'])
		{
			$sp_date = explode('.',$row['date']);
			$special_day[(int)$sp_date[1]][(int)$sp_date[0]] = $row['name'];
			$sd_color[(int)$sp_date[1]][(int)$sp_date[0]] = $row['color'];
			$sd_bcolor[(int)$sp_date[1]][(int)$sp_date[0]] = $row['bcolor'];
		}
		if ($row['name'] == 'Advent')
		{
			$advent = 4;
			$w = date("N", mktime(12, 0, 0, 12,25, $year));
			for ($i=0;$i<4;$i++)
			{
			$special_day[date('n',mktime(12,0,0,12,25-$w-7*$i,$year))][date('j',mktime(12,0,0,12,25-$w-7*$i,$year))]=$advent.'.Advent';
			$sd_color[date('n',mktime(12,0,0,12,25-$w-7*$i,$year))][date('j',mktime(12,0,0,12,25-$w-7*$i,$year))] = $row['color'];
			$sd_bcolor[date('n',mktime(12,0,0,12,25-$w-7*$i,$year))][date('j',mktime(12,0,0,12,25-$w-7*$i,$year))] = $row['bcolor'];
			$advent--;
			}
		}
		if ($row['name'] == 'Buß- und Bettag')
		{
			$w = date("N", mktime(12, 0, 0, 12,25, $year));
			$special_day[date('n',mktime(12,0,0,12,25-$w-32,$year))][date('j',mktime(12,0,0,12,25-$w-32,$year))] = $row['name'];
			$sd_color[date('n',mktime(12,0,0,12,25-$w-32,$year))][date('j',mktime(12,0,0,12,25-$w-32,$year))] = $row['color'];
			$sd_bcolor[date('n',mktime(12,0,0,12,25-$w-32,$year))][date('j',mktime(12,0,0,12,25-$w-32,$year))] = $row['bcolor'];
		}
	}
}
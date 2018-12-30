<?php
	define('IN_PHPBB', true);
    $phpbb_root_path = './';
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    include($phpbb_root_path . 'common.' . $phpEx);
	include($phpbb_root_path . 'includes/functions_posting.' . $phpEx); 
	include($phpbb_root_path . 'includes/functions_admin.' . $phpEx);

    // Start session management
    $user->session_begin();
    $auth->acl($user->data);
    $user->setup();
	
	if(!empty($_GET['id']))
	{
		$redirect = false;
		$isauth_admin = 0;
		$isauth_read = 0;
		$userid = $user->data['user_id'];
		$sql = "SELECT * FROM phpbb_user_group WHERE user_id = '$userid'";
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			if ($row['group_id'] == '5' || $row['group_id'] == '8')
			{
				$isauth_admin = 1;
			}
			
			if ($row['group_id'] == '9' || $row['group_id'] == '10')
			{
				$isauth_read = 1;
			}
		}
		
		if($isauth_admin OR $isauth_read)
			$display = true;
		else
			$display = false;
		
		$id_forum = intval($_GET['id']);	
		
		$sql = "SELECT * FROM pilotes WHERE id_forum = $id_forum";
		$result = $db->sql_query($sql);
		$data = $db->sql_fetchrow($result);
		$pseudo = $data['pseudo'];
		$ffs = $data['ffs'];
		$ffs2 = $data['ffs2'];
		$ffs3 = $data['ffs3'];
		$id = $data['id'];
		
		if($ffs != "")
		{
		
		$sql = "SELECT user_avatar FROM phpbb_users WHERE user_id = $id_forum";
		$result = $db->sql_query($sql);
		$data = $db->sql_fetchrow($result);
		$avatar = $data['user_avatar'];
		
		$sql = "SELECT phpbb_ranks.rank_title, phpbb_ranks.rank_image FROM phpbb_ranks INNER JOIN phpbb_users ON phpbb_ranks.rank_id = phpbb_users.user_rank WHERE phpbb_users.user_id = $id_forum";
		$result = $db->sql_query($sql);
		$data = $db->sql_fetchrow($result);
		$rank_title = $data['rank_title'];
		$rank_image = $data['rank_image'];
		
		if($isauth_admin)
			$display_admin = true;
		else
			$display_admin = false;
			
		if($userid == $id_forum && $isauth_read)
			$display_own = true;
		else
			$display_own = false;
		
		$template->assign_vars(array(
			'FFS'		=>		$ffs,
			'FFS2'		=>		$ffs2,
			'FFS3'		=>		$ffs3,
			'PSEUDO'	=>		$pseudo,
			'AVATAR'	=>		$avatar,
			'RANK_TITLE'=>		$rank_title,
			'RANK_IMAGE'=>		$rank_image,
			'ID_FORUM'  =>		$id_forum,
			'DISPLAY_ADMIN' =>  $display_admin,
			'DISPLAY_OWN'	=>	$display_own
		));
		
		if(!empty($_GET['mode']) && isset($_GET['id_module']))
		{
			if($isauth_admin)
			{
				$mode = $_GET['mode'];
				$id_module = intval($_GET['id_module']);
				$ffs_module = intval($_GET['ffs']);
				
				if($mode == "no")
				{
					$sql = "DELETE FROM validation_modules WHERE id_pilote = $id AND id_module = $id_module";
					$db->sql_query($sql);
				}
				else
				{
					$sql = "DELETE FROM validation_modules WHERE id_pilote = $id AND id_module = $id_module";
					$db->sql_query($sql);
					$sql = "INSERT INTO validation_modules VALUES(NULL, $id, $id_module, '$mode', $ffs_module)";
					$result = $db->sql_query($sql);
				}
			}
		}
		
		//AFFICHAGE MODULE FFS 1
		
		$sql = "SELECT * FROM modules WHERE FFS = $ffs ORDER BY lvl ASC, sub ASC";
		$result = $db->sql_query($sql);
		$modules = $db->sql_fetchrowset($result);
		
		$sql2 = "SELECT validation_modules.id_module, validation_modules.status FROM pilotes INNER JOIN validation_modules ON pilotes.id = validation_modules.id_pilote WHERE pilotes.id_forum = $id_forum";
		$result2 = $db->sql_query($sql2);
		$validation = $db->sql_fetchrowset($result2);
		$not_first = false;
		
		foreach($modules as $key => $element)
		{
			$lvl = $element['lvl'];
			$sub = $element['sub'];
			$nom = $element['name'];
			$examen = $element['examen'];
			$id_module = $element['id'];
			$status_module = "No";
			foreach($validation as $key2 => $status)
			{
				if($id_module == $status['id_module'])
				{
					$status_module = $status['status'];
				}
			}
			
			switch($status_module){
			case "No":
				$color = "d72e2e";
				break;
			case "valid":
				if($examen)
					$color = "0012ff";
				else
					$color = "14ad43";
				break;
			case "wip":
				$color = "ec911c";
				break;
			default:
				$color = "000000";
				break;
			}
			
			$template->assign_block_vars('module',array(
				'ID_MODULE'	=>		$id_module,
				'LVL'		=>		$lvl,
				'SUB'		=>		$sub,
				'NOM'		=>		$nom,
				'STATUS'	=>		$color,
				'EXAMEN'	=>		$examen,
				'NOT_FIRST'	=>		$not_first
			));
			
			$not_first = true;
		}
		
		//AFFICHAGE MODULE FFS 2
		if($ffs2 != 0)
		{
		$sql3 = "SELECT * FROM modules WHERE FFS = $ffs2 ORDER BY lvl ASC, sub ASC";
		$result3 = $db->sql_query($sql3);
		$modules3 = $db->sql_fetchrowset($result3);
		
		$sql4 = "SELECT validation_modules.id_module, validation_modules.status FROM pilotes INNER JOIN validation_modules ON pilotes.id = validation_modules.id_pilote WHERE pilotes.id_forum = $id_forum";
		$result4 = $db->sql_query($sql4);
		$validation4 = $db->sql_fetchrowset($result4);
		$not_first = false;
		
		foreach($modules3 as $key => $element)
		{
			$lvl = $element['lvl'];
			$sub = $element['sub'];
			$nom = $element['name'];
			$examen = $element['examen'];
			$id_module = $element['id'];
			$status_module = "No";
			foreach($validation4 as $key2 => $status)
			{
				if($id_module == $status['id_module'])
				{
					$status_module = $status['status'];
				}
			}
			
			switch($status_module){
			case "No":
				$color = "d72e2e";
				break;
			case "valid":
				if($examen)
					$color = "0012ff";
				else
					$color = "14ad43";
				break;
			case "wip":
				$color = "ec911c";
				break;
			default:
				$color = "000000";
				break;
			}
			
			$template->assign_block_vars('module2',array(
				'ID_MODULE'	=>		$id_module,
				'LVL'		=>		$lvl,
				'SUB'		=>		$sub,
				'NOM'		=>		$nom,
				'STATUS'	=>		$color,
				'EXAMEN'	=>		$examen,
				'NOT_FIRST'	=>		$not_first
			));
			$not_first = true;
		}
		}
		
		//AFFICHAGE MODULE FFS 3
		if($ffs3 != 0)
		{
		$sql = "SELECT * FROM modules WHERE FFS = $ffs3 ORDER BY lvl ASC, sub ASC";
		$result = $db->sql_query($sql);
		$modules = $db->sql_fetchrowset($result);
		
		$sql2 = "SELECT validation_modules.id_module, validation_modules.status FROM pilotes INNER JOIN validation_modules ON pilotes.id = validation_modules.id_pilote WHERE pilotes.id_forum = $id_forum";
		$result2 = $db->sql_query($sql2);
		$validation = $db->sql_fetchrowset($result2);
		$not_first = false;
		foreach($modules as $key => $element)
		{
			$lvl = $element['lvl'];
			$sub = $element['sub'];
			$nom = $element['name'];
			$examen = $element['examen'];
			$id_module = $element['id'];
			$status_module = "No";
			foreach($validation as $key2 => $status)
			{
				if($id_module == $status['id_module'])
				{
					$status_module = $status['status'];
				}
			}
			
			switch($status_module){
			case "No":
				$color = "d72e2e";
				break;
			case "valid":
				if($examen)
					$color = "0012ff";
				else
					$color = "14ad43";
				break;
			case "wip":
				$color = "ec911c";
				break;
			default:
				$color = "000000";
				break;
			}
			
			$template->assign_block_vars('module3',array(
				'ID_MODULE'	=>		$id_module,
				'LVL'		=>		$lvl,
				'SUB'		=>		$sub,
				'NOM'		=>		$nom,
				'STATUS'	=>		$color,
				'EXAMEN'	=>		$examen,
				'NOT_FIRST'	=>		$not_first
			));
			$not_first = true;
		}
		}
		
		// ENREGISTREMENT NOUVEAU VOL
			if(isset($_POST['day']) && isset($_POST['contexte']) && isset($_POST['add']) && intval($_POST['add'])==1)
			{
				if($userid == $id_forum && $isauth_read)
				{
					$titre = addslashes($_POST['titre']);
					$day = intval($_POST['day']);
					$month = intval($_POST['month']);
					$year = intval($_POST['year']);
					$heure = intval($_POST['heure']);
					$minutes = intval($_POST['minutes']);
					$contexte = addslashes($_POST['contexte']);
					$appareil = $_POST['appareil'];
					$pilote = addslashes($_POST['pilote']);
					$lien = addslashes($_POST['lien']);
					$type = $_POST['type'];
					
					$sql_add_vol = "INSERT INTO fiche_vol VALUE(NULL, $id_forum, '$titre', $ffs, $day, $month, $year, $heure, $minutes, '$pilote', '', '$contexte', '$lien', '$appareil', '$type')";
					$db->sql_query($sql_add_vol);
					$redirect = true;
				}
			}
			
		// SUPPRESSION D'UN VOL
		if(isset($_GET['del']) && intval($_GET['del']) == 1)
		{
			if(($userid == $id_forum && $isauth_read) OR $isauth_admin)
			{
				$id_fiche = $_GET['id_fiche'];
				$sql_del_fiche = "DELETE FROM fiche_vol WHERE id = $id_fiche";
				$db->sql_query($sql_del_fiche);
				$redirect = true;
			}
		}
		
		// AJOUT COMMENTAIRE INSTRUCTEUR
		if(isset($_GET['instruct']) && intval($_GET['instruct']) == 1)
		{
			$instruct_comment = true;
			$id_fiche = intval($_GET['id_fiche']);
			$sql = "SELECT avis_instruc FROM fiche_vol WHERE id = $id_fiche";
			$result = $db->sql_query($sql);
			$comment = $db->sql_fetchrow($result);
			$template->assign_vars(array(
				'INSTRUCT_COMMENT'		=>		$instruct_comment,
				'ID_FICHE'				=>		$id_fiche,
				'PREVIOUS_COMMENT'		=>		$comment['avis_instruc']
			));
		}
		
		// EDITION VOL
		if(isset($_GET['edit']) && intval($_GET['edit']) == 1)
		{
			$edit_fiche = true;
			$id_fiche = intval($_GET['id_fiche']);
			$sql = "SELECT * FROM fiche_vol WHERE id = $id_fiche";
			$result = $db->sql_query($sql);
			$comment = $db->sql_fetchrow($result);
			$template->assign_vars(array(
				'EDIT_FICHE'			=>		$edit_fiche,
				'ID_FICHE'				=>		$id_fiche,
				'PREVIOUS_COMMENT'		=>		$comment['avis_instruc'],
				'EDIT_TYPE'				=>		$comment['type'],
				'EDIT_TITRE'			=>		$comment['titre'],
				'EDIT_DAY'				=>		$comment['day'],
				'EDIT_MONTH'			=>		$comment['month'],
				'EDIT_YEAR'				=>		$comment['year'],
				'EDIT_HEURE'			=>		$comment['heure'],
				'EDIT_MINUTES'			=>		$comment['minutes'],
				'EDIT_CONTEXTE'			=>		$comment['contexte'],
				'EDIT_APPAREIL'			=>		$comment['appareil'],
				'EDIT_AVIS_PILOTE'		=>		$comment['avis_pilote'],
				'EDIT_LIEN'				=>		$comment['lien']
			));
		}
		
		if(isset($_POST['edit']) && intval($_POST['edit']) == 1)
		{
			if(($userid == $id_forum && $isauth_read) OR $isauth_admin)
			{
				$id_fiche = intval($_POST['id_fiche']);
				$titre = addslashes($_POST['titre']);
				$day = intval($_POST['day']);
				$month = intval($_POST['month']);
				$year = intval($_POST['year']);
				$heure = intval($_POST['heure']);
				$minutes = intval($_POST['minutes']);
				$contexte = addslashes($_POST['contexte']);
				$appareil = $_POST['appareil'];
				$pilote = addslashes($_POST['pilote']);
				$lien = addslashes($_POST['lien']);
				$type = $_POST['type'];
					
				$sql_add_vol = "UPDATE fiche_vol SET titre = '$titre', day = $day, month = $month, year = $year, heure = $heure, minutes = $minutes, avis_pilote = '$pilote', contexte = '$contexte', lien = '$lien', appareil = '$appareil' WHERE id = $id_fiche";
				$db->sql_query($sql_add_vol);
				$redirect = true;
			}
		}
		
		if(isset($_POST['avis_instruct']))
		{
			if($isauth_admin)
			{
				$avis_instruct = addslashes($_POST['avis_instruct']);
				$id_fiche = intval($_POST['id_fiche']);
				$sql = "UPDATE fiche_vol SET avis_instruc = '$avis_instruct' WHERE id = $id_fiche";
				$db->sql_query($sql);
			}
		}
		
		//Récupération médailles
		$sql_vad_qualif = "SELECT * FROM qualifs_pilotes WHERE id_forum = $id_forum";
		$result_vad_qualif = $db->sql_query($sql_vad_qualif);
		$etat_qualif = $db->sql_fetchrow($result_vad_qualif);
		$etat_vad = $etat_qualif['vad_qualif'];
		
		if($etat_vad)
		{
			$display_vad = true;
			$display_medal = true;
		}
		else
		{
			$display_vad = false;
			$display_medal = false;
		}
		
		//Récupération des vols effectués
		if(!empty($_GET['p']))
			$page = intval($_GET['p'])-1;
		else
			$page = 0;
			
		$sql_somme = "SELECT SUM(minutes) as sum_min, SUM(heure) as sum_hour FROM fiche_vol WHERE id_pilote = $id_forum AND type = 'training'";
		$result_somme = $db->sql_query($sql_somme);
		$somme_pilote = $db->sql_fetchrow($result_somme);
		$somme_heure = $somme_pilote['sum_hour'];
		$somme_minutes = $somme_pilote['sum_min'];
		
		$total_minutes = $somme_minutes - floor($somme_minutes/60)*60;
		$total_heure = $somme_heure + floor($somme_minutes/60);
		
		if($total_minutes < 10)
			$total_minutes = "0".$total_minutes;
			
		if($total_heure < 10)
			$total_heure = "0".$total_heure;
		
		$total_pilote_train = $total_heure."h ".$total_minutes."min";
		$total_heure_train = $total_heure;
		$total_minutes_train = $total_minutes;
		
		$total_minutes = 0;
		$somme_minutes = 0;
		$total_heure = 0;
		$somme_minutes = 0;
		
		$sql_somme = "SELECT SUM(minutes) as sum_min, SUM(heure) as sum_hour FROM fiche_vol WHERE id_pilote = $id_forum AND type = 'mission'";
		$result_somme = $db->sql_query($sql_somme);
		$somme_pilote = $db->sql_fetchrow($result_somme);
		$somme_heure = $somme_pilote['sum_hour'];
		$somme_minutes = $somme_pilote['sum_min'];
		
		$total_minutes = $somme_minutes - floor($somme_minutes/60)*60;
		$total_heure = $somme_heure + floor($somme_minutes/60);
		
		if($total_minutes < 10)
			$total_minutes = "0".$total_minutes;
			
		if($total_heure < 10)
			$total_heure = "0".$total_heure;
		
		$total_pilote_mission = $total_heure."h ".$total_minutes."min";
		$total_heure = $total_heure + $total_heure_train;
		$total_minutes = $total_minutes + $total_minutes_train;
		
		$total_minutes = $total_minutes - floor($total_minutes/60)*60;
		$total_heure = $total_heure + floor($total_minutes/60);
		
		if($total_minutes < 10)
			$total_minutes = "0".$total_minutes;
			
		if($total_heure < 10)
			$total_heure = "0".$total_heure;
			
		$total_pilote = $total_heure."h ".$total_minutes."min";
		
		$sql= "SELECT COUNT(*) as nbre FROM fiche_vol WHERE id_pilote = $id_forum AND type='training'";
		$result_nbr = $db->sql_query($sql);
		$nbr_array = $db->sql_fetchrow($result_nbr);
		$nbr_train = $nbr_array['nbre'];
		
		$sql= "SELECT COUNT(*) as nbre FROM fiche_vol WHERE id_pilote = $id_forum AND type='mission'";
		$result_nbr = $db->sql_query($sql);
		$nbr_array = $db->sql_fetchrow($result_nbr);
		$nbr_mission = $nbr_array['nbre'];
		
		$nbr_total = $nbr_train + $nbr_mission;
		
		$limit1 = 5*$page;
		$limit2 = 5*($page+1);
		$sql_vol = "SELECT * FROM fiche_vol WHERE id_pilote = $id_forum ORDER BY year DESC, month DESC, day DESC LIMIT $limit1, $limit2";
		$result_vol = $db->sql_query($sql_vol);
		$count_vol = 0;
		while($vol = $db->sql_fetchrow($result_vol))
		{
			$count_vol++;
			$day = $vol['day'];
			$month = $vol['month'];
			$year = $vol['year'];
			$heure = $vol['heure'];
			$minutes = $vol['minutes'];
			$id_vol = $vol['id'];
			
			if($heure < 10)
				$heure = "0".$heure;
			if($minutes < 10)
				$minutes = "0".$minutes;
				
			$tps_vol = $heure.":".$minutes;
			
			if($day < 10)
				$day = "0".$day;
			
			if($month < 10)
				$month = "0".$month;
			
			if(strlen($year) == 2)
				$year = "20".$year;
			
			$date = $day."/".$month."/".$year;
			
			$template->assign_block_vars('vol',array(
				'ID_VOL'	=>		$id_vol,
				'TYPE'		=>		$vol['type'],
				'TITRE'		=>		$vol['titre'],
				'DATE'		=>		$date,
				'TPS'		=>		$tps_vol,
				'AVIS_PILOTE'	=>	nl2br($vol['avis_pilote']),
				'AVIS_INSTRUC'	=>	nl2br($vol['avis_instruc']),
				'CONTEXTE'	=>		nl2br($vol['contexte']),
				'LIEN'		=>		$vol['lien'],
				'APPAREIL'	=>		$vol['appareil']			
			));
		}
		
		}
		
		
		
		if($userid == $id_forum)
		$add_vol = true;
		else
		$add_vol = false;
	$template->assign_vars(array(
		'COUNT_VOL'		=>	$count_vol,
		'CURRENT_YEAR'	=>	date('Y'),
		'PAGE'			=>	$page+1,
		'PREVIOUS_PAGE'	=>	$page,
		'NEXT_PAGE'		=>	$page+2,
		'ADD_VOL'		=>	$add_vol,
		'TPS_TRAIN'		=>	$total_pilote_train,
		'TPS_MISSION'	=>	$total_pilote_mission,
		'TPS_TOTAL'		=>	$total_pilote,
		'NBR_VOL_TOTAL'	=>	$nbr_total,
		'NBR_VOL_TRAIN'	=>	$nbr_train,
		'NBR_VOL_MISSION'	=>	$nbr_mission,
		'REDIRECT'		=>	$redirect,
		'DISPLAY_PAGE'	=>	$display,
		'S_DISPLAY_MEDALS'	=> $display_medal,
		'S_DISPLAY_VAD_QUALIF'  => $display_vad
	));
	}
	
	
	
	
	//---------------------------------------------------------------------------
    page_header('Module de formation v1.0');
	$template->set_filenames(array(
    'body' => 'fiche_pilote.html',
    ));

    make_jumpbox(append_sid("{$phpbb_root_path}viewforum.$phpEx"));
    page_footer();
?>
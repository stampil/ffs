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
	
	//---------------------------------------------------------------------------
		$isauth_admin = 0;
		$isauth_read = 0;
		$FFS = intval($_GET['FFS']);
		$mode = intval($_GET['mode']);
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

		if(!empty($_GET['mode']) && $_GET['mode'] == 1 && !empty($_GET['action']) && $_GET['action'] == "add" && isset($_POST['add_module']) && isset($_POST['add_ssmodule']) && !empty($_POST['add_intitule']))
		{
			if($isauth_admin)
			{
				$module_add = intval($_POST['add_module']);
				$ssmodule_add = intval($_POST['add_ssmodule']);
				$intitule_add = addslashes($_POST['add_intitule']);
				$examen = 0;
				$examen = intval($_POST['examen']);
				
				$sql = "INSERT INTO modules VALUES(NULL, '$intitule_add',$FFS,$module_add,$ssmodule_add,$examen)";
				$db->sql_query($sql);
			}
		}
		
		if(!empty($_GET['mode']) && $_GET['mode'] == 1 &&!empty($_GET['action']) && $_GET['action'] == "delete" && isset($_GET['chap']) && isset($_GET['schap']))
		{
			if($isauth_admin)
			{
				$chap = intval($_GET['chap']);
				$schap = intval($_GET['schap']);
				$sql = "SELECT id FROM modules WHERE (lvl = $chap AND sub = $schap AND FFS = $FFS)";
				$result = $db->sql_query($sql);
				$data = $db->sql_fetchrow($result);
				$id_module = $data['id'];
				$sql = "DELETE FROM modules WHERE (lvl = $chap AND sub = $schap AND FFS = $FFS)";
				$db->sql_query($sql);
				$sql = "DELETE FROM validation_modules WHERE id_module = $id_module";
				$db->sql_query($sql);
			}
		}
		
		if(!empty($_GET['mode']) && $_GET['mode'] == 3 &&!empty($_GET['action']) && $_GET['action'] == "delete" && isset($_GET['id']))
		{
			if($isauth_admin)
			{
				$id_pilote_del = intval($_GET['id']);
				$sql="SELECT * FROM pilotes WHERE id_forum = $id_pilote_del";
				$result = $db->sql_query($sql);
				$data = $db->sql_fetchrow($result);
				if(!data)
				{
				}
				else
				{
					if($data['ffs']==$FFS)
					{
						$sql = "UPDATE pilotes SET ffs = 0 WHERE id_forum = $id_pilote_del";
						$db->sql_query($sql);
					}
					elseif($data['ffs2']==$FFS)
					{
						$sql = "UPDATE pilotes SET ffs2 = 0 WHERE id_forum = $id_pilote_del";
						$db->sql_query($sql);
					}
					elseif($data['ffs3']==$FFS)
					{
						$sql = "UPDATE pilotes SET ffs3 = 0 WHERE id_forum = $id_pilote_del";
						$db->sql_query($sql);
					}
				}
			}
		}
		
		if(!empty($_GET['mode']) && $_GET['mode'] == 3 && !empty($_GET['action']) && $_GET['action'] == "add" && isset($_POST['add_pilote']) && isset($_POST['add_id']))
		{
			if($isauth_admin)
			{
				$pilote_add = addslashes($_POST['add_pilote']);
				$id_pilote_add = intval($_POST['add_id']);
				$leader_add = 0;
				$leader_add = intval($_POST['leader']);
				$sql = "SELECT * FROM pilotes WHERE id_forum = $id_pilote_add";
				$result = $db->sql_query($sql);
				$data = $db->sql_fetchrow($result);
				if(!$data)
				{
					$sql = "INSERT INTO pilotes VALUES(NULL, '$pilote_add', $id_pilote_add, $FFS, 0, 0, $leader_add)";
					$db->sql_query($sql);
				}
				else
				{
					$id_ligne_add = $data['id'];
					$ffs_add = $data['ffs'];
					$ffs2_add = $data['ffs2'];
					$ffs3_add = $data['ffs3'];
					
					if($ffs_add == 0)
					{
						$sql = "UPDATE pilotes SET ffs = $FFS, leader=$leader_add WHERE id = $id_ligne_add";
						$db->sql_query($sql);
						if($ffs2_add == 0 && $ffs_add <> $FFS)
						{
							$sql = "UPDATE pilotes SET ffs2 = $FFS, leader=$leader_add WHERE id = $id_ligne_add";
							$db->sql_query($sql);
						}
						else
						{
							if($ffs3_add == 0 && $ffs2_add <> $FFS)
							{
								$sql = "UPDATE pilotes SET ffs3 = $FFS, leader=$leader_add WHERE id = $id_ligne_add";
								$db->sql_query($sql);
							}
						}
					}
				}
				
			}
		}
		
		switch($mode){
			case 1:			// Mode ADMIN. Création Liste training
				if($isauth_admin == 1) {$disp_admin = true; $disp_pilot_admin = false;} else {$disp_admin = false;}
				$disp_user = false;
				$disp_normal = false;
			break;
			case 2:			// Mode USER. Affichage fiche pilote
				$disp_admin = false;
				$disp_pilot_admin = false;
				if($isauth_read == 1) {$disp_user = true;} else {$disp_user = false;}
				$disp_normal = false;
			break;
			case 3:
				if($isauth_admin == 1) {$disp_admin = false; $disp_pilot_admin = true;} else {$disp_admin = false;}
				$disp_user = false;
				$disp_normal = false;
			break;
			default:		// Mode par défaut (Affichage liste pilotes et liste items)
				$disp_admin = false;
				$disp_pilot_admin=false;
				$disp_user = false;
				if($isauth_read == 1) {$disp_normal = true;} else {$disp_normal = false;}
			break;
		}

		if($FFS <> 3 AND $FFS <> 4 AND $FFS <> 2 AND $FFS <> 6 AND $FFS <> 16 AND $FFS <> 26 AND $FFS <> 12 AND $FFS <> 131)
			$disp_FFS00 = true;
		else
			$disp_FFS00 = false;
		
		$sql = "SELECT * FROM modules WHERE FFS = $FFS ORDER BY lvl ASC, sub ASC";
		$result = $db->sql_query($sql);
		while($data = $db->sql_fetchrow($result))
		{			
			$template->assign_block_vars('modules', array(
				'TYPE'		=>	$data['sub'],
				'MOD'		=>	$data['lvl'],
				'NOM'		=> 	$data['name'],
				'EXAMEN'	=>	$data['examen']
			));
		}
		$sql = "SELECT * FROM modules WHERE FFS = $FFS ORDER BY lvl ASC, sub ASC";
		$result = $db->sql_query($sql);
		$data = $db->sql_fetchrowset($result);
		
		$sql_pilote = "SELECT * FROM pilotes WHERE (ffs = $FFS OR ffs2 = $FFS OR ffs3 = $FFS) ORDER BY pseudo ASC";
		$results_pilote = $db->sql_query($sql_pilote);
		
		$sql2 = "SELECT pilotes.pseudo, validation_modules.id_module, validation_modules.status FROM pilotes INNER JOIN validation_modules ON pilotes.id = validation_modules.id_pilote WHERE (pilotes.ffs = $FFS OR pilotes.ffs2 = $FFS OR pilotes.ffs3 = $FFS) ORDER BY pilotes.pseudo ASC";
		$result2 = $db->sql_query($sql2);
		$data2 = $db->sql_fetchrowset($result2);
		
		$affec = 0;
		while($data_pilote = $db->sql_fetchrow($results_pilote))
		{
			//On prend un pilote
			$pilote = $data_pilote['pseudo'];
			$id_pilote = $data_pilote['id_forum'];
			$template->assign_block_vars('pilote', array(
				'PSEUDO' => $pilote,
				'ID_FORUM' => $id_pilote
			));
			
			foreach($data as $key => $tableau)
			{
					//On boucle sur le nombre d'étapes dans le module
					foreach($data2 as $key2 => $tableau2)
					{
						//On boucle sur les modules validés
						if($tableau2['pseudo'] == $pilote)
						{
							if($tableau2['id_module'] == $tableau['id'])
							{
								//On affecte le status qui va bien
								$status = $tableau2['status'];
								$affec = 1;
							}
						}
					}
					if($affec == 0)
						$status = "No";
					
					if($tableau['sub'] == 0)
						$title = true;
					else
						$title = false;
				
					$template->assign_block_vars('pilote.module', array(
						'STATUS' => $status,
						'TITLE' => $title,
						'NOM' => $tableau['name'],
						'EXAMEN' => $tableau['examen']
					));
					$affec=0;
			}
		}
		
	//---------------------------------------------------------------------------
	//	Assignation variables
	//---------------------------------------------------------------------------
	$template->assign_vars(array(
		'S_DISPLAY_FFS' => $FFS,
		'S_DISPLAY_FFS00' => $disp_FFS00,
		'S_DISPLAY_ADMIN' => $disp_admin,
		'S_DISPLAY_USER'  => $disp_user,
		'S_DISPLAY_NORMAL' => $disp_normal,
		'S_DISPLAY_PILOT_ADMIN' => $disp_pilot_admin,
		'S_USER_ADMIN' => $isauth_admin,
		'S_TEST' => $module_add.$ssmodule_add.$intitule_add
	));

	
	//---------------------------------------------------------------------------

    page_header('Module de formation v1.0');
$template->set_filenames(array(
    'body' => 'module_training.html',
    ));

    make_jumpbox(append_sid("{$phpbb_root_path}viewforum.$phpEx"));
    page_footer();

    

?>
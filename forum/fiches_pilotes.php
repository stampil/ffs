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
        
    //------------------------------------------------------------------------------------------
        $sql = "SELECT * FROM fiche_pilote";
        $result = $db->sql_query($sql);
        while($data = $db->sql_fetchrow($result))
        {
            $template->assign_block_vars('fiche_pilote', array(
                'HEURE_VOL'         => $data['Nb_heure_vol'],
                'DONT_VOL_NUIT'     => $data['Vol_nuit'],
                'LANDINGS'          => $data['Nb_Landing'],
                'DONT_LDNG_NUIT'    => $data['Landing_nuit']
            ));
        }    
    //------------------------------------------------------------------------------------------

    
        
page_header('Fiche pilote v1.0');
$template->set_filenames(array(
    'body' => 'fiches_pilotes.html',
    ));

    make_jumpbox(append_sid("{$phpbb_root_path}viewforum.$phpEx"));
    page_footer();

?>

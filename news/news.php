<?php
/*##################################################
 *                                news.php
 *                            -------------------
 *   begin                : June 20, 2005
 *   copyright          : (C) 2005 Viarre R�gis
 *   email                : crowkait@phpboost.com
 *
 *  
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../includes/begin.php');
require_once('../news/news_begin.php');
require_once('../includes/header.php');

$idnews = !empty($_GET['id']) ? numeric($_GET['id']) : 0;	
$idcat = !empty($_GET['cat']) ? numeric($_GET['cat']) : 0;

$is_admin = $Member->Check_level(2);
if( empty($idnews) && empty($idcat) ) 
{
	$Template->Set_filenames(array(
		'news' => '../templates/' . $CONFIG['theme'] . '/news/news.tpl'
	));

	if( $CONFIG_NEWS['activ_edito'] == '1' ) //Affichage de l'�dito
	{
		$Template->Assign_block_vars('edito', array(
			'CONTENTS' => second_parse(stripslashes($CONFIG_NEWS['edito'])),
			'TITLE' => $CONFIG_NEWS['edito_title'],
			'EDIT' => $is_admin ? '<a href="../news/admin_news_config.php" title="' . $LANG['edit'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" class="valign_middle" /></a>&nbsp;' : ''
		));
	}	

	if( $show_archive )
	{
		$CONFIG_NEWS['pagination_news'] = $CONFIG_NEWS['pagination_arch'];
		$url_pagin = transid('.php?arch=1&amp;p=%d');
		$show_archive = 1;
	}
	else
	{
		$url_pagin = transid('.php?p=%d', '-0-0-%d.php');
		$show_archive = 0;
	}	
		
	//Pagination activ�e, sinon affichage lien vers les archives.
	if( $CONFIG_NEWS['activ_pagin'] == '1' )
	{
		//On cr�e une pagination (si activ�) si le nombre de news est trop important.
		include_once('../includes/pagination.class.php'); 
		$Pagination = new Pagination();
		$show_pagin = $Pagination->Display_pagination('news' . $url_pagin, $CONFIG_NEWS['nbr_news'], 'p', $CONFIG_NEWS['pagination_news'], 3);
		$first_msg = $Pagination->First_msg($CONFIG_NEWS['pagination_news'], 'p'); 
		$archives= '';
	}
	else
	{
		$first_msg = 0;
		$archives = ( ($CONFIG_NEWS['nbr_news'] > $CONFIG_NEWS['pagination_news']) && ($CONFIG_NEWS['nbr_news'] != 0) ) ? '<a href="news.php?arch=1" title="' . $LANG['archive'] . '">' . $LANG['archive'] . '</a>' : '';
		$show_pagin = '';
	}
		
	$Template->Assign_vars(array(
		'PAGINATION' => $show_pagin,
		'ARCHIVES' => $archives,
		'THEME' => $CONFIG['theme'],
		'L_ALERT_DELETE_NEWS' => $LANG['alert_delete_news'],
		'L_LAST_NEWS' => $LANG['last_news'],
		'L_ON' => $LANG['on']
	));
	
	//Si les news en block sont activ�es on recup�re la page.
	if( $CONFIG_NEWS['type'] == 1 && !$show_archive )
	{		
		$column = ($CONFIG_NEWS['nbr_column'] > 1) ? true : false;
		if( $column )
		{
			$i = 0;
			$CONFIG_NEWS['nbr_column'] = ceil($CONFIG_NEWS['pagination_news']/$CONFIG_NEWS['nbr_column']);
			$CONFIG_NEWS['nbr_column'] = !empty($CONFIG_NEWS['nbr_column']) ? $CONFIG_NEWS['nbr_column'] : 1;
			$column_width = floor(100/$CONFIG_NEWS['nbr_column']);	
			
			$Template->Assign_vars(array(
				'START_TABLE_NEWS' => '<table style="margin:auto;width:98%"><tr><td style="vertical-align:top;width:' . $column_width . '%">',
				'END_TABLE_NEWS' => '</td></tr></table>'
			));	
		}
		else
			$new_row = '';
		
		$z = 0;
		$result = $Sql->Query_while("SELECT n.contents, n.extend_contents, n.title, n.id, n.timestamp, n.user_id, n.img, n.alt, n.nbr_com, nc.id AS idcat, nc.icon, m.login
		FROM ".PREFIX."news n
		LEFT JOIN ".PREFIX."news_cat nc ON nc.id = n.idcat
		LEFT JOIN ".PREFIX."member m ON m.user_id = n.user_id		
		WHERE n.visible = 1 AND n.archive = '" . $show_archive . "'
		ORDER BY n.timestamp DESC 
		" . $Sql->Sql_limit($first_msg, $CONFIG_NEWS['pagination_news']), __LINE__, __FILE__);
		while($row = $Sql->Sql_fetch_assoc($result) )
		{ 
			//Initialisation
			list($admin, $del, $com, $link) = array('', '', '', ''); 			
			if( $CONFIG_NEWS['activ_com'] == 1 ) //Si les commentaires sont activ�s.
			{
				$l_com = ($row['nbr_com'] > 1) ? $LANG['com_s'] : $LANG['com'];

				$com_true = $l_com . ' (' . $row['nbr_com'] . ')</a>';
				$com_false = $LANG['post_com'] . '</a>';
				$com = (!empty($row['nbr_com'])) ? $com_true : $com_false;

				$link_pop = "<a class=\"com\" href=\"#\" onclick=\"popup('" . HOST . DIR . transid("/includes/com.php?i=" . $row['id'] . "news") . "', 'news');\">";			
				$link_current = '<a class="com" href="' . HOST . DIR . '/news/news' . transid('.php?cat=0&amp;id=' . $row['id'] . '&amp;i=0', '-0-' . $row['id'] . '+' . url_encode_rewrite($row['title']) . '.php?i=0') . '#news">';				
				$link = ($CONFIG['com_popup'] == '0') ? $link_current : $link_pop;
			}
			
			if( $is_admin )
			{
				$admin = '&nbsp;&nbsp;<a href="../news/admin_news.php?id=' . $row['id'] . '" title="' . $LANG['edit'] . '"><img  style="vertical-align:middle;" src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" /></a>';
				$del = '&nbsp;&nbsp;<a href="../news/admin_news.php?delete=1&amp;id=' . $row['id'] . '" title="' . $LANG['delete'] . '" onClick="javascript:return Confirm();"><img style="vertical-align:middle;" src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" /></a>';
			}
			
			//S�paration des news en colonnes si activ�.
			if( $column )
			{	
				$new_row = (($i%$CONFIG_NEWS['nbr_column']) == 0 && $i > 0) ? '</ul></td><td style="vertical-align:top;width:' . $column_width . '%"><ul style="margin:0;padding:0;list-style-type:none;">' : '';	
				$i++;
			}
				
			$Template->Assign_block_vars('news', array(
				'ID' => $row['id'],
				'ICON' => ((!empty($row['icon']) && $CONFIG_NEWS['activ_icon'] == 1) ? '<a href="news' . transid('.php?cat=' . $row['idcat'], '-' . $row['idcat'] . '.php') . '"><img src="' . $row['icon'] . '" alt="" style="vertical-align:middle;" /></a>' : ''),
				'TITLE' => $row['title'],
				'CONTENTS' => second_parse($row['contents']),
				'EXTEND_CONTENTS' => (!empty($row['extend_contents']) ? '<a style="font-size:10px" href="news' . transid('.php?id=' . $row['id'], '-0-' . $row['id'] . '.php') . '">[' . $LANG['extend_contents'] . ']</a><br /><br />' : ''),
				'IMG' => (!empty($row['img']) ? '<img src="' . $row['img'] . '" alt="' . $row['alt'] . '" title="' . $row['alt'] . '" class="img_right" />' : ''),
				'PSEUDO' => $row['login'],				
				'DATE' => gmdate_format('date_format_short', $row['timestamp']),
				'COM' => $link . $com,
				'EDIT' => $admin,
				'DEL' => $del,
				'NEW_ROW' => $new_row, 
				'U_MEMBER_ID' => transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php'),
				'U_NEWS_LINK' => transid('.php?id=' . $row['id'], '-0-' . $row['id'] . '+' . url_encode_rewrite($row['title']) . '.php')
			));
			$z++;
		}
		$Sql->Close($result);	
		
		if( $z == 0 )
		{
			$Template->Assign_block_vars('no_news_available', array(
				'L_NO_NEWS_AVAILABLE' => $LANG['no_news_available']
			));
		}
	}
	else //News en liste
	{
		$column = ($CONFIG_NEWS['nbr_column'] > 1) ? true : false;
		if( $column )
		{
			$i = 0;
			$CONFIG_NEWS['nbr_column'] = ceil($CONFIG_NEWS['pagination_news']/$CONFIG_NEWS['nbr_column']);
			$CONFIG_NEWS['nbr_column'] = !empty($CONFIG_NEWS['nbr_column']) ? $CONFIG_NEWS['nbr_column'] : 1;
			$column_width = floor(100/$CONFIG_NEWS['nbr_column']);	
			
			$Template->Assign_block_vars('news_link', array(
				'START_TABLE_NEWS' => '<table style="margin:auto;width:98%"><tr><td style="vertical-align:top;width:' . $column_width . '%"><ul style="margin:0;padding:0;list-style-type:none;">',
				'END_TABLE_NEWS' => '</ul></td></tr></table>'
			));	
		}
		else
		{	
			$Template->Assign_block_vars('news_link', array(
				'START_TABLE_NEWS' => '<ul style="margin:0;padding:0;list-style-type:none;">',
				'END_TABLE_NEWS' => '</ul>'
			));
			$new_row = '';
		}
		
		$result = $Sql->Query_while("SELECT n.id, n.title, n.timestamp, nc.id AS idcat, nc.icon
		FROM ".PREFIX."news n
		LEFT JOIN ".PREFIX."news_cat nc ON nc.id = n.idcat
		WHERE n.visible = 1 AND n.archive = '" . $show_archive . "'
		ORDER BY n.timestamp DESC 
		" . $Sql->Sql_limit($first_msg, $CONFIG_NEWS['pagination_news']), __LINE__, __FILE__);
		while ($row = $Sql->Sql_fetch_assoc($result))
		{ 
			//S�paration des news en colonnes si activ�.
			if( $column )
			{	
				$new_row = (($i%$CONFIG_NEWS['nbr_column']) == 0 && $i > 0) ? '</ul></td><td style="vertical-align:top;width:' . $column_width . '%"><ul style="margin:0;padding:0;list-style-type:none;">' : '';	
				$i++;
			}
			
			$Template->Assign_block_vars('news_link.list', array(
				'ICON' => ((!empty($row['icon']) && $CONFIG_NEWS['activ_icon'] == 1) ? '<a href="news' . transid('.php?cat=' . $row['idcat'], '-' . $row['idcat'] . '.php') . '"><img style="vertical-align:middle;" src="' . $row['icon'] . '" alt="" /></a>' : ''),
				'DATE' => gmdate_format('date_format_short', $row['timestamp']),
				'TITLE' => $row['title'],
				'NEW_ROW' => $new_row, 
				'U_NEWS' => 'news' . transid('.php?id=' . $row['id'], '-0-' . $row['id'] . '+' . url_encode_rewrite($row['title']) . '.php')
			));
		}
		$Sql->Close($result);
	}
}
elseif( !empty($idnews) ) //On affiche la news correspondant � l'id envoy�.
{
	if( empty($news['id']) )
		$Errorh->Error_handler('e_unexist_news', E_USER_REDIRECT);
	
	$Template->Set_filenames(array('news' => '../templates/' . $CONFIG['theme'] . '/news/news.tpl'));
	
	//Initialisation
	list($admin, $del, $com, $link) = array('', '', '', '', ''); 		
	if( $is_admin )
	{
		$admin = '&nbsp;&nbsp;<a href="../news/admin_news.php?id=' . $news['id'] . '" title="' . $LANG['edit'] . '"><img style="vertical-align:middle;" src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" /></a>';
		$del = '&nbsp;&nbsp;<a href="../news/admin_news.php?delete=1&amp;id=' . $news['id'] . '" title="' . $LANG['delete'] . '" onClick="javascript:return Confirm();"><img style="vertical-align:middle;" src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" /></a>';
	}

	$Template->Assign_vars(array(
		'THEME' => $CONFIG['theme'],
		'L_ALERT_DELETE_NEWS' => $LANG['alert_delete_news'],
		'L_ON' => $LANG['on']
	));
	
	//Commentaires		
	if( $CONFIG_NEWS['activ_com'] == 1 ) //Si les commentaires sont activ�s.
	{
		$l_com = ($news['nbr_com'] > 1) ? $LANG['com_s'] : $LANG['com'];
		$com_true = $l_com .  ' (' . $news['nbr_com'] . ')</a>';
		$com_false = $LANG['post_com'] . '</a>';

		$com = ( !empty($news['nbr_com']) ) ? $com_true : $com_false;			
		$link_pop = "<a class=\"com\" href=\"#\" onclick=\"popup('" . HOST . DIR . transid("/includes/com.php?i=" . $idnews . "news") . "', 'news');\">";			
		$link_current = '<a class="com" href="' . HOST . DIR . '/news/news' . transid('.php?cat=0&amp;id=' . $idnews . '&amp;i=0', '-0-' . $idnews . '+' . url_encode_rewrite($news['title']) . '.php?i=0') . '#news">';		
		$link = ($CONFIG['com_popup'] == '0') ? $link_current : $link_pop;
	}

	$Template->Assign_block_vars('news', array(
		'ID' => $news['id'],
		'ICON' => ((!empty($news['icon']) && $CONFIG_NEWS['activ_icon'] == 1) ? '<a href="news.php?cat=' . $news['idcat'] . '"><img style="vertical-align:middle;" src="' . $news['icon'] . '" alt="" /></a>' : ''),
		'TITLE' => $news['title'],
		'CONTENTS' => second_parse($news['contents']),
		'EXTEND_CONTENTS' => second_parse($news['extend_contents']) . '<br /><br />',
		'IMG' => (!empty($news['img']) ? '<img src="' . $news['img'] . '" alt="' . $news['alt'] . '" title="' . $news['alt'] . '" class="img_right" style="margin: 6px; border: 1px solid #000000;" />' : ''),
		'PSEUDO' => $news['login'],
		'DATE' => gmdate_format('date_format_short', $news['timestamp']),
		'COM' => $link . $com,
		'EDIT' => $admin,
		'DEL' => $del,
		'U_MEMBER_ID' => transid('.php?id=' . $news['user_id'], '-' . $news['user_id'] . '.php'),
	));	
}
elseif( !empty($idcat) )
{
	$Template->Set_filenames(array('news' => '../templates/' . $CONFIG['theme'] . '/news/news_cat.tpl'));
	
	$cat = $Sql->Query_array('news_cat', 'id', 'name', 'icon', "WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
	if( empty($cat['id']) )
		$Errorh->Error_handler('error_unexist_cat', E_USER_REDIRECT);
	
	$Template->Assign_vars(array(
		'CAT_NAME' => $cat['name'],
		'EDIT' => ($is_admin) ? '&nbsp;&nbsp;<a href="admin_news_cat.php?id=' . $cat['id'] . '" title="' . $LANG['edit'] . '"><img class="valign_middle" src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" /></a>' : '',
		'L_CATEGORY' => $LANG['category']
	));
		
	$result = $Sql->Query_while("SELECT n.id, n.title, n.nbr_com, nc.id AS idcat, nc.icon
	FROM ".PREFIX."news n
	LEFT JOIN ".PREFIX."news_cat nc ON nc.id = n.idcat
	WHERE n.visible = 1 AND n.idcat = '" . $idcat . "'
	ORDER BY n.timestamp DESC", __LINE__, __FILE__);
	while ($row = $Sql->Sql_fetch_assoc($result))
	{ 
		$Template->Assign_block_vars('list', array(
			'ICON' => ((!empty($row['icon']) && $CONFIG_NEWS['activ_icon'] == 1) ? '<a href="news' . transid('.php?cat=' . $row['idcat'], '-' . $row['idcat'] . '.php') . '"><img style="vertical-align:middle;" src="' . $row['icon'] . '" alt="" /></a>' : ''),
			'TITLE' => $row['title'],
			'COM' => $row['nbr_com'],
			'U_NEWS' => 'news' . transid('.php?id=' . $row['id'], '-0-' . $row['id'] . '+'  . url_encode_rewrite($row['title']) . '.php')
		));
	}
}
	
//Affichage commentaires.
if( isset($_GET['i']) && !empty($idnews) )
{
	$_com_vars = 'news.php?cat=0&amp;id=' . $idnews . '&amp;i=%d';
	$_com_vars_e = 'news.php?cat=0&id=' . $idnews . '&i=1';
	$_com_vars_r = 'news-0-' . $idnews . '.php?i=%d%s';
	$_com_idprov = $idnews;
	$_com_script = 'news';
	include_once('../includes/com.php');
}	
$Template->Pparse('news');
	
require_once('../includes/footer.php'); 

?>
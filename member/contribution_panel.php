<?php
/*##################################################
 *                               contribution_panel.php
 *                            -------------------
 *   begin                : July 21, 2008
 *   copyright          : (C) 2008 Beno�t Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *  
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../kernel/begin.php');

define('TITLE', $LANG['contribution_panel']);

require_once('../kernel/header.php');

if( !$Member->Check_level(MODO_LEVEL) ) //Si il n'est pas mod�rateur
	$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
	
$contribution_id = retrieve(GET, 'id', 0);
	
require_once(PATH_TO_ROOT . '/kernel/framework/contribution/contribution.class.php');
require_once(PATH_TO_ROOT . '/kernel/framework/contribution/contribution_panel.class.php');
require_once(PATH_TO_ROOT . '/kernel/framework/util/date.class.php');

$template = new Template('contribution_panel.tpl');

if( $contribution_id > 0 )
{
	$template->assign_vars(array(
		'C_CONSULT_CONTRIBUTION' => true
	));
	
	$contribution = new Contribution();
	$contribution->load_from_db($contribution_id);
	
	$template->assign_vars(array(
		'ENTITLED' => $contribution->get_entitled()
	));
}
else
{
	$template->assign_vars(array(
		'C_CONTRIBUTION_LIST' => true
	));

	$contribution_panel = new ContributionPanel();

	$contribution = new Contribution();
	$contribution->set_entitled('Proposition de news');
	$contribution->set_fixing_url('news/news.php');
	$contribution->set_module('news');
	$contribution->set_creation_date(new Date());
	$contribution->set_poster_id(4);
	//$contribution->create_in_db();

	//On liste les contributions
	$result = $Sql->Query_while("SELECT id, entitled, fixing_url, module, current_status, creation_date, fixing_date, auth, poster_id, fixer_id, poster_member.login poster_login, fixer_member.login fixer_login, description
		FROM ".PREFIX."contributions c
		LEFT JOIN ".PREFIX."member poster_member ON poster_member.user_id = c.poster_id
		LEFT JOIN ".PREFIX."member fixer_member ON fixer_member.user_id = c.poster_id
		ORDER BY creation_date DESC", __LINE__, __FILE__);

	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$this_contribution = new Contribution;
		$this_contribution->build_from_db($row['id'], $row['entitled'], $row['description'], $row['fixing_url'], $row['module'], $row['current_status'], new Date(DATE_TIMESTAMP, TIMEZONE_USER, $row['creation_date']), new Date(DATE_TIMESTAMP, TIMEZONE_USER, $row['fixing_date']), $row['auth'], $row['poster_id'], $row['fixer_id']);
		
		$template->assign_block_vars('contributions', array(
			'ENTITLED' => $this_contribution->get_entitled(),
			'MODULE' => $this_contribution->get_module_name(),
			'STATUS' => $this_contribution->get_status_name(),
			'CREATION_DATE' => $this_contribution->get_creation_date()->format(DATE_FORMAT_SHORT),
			'FIXING_DATE' => $this_contribution->get_fixing_date()->format(DATE_FORMAT_SHORT),
			'POSTER' => $row['poster_login'],
			'FIXER' => $row['fixer_login'],
			'ACTIONS' => '',
			'U_FIXER_PROFILE' => PATH_TO_ROOT . '/member/' . transid('member.php?id=' . $row['fixer_id'], 'member-' . $row['fixer_id'] . '.php'),
			'U_POSTER_PROFILE' => PATH_TO_ROOT . '/member/' . transid('member.php?id=' . $row['poster_id'], 'member-' . $row['poster_id'] . '.php'),
			'U_CONSULT' => PATH_TO_ROOT . '/member/' . transid('contribution_panel.php?id=' . $row['id']),
			'C_FIXED' => $this_contribution->get_current_status() == CONTRIBUTION_STATUS_PROCESSED
		));
	}
}

$template->parse();

require_once('../kernel/footer.php');

?>
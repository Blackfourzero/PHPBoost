<?php
/*##################################################
 *                                groups.class.php
 *                            -------------------
 *   begin                : May 18, 2007
 *   copyright          : (C) 2007 Viarre R�gis
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

define('ADMIN_NOAUTH_DEFAULT', false); //Admin non obligatoirement s�lectionn�.
define('GROUP_DEFAULT_IDSELECT', '');
define('GROUP_DISABLE_SELECT', 'disabled="disabled" ');
define('GROUP_DISABLED_ADVANCED_AUTH', true); //D�sactivation des autorisations avanc�es.

/**
 * @author R�gis VIARRE <crowkait@phpboost.com>
 * @desc This class provide methods to manage user in groups.
 * @package members
 */
class Group
{
	## Public methods ##
	/**
	 * @desc Constructor. Load informations groups.
	 * @param array $groups_info Informations of all groups.
	 */
	function Group(&$groups_info)
	{
		$this->groups_name = array();
		foreach ($groups_info as $idgroup => $array_group_info)
			$this->groups_name[$idgroup] = $array_group_info['name'];
	}

	/**
	 * @desc Add a member in a group
	 * @param int $user_id User id
	 * @param int $idgroup Group id
	 * @return boolean True if the member has been succefully added.
	 */
	function add_member($user_id, $idgroup)
	{
		global $Sql;

		//On ins�re le groupe au champ membre.
		$user_groups = $Sql->query("SELECT user_groups FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
		if (strpos($user_groups, $idgroup . '|') === false) //Le membre n'appartient pas d�j� au groupe.
			$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_groups = '" . (!empty($user_groups) ? $user_groups : '') . $idgroup . "|' WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
		else
			return false;

		//On ins�re le membre dans le groupe.
		$group_members = $Sql->query("SELECT members FROM " . DB_TABLE_GROUP . " WHERE id = '" . $idgroup . "'", __LINE__, __FILE__);
		if (strpos($group_members, $user_id . '|') === false) //Le membre n'appartient pas d�j� au groupe.
			$Sql->query_inject("UPDATE " . DB_TABLE_GROUP . " SET members = '" . $group_members . $user_id . "|' WHERE id = '" . $idgroup . "'", __LINE__, __FILE__);
		else
			return false;
			
		return true;
	}
 
	/**
	 * @desc Edit the user groups, compute difference between previous and new groups.
	 * @param int $user_id The user id
	 * @param array $array_user_groups The new array of groups.
	 */
	function edit_member($user_id, $array_user_groups)
	{
		global $Sql;
		
		//R�cup�ration des groupes pr�c�dent du membre.
		$user_groups_old = $Sql->query("SELECT user_groups FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
		$array_user_groups_old = explode('|', $user_groups_old);
		
		//Insertion du diff�rentiel positif des groupes pr�c�dent du membre et ceux choisis dans la table des groupes.		
		$array_diff_pos = array_diff($array_user_groups, $array_user_groups_old);
		foreach ($array_diff_pos as $key => $idgroup)				
		{	
			if (!empty($idgroup))	
				$this->add_member($user_id, $idgroup);
		}	
		
		//Insertion du diff�rentiel n�gatif des groupes pr�c�dent du membre et ceux choisis dans la table des groupes.
		$array_diff_neg = array_diff($array_user_groups_old, $array_user_groups);
		foreach ($array_diff_neg as $key => $idgroup)				
		{	
			if (!empty($idgroup))
				$this->remove_member($user_id, $idgroup);
		}
	}
	
	/**
	 * @desc Return the array of user groups (id => name)
	 * @return array The array groups
	 */
	function get_groups_array()
	{
		return $this->groups_name;
	}
 
	/**
	 * @desc Remove a member in a group.
	 * @param int $user_id The user id
	 * @param int $idgroup The id group.
	 */
	function remove_member($user_id, $idgroup)
	{
		global $Sql;

		//Suppression dans la table des membres.
		$user_groups = $Sql->query("SELECT user_groups FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_groups = '" . str_replace($idgroup . '|', '', $user_groups) . "' WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
			
		//Suppression dans la table des groupes.
		$members_group = $Sql->query("SELECT members FROM " . DB_TABLE_GROUP . " WHERE id = '" . $idgroup . "'", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE " . DB_TABLE_GROUP . " SET members = '" . str_replace($user_id . '|', '', $members_group) . "' WHERE id = '" . $idgroup . "'", __LINE__, __FILE__);
	}
	
	var $groups_name; //Tableau contenant le nom des groupes disponibles.
	var $groups_auth; //Tableau contenant uniquement les autorisations des groupes disponibles.
}

?>

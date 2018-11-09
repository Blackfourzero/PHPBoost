<?php
/*##################################################
 *                       UserService.class.php
 *                            -------------------
 *   begin                : March 31, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 * @desc This class manage users
 * @package {@package}
 */
class UserService
{
	private static $querier;
	
	public static function __static()
	{
		self::$querier = PersistenceContext::get_querier();
	}
	
	/**
	 * @desc Create a user
	 * @param UserAuthentification $user_authentification
	 * @param User $user
	 * @return InjectQueryResult
	 */
	public static function create(User $user, AuthenticationMethod $auth_method, $extended_fields = array(), $auth_method_data = array())
	{
		if (!self::user_exists('WHERE display_name = :display_name', array('display_name' => TextHelper::htmlspecialchars($user->get_display_name()))))
		{
			$result = self::$querier->insert(DB_TABLE_MEMBER, array(
				'display_name' => TextHelper::htmlspecialchars($user->get_display_name()),
				'level' => $user->get_level(),
				'groups' => implode('|', $user->get_groups()),
				'email' => $user->get_email(),
				'show_email' => (int)$user->get_show_email(),
				'locale' => $user->get_locale(),
				'timezone' => $user->get_timezone(),
				'theme' => $user->get_theme(),
				'editor' => $user->get_editor(),
				'registration_date' => time()
			));

			$user_id = $result->get_last_inserted_id();
			
			if ($extended_fields instanceof MemberExtendedFieldsService)
			{
				try {
					$fields_data = $extended_fields->get_data($user_id);
				} catch (MemberExtendedFieldErrorsMessageException $e) {
					self::$querier->delete(DB_TABLE_MEMBER, 'WHERE user_id=:user_id', array('user_id' => $user_id));
					throw new MemberExtendedFieldErrorsMessageException($e->getMessage());
				}
			}
			elseif (!is_array($extended_fields))
			{
				$fields_data = array();
			}
			else
			{
				$fields_data = $extended_fields;
			}
			
			$auth_method->associate($user_id, $auth_method_data);
			$fields_data['user_id'] = $user_id;
			self::$querier->insert(DB_TABLE_MEMBER_EXTENDED_FIELDS, $fields_data);
			
			self::regenerate_cache();
			
			return $user_id;
		}
		return false;
	}
	
	public static function delete_by_id($user_id)
	{
		MemberExtendedFieldsService::delete_user_fields($user_id);
		
		$user_auth_types = AuthenticationService::get_user_types_authentication($user_id);
		$activated_external_authentication = AuthenticationService::get_external_auths_activated();
		foreach ($activated_external_authentication as $id => $authentication)
		{
			if (in_array($id, $user_auth_types))
				$authentication->delete_session_token();
		}
		
		$condition = 'WHERE user_id=:user_id';
		$parameters = array('user_id' => $user_id);
		self::$querier->delete(DB_TABLE_MEMBER, $condition, $parameters);
		self::$querier->delete(DB_TABLE_MEMBER_EXTENDED_FIELDS, $condition, $parameters);
		self::$querier->delete(DB_TABLE_SESSIONS, $condition, $parameters);
		self::$querier->delete(DB_TABLE_INTERNAL_AUTHENTICATION, $condition, $parameters);
		self::$querier->delete(DB_TABLE_AUTHENTICATION_METHOD, $condition, $parameters);

		$upload = new Uploads();
		$upload->Empty_folder_member($user_id);
		
		self::regenerate_cache();
	}
	
	/**
	 * @desc Update user
	 * @param User $user 
	 * @param string $condition the SQL condition update user
	 * @param array $parameters 
	 */
	public static function update(User $user, $extended_fields = null)
	{
		$condition = 'WHERE user_id=:user_id';
		$parameters = array('user_id' => $user->get_id());
		self::$querier->update(DB_TABLE_MEMBER, array(
			'display_name' => TextHelper::htmlspecialchars($user->get_display_name()),
			'level' => $user->get_level(),
			'groups' => implode('|', $user->get_groups()),
			'email' => $user->get_email(),
			'show_email' => (int)$user->get_show_email(),
			'locale' => $user->get_locale(),
			'timezone' => $user->get_timezone(),
			'theme' => $user->get_theme(),
			'editor' => $user->get_editor()
		), $condition, $parameters);

		if ($extended_fields !== null)
		{
			if ($extended_fields instanceof MemberExtendedFieldsService)
			{
				try {
					$fields_data = $extended_fields->get_data($user->get_id());
				} catch (MemberExtendedFieldErrorsMessageException $e) {
					throw new MemberExtendedFieldErrorsMessageException($e->getMessage());
				}
			}
			elseif (is_array($extended_fields))
				$fields_data = $extended_fields;
			else
				$fields_data = array();
			
			if ($fields_data)
				self::$querier->update(DB_TABLE_MEMBER_EXTENDED_FIELDS, $fields_data, $condition, $parameters);
		}
		
		SessionData::recheck_cached_data_from_user_id($user->get_id());
		
		self::regenerate_cache();
	}
	
	public static function update_punishment(User $user)
	{
		self::$querier->update(DB_TABLE_MEMBER, array(
			'warning_percentage' => $user->get_warning_percentage(),
			'delay_readonly' => $user->get_delay_readonly(),
			'delay_banned' => $user->get_delay_banned(),
		), 'WHERE user_id=:user_id', array('user_id' => $user->get_id()));
	}
	
	/**
	 * @desc Returns a user
	 * @param string $condition
	 * @param array $parameters
	 * @return User
	 */
	public static function get_user($user_id)
	{
		$row = self::$querier->select_single_row(PREFIX . 'member', array('*'), 'WHERE user_id=:user_id', array('user_id' => $user_id));
		$user = new User();
		$user->set_properties($row);
		return $user;
	}
        
	public static function user_exists($condition, Array $parameters)
	{
		try {
			return self::$querier->get_column_value(DB_TABLE_MEMBER, 'user_id', $condition, $parameters);
		} catch (RowNotFoundException $e) {
			return false;
		}
	}
	
	public static function get_level_lang($level)
	{
		$lang = LangLoader::get('user-common');
		switch ($level) 
		{
			case User::ROBOT_LEVEL:
				return $lang['robot'];
			break;
			case User::VISITOR_LEVEL:
				return $lang['visitor'];
			break;
			case User::MEMBER_LEVEL:
				return $lang['member'];
			break;
			case User::MODERATOR_LEVEL:
			 	return $lang['moderator'];
			break;
			case User::ADMIN_LEVEL:
				return $lang['administrator'];
			break;
		}
	}
	
	public static function get_level_class($level)
	{
		switch ($level)
		{
			case User::ROBOT_LEVEL:
				return 'robot';
			break;
			case User::MEMBER_LEVEL:
				return 'member';
			break;
			case User::MODERATOR_LEVEL:
				return 'modo';
			break;
			case User::ADMIN_LEVEL:
				return 'admin';
			break;
			default:
				return '';
		}
	}
	
	public static function remove_old_unactivated_member_accounts()
	{
		$user_account_settings = UserAccountsConfig::load();
		$delay_unactiv_max = $user_account_settings->get_unactivated_accounts_timeout() * 3600 * 24;
		if ($delay_unactiv_max > 0 && $user_account_settings->get_member_accounts_validation_method() != UserAccountsConfig::ADMINISTRATOR_USER_ACCOUNTS_VALIDATION)
		{
			$result = self::$querier->select_rows(DB_TABLE_INTERNAL_AUTHENTICATION, array('user_id'), 
			'WHERE last_connection < :last_connection AND approved = 0', array('last_connection' => (time() - $delay_unactiv_max)));
			foreach ($result as $row)
			{
				self::delete_by_id($row['user_id']);
			}
			$result->dispose();
		}
	}
	
	public static function count_admin_members()
	{
		return self::$querier->count(DB_TABLE_MEMBER, 'WHERE level = :level', array('level' => User::ADMIN_LEVEL));
	}
	
	public static function display_user_profile_link($user_id)
	{
		if ($user_id != User::VISITOR_LEVEL)
		{
			$user = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, array('display_name', 'level', 'groups'), 'WHERE user_id=:user_id', array('user_id' => $user_id));
			
			if ($user)
			{
				$tpl = new FileTemplate('user/UserProfileLink.tpl');
				
				$group_color = User::get_group_color($user['groups'], $user['level']);
				
				$tpl->put_all(array(
					'C_GROUP_COLOR' => !empty($group_color),
					'DISPLAY_NAME'  => $user['display_name'],
					'LEVEL_CLASS'   => self::get_level_class($user['level']),
					'GROUP_COLOR'   => $group_color,
					'U_PROFILE'     => UserUrlBuilder::profile($user_id)->rel()
				));
				
				return $tpl->render();
			}
		}
		return '';
	}
	
	public static function regenerate_cache()
	{
		GroupsCache::invalidate();
		StatsCache::invalidate();
	}
}
?>
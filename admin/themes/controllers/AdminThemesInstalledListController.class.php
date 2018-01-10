<?php
/*##################################################
 *                      AdminThemesInstalledListController.class.php
 *                            -------------------
 *   begin                : April 20, 2011
 *   copyright            : (C) 2011 K�vin MASSY
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

class AdminThemesInstalledListController extends AdminController
{
	private $lang;
	private $view;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_view();
		$this->save($request);
		
		return new AdminThemesDisplayResponse($this->view, $this->lang['themes.installed_theme']);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('admin-themes-common');
		$this->view = new FileTemplate('admin/themes/AdminThemesInstalledListController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function build_view()
	{
		$installed_themes = ThemesManager::get_installed_themes_map_sorted_by_localized_name();
		$selected_theme_number = 0;
		$theme_number = 1;
		foreach($installed_themes as $theme)
		{
			$configuration = $theme->get_configuration();
			$authorizations = $theme->get_authorizations();
			$author_email = $configuration->get_author_mail();
			$author_website = $configuration->get_author_link();
			$pictures = $configuration->get_pictures();
			
			$this->view->assign_block_vars('themes_installed', array(
				'C_AUTHOR_EMAIL' => !empty($author_email),
				'C_AUTHOR_WEBSITE' => !empty($author_website),
				'C_IS_DEFAULT_THEME' => $theme->get_id() == ThemesManager::get_default_theme(),
				'C_IS_ACTIVATED' => $theme->is_activated(),
				'C_PICTURES' => count($pictures) > 0,
				'THEME_NUMBER' => $theme_number,
				'ID' => $theme->get_id(),
				'NAME' => $configuration->get_name(),
				'VERSION' => $configuration->get_version(),
				'MAIN_PICTURE' => count($pictures) > 0 ? Url::to_rel('/templates/' . $theme->get_id() . '/' . current($pictures)) : '',
				'AUTHOR' => $configuration->get_author_name(),
				'AUTHOR_EMAIL' => $author_email,
				'AUTHOR_WEBSITE' => $author_website,
				'DESCRIPTION' => $configuration->get_description() !== '' ? $configuration->get_description() : $this->lang['themes.bot_informed'],
				'COMPATIBILITY' => $configuration->get_compatibility(),
				'AUTHORIZATIONS' => Authorizations::generate_select(Theme::ACCES_THEME, $authorizations, array(2 => true), $theme->get_id()),
				'HTML_VERSION' => $configuration->get_html_version() !== '' ? $configuration->get_html_version() : $this->lang['themes.bot_informed'],
				'CSS_VERSION' => $configuration->get_css_version() !== '' ? $configuration->get_css_version() : $this->lang['themes.bot_informed'],
				'MAIN_COLOR' => $configuration->get_main_color() !== '' ? $configuration->get_main_color() : $this->lang['themes.bot_informed'],
				'WIDTH' => $configuration->get_variable_width() ? $this->lang['themes.variable-width'] : $configuration->get_width()
			));
			
			if (count($pictures) > 0)
			{
				unset($pictures[0]);
				foreach ($pictures as $picture)
				{
					$this->view->assign_block_vars('themes_installed.pictures', array(
						'URL' => Url::to_rel('/templates/' . $theme->get_id() . '/' . $picture)
					));
				}
			}
			
			if ($theme->get_id() == ThemesManager::get_default_theme())
				$selected_theme_number = $theme_number;
			
			$theme_number++;
		}
		
		$installed_themes_number = count($installed_themes);
		$this->view->put_all(array(
			'C_MORE_THAN_ONE_THEME_INSTALLED' => $installed_themes_number > 1,
			'THEMES_NUMBER' => $installed_themes_number,
			'SELECTED_THEME_NUMBER' => $selected_theme_number
		));
	}
	
	public function save(HTTPRequestCustom $request)
	{
		$installed_themes = ThemesManager::get_installed_themes_map();
		
		if ($request->get_string('delete-selected-themes', false))
		{
			$theme_ids = array();
			$theme_number = 1;
			foreach ($installed_themes as $theme)
			{
				if ($request->get_value('delete-checkbox-' . $theme_number, 'off') == 'on')
				{
					$theme_ids[] = $theme->get_id();
				}
				$theme_number++;
			}
			AppContext::get_response()->redirect(AdminThemeUrlBuilder::delete_theme(implode('---', $theme_ids)));
		}
		else
		{
			foreach ($installed_themes as $theme)
			{
				if ($request->get_string('delete-' . $theme->get_id(), ''))
				{
					AppContext::get_response()->redirect(AdminThemeUrlBuilder::delete_theme($theme->get_id()));
				}
			}
		}
		
		if ($request->get_bool('update', false))
		{
			foreach ($installed_themes as $theme)
			{
				if ($theme->get_id() !== ThemesManager::get_default_theme())
				{
					$activated = $request->get_bool('activated-' . $theme->get_id(), false);
					$authorizations = Authorizations::auth_array_simple(Theme::ACCES_THEME, $theme->get_id());
					ThemesManager::change_informations($theme->get_id(), $activated, $authorizations);
				}
			}
			AppContext::get_response()->redirect(AdminThemeUrlBuilder::list_installed_theme(), LangLoader::get_message('process.success', 'status-messages-common'));
		}
	}
}
?>
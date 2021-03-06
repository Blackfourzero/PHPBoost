<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 09 08
 * @since       PHPBoost 2.0 - 2008 07 07
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class CalendarExtensionPointProvider extends ItemsModuleExtensionPointProvider
{
	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), CalendarDisplayCategoryController::get_view());
	}

	public function sitemap()
	{
		return new CalendarSitemapExtensionPoint();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(
			new DispatcherUrlMapping('/calendar/index.php', 'events_list/today/?$', '', 'events_list/&display_current_day_events=1', true),
			new DispatcherUrlMapping('/calendar/index.php')
		));
	}
}
?>

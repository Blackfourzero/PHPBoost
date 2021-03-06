<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 05 08
 * @since       PHPBoost 4.0 - 2014 02 11
 * @contributor xela <xela@phpboost.com>
*/
#################################################*/

class CalendarModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('calendar');
		
		$this->content_tables = array(PREFIX . 'calendar_events_content');
		self::$delete_old_files_list = array(
			'/controllers/categories/CalendarCategoriesFormController.class.php',
			'/controllers/categories/CalendarCategoriesManageController.class.php',
			'/controllers/categories/CalendarDeleteCategoryController.class.php',
			'/phpboost/CalendarComments.class.php',
			'/phpboost/CalendarCurrentMonthEventsCache.class.php',
			'/phpboost/CalendarNewContent.class.php',
			'/phpboost/CalendarSitemapExtensionPoint.class.php',
			'/phpboost/CalendarHomePageExtensionPoint.class.php',
			'/services/CalendarCategoriesCache.class.php',
			'/services/CalendarAuthorizationsService.class.php',
			'/util/AdminCalendarDisplayResponse.class.php'
		);
		
		$this->database_columns_to_add = array(
			array(
				'table_name' => PREFIX . 'calendar_events_content',
				'columns' => array(
					'cancelled' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0)
				)
			)
		);
	}
}
?>

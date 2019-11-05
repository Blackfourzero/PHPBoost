<?php
/**
 * @copyright	&copy; 2005-2019 PHPBoost
 * @license		https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author		Julien BRISWALTER <j1.seth@phpboost.com>
 * @version		PHPBoost 5.3 - last update: 2019 11 05
 * @since		PHPBoost 5.3 - 2019 11 02
*/

class CategoriesService
{
	protected static $categories_manager;

	public static function get_authorized_categories($current_id_category, $are_descriptions_displayed_to_guests = true, $module_id = '', $id_category = CategoriesItemsParameters::DEFAULT_FIELD_NAME)
	{
		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);

		if (AppContext::get_current_user()->is_guest())
			$search_category_children_options->set_allow_only_member_level_authorizations($are_descriptions_displayed_to_guests);

		$categories = self::get_categories_manager($module_id, $id_category)->get_children($current_id_category, $search_category_children_options, true);
		return array_keys($categories);
	}

	public static function get_categories_manager($module_id = '', $id_category = CategoriesItemsParameters::DEFAULT_FIELD_NAME)
	{
		if (self::$categories_manager === null || (!empty($module_id) && $module_id != self::$categories_manager->get_module_id()))
		{
			$module_id = !empty($module_id) ? $module_id : Environment::get_running_module_name();
			$categories_cache_class = ucfirst($module_id) . 'CategoriesCache';
			if (class_exists($categories_cache_class) && is_subclass_of($categories_cache_class, 'CategoriesCache'))
				$categories_cache = call_user_func($categories_cache_class .'::load');
			else
				throw new Exception('Class ' . $categories_cache_class . ' does not exist in module ' . Environment::get_running_module_name());
			
			$categories_items_parameters = new CategoriesItemsParameters();
			$categories_items_parameters->set_table_name_contains_items($categories_cache->get_table_name_containing_items());
			
			if ($id_category != CategoriesItemsParameters::DEFAULT_FIELD_NAME)
				$categories_items_parameters->set_field_name_id_category($id_category);
			
			self::$categories_manager = new CategoriesManager($categories_cache, $categories_items_parameters);
		}
		return self::$categories_manager;
	}
}
?>

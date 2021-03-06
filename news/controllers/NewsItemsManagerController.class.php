<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 06 13
 * @since       PHPBoost 4.0 - 2013 06 24
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class NewsItemsManagerController extends AdminModuleController
{
	private $lang;
	private $view;

	private $elements_number = 0;
	private $ids = array();

	public function execute(HTTPRequestCustom $request)
	{
	    $this->check_authorizations();

		$this->init();

		$current_page = $this->build_table();

		$this->execute_multiple_delete_if_needed($request);

		return $this->generate_response($current_page);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'news');
		$this->view = new StringTemplate('# INCLUDE table #');
	}

	private function build_table()
	{
		$display_categories = CategoriesService::get_categories_manager()->get_categories_cache()->has_categories();

		$columns = array(
			new HTMLTableColumn(LangLoader::get_message('form.name', 'common'), 'title'),
			new HTMLTableColumn(LangLoader::get_message('category', 'categories-common'), 'id_category'),
			new HTMLTableColumn(LangLoader::get_message('author', 'common'), 'display_name'),
			new HTMLTableColumn(LangLoader::get_message('form.date.creation', 'common'), 'creation_date'),
			new HTMLTableColumn(LangLoader::get_message('status', 'common'), 'publication'),
			new HTMLTableColumn(LangLoader::get_message('actions', 'admin-common'), '', array('sr-only' => true))
		);

		if (!$display_categories)
			unset($columns[1]);

		$table_model = new SQLHTMLTableModel(NewsSetup::$news_table, 'table', $columns, new HTMLTableSortingRule('creation_date', HTMLTableSortingRule::DESC));

		$table_model->set_caption($this->lang['news.management']);

        $table = new HTMLTable($table_model);

		$results = array();
		$result = $table_model->get_sql_results('news LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = news.author_user_id');
		foreach ($result as $row)
		{
			$news = new News();
			$news->set_properties($row);
			$category = $news->get_category();
			$user = $news->get_author_user();

			$this->elements_number++;
			$this->ids[$this->elements_number] = $news->get_id();

			$edit_link = new EditLinkHTMLElement(NewsUrlBuilder::edit_item($news->get_id()));
			$delete_link = new DeleteLinkHTMLElement(NewsUrlBuilder::delete_item($news->get_id()));

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();

			$row = array(
				new HTMLTableRowCell(new LinkHTMLElement(NewsUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $news->get_id(), $news->get_rewrited_title()), $news->get_title()), 'left'),
				new HTMLTableRowCell(new LinkHTMLElement(NewsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()), ($category->get_id() == Category::ROOT_CATEGORY ? LangLoader::get_message('none_e', 'common') : $category->get_name()))),
				new HTMLTableRowCell($author),
				new HTMLTableRowCell($news->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
				new HTMLTableRowCell($news->get_status()),
				new HTMLTableRowCell($edit_link->display() . $delete_link->display(), 'controls')
			);

			if (!$display_categories)
				unset($row[1]);

			$results[] = new HTMLTableRow($row);
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('table', $table->display());

		return $table->get_page_number();
	}

	private function execute_multiple_delete_if_needed(HTTPRequestCustom $request)
	{
		if ($request->get_string('delete-selected-elements', false))
		{
			for ($i = 1 ; $i <= $this->elements_number ; $i++)
			{
				if ($request->get_value('delete-checkbox-' . $i, 'off') == 'on')
				{
					if (isset($this->ids[$i]))
					{
						NewsService::delete($this->ids[$i]);
					}
				}
			}

				NewsService::clear_cache();

				AppContext::get_response()->redirect(NewsUrlBuilder::manage_items(), LangLoader::get_message('process.success', 'status-messages-common'));

		}
	}

	private function check_authorizations()
	{
		if (!CategoriesAuthorizationsService::check_authorizations()->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response($page = 1)
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['news.management'], $this->lang['module.title'], $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(NewsUrlBuilder::manage_items());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], NewsUrlBuilder::home());

		$breadcrumb->add($this->lang['news.management'], NewsUrlBuilder::manage_items());

		return $response;
	}
}
?>

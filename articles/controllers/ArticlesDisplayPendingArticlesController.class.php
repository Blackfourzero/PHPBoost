<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 21
 * @since       PHPBoost 4.0 - 2013 03 28
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ArticlesDisplayPendingArticlesController extends AbstractItemController
{
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view($request);

		return $this->generate_response($request);
	}

	private function build_sorting_form($field, $mode)
	{
		$common_lang = LangLoader::get('common');

		$form = new HTMLForm(__CLASS__, '', false);
		$form->set_css_class('options');

		$fieldset = new FormFieldsetHorizontal('filters', array('description' => $common_lang['sort_by']));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_fields', '', $field, array(
				new FormFieldSelectChoiceOption($common_lang['form.date.creation'], Article::SORT_FIELDS_URL_VALUES[Article::SORT_DATE]),
				new FormFieldSelectChoiceOption($common_lang['form.title'], Article::SORT_FIELDS_URL_VALUES[Article::SORT_ALPHABETIC]),
				new FormFieldSelectChoiceOption($common_lang['author'], Article::SORT_FIELDS_URL_VALUES[Article::SORT_AUTHOR])
			), array('events' => array('change' => 'document.location = "'. ItemsUrlBuilder::display_pending(self::get_module()->get_id())->rel() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', '', $mode,
			array(
				new FormFieldSelectChoiceOption($common_lang['sort.asc'], 'asc'),
				new FormFieldSelectChoiceOption($common_lang['sort.desc'], 'desc')
			),
			array('events' => array('change' => 'document.location = "' . ItemsUrlBuilder::display_pending(self::get_module()->get_id())->rel() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));

		$this->view->put('SORTING_FORM', $form->display());
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();
		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $this->config->get_summary_displayed_to_guests());
		$comments_config = CommentsConfig::load();
		$content_management_config = ContentManagementConfig::load();

		$mode = $request->get_getstring('sort', $this->config->get_items_default_sort_mode());
		$field = $request->get_getstring('field', Article::SORT_FIELDS_URL_VALUES[$this->config->get_items_default_sort_field()]);

		$sort_mode = TextHelper::strtoupper($mode);
		$sort_mode = (in_array($sort_mode, array(Article::ASC, Article::DESC)) ? $sort_mode : $this->config->get_items_default_sort_mode());

		if (in_array($field, array(Article::SORT_FIELDS_URL_VALUES[Article::SORT_ALPHABETIC], Article::SORT_FIELDS_URL_VALUES[Article::SORT_AUTHOR], Article::SORT_FIELDS_URL_VALUES[Article::SORT_DATE])))
			$sort_field = array_search($field, Article::SORT_FIELDS_URL_VALUES);
		else
			$sort_field = Article::SORT_DATE;

		$condition = 'WHERE id_category IN :authorized_categories
		' . (!CategoriesAuthorizationsService::check_authorizations()->moderation() ? ' AND author_user_id = :user_id' : '') . '
		AND (published = 0 OR (published = 2 AND (publishing_start_date > :timestamp_now OR (publishing_end_date != 0 AND publishing_end_date < :timestamp_now))))';
		$parameters = array(
			'authorized_categories' => $authorized_categories,
			'user_id' => AppContext::get_current_user()->get_id(),
			'timestamp_now' => $now->get_timestamp()
		);

		$page = $request->get_getint('page', 1);
		$pagination = $this->get_pagination($condition, $parameters, $field, TextHelper::strtolower($sort_mode), $page);

		$result = PersistenceContext::get_querier()->select('SELECT articles.*, member.*, com.number_comments, notes.number_notes, notes.average_notes, note.note
		FROM '. ArticlesSetup::$articles_table .' articles
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = articles.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = articles.id AND com.module_id = "articles"
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = articles.id AND notes.module_name = "articles"
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = articles.id AND note.module_name = "articles" AND note.user_id = :user_id
		' . $condition . '
		ORDER BY ' . $sort_field . ' ' . $sort_mode . '
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));

		$nbr_articles_pending = $result->get_rows_count();

		$this->build_sorting_form($field, TextHelper::strtolower($sort_mode));

		$this->view->put_all(array(
			'C_PENDING'               => true,
			'C_ITEMS'                 => $nbr_articles_pending > 0,
			'C_SEVERAL_ITEMS'         => $result->get_rows_count() > 1,
			'C_GRID_VIEW'             => $this->config->get_display_type() == ArticlesConfig::GRID_VIEW,
			'C_LIST_VIEW'             => $this->config->get_display_type() == ArticlesConfig::LIST_VIEW
		));

		if ($nbr_articles_pending > 0)
		{
			$this->view->put_all(array(
				'C_SORTING_FORM'     => true,
				'C_PAGINATION'       => $pagination->has_several_pages(),
				'PAGINATION'         => $pagination->display(),
				'CATEGORIES_PER_ROW' => $this->config->get_categories_per_row(),
				'ITEMS_PER_ROW'      => $this->config->get_items_per_row(),
			));

			while($row = $result->fetch())
			{
				$article = new Article();
				$article->set_properties($row);

				$this->build_keywords_view($article);

				$this->view->assign_block_vars('articles', $article->get_array_tpl_vars());

				foreach ($article->get_sources() as $name => $url)
				{
					$this->view->assign_block_vars('articles.sources', $article->get_array_tpl_source_vars($name));
				}
			}
		}
		$result->dispose();
	}

	private function build_sources_view(Article $article)
	{
		$sources = $article->get_sources();
		$nbr_sources = count($sources);
		if ($nbr_sources)
		{
			$this->view->put('articles.C_SOURCES', $nbr_sources > 0);

			$i = 1;
			foreach ($sources as $name => $url)
			{
				$this->view->assign_block_vars('articles.sources', array(
					'C_SEPARATOR' => $i < $nbr_sources,
					'NAME' => $name,
					'URL' => $url,
				));
				$i++;
			}
		}
	}

	private function build_keywords_view(Article $article)
	{
		$keywords = $article->get_keywords();
		$nbr_keywords = count($keywords);
		$this->view->put('C_KEYWORDS', $nbr_keywords > 0);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->view->assign_block_vars('keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => ArticlesUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	protected function get_template_to_use()
	{
		return new FileTemplate('articles/ArticlesDisplaySeveralArticlesController.tpl');
	}

	protected function check_authorizations()
	{
		if (!(CategoriesAuthorizationsService::check_authorizations()->write() || CategoriesAuthorizationsService::check_authorizations()->contribution() || CategoriesAuthorizationsService::check_authorizations()->moderation()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function get_pagination($condition, $parameters, $field, $mode, $page)
	{
		$number_articles = self::get_items_manager()->count($condition, $parameters);

		$pagination = new ModulePagination($page, $number_articles, (int)ArticlesConfig::load()->get_items_per_page());
		$pagination->set_url(ArticlesUrlBuilder::display_pending_articles($field, $mode, '/%d'));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function generate_response(HTTPRequestCustom $request)
	{
		$sort_field = $request->get_getstring('field', Article::SORT_FIELDS_URL_VALUES[$this->config->get_items_default_sort_field()]);
		$sort_mode = $request->get_getstring('sort', $this->config->get_items_default_sort_mode());
		$page = $request->get_getint('page', 1);
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['articles.pending.items'], self::get_module()->get_configuration()->get_name(), $page);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['articles.seo.description.pending'], $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(ArticlesUrlBuilder::display_pending_articles($sort_field, $sort_mode, $page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add(self::get_module()->get_configuration()->get_name(), ModulesUrlBuilder::home());
		$breadcrumb->add($this->lang['articles.pending.items'], ArticlesUrlBuilder::display_pending_articles($sort_field, $sort_mode, $page));

		return $response;
	}
}
?>

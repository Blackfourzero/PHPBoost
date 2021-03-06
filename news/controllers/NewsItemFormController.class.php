<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 09 17
 * @since       PHPBoost 4.0 - 2013 02 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class NewsItemFormController extends ModuleController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;

	private $lang;
	private $common_lang;

	private $item;
	private $is_new_item;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->check_authorizations();

		$this->build_form($request);

		$view = new StringTemplate('# INCLUDE MESSAGE ## INCLUDE FORM #');
		$view->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->redirect();
		}

		$view->put('FORM', $this->form->display());

		return $this->generate_response($view);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'news');
		$this->common_lang = LangLoader::get('common');
		$this->config = NewsConfig::load();
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('news', $this->lang['module.title']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('title', $this->common_lang['form.name'], $this->get_news()->get_title(), array('required' => true)));

		if (CategoriesAuthorizationsService::check_authorizations($this->get_news()->get_id_category())->moderation())
		{
			$fieldset->add_field(new FormFieldCheckbox('personalize_rewrited_title', $this->common_lang['form.rewrited_name.personalize'], $this->get_news()->rewrited_title_is_personalized(), array(
			'events' => array('click' => '
			if (HTMLForms.getField("personalize_rewrited_title").getValue()) {
				HTMLForms.getField("rewrited_title").enable();
			} else {
				HTMLForms.getField("rewrited_title").disable();
			}'
			))));

			$fieldset->add_field(new FormFieldTextEditor('rewrited_title', $this->common_lang['form.rewrited_name'], $this->get_news()->get_rewrited_title(), array(
				'description' => $this->common_lang['form.rewrited_name.description'],
				'hidden' => !$this->get_news()->rewrited_title_is_personalized()
			), array(new FormFieldConstraintRegex('`^[a-z0-9\-]+$`iu'))));
		}

		if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(CategoriesService::get_categories_manager()->get_select_categories_form_field('id_cat', $this->common_lang['form.category'], $this->get_news()->get_id_category(), $search_category_children_options));
		}

		$fieldset->add_field(new FormFieldRichTextEditor('contents', $this->common_lang['form.contents'], $this->get_news()->get_contents(), array('rows' => 15, 'required' => true)));

		$fieldset->add_field(new FormFieldCheckbox('enable_summary', $this->lang['news.form.summary.enabled'], $this->get_news()->get_summary_enabled(),
			array('description' => StringVars::replace_vars($this->lang['news.form.summary.enabled.description'], array('number' => NewsConfig::load()->get_characters_number_to_cut())), 'events' => array('click' => '
			if (HTMLForms.getField("enable_summary").getValue()) {
				HTMLForms.getField("summary").enable();
			} else {
				HTMLForms.getField("summary").disable();
			}'))
		));

		$fieldset->add_field(new FormFieldRichTextEditor('summary', $this->lang['news.form.summary'], $this->get_news()->get_summary(), array(
			'hidden' => !$this->get_news()->get_summary_enabled(),
			'description' => !NewsConfig::load()->get_full_item_display() ? '<span class="error">' . $this->lang['news.form.summary.description'] . '</span>' : ''
		)));

		if ($this->config->get_author_displayed() == true)
		{
			$fieldset->add_field(new FormFieldCheckbox('author_custom_name_enabled', $this->common_lang['form.author_custom_name_enabled'], $this->get_news()->is_author_custom_name_enabled(),
				array('events' => array('click' => '
				if (HTMLForms.getField("author_custom_name_enabled").getValue()) {
					HTMLForms.getField("author_custom_name").enable();
				} else {
					HTMLForms.getField("author_custom_name").disable();
				}'))
			));

			$fieldset->add_field(new FormFieldTextEditor('author_custom_name', $this->common_lang['form.author_custom_name'], $this->get_news()->get_author_custom_name(), array(
				'hidden' => !$this->get_news()->is_author_custom_name_enabled(),
			)));
		}

		$other_fieldset = new FormFieldsetHTML('other', $this->common_lang['form.other']);
		$form->add_fieldset($other_fieldset);

		$other_fieldset->add_field(new FormFieldUploadFile('thumbnail', $this->common_lang['form.picture'], $this->get_news()->get_thumbnail()->relative()));

		$other_fieldset->add_field(KeywordsService::get_keywords_manager()->get_form_field($this->get_news()->get_id(), 'keywords', $this->common_lang['form.keywords'], array('description' => $this->common_lang['form.keywords.description'])));

		$other_fieldset->add_field(new FormFieldSelectSources('sources', $this->common_lang['form.sources'], $this->get_news()->get_sources()));

		if (CategoriesAuthorizationsService::check_authorizations($this->get_news()->get_id_category())->moderation())
		{
			$publication_fieldset = new FormFieldsetHTML('publication', $this->common_lang['form.approbation']);
			$form->add_fieldset($publication_fieldset);

			$publication_fieldset->add_field(new FormFieldDateTime('creation_date', $this->common_lang['form.date.creation'], $this->get_news()->get_creation_date(),
				array('required' => true)
			));

			if (!$this->is_new_item)
			{
				$publication_fieldset->add_field(new FormFieldCheckbox('update_creation_date', $this->common_lang['form.update.date.creation'], false, array('hidden' => $this->get_news()->get_publication() != News::NOT_APPROVAL)
				));
			}

			$publication_fieldset->add_field(new FormFieldSimpleSelectChoice('publication', $this->common_lang['form.approbation'], $this->get_news()->get_publication(),
				array(
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.not'], News::NOT_APPROVAL),
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.now'], News::APPROVAL_NOW),
					new FormFieldSelectChoiceOption($this->common_lang['status.approved.date'], News::APPROVAL_DATE),
				),
				array('events' => array('change' => '
				if (HTMLForms.getField("publication").getValue() == 2) {
					jQuery("#' . __CLASS__ . '_start_date_field").show();
					HTMLForms.getField("end_date_enable").enable();
				} else {
					jQuery("#' . __CLASS__ . '_start_date_field").hide();
					HTMLForms.getField("end_date_enable").disable();
				}'))
			));

			$publication_fieldset->add_field(new FormFieldDateTime('start_date', $this->common_lang['form.date.start'], ($this->get_news()->get_start_date() === null ? new Date() : $this->get_news()->get_start_date()), array('hidden' => ($this->get_news()->get_publication() != News::APPROVAL_DATE))));

			$publication_fieldset->add_field(new FormFieldCheckbox('end_date_enable', $this->common_lang['form.date.end.enable'], $this->get_news()->end_date_enabled(), array(
			'hidden' => ($this->get_news()->get_publication() != News::APPROVAL_DATE),
			'events' => array('click' => '
			if (HTMLForms.getField("end_date_enable").getValue()) {
				HTMLForms.getField("end_date").enable();
			} else {
				HTMLForms.getField("end_date").disable();
			}'
			))));

			$publication_fieldset->add_field(new FormFieldDateTime('end_date', $this->common_lang['form.date.end'], ($this->get_news()->get_end_date() === null ? new Date() : $this->get_news()->get_end_date()), array('hidden' => !$this->get_news()->end_date_enabled())));

			$publication_fieldset->add_field(new FormFieldCheckbox('top_list', $this->lang['news.form.top_list'], $this->get_news()->top_list_enabled()));
		}

		$this->build_contribution_fieldset($form);

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function build_contribution_fieldset($form)
	{
		$user_common = LangLoader::get('user-common');
		if ($this->get_news()->get_id() === null && $this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', $user_common['contribution']);
			$fieldset->set_description(MessageHelper::display($user_common['contribution.extended.explain'], MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', $user_common['contribution.description'], '', array('description' => $user_common['contribution.description.explain'])));
		}
		elseif ($this->get_news()->is_visible() && $this->get_news()->is_authorized_to_edit() && !AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
		{
			$fieldset = new FormFieldsetHTML('member_edition', $user_common['contribution.member.edition']);
			$fieldset->set_description(MessageHelper::display($user_common['contribution.member.edition.explain'], MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('edition_description', $user_common['contribution.member.edition.description'], '',
				array('description' => $user_common['contribution.member.edition.description.desc'])
			));
		}
	}

	private function is_contributor_member()
	{
		return (!CategoriesAuthorizationsService::check_authorizations()->write() && CategoriesAuthorizationsService::check_authorizations()->contribution());
	}

	private function get_news()
	{
		if ($this->item === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->item = NewsService::get_news('WHERE id=:id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_item = true;
				$this->item = new News();
				$this->item->init_default_properties(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY));
			}
		}
		return $this->item;
	}

	private function check_authorizations()
	{
		$item = $this->get_news();

		if ($item->get_id() === null)
		{
			if (!$item->is_authorized_to_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$item->is_authorized_to_edit())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}
	}

	private function save()
	{
		$this->item->set_title($this->form->get_value('title'));

		if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
			$this->item->set_id_category($this->form->get_value('id_cat')->get_raw_value());

		$this->item->set_contents($this->form->get_value('contents'));
		$this->item->set_summary(($this->form->get_value('enable_summary') ? $this->form->get_value('summary') : ''));
		$this->item->set_thumbnail(new Url($this->form->get_value('thumbnail')));

		if ($this->config->get_author_displayed() == true)
			$this->item->set_author_custom_name(($this->form->get_value('author_custom_name') && $this->form->get_value('author_custom_name') !== $this->item->get_author_user()->get_display_name() ? $this->form->get_value('author_custom_name') : ''));

		$this->item->set_sources($this->form->get_value('sources'));

		if (!CategoriesAuthorizationsService::check_authorizations($this->item->get_id_category())->moderation())
		{
			if ($this->item->get_id() === null)
				$this->item->set_creation_date(new Date());

			$this->item->set_rewrited_title(Url::encode_rewrite($this->item->get_title()));
			$this->item->clean_start_and_end_date();

			if (CategoriesAuthorizationsService::check_authorizations($this->item->get_id_category())->contribution() && !CategoriesAuthorizationsService::check_authorizations($this->item->get_id_category())->write())
				$this->item->set_publication(News::NOT_APPROVAL);
		}
		else
		{
			if ($this->form->get_value('update_creation_date'))
			{
				$this->item->set_creation_date(new Date());
			}
			else
			{
				$this->item->set_creation_date($this->form->get_value('creation_date'));
			}

			$rewrited_title = $this->form->get_value('rewrited_title', '');
			$rewrited_title = $this->form->get_value('personalize_rewrited_title') && !empty($rewrited_title) ? $rewrited_title : Url::encode_rewrite($this->item->get_title());
			$this->item->set_rewrited_title($rewrited_title);
			$this->item->set_top_list_enabled($this->form->get_value('top_list'));
			$this->item->set_publication($this->form->get_value('publication')->get_raw_value());
			if ($this->item->get_publication() == News::APPROVAL_DATE)
			{
				$config = NewsConfig::load();
				$deferred_operations = $config->get_deferred_operations();

				$old_start_date = $this->item->get_start_date();
				$start_date = $this->form->get_value('start_date');
				$this->item->set_start_date($start_date);

				if ($old_start_date !== null && $old_start_date->get_timestamp() != $start_date->get_timestamp() && in_array($old_start_date->get_timestamp(), $deferred_operations))
				{
					$key = array_search($old_start_date->get_timestamp(), $deferred_operations);
					unset($deferred_operations[$key]);
				}

				if (!in_array($start_date->get_timestamp(), $deferred_operations))
					$deferred_operations[] = $start_date->get_timestamp();

				if ($this->form->get_value('end_date_enable'))
				{
					$old_end_date = $this->item->get_end_date();
					$end_date = $this->form->get_value('end_date');
					$this->item->set_end_date($end_date);

					if ($old_end_date !== null && $old_end_date->get_timestamp() != $end_date->get_timestamp() && in_array($old_end_date->get_timestamp(), $deferred_operations))
					{
						$key = array_search($old_end_date->get_timestamp(), $deferred_operations);
						unset($deferred_operations[$key]);
					}

					if (!in_array($end_date->get_timestamp(), $deferred_operations))
						$deferred_operations[] = $end_date->get_timestamp();
				}
				else
				{
					$this->item->clean_end_date();
				}

				$config->set_deferred_operations($deferred_operations);
				NewsConfig::save();
			}
			else
			{
				$this->item->clean_start_and_end_date();
			}
		}

		if ($this->item->get_id() === null)
		{
			$this->item->set_author_user(AppContext::get_current_user());
			$id_news = NewsService::add($this->item);
		}
		else
		{
			$id_news = $this->item->get_id();
			NewsService::update($this->item);
		}

		$this->contribution_actions($this->item, $id_news);

		KeywordsService::get_keywords_manager()->put_relations($id_news, $this->form->get_value('keywords'));

		NewsService::clear_cache();
	}

	private function contribution_actions(News $item, $id_news)
	{
		if ($this->is_contributor_member())
		{
			$contribution = new Contribution();
			$contribution->set_id_in_module($id_news);
			if ($item->get_id() === null)
				$contribution->set_description(stripslashes($this->form->get_value('contribution_description')));
			else
				$contribution->set_description(stripslashes($this->form->get_value('edition_description')));

			$contribution->set_entitled($item->get_title());
			$contribution->set_fixing_url(NewsUrlBuilder::edit_item($id_news)->relative());
			$contribution->set_poster_id(AppContext::get_current_user()->get_id());
			$contribution->set_module('news');
			$contribution->set_auth(
				Authorizations::capture_and_shift_bit_auth(
					CategoriesService::get_categories_manager()->get_heritated_authorizations($item->get_id_category(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
					Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
				)
			);
			ContributionService::save_contribution($contribution);
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria('news', $id_news);
			if (count($corresponding_contributions) > 0)
			{
				foreach ($corresponding_contributions as $contribution)
				{
					$contribution->set_status(Event::EVENT_STATUS_PROCESSED);
					ContributionService::save_contribution($contribution);
				}
			}
		}
		$item->set_id($id_news);
	}

	private function redirect()
	{
		$category = $this->item->get_category();

		if ($this->is_new_item && $this->is_contributor_member() && !$this->item->is_visible())
		{
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		elseif ($this->item->is_visible())
		{
			if ($this->is_new_item)
				AppContext::get_response()->redirect(NewsUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title()), StringVars::replace_vars($this->lang['news.message.success.add'], array('title' => $this->item->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : NewsUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title())), StringVars::replace_vars($this->lang['news.message.success.edit'], array('title' => $this->item->get_title())));
		}
		else
		{
			if ($this->is_new_item)
				AppContext::get_response()->redirect(NewsUrlBuilder::display_pending_items(), StringVars::replace_vars($this->lang['news.message.success.add'], array('title' => $this->item->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : NewsUrlBuilder::display_pending_items()), StringVars::replace_vars($this->lang['news.message.success.edit'], array('title' => $this->item->get_title())));
		}
	}

	private function generate_response(View $view)
	{
		$location_id = $this->get_news()->get_id() ? 'news-edit-'. $this->get_news()->get_id() : '';

		$response = new SiteDisplayResponse($view, $location_id);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], NewsUrlBuilder::home());

		if ($this->get_news()->get_id() === null)
		{
			$graphical_environment->set_page_title($this->lang['news.add'], $this->lang['module.title']);
			$breadcrumb->add($this->lang['news.add'], NewsUrlBuilder::add_item($this->item->get_id_category()));
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['news.add']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(NewsUrlBuilder::add_item($this->item->get_id_category()));
		}
		else
		{
			if (!AppContext::get_session()->location_id_already_exists($location_id))
				$graphical_environment->set_location_id($location_id);

			$graphical_environment->set_page_title($this->lang['news.edit'], $this->lang['module.title']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['news.edit']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(NewsUrlBuilder::edit_item($this->item->get_id()));

			$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($this->item->get_id_category(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), NewsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
			}
			$category = $this->item->get_category();
			$breadcrumb->add($this->item->get_title(), NewsUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title()));
			$breadcrumb->add($this->lang['news.edit'], NewsUrlBuilder::edit_item($this->item->get_id()));
		}

		return $response;
	}
}
?>

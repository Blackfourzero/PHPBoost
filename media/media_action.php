<?php
/*##################################################
 *              	 media_action.php
 *              	-------------------
 * begin        	: October 20, 2008
 * copyright    	: (C) 2007 Geoffrey ROGUELON
 * email        	: liaght@gmail.com
 *
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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 ###################################################*/

require_once('../kernel/begin.php');
require_once('media_begin.php');

if (AppContext::get_current_user()->is_readonly())
{
	$controller = PHPBoostErrors::user_in_read_only();
	DispatchManager::redirect($controller);
}

$tpl = new FileTemplate('media/media_action.tpl');

$config = MediaConfig::load();
$request = AppContext::get_request();

$submit = $request->get_postvalue('submit', false);

$unvisible = retrieve(GET, 'unvisible', 0, TINTEGER);
$add = retrieve(GET, 'add', 0, TINTEGER);
$edit = retrieve(GET, 'edit', 0, TINTEGER);
$delete = retrieve(GET, 'del', 0, TINTEGER);

// Modification du statut du fichier.
if ($unvisible > 0)
{
	AppContext::get_session()->csrf_get_protect();

	try {
		$media = PersistenceContext::get_querier()->select_single_row(PREFIX . 'media', array('*'), 'WHERE id=:id', array('id' => $unvisible));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
	
	// Gestion des erreurs.
	if (empty($media))
	{
		$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $LANG['e_unexist_media']);
		DispatchManager::redirect($controller);
	}
	elseif (!MediaAuthorizationsService::check_authorizations($media['idcat'])->moderation())
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	bread_crumb($media['idcat']);
	$Bread_crumb->add($media['name'], url('media.php?id=' . $media['id'], 'media-' . $media['id'] . '-' . $media['idcat'] . '+' . Url::encode_rewrite($media['name']) . '.php'));
	$Bread_crumb->add($MEDIA_LANG['hide_media'], url('media_action.php?unvisible=' . $media['id'] . '&amp;token=' . AppContext::get_session()->get_token()));

	define('TITLE', $MEDIA_LANG['media_moderation']);

	PersistenceContext::get_querier()->update(PREFIX . 'media', array('infos' => MEDIA_STATUS_UNVISIBLE), 'WHERE id=:id', array('id' => $unvisible));

	require_once('../kernel/header.php');

	AppContext::get_response()->redirect('media' . url('.php?cat=' . $media['idcat'], '-0-' . $media['idcat'] . '.php'));
}
// Suppression d'un fichier.
elseif ($delete > 0)
{
	AppContext::get_session()->csrf_get_protect();

	try {
		$media = PersistenceContext::get_querier()->select_single_row(PREFIX . 'media', array('*'), 'WHERE id=:id', array('id' => $delete));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
	
	if (empty($media))
	{
		$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $LANG['e_unexist_media']);
		DispatchManager::redirect($controller);
	}
	elseif (!MediaAuthorizationsService::check_authorizations($media['idcat'])->moderation())
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	PersistenceContext::get_querier()->delete(PREFIX . 'media', 'WHERE id=:id', array('id' => $delete));

	NotationService::delete_notes_id_in_module('media', $delete);
	
	CommentsService::delete_comments_topic_module('media', $delete);
	
	// Feeds Regeneration
	Feed::clear_cache('media');
	
	MediaCategoriesCache::invalidate();
	
	$category = MediaService::get_categories_manager()->get_categories_cache()->get_category($media['idcat']);
	bread_crumb($media['idcat']);
	$Bread_crumb->add($MEDIA_LANG['delete_media'], url('media.php?cat=' . $media['idcat'], 'media-0-' . $media['idcat'] . '+' . $category->get_rewrited_name() . '.php'));

	define('TITLE', $MEDIA_LANG['delete_media']);
	require_once('../kernel/header.php');

	AppContext::get_response()->redirect('media' . url('.php?cat=' . $media['idcat'], '-' . $media['idcat'] . '.php'));
}
// Formulaire d'ajout ou d'édition.
elseif ($add >= 0 && !$submit || $edit > 0)
{
	$editor = AppContext::get_content_formatting_service()->get_default_editor();
	$editor->set_identifier('contents');
	
	$tpl->put_all(array(
		'C_ADD_MEDIA' => true,
		'U_TARGET' => url('media_action.php'),
		'L_TITLE' => $MEDIA_LANG['media_name'],
		'L_WIDTH' => $MEDIA_LANG['media_width'],
		'L_HEIGHT' => $MEDIA_LANG['media_height'],
		'L_U_MEDIA' => $MEDIA_LANG['media_url'],
		'L_CONTENTS' => $MEDIA_LANG['media_description'],
		'KERNEL_EDITOR' => $editor->display(),
		'L_APPROVED' => $MEDIA_LANG['media_approved'],
		'L_CONTRIBUTION_LEGEND' => $LANG['contribution'],
		'L_NOTICE_CONTRIBUTION' => $MEDIA_LANG['notice_contribution'],
		'L_CONTRIBUTION_COUNTERPART' => $MEDIA_LANG['contribution_counterpart'],
		'L_CONTRIBUTION_COUNTERPART_EXPLAIN' => $MEDIA_LANG['contribution_counterpart_explain'],
		'L_REQUIRE' => LangLoader::get_message('form.explain_required_fields', 'status-messages-common'),
		'L_REQUIRE_NAME' => $MEDIA_LANG['require_name'],
		'L_REQUIRE_URL' => $MEDIA_LANG['require_url'],
		'L_RESET' => $LANG['reset'],
		'L_PREVIEW' => $LANG['preview'],
		'L_SUBMIT' => $edit > 0 ? $LANG['update'] : $LANG['submit']
	));

	// Construction du tableau des catégories musicales.
	$categories = MediaService::get_categories_manager()->get_categories_cache()->get_categories();
	$js_id_music = array();
	foreach ($categories as $cat)
	{
		if ($cat->get_content_type() == MediaConfig::CONTENT_TYPE_MUSIC)
		{
			$js_id_music[] = $cat->get_id();
		}
	}
	
	$search_category_children_options = new SearchCategoryChildrensOptions();
	$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
	$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
	
	$media = '';
	
	// Édition.
	if ($edit > 0)
	{
		try {
			$media = PersistenceContext::get_querier()->select_single_row(PREFIX . 'media', array('*'), 'WHERE id=:id', array('id' => $edit));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		if (!MediaAuthorizationsService::check_authorizations($media['idcat'])->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		
		bread_crumb($media['idcat']);
		
		$categories_tree = MediaService::get_categories_manager()->get_select_categories_form_field('idcat', '', $media['idcat'], $search_category_children_options);
		$method = new ReflectionMethod('AbstractFormFieldChoice', 'get_options');
		$method->setAccessible(true);
		$categories_tree_options = $method->invoke($categories_tree);
		$categories_list = '';
		foreach ($categories_tree_options as $option)
		{
			$categories_list .= $option->display()->render();
		}
		
		$tpl->put_all(array(
			'L_PAGE_TITLE' => $MEDIA_LANG['edit_media'],
			'C_CONTRIBUTION' => 0,
			'IDEDIT' => $media['id'],
			'NAME' => $media['name'],
			'CATEGORIES' => $categories_list,
			'WIDTH' => $media['width'],
			'HEIGHT' => $media['height'],
			'U_MEDIA' => $media['url'],
			'DESCRIPTION' => FormatingHelper::unparse(stripslashes($media['contents'])),
			'APPROVED' => ($media['infos'] & MEDIA_STATUS_APROBED) !== 0 ? ' checked="checked"' : '',
			'C_APROB' => ($media['infos'] & MEDIA_STATUS_APROBED) === 0,
			'JS_ID_MUSIC' => '"' . implode('", "', $js_id_music) . '"',
			'C_MUSIC' => in_array($media['mime_type'], $mime_type['audio'])
		));
	}
	// Ajout.
	elseif (($write = MediaAuthorizationsService::check_authorizations()->write()) || MediaAuthorizationsService::check_authorizations()->contribution())
	{
		bread_crumb($add);

		$editor = AppContext::get_content_formatting_service()->get_default_editor();
		$editor->set_identifier('counterpart');
		
		$categories_tree = MediaService::get_categories_manager()->get_select_categories_form_field('idcat', '', Category::ROOT_CATEGORY, $search_category_children_options);
		$method = new ReflectionMethod('AbstractFormFieldChoice', 'get_options');
		$method->setAccessible(true);
		$categories_tree_options = $method->invoke($categories_tree);
		$categories_list = '';
		foreach ($categories_tree_options as $option)
		{
			$categories_list .= $option->display()->render();
		}
		
		$tpl->put_all(array(
			'L_PAGE_TITLE' => $write ? $MEDIA_LANG['add_media'] : $MEDIA_LANG['contribute_media'],
			'C_CONTRIBUTION' => !$write,
			'CONTRIBUTION_COUNTERPART_EDITOR' => $editor->display(),
			'IDEDIT' => 0,
			'NAME' => '',
			'CATEGORIES' => $categories_list,
			'WIDTH' => '425',
			'HEIGHT' => '344',
			'U_MEDIA' => '',
			'DESCRIPTION' => '',
			'APPROVED' => 'checked="checked"',
			'C_APROB' => false,
			'JS_ID_MUSIC' => '"' . implode('", "', $js_id_music) . '"',
			'C_MUSIC' => $config->is_root_category_content_type_music()
		));
	}
	else
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	if (!empty($media))
	{
		$Bread_crumb->add($media['name'], url('media.php?id=' . $media['id'], 'media-' . $media['id'] . '-' . $media['idcat'] . '+' . Url::encode_rewrite($media['name']) . '.php'));
		$Bread_crumb->add($MEDIA_LANG['edit_media'], url('media_action.php?edit=' . $media['id']));
		define('TITLE', $MEDIA_LANG['edit_media']);
	}
	else
	{
		$Bread_crumb->add($MEDIA_LANG['add_media'], url('media_action.php'));
		define('TITLE', $MEDIA_LANG['add_media']);
	}

	require_once('../kernel/header.php');
}
// Traitement du formulaire.
elseif ($submit)
{
	AppContext::get_session()->csrf_get_protect();
	
	$media = array(
		'idedit' => retrieve(POST, 'idedit', 0, TINTEGER),
		'name' => retrieve(POST, 'name', '', TSTRING),
		'idcat' => retrieve(POST, 'idcat', 0, TINTEGER),
		'width' => min(retrieve(POST, 'width', $config->get_max_video_width(), TINTEGER), $config->get_max_video_width()),
		'height' => min(retrieve(POST, 'height', $config->get_max_video_height(), TINTEGER), $config->get_max_video_height()),
		'url' => retrieve(POST, 'u_media', '', TSTRING),
		'contents' => retrieve(POST, 'contents', '', TSTRING_PARSE),
		'approved' => retrieve(POST, 'approved', 0, TBOOL),
		'contrib' => retrieve(POST, 'contrib', 0, TBOOL),
		'counterpart' => retrieve(POST, 'counterpart', '', TSTRING_PARSE)
	);
	
	$category = MediaService::get_categories_manager()->get_categories_cache()->get_category($media['idcat']);
	bread_crumb($media['idcat']);

	if ($media['idedit'])
	{
		$Bread_crumb->add($media['name'], url('media.php?id=' . $media['idedit'], 'media-' . $media['idedit'] . '-' . $media['idcat'] . '+' . Url::encode_rewrite($media['name']) . '.php'));
		$Bread_crumb->add($MEDIA_LANG['edit_media'], url('media_action.php?edit=' . $media['idedit']));
		define('TITLE', $MEDIA_LANG['edit_media']);
	}
	else
	{
		$Bread_crumb->add($MEDIA_LANG['add_media'], url('media_action.php?add=' . $media['idcat']));
		define('TITLE', $MEDIA_LANG['add_media']);
	}

	require_once('../kernel/header.php');
	
	if (!empty($media['url']))
	{
		if ($category->get_content_type() == MediaConfig::CONTENT_TYPE_MUSIC)
		{
			$mime_type = $mime_type['audio'];
			$host_ok = $host_ok['audio'];
		}
		elseif ($category->get_content_type() == MediaConfig::CONTENT_TYPE_VIDEO)
		{
			$mime_type = $mime_type['video'];
			$host_ok = $host_ok['video'];
		}
		else
		{
			$mime_type = array_merge($mime_type['audio'], $mime_type['video']);
			$host_ok = array_merge($host_ok['audio'], $host_ok['video']);
		}

		$url_media = preg_replace('`\?.*`', '', $media['url']);
		
		if (($pathinfo = pathinfo($url_media)) && !empty($pathinfo['extension']))
		{
			if (array_key_exists($pathinfo['extension'], $mime_type))
			{
				$media['mime_type'] = $mime_type[$pathinfo['extension']];
			}
			else
			{
				$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $LANG['e_mime_disable_media']);
				DispatchManager::redirect($controller);
			}
		}
		elseif (function_exists('get_headers') && ($headers = get_headers($media['url'], 1)) && !empty($headers['Content-Type']))
		{
			if (!is_array($headers['Content-Type']) && in_array($headers['Content-Type'], $mime_type))
			{
				$media['mime_type'] = $headers['Content-Type'];
			}
			elseif (is_array($headers['Content-Type']))
			{
				foreach ($headers['Content-Type'] as $type)
				{
					if (in_array($type, $mime_type))
					{
						$media['mime_type'] = $type;
					}
				}
				
				if (empty($media['mime_type']))
				{
					$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $LANG['e_mime_disable_media']);
					DispatchManager::redirect($controller);
				}
			}
			else
			{
				$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $LANG['e_mime_disable_media']);
				DispatchManager::redirect($controller);
			}
		}
		elseif (($url_parsed = parse_url($media['url'])) && in_array($url_parsed['host'], $host_ok) && in_array('application/x-shockwave-flash', $mime_type))
		{
			$media['mime_type'] = 'application/x-shockwave-flash';
		}
		else
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $LANG['e_mime_unknow_media']);
			DispatchManager::redirect($controller);
		}
	}
	else
	{
		$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $LANG['e_link_empty_media']);
		DispatchManager::redirect($controller);
	}

	// Édition
	if ($media['idedit'] && MediaAuthorizationsService::check_authorizations($media['idcat'])->moderation())
	{
		PersistenceContext::get_querier()->update(PREFIX . "media", array('idcat' => $media['idcat'], 'name' => $media['name'], 'url' => $media['url'], 'contents' => $media['contents'], 'infos' => (MediaAuthorizationsService::check_authorizations($media['idcat'])->write() ? MEDIA_STATUS_APROBED : 0), 'width' => $media['width'], 'height' => $media['height']), 'WHERE id = :id', array('id' => $media['idedit']));

		if ($media['approved'])
		{
			$corresponding_contributions = ContributionService::find_by_criteria('media', $media['idedit']);

			if (count($corresponding_contributions) > 0)
			{
				$media_contribution = $corresponding_contributions[0];
				$media_contribution->set_status(Event::EVENT_STATUS_PROCESSED);

				ContributionService::save_contribution($media_contribution);
			}
		}

		// Feeds Regeneration
		Feed::clear_cache('media');
		
		MediaCategoriesCache::invalidate();
		
		AppContext::get_response()->redirect('media' . url('.php?id=' . $media['idedit']));
	}
	// Ajout
	elseif (!$media['idedit'] && (($auth_write = MediaAuthorizationsService::check_authorizations($media['idcat'])->write()) || MediaAuthorizationsService::check_authorizations($media['idcat'])->contribution()))
	{
		$result = PersistenceContext::get_querier()->insert(PREFIX . "media", array('idcat' => $media['idcat'], 'iduser' => AppContext::get_current_user()->get_id(), 'timestamp' => time(), 'name' => $media['name'], 'contents' => FormatingHelper::strparse($media['contents']), 'url' => $media['url'], 'mime_type' => $media['mime_type'], 'infos' => (MediaAuthorizationsService::check_authorizations($media['idcat'])->write() ? MEDIA_STATUS_APROBED : 0), 'width' => $media['width'], 'height' => $media['height']));

		$new_id_media = $result->get_last_inserted_id();
		// Feeds Regeneration
		Feed::clear_cache('media');
		
		MediaCategoriesCache::invalidate();
		
		if (!$auth_write)
		{
			$media_contribution = new Contribution();
			$media_contribution->set_id_in_module($new_id_media);
			$media_contribution->set_description(stripslashes($media['counterpart']));
			$media_contribution->set_entitled($media['name']);
			$media_contribution->set_fixing_url('/media/media_action.php?edit=' . $new_id_media);
			$media_contribution->set_poster_id(AppContext::get_current_user()->get_id());
			$media_contribution->set_module('media');
			$media_contribution->set_auth(
				Authorizations::capture_and_shift_bit_auth(
					MediaService::get_categories_manager()->get_heritated_authorizations($media['idcat'], Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
					Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
				)
			);

			ContributionService::save_contribution($media_contribution);

			DispatchManager::redirect(new UserContributionSuccessController());
		}
		else
		{
			AppContext::get_response()->redirect('media' . url('.php?id=' . $new_id_media));
		}
	}
	else
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
        DispatchManager::redirect($error_controller);
	}
}
else
{
	$error_controller = PHPBoostErrors::unexisting_page();
    DispatchManager::redirect($error_controller);
}

$tpl->display();

require_once('../kernel/footer.php');

?>

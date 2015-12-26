# IF C_ROOT_CATEGORY #
	${resources('PHPBoostOfficial/common')}
	<script>
	<!--
	function toggle_root_cat_display() {
		if (jQuery('.content').is(":visible"))
		{
			jQuery('.content').hide(400);
			# IF C_CATEGORY_DESCRIPTION #jQuery('.cat-description').show(200);# ENDIF #
			jQuery('.root-categories-container').show(200);
			jQuery('#display-tree').html('<i class="fa fa-folder"></i> ' + ${escapejs(LangLoader::get_message('download.display_root_cat', 'common', 'PHPBoostOfficial'))});
		} else {
			jQuery('.root-categories-container').hide(400);
			# IF C_CATEGORY_DESCRIPTION #jQuery('.cat-description').hide(400);# ENDIF #
			jQuery('.content').show(200);
			jQuery('#display-tree').html('<i class="fa fa-folder"></i> ' + ${escapejs(LangLoader::get_message('download.display_tree', 'common', 'PHPBoostOfficial'))});
		}
	};
	-->
	</script>
	
	<section id="module-download-pbt">
		<header>
			<h1>
				<a href="${relative_url(SyndicationUrlBuilder::rss('download', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
				${LangLoader::get_message('module_title', 'common', 'download')} # IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF #
			</h1>
			# IF C_CATEGORY_DESCRIPTION #
				<div class="cat-description" style="display: none;">
					{CATEGORY_DESCRIPTION}
				</div>
			# ENDIF #
		</header>
		
		<div class="root-categories-container" style="display: none;">
			# IF C_SUB_CATEGORIES #
			<div class="subcat-container">
				# START sub_categories_list #
				<div class="subcat-element" style="width:{CATS_COLUMNS_WIDTH}%;">
					<div class="subcat-content">
						# IF sub_categories_list.C_CATEGORY_IMAGE #<a itemprop="about" href="{sub_categories_list.U_CATEGORY}"><img itemprop="thumbnailUrl" src="{sub_categories_list.CATEGORY_IMAGE}" alt="{sub_categories_list.CATEGORY_NAME}" /></a># ENDIF #
						<br />
						<a itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a>
						<br />
						<span class="small">{sub_categories_list.DOWNLOADFILES_NUMBER} # IF sub_categories_list.C_MORE_THAN_ONE_DOWNLOADFILE #${TextHelper::lowercase_first(LangLoader::get_message('files', 'common', 'download'))}# ELSE #${TextHelper::lowercase_first(LangLoader::get_message('file', 'common', 'download'))}# ENDIF #</span>
					</div>
				</div>
				# END sub_categories_list #
				<div class="spacer"></div>
			</div>
			# IF C_SUBCATEGORIES_PAGINATION #<span class="center"># INCLUDE SUBCATEGORIES_PAGINATION #</span># ENDIF #
			# ELSE #
				# IF NOT C_CATEGORY_DISPLAYED_TABLE #<div class="spacer"></div># ENDIF #
			# ENDIF #
		</div>
		
		<div class="content">
			# IF C_DESCRIPTION #
				<!-- {DESCRIPTION} -->
			# ENDIF #
			<div class="pbt-header">
				<img class="pbt-header-img" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/logo.png" alt="" />
				<div class="pbt-content">
					<p class="pbt-title">{@download.header.title}</p>
					<span class="pbt-desc">{@download.header.description}</span>
				</div>
			</div>
			<hr style="margin:25px 0px;" />
			{@download.page_content.title} :
			<br /><br />
			<ul>
				<li>{@download.page_content.last_stable_version}</li>
				<li>{@download.page_content.previous_version}</li>
				<li>{@download.page_content.updates}</li>
				<li>{@download.page_content.updates_scripts}</li>
			</ul>
			<hr style="margin:25px auto 25px auto;" />

			<article class="block">
				<header>
					<h1>{@download} {@download.last_major_version_number} - {@download.last_version_name}</h1>
					<p class="pbt-desc">
						{@download.last_version.description}
					</p>
				</header>
				
				<div class="pbt-button-container">
					<div class="pbt-button pbt-button-blue">
						<a href="{@download.last_version_download_link}" class="pbt-button-a">
							<div class="pbt-custom-img pbt-custom-img-phpboost"></div>
							<p class="pbt-button-title">{@download} {@download.last_major_version_number}</p>
							<p class="pbt-button-com">Rev : {@download.last_complete_version_number} | Req : {@download.last_minimal_php_version} | .zip </p>
						</a>
					</div>
					<div class="pbt-button pbt-button-green">
						<a href="{@download.last_version_updates_cat_link}" class="pbt-button-a">
							<div class="pbt-custom-img pbt-custom-img-phpboost"></div>
							<p class="pbt-button-title">{@download.updates}</p>
							<p class="pbt-button-com pbt-button-com-green">{@download.updates.description}</p>
						</a>
					</div>
				</div>
				
				<div class="pbt-dev-container">
					<a href="{@download.last_version_pdk_link}" class="pbt-dev">{@download.pdk}</a>
				</div>
				
				<hr style="margin:10px auto 0px auto;" />
				
				<div class="pbt-custom-content">
					<div style="width: 90%;margin:auto;">
						<div class="pbt-custom-container">
							<div class="pbt-custom-img pbt-custom-img-modules"></div>
							<h2 class="title pbt-custom-subtitle">
								<a href="{@download.last_version_modules_cat_link}">{@download.compatible_modules}</a>
							</h2>
							<p class="pbt-custom-desc">{@download.compatible_modules.description}</p>
						</div>
						<div class="pbt-custom-container">
							<div class="pbt-custom-img pbt-custom-img-themes"></div>
							<h2 class="title pbt-custom-subtitle">
								<a href="{@download.last_version_themes_cat_link}">{@download.compatible_themes}</a>
							</h2>
							<p class="pbt-custom-desc">{@download.compatible_themes.description}</p>
						</div>
					</div>
					
					<div class="spacer"></div>
				</div>
			</article>

			<article class="block">
				<header>
					<h1>{@download} {@download.previous_major_version_number} - {@download.previous_version_name}</h1>
					<p class="pbt-desc">
						{@download.previous_version.description}
					</p>
				</header>

				<div class="pbt-button-container">
					<div class="pbt-button pbt-button-blue">
						<a href="{@download.previous_version_download_link}" class="pbt-button-a">
							<div class="pbt-custom-img pbt-custom-img-phpboost"></div>
							<p class="pbt-button-title">{@download} {@download.previous_major_version_number}</p>
							<p class="pbt-button-com">Rev : {@download.previous_complete_version_number} | Req : PHP {@download.previous_minimal_php_version} | .zip </p>
						</a>
					</div>
					<div class="pbt-button pbt-button-green">
						<a href="{@download.previous_version_updates_cat_link}" class="pbt-button-a">
							<div class="pbt-custom-img pbt-custom-img-phpboost"></div>
							<p class="pbt-button-title">{@download.updates}</p>
							<p class="pbt-button-com pbt-button-com-green">{@download.updates.description}</p>
						</a>
					</div>
				</div>
				
				<div class="pbt-dev-container">
					<a href="{@download.previous_version_pdk_link}" class="pbt-dev">{@download.pdk}</a>
				</div>
				
				<hr style="margin:10px auto 0px auto;" />
				
				<div class="pbt-custom-content">
					<div style="width: 90%;margin:auto;">
						<div class="pbt-custom-container">
							<div class="pbt-custom-img pbt-custom-img-modules"></div>
							<h2 class="title pbt-custom-subtitle">
								<a href="{@download.previous_version_modules_cat_link}">{@download.compatible_modules}</a>
							</h2>
							<p class="pbt-custom-desc">{@download.compatible_modules.description}</p>
						</div>
						<div class="pbt-custom-container">
							<div class="pbt-custom-img pbt-custom-img-themes"></div>
							<h2 class="title pbt-custom-subtitle">
								<a href="{@download.previous_version_themes_cat_link}">{@download.compatible_themes}</a>
							</h2>
							<p class="pbt-custom-desc">{@download.compatible_themes.description}</p>
						</div>
					</div>
					
					<div class="spacer"></div>
				</div>
			</article>
		</div>
		
		<hr style="margin:20px auto 30px auto;" />
			
		<div class="pbt-button pbt-button-gray center" style="margin: auto; width: 34%; display: inherit;">
			<a id="display-tree" href="" onclick="toggle_root_cat_display();return false;" class="pbt-button-a" style="width: auto;">
				<i class="fa fa-folder"></i> {@download.display_tree}
			</a>
		</div>
		<footer># IF C_PAGINATION #<div class="center"># INCLUDE PAGINATION #</div># ENDIF #</footer>
	</section>
# ELSE #
<section id="module-download">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('download', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			# IF C_PENDING #{@download.pending}# ELSE #{@module_title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF # # IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF ## ENDIF #
		</h1>
		# IF C_CATEGORY_DESCRIPTION #
			<div class="cat-description">
				{CATEGORY_DESCRIPTION}
			</div>
		# ENDIF #
	</header>
	
	# IF C_SUB_CATEGORIES #
	<div class="subcat-container">
		# START sub_categories_list #
		<div class="subcat-element" style="width:{CATS_COLUMNS_WIDTH}%;">
			<div class="subcat-content">
				# IF sub_categories_list.C_CATEGORY_IMAGE #<a itemprop="about" href="{sub_categories_list.U_CATEGORY}"><img itemprop="thumbnailUrl" src="{sub_categories_list.CATEGORY_IMAGE}" alt="{sub_categories_list.CATEGORY_NAME}" /></a># ENDIF #
				<br />
				<a itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a>
				<br />
				<span class="small">{sub_categories_list.DOWNLOADFILES_NUMBER} # IF sub_categories_list.C_MORE_THAN_ONE_DOWNLOADFILE #${TextHelper::lowercase_first(LangLoader::get_message('files', 'common', 'download'))}# ELSE #${TextHelper::lowercase_first(LangLoader::get_message('file', 'common', 'download'))}# ENDIF #</span>
			</div>
		</div>
		# END sub_categories_list #
		<div class="spacer"></div>
	</div>
	# IF C_SUBCATEGORIES_PAGINATION #<span class="center"># INCLUDE SUBCATEGORIES_PAGINATION #</span># ENDIF #
	# ELSE #
		# IF NOT C_CATEGORY_DISPLAYED_TABLE #<div class="spacer"></div># ENDIF #
	# ENDIF #
	
	<div class="content">
	# IF C_FILES #
		# IF C_MORE_THAN_ONE_FILE #
			# INCLUDE SORT_FORM #
			<div class="spacer"></div>
		# ENDIF #
		# IF C_CATEGORY_DISPLAYED_TABLE #
			<table id="table">
				<thead>
					<tr>
						<th>${LangLoader::get_message('form.name', 'common')}</th>
						<th class="col-small">${LangLoader::get_message('form.keywords', 'common')}</th>
						<th class="col-small">${LangLoader::get_message('form.date.creation', 'common')}</th>
						<th class="col-small">{@downloads_number}</th>
						# IF C_NOTATION_ENABLED #<th>${LangLoader::get_message('note', 'common')}</th># ENDIF #
						# IF C_COMMENTS_ENABLED #<th class="col-small">${LangLoader::get_message('comments', 'comments-common')}</th># ENDIF #
						# IF C_MODERATION #<th class="col-smaller"></th># ENDIF #
					</tr>
				</thead>
				<tbody>
					# START downloadfiles #
					<tr>
						<td>
							<a href="{downloadfiles.U_LINK}" itemprop="name">{downloadfiles.NAME}</a>
						</td>
						<td>
							# IF downloadfiles.C_KEYWORDS #
								# START downloadfiles.keywords #
									<a itemprop="keywords" href="{downloadfiles.keywords.URL}">{downloadfiles.keywords.NAME}</a># IF downloadfiles.keywords.C_SEPARATOR #, # ENDIF #
								# END downloadfiles.keywords #
							# ELSE #
								${LangLoader::get_message('none', 'common')}
							# ENDIF #
						</td>
						<td>
							<time datetime="{downloadfiles.DATE_ISO8601}" itemprop="datePublished">{downloadfiles.DATE}</time>
						</td>
						<td>
							{downloadfiles.NUMBER_DOWNLOADS}
						</td>
						# IF C_NOTATION_ENABLED #
						<td>
							{downloadfiles.STATIC_NOTATION}
						</td>
						# ENDIF #
						# IF C_COMMENTS_ENABLED #
						<td>
							# IF downloadfiles.C_COMMENTS # {downloadfiles.NUMBER_COMMENTS} # ENDIF # {downloadfiles.L_COMMENTS}
						</td>
						# ENDIF #
						# IF C_MODERATION #
						<td>
							# IF downloadfiles.C_EDIT #
								<a href="{downloadfiles.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
							# ENDIF #
							# IF downloadfiles.C_DELETE #
								<a href="{downloadfiles.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
							# ENDIF #
						</td>
						# ENDIF #
					</tr>
					# END downloadfiles #
				</tbody>
			</table>
		# ELSE #
			# START downloadfiles #
			<article id="article-download-{downloadfiles.ID}" class="article-download article-several# IF C_CATEGORY_DISPLAYED_SUMMARY # block# ENDIF #" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
				<header>
					<h2>
						<span class="actions">
							# IF downloadfiles.C_EDIT #<a href="{downloadfiles.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a># ENDIF #
							# IF downloadfiles.C_DELETE #<a href="{downloadfiles.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a># ENDIF #
						</span>
						<a href="{downloadfiles.U_LINK}" itemprop="name">{downloadfiles.NAME}</a>
					</h2>
					
					<meta itemprop="url" content="{downloadfiles.U_LINK}">
					<meta itemprop="description" content="${escape(downloadfiles.DESCRIPTION)}"/>
					# IF C_COMMENTS_ENABLED #
					<meta itemprop="discussionUrl" content="{downloadfiles.U_COMMENTS}">
					<meta itemprop="interactionCount" content="{downloadfiles.NUMBER_COMMENTS} UserComments">
					# ENDIF #
				</header>
				
				# IF C_CATEGORY_DISPLAYED_SUMMARY #
					<div class="more">
						<i class="fa fa-download" title="{downloadfiles.L_DOWNLOADED_TIMES}"></i>
						<span title="{downloadfiles.L_DOWNLOADED_TIMES}">{downloadfiles.NUMBER_DOWNLOADS}</span>
						# IF C_COMMENTS_ENABLED #
							| <i class="fa fa-comment" title="${LangLoader::get_message('comments', 'comments-common')}"></i>
							# IF downloadfiles.C_COMMENTS # {downloadfiles.NUMBER_COMMENTS} # ENDIF # {downloadfiles.L_COMMENTS}
						# ENDIF #
						# IF downloadfiles.C_KEYWORDS #
							| <i class="fa fa-tags" title="${LangLoader::get_message('form.keywords', 'common')}"></i> 
							# START downloadfiles.keywords #
								<a itemprop="keywords" href="{downloadfiles.keywords.URL}">{downloadfiles.keywords.NAME}</a>
								# IF downloadfiles.keywords.C_SEPARATOR #, # ENDIF #
							# END downloadfiles.keywords #
						# ENDIF #
						# IF C_NOTATION_ENABLED #
							<span class="float-right">{downloadfiles.STATIC_NOTATION}</span>
						# ENDIF #
						<div class="spacer"></div>
					</div>
					<div class="content">
						# IF downloadfiles.C_PICTURE #
						<span class="download-picture">
							<img src="{downloadfiles.U_PICTURE}" alt="{downloadfiles.NAME}" itemprop="image" />
						</span>
						# ENDIF #
						{downloadfiles.DESCRIPTION}# IF downloadfiles.C_READ_MORE #... <a href="{downloadfiles.U_LINK}" class="read-more">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #
						<div class="spacer"></div>
					</div>
				# ELSE #
					<div class="content">
						<div class="options infos">
							<div class="center">
								# IF downloadfiles.C_PICTURE #
									<img src="{downloadfiles.U_PICTURE}" alt="{downloadfiles.NAME}" itemprop="image" />
									<div class="spacer"></div>
								# ENDIF #
								# IF downloadfiles.C_VISIBLE #
									<a href="{downloadfiles.U_DOWNLOAD}" class="basic-button">
										<i class="fa fa-download"></i> {@download}
									</a>
									# IF IS_USER_CONNECTED #
									<a href="{downloadfiles.U_DEADLINK}" class="basic-button alt" title="${LangLoader::get_message('deadlink', 'common')}">
										<i class="fa fa-unlink"></i>
									</a>
									# ENDIF #
								# ENDIF #
							</div>
							<h6>{@file_infos}</h6>
							<span class="text-strong">${LangLoader::get_message('size', 'common')} : </span><span># IF downloadfiles.C_SIZE #{downloadfiles.SIZE}# ELSE #${LangLoader::get_message('unknown_size', 'common')}# ENDIF #</span><br/>
							<span class="text-strong">${LangLoader::get_message('form.date.creation', 'common')} : </span><span><time datetime="{downloadfiles.DATE_ISO8601}" itemprop="datePublished">{downloadfiles.DATE}</time></span><br/>
							# IF C_UPDATED_DATE #<span class="text-strong">${LangLoader::get_message('form.date.update', 'common')} : </span><span><time datetime="{downloadfiles.UPDATED_DATE_ISO8601}" itemprop="dateModified">{downloadfiles.UPDATED_DATE}</time></span><br/># ENDIF #
							<span class="text-strong">{@downloads_number} : </span><span>{downloadfiles.NUMBER_DOWNLOADS}</span><br/>
							# IF NOT C_CATEGORY #<span class="text-strong">${LangLoader::get_message('category', 'categories-common')} : </span><span><a itemprop="about" class="small" href="{downloadfiles.U_CATEGORY}">{downloadfiles.CATEGORY_NAME}</a></span><br/># ENDIF #
							# IF downloadfiles.C_KEYWORDS #
								<span class="text-strong">${LangLoader::get_message('form.keywords', 'common')} : </span>
								<span>
									# START downloadfiles.keywords #
										<a itemprop="keywords" class="small" href="{downloadfiles.keywords.URL}">{downloadfiles.keywords.NAME}</a># IF downloadfiles.keywords.C_SEPARATOR #, # ENDIF #
									# END downloadfiles.keywords #
								</span><br/>
							# ENDIF #
							# IF C_AUTHOR_DISPLAYED #
								<span class="text-strong">${LangLoader::get_message('author', 'common')} : </span>
								<span>
									# IF downloadfiles.C_CUSTOM_AUTHOR_DISPLAY_NAME #
										{downloadfiles.CUSTOM_AUTHOR_DISPLAY_NAME}
									# ELSE #
										# IF downloadfiles.C_AUTHOR_EXIST #<a itemprop="author" rel="author" class="small {downloadfiles.USER_LEVEL_CLASS}" href="{downloadfiles.U_AUTHOR_PROFILE}" # IF downloadfiles.C_USER_GROUP_COLOR # style="color:{downloadfiles.USER_GROUP_COLOR}" # ENDIF #>{downloadfiles.PSEUDO}</a># ELSE #{downloadfiles.PSEUDO}# ENDIF #  
									# ENDIF #
								</span><br/>
							# ENDIF #
							# IF C_COMMENTS_ENABLED #
								<span># IF downloadfiles.C_COMMENTS # {downloadfiles.NUMBER_COMMENTS} # ENDIF # {downloadfiles.L_COMMENTS}</span>
							# ENDIF #
							# IF downloadfiles.C_VISIBLE #
								# IF C_NOTATION_ENABLED #
									<div class="spacer"></div>
									<div class="center">{downloadfiles.NOTATION}</div>
								# ENDIF #
							# ENDIF #
						</div>
						
						<div itemprop="text">{downloadfiles.CONTENTS}</div>
					</div>
				# ENDIF #
				
				<footer></footer>
			</article>
			# END downloadfiles #
		# ENDIF #
	# ELSE #
		# IF NOT C_HIDE_NO_ITEM_MESSAGE #
		<div class="center">
			${LangLoader::get_message('no_item_now', 'common')}
		</div>
		# ENDIF #
	# ENDIF #
	</div>
	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>
# ENDIF #

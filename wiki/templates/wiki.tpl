<section id="module-wiki">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('wiki', ID_CAT))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication smaller" aria-hidden="true" title="${LangLoader::get_message('syndication', 'common')}"></i></a>
			{CATEGORY_TITLE}
		</h1>
	</header>
	<article id="article-wiki-{ID}" class="article-wiki# IF C_NEW_CONTENT # new-content# ENDIF #">
		<header>
			<h2>
				{TITLE}
			</h2>
		</header>

		<div class="elements-container columns-2">
			# START cat #
				# IF cat.list_cats #
					<aside class="block">
						<div class="wiki-list-container">
							<div class="wiki-list-top">{L_SUB_CATS}</div>
							<div class="wiki-list-content">
								# START cat.list_cats #
									<div class="wiki-list-item">
										<i class="fa fa-folder-o" aria-hidden="true"></i> <a href="{cat.list_cats.U_CAT}">{cat.list_cats.NAME}</a>
									</div>
								# END cat.list_cats #
								# START cat.no_sub_cat #
									<div class="wiki-list-item">{cat.no_sub_cat.NO_SUB_CAT}</div>
								# END cat.no_sub_cat #
							</div>
						</div>
					</aside>
				# ENDIF #
				<aside class="block">
					<div class="wiki-list-container">
						<div class="wiki-list-top">{L_SUB_ARTICLES}</div>
						<div class="wiki-list-content">
							# START cat.list_art #
								<div class="wiki-list-item">
									<i class="fa fa-file-o" aria-hidden="true"></i> <a href="{cat.list_art.U_ARTICLE}">{cat.list_art.TITLE}</a>
								</div>
							# END cat.list_art #
							# START cat.no_sub_article #
								<div class="wiki-list-item">{cat.no_sub_article.NO_SUB_ARTICLE}</div>
							# END cat.no_sub_article #
						</div>
					</div>
				</aside>


			# END cat #
			<div class="spacer"></div>
		</div>
		# INCLUDE wiki_tools #
		<article>
			<div class="content">
				# START warning #
				<div class="message-helper warning">{warning.UPDATED_ARTICLE}</div>
				# END warning #

				# START redirect #
					<div style="width:30%;">
					{redirect.REDIRECTED}
						# START redirect.remove_redirection #
							<a href="{redirect.remove_redirection.U_REMOVE_REDIRECTION}" data-confirmation="{redirect.remove_redirection.L_ALERT_REMOVE_REDIRECTION}" aria-label="{redirect.remove_redirection.L_REMOVE_REDIRECTION}"><i class="fa fa-delete" aria-hidden="true" title="{redirect.remove_redirection.L_REMOVE_REDIRECTION}"></i></a>
						# END redirect.remove_redirection #
					</div>
					<div class="spacer"></div>
				# END redirect #

				# START status #
					<div class="spacer"></div>
					<div class="blockquote">{status.ARTICLE_STATUS}</div>
					<div class="spacer"></div>
				# END status #

				# START menu #
					# IF C_STICKY_MENU #
						<span class="wiki-sticky-title blink">{L_TABLE_OF_CONTENTS}</span>
						<div class="wiki-sticky">
							{menu.MENU}
						</div>
					# ELSE #
						<div class="wiki-summary">
							<div class="wiki-summary-title">{L_TABLE_OF_CONTENTS}</div>
							{menu.MENU}
						</div>
					# ENDIF #
				# END menu #

				{CONTENTS}
				<div class="spacer"></div>
				${ContentSharingActionsMenuService::display()}
			</div>
		</article>
		<footer>
			<div class="wiki-hits">{HITS}</div>
		</footer>
	</article>
	<footer></footer>
</section>

# IF C_STICKY_MENU #
<script>

/* Push the body and the nav over by the menu div width over */
	var summaryWidth = jQuery('.wiki-sticky').innerWidth();
	var viewportWidth = jQuery(window).width();

	jQuery('.wiki-sticky-title').click(function(f) {
		jQuery('.wiki-sticky-title').removeClass('blink');
		jQuery('.wiki-sticky').animate({
			left: "0px",
			'max-width': viewportWidth + 'px'
		}, 200);

		jQuery('body').animate({
			left: summaryWidth + 'px'
		}, 200);
		f.stopPropagation();
	});

	/* Then push them back by clicking outside the menu or on an inside link*/
	jQuery(document).click(function(f) {
		if (jQuery(f.target).is('.wiki-sticky-title') === false) {
			jQuery('.wiki-sticky').animate({
				left: -summaryWidth + 'px'
			}, 200);

			jQuery('body').animate({
				left: "0px"
			}, 200);
		}
	});
	jQuery('.wiki-sticky a').click(function() {
		jQuery('.wiki-sticky').animate({
			left: -summaryWidth + 'px'
		}, 200);

		jQuery('body').animate({
			left: "0px"
		}, 200);
	});

	// smooth scroll when clicking on an inside link
	jQuery('.wiki-sticky a').click(function(){
		var the_id = jQuery(this).attr("href");

		jQuery('html, body').animate({
			scrollTop:jQuery(the_id).offset().top
		}, 'slow');
		return false;
	});
</script>
# ELSE #

<script>
	<!--
	// smooth scroll when clicking on a inside link
	jQuery('a[href^="#paragraph"]').click(function() {
		var the_id = $(this).attr("href");

		$('html, body').animate({
			scrollTop:$(the_id).offset().top
		}, 'slow');
		return false;
	})
	-->
</script>
# ENDIF #

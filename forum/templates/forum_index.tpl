
	# INCLUDE forum_top #

	# START forums_list #
		# START forums_list.endcats #
					</tbody>
				</table>
			</div>
		</article>

		# END forums_list.endcats #

		# START forums_list.cats #
		<script>
			<!--
			jQuery('#table-{forums_list.cats.IDCAT}').basictable();
			-->
		</script>

		<article itemscope="itemscope" itemtype="http://schema.org/Creativework" id="article-forum-{forums_list.cats.IDCAT}" class="forum-contents">
			<header>
				<h2>
					<span class="forum-cat-title">
						<a href="${relative_url(SyndicationUrlBuilder::rss('forum',forums_list.cats.IDCAT))}" class="fa fa-syndication" title="${LangLoader::get_message('syndication', 'common')}"></a>
						&nbsp;&nbsp;<a href="{forums_list.cats.U_FORUM_VARS}" class="forum-link-cat" title="{forums_list.cats.NAME}">{forums_list.cats.NAME}</a>
					</span>
					# IF C_DISPLAY_UNREAD_DETAILS #
					<span class="float-right">
						<a href="{PATH_TO_ROOT}/forum/unread.php?cat={forums_list.cats.IDCAT}" title="{L_DISPLAY_UNREAD_MSG}"><i class="fa fa-notread"></i></a>
					</span>
					# ENDIF #
				</h2>
			</header>
			<div class="content">
				<table id="table-{forums_list.cats.IDCAT}" class="forum-table">
					<thead>
						<tr>
							<th class="forum-announce-topic"><i class="fa fa-eye"></i></th>
							<th class="forum-topic" title="{L_FORUM}"><i class="fa fa-file-o hidden-small-screens"></i><span class="hidden-large-screens">{L_FORUM}</span></th>
							<th class="forum-topic" title="{L_TOPIC}"><i class="fa fa-files-o hidden-small-screens"></i><span class="hidden-large-screens">{L_TOPIC}</span></th>
							<th class="forum-message-nb"><i class="fa fa-comments-o fa-fw hidden-small-screens" title="{L_MESSAGE}"></i><span class="hidden-large-screens">{L_MESSAGE}</span></th>
							<th class="forum-last-topic"><i class="fa fa-clock-o fa-fw hidden-small-screens" title="{L_LAST_MESSAGE}"></i><span class="hidden-large-screens">{L_LAST_MESSAGE}</span></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<td colspan="5">
							</td>
						</tr>
					</tfoot>
					<tbody>
		# END forums_list.cats #
		# START forums_list.subcats #
						<tr>
							# IF forums_list.subcats.U_FORUM_URL #
							<td class="forum-announce-topic">
								<i class="fa fa-globe"></i>
							</td>
							<td class="forum-topic" colspan="4">
								<a href="{forums_list.subcats.U_FORUM_URL}" title="{forums_list.subcats.NAME}">{forums_list.subcats.NAME}</a>
								<span class="smaller hidden-small-screens">{forums_list.subcats.DESC}</span>
							</td>
							# ELSE #
							<td class="forum-announce-topic">
								<i class="fa # IF forums_list.subcats.C_BLINK #blink # ENDIF #{forums_list.subcats.IMG_ANNOUNCE}"></i>
							</td>
							<td class="forum-topic">
								<a href="{forums_list.subcats.U_FORUM_VARS}" title="{forums_list.subcats.NAME}">{forums_list.subcats.NAME}</a>
								<span class="smaller hidden-small-screens">{forums_list.subcats.DESC}</span>
								# IF forums_list.subcats.C_SUBFORUMS #<span class="small"><span class="strong">{forums_list.subcats.L_SUBFORUMS} : </span>{forums_list.subcats.SUBFORUMS}</span># ENDIF #
							</td>
							<td class="forum-subject-nb">
								{forums_list.subcats.NBR_TOPIC}
							</td>
							<td class="forum-message-nb">
								{forums_list.subcats.NBR_MSG}
							</td>
							<td class="forum-last-topic">
							# IF forums_list.subcats.C_LAST_TOPIC_MSG #
								<span class="last-topic-title small">
									<i class="fa fa-file-o fa-fw"></i> <a href="{forums_list.subcats.U_LAST_TOPIC}">
										{forums_list.subcats.LAST_TOPIC_TITLE}
									</a>
								</span>
								<span class="last-topic-date small">
									<i class="fa fa-hand-o-right fa-fw"></i> <a href="{forums_list.subcats.U_LAST_MSG}" title="">
										{forums_list.subcats.LAST_MSG_DATE_FULL}
									</a>
								</span>
								<span class="last-topic-user small">
									<i class="fa fa-user-o fa-fw"></i>
									# IF forums_list.subcats.C_LAST_MSG_GUEST #
										<a href="{forums_list.subcats.U_LAST_MSG_USER_PROFIL}" class="{forums_list.subcats.LAST_MSG_USER_LEVEL}" {forums_list.subcats.LAST_MSG_USER_GROUP_COLOR}>{forums_list.subcats.LAST_MSG_USER_LOGIN}</a>
									# ELSE #
										${LangLoader::get_message('guest', 'main')}
									# ENDIF #
								</span>
							# ELSE #
								<em>{forums_list.subcats.L_NO_MSG}</em>
							# ENDIF #
							</td>
							# ENDIF #
						</tr>
		# END forums_list.subcats #

	# END forums_list #

	# INCLUDE forum_bottom #

# IF C_QUESTIONS #
<script>
<!--
	var questions_number = {QUESTIONS_NUMBER};

	function delete_question(id_question)
	{
		if (confirm(${escapejs(LangLoader::get_message('confirm.delete', 'status-messages-common'))}))
		{
			jQuery.ajax({
				url: '${relative_url(FaqUrlBuilder::ajax_delete())}',
				type: "post",
				dataType: "json",
				data: {'id' : id_question, 'token' : '{TOKEN}'},
				success: function(returnData) {
					if(returnData.code > 0) {
						jQuery("#question-" + returnData.code).remove();
						# IF NOT C_DISPLAY_TYPE_ANSWERS_HIDDEN #
						jQuery("#title-question-" + returnData.code).remove();
						# ENDIF #

						if (returnData.questions_number == 0) {
							# IF NOT C_DISPLAY_TYPE_ANSWERS_HIDDEN #
							jQuery("#questions-titles-list").hide();
							# ENDIF #
							jQuery("#no-item-message").show();
						}
					}
				}
			});
		}
	}

	# IF C_DISPLAY_TYPE_ANSWERS_HIDDEN #
	function show_answer(id_question)
	{
		if (jQuery("#question" + id_question)) {
			if(jQuery("#answer" + id_question).css('display') == 'none')
			{
				jQuery("#answer" + id_question).fadeIn();
				jQuery("#question" + id_question).removeClass('fa-caret-right');
				jQuery("#question" + id_question).addClass('fa-caret-down');
			}
			else
			{
				jQuery("#answer" + id_question).fadeOut();
				jQuery("#question" + id_question).removeClass('fa-caret-down');
				jQuery("#question" + id_question).addClass('fa-caret-right');
			}
		}
	}

	jQuery(document).ready(function() {
		var anchor = window.location.hash;
		var id_question;

		id_question = anchor.substring(9,anchor.length);
		if (anchor.substring(0,9) == "#question" && id_question.match(/^[0-9]+$/))
			show_answer(id_question);
	});
	# ENDIF #
-->
</script>
# ENDIF #
# INCLUDE MSG #
<section id="module-faq">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('faq', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			# IF C_PENDING #{@faq.pending}# ELSE #{@module_title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF # # IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit fa-fw"></i></a># ENDIF ## IF C_DISPLAY_REORDER_LINK #<a href="{U_REORDER_QUESTIONS}" title="{@faq.reorder_questions}"><i class="fa fa-exchange-alt fa-fw"></i></a># ENDIF ## ENDIF #
		</h1>
		# IF C_CATEGORY_DESCRIPTION #
			<div class="cat-description">
				{CATEGORY_DESCRIPTION}
			</div>
		# ENDIF #
	</header>

	# IF C_SUB_CATEGORIES #
	<div class="subcat-container elements-container# IF C_SEVERAL_CATS_COLUMNS # columns-{NUMBER_CATS_COLUMNS}# ENDIF #">
		# START sub_categories_list #
		<div class="subcat-element block">
			<div class="subcat-content">
				# IF sub_categories_list.C_CATEGORY_IMAGE #
					<a class="subcat-thumbnail" itemprop="about" href="{sub_categories_list.U_CATEGORY}" title="{sub_categories_list.CATEGORY_NAME}">
						<img itemprop="thumbnailUrl" src="{sub_categories_list.CATEGORY_IMAGE}" alt="{sub_categories_list.CATEGORY_NAME}" />
					</a>
				# ENDIF #
				<a class="subcat-title" itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a>
				<span class="subcat-options">{sub_categories_list.QUESTIONS_NUMBER} # IF sub_categories_list.C_MORE_THAN_ONE_QUESTION #${TextHelper::lcfirst(LangLoader::get_message('faq.questions', 'common', 'faq'))}# ELSE #${TextHelper::lcfirst(LangLoader::get_message('faq.form.question', 'common', 'faq'))}# ENDIF #</span>
			</div>
		</div>
		# END sub_categories_list #
		<div class="spacer"></div>
	</div>
	# IF C_SUBCATEGORIES_PAGINATION #<span class="center"># INCLUDE SUBCATEGORIES_PAGINATION #</span># ENDIF #
	# ELSE #
	<div class="spacer"></div>
	# ENDIF #

	# IF C_QUESTIONS #
		# IF C_PENDING #
			# IF C_MORE_THAN_ONE_QUESTION #
			# INCLUDE SORT_FORM #
			<div class="spacer"></div>
			# ENDIF #
		# ENDIF #
		# IF NOT C_DISPLAY_TYPE_ANSWERS_HIDDEN #
		<div id="questions-titles-list">
				<ol>
				# START questions #
					<li id="title-question-{questions.ID}"# IF questions.C_NEW_CONTENT # class="new-content"# ENDIF #>
						<a href="#question{questions.ID}">{questions.QUESTION}</a>
					</li>
				# END questions #
				</ol>
				<hr />
		</div>
		# ENDIF #

		<div class="content elements-container">
			# START questions #
			<article id="article-faq-{questions.ID}" itemscope="itemscope" itemtype="http://schema.org/CreativeWork" class="article-faq article-several# IF questions.C_NEW_CONTENT # new-content# ENDIF #">
				<header class="faq-question-element">
					<h3 class="question-title">
						# IF questions.C_ACTION_USER #
						<span class="actions"><a href="{questions.U_LINK}" title="{questions.L_LINK_QUESTION}"><i class="fa fa-hand-point-right fa-fw"></i></a></span>
						# ELSE #
						<span class="actions actions-menu question-actions" id="question-{questions.ID}-actions">
							<a href="" title="Menu d'action" class="actions-title" onclick="open_submenu('question-{questions.ID}-actions', 'opened', 'question-actions');return false;"><i class="fa fa-actions-menu"></i></a>
							<ul class="actions-submenu">
								<li class="action"><a href="{questions.U_LINK}" title="{questions.L_LINK_QUESTION}" onclick="copy_to_clipboard('{questions.U_ABSOLUTE_LINK}');"><i class="fa fa-hand-point-right fa-fw"></i> {questions.L_LINK_QUESTION}</a></li>
								# IF questions.C_EDIT #
								<li class="action"><a href="{questions.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit fa-fw"></i> ${LangLoader::get_message('edit', 'common')}</a></li>
								# ENDIF #
								# IF questions.C_DELETE #
								<li class="action"><a href="" onclick="delete_question({questions.ID});return false;" title="${LangLoader::get_message('delete', 'common')}"><i class="fa fa-delete"></i> ${LangLoader::get_message('delete', 'common')}</a></li>
								# ENDIF #
							</ul>
						</span>
						# ENDIF #

						# IF C_DISPLAY_TYPE_ANSWERS_HIDDEN #
						<a href="" onclick="show_answer({questions.ID});return false;" title="{questions.L_SHOW_ANSWER}"><i id="question{questions.ID}" class="fa fa-caret-right fa-fw question-anchor"></i></a>
						<a href="" onclick="show_answer({questions.ID});return false;" title="{questions.QUESTION}"><span itemprop="name">{questions.QUESTION}</span></a>
						# ELSE #
						<i id="question{questions.ID}" class="fa fa-caret-right fa-fw question-anchor"></i>
						<span itemprop="name">{questions.QUESTION}</span>
						# ENDIF #
					</h3>
					<meta itemprop="url" content="{questions.U_LINK}">
				</header>

				<div id="answer{questions.ID}" class="content faq-answer-container"# IF C_DISPLAY_TYPE_ANSWERS_HIDDEN # style="display: none;"# ENDIF #>
					<div itemprop="text">{questions.ANSWER}</div>
				</div>

				<footer></footer>
			</article>
			# END questions #
		</div>
	# ENDIF #
	# IF NOT C_HIDE_NO_ITEM_MESSAGE #
		<div id="no-item-message"# IF C_QUESTIONS # style="display: none;"# ENDIF #>
			<div class="center">
				${LangLoader::get_message('no_item_now', 'common')}
			</div>
		</div>
	# ENDIF #

	<footer></footer>
</section>

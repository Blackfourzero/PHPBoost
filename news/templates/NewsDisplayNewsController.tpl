<script type="text/javascript">
<!--
function Confirm()
{
	return confirm("{L_ALERT_DELETE_NEWS}");
}
-->
</script>

<div class="module_position">
	<div class="module_top_l"></div>
	<div class="module_top_r"></div>
	<div class="module_top">
		<div class="module_top_title">
			<a href="{U_SYNDICATION}" title="{L_SYNDICATION}" class="img_link">
				<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="{L_SYNDICATION}"/>
			</a>
			{NAME}
		</div>
		<div class="module_top_com">
			<a href="{U_COMMENTS}" title="{L_COMMENTS}" class="img_link">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/com_mini.png" alt="" class="valign_middle" />
				{L_COMMENTS}
			</a>
			# IF C_EDIT #
			<a href="{U_EDIT}" title="{L_EDIT}" class="img_link">
				<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_EDIT}" />
			</a>
			# ENDIF #
			# IF C_DELETE #
			<a href="{U_DELETE}" title="{L_DELETE}" onclick="javascript:return Confirm();">
				<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" />
			</a>
			# ENDIF #
		</div>
		<div class="spacer"></div>
	</div>
	<div class="module_contents">
		# IF C_PICTURE #<img src="{U_PICTURE}" alt="{NAME}" title="{NAME}" class="img_right" /># ENDIF #
		
		{CONTENTS}
		
		<div class="spacer"></div>
		
		# IF C_SOURCES #
		<div style="float:right">
			<div class="text_small"><b> {L_SOURCES} : </b># START sources #{sources.COMMA}<a href="{sources.URL}" class="small_link">{sources.NAME}</a># END sources #</div>
		</div>
		# ENDIF #
		<div class="spacer"></div>
	</div>
	<div class="module_bottom_l"></div>
	<div class="module_bottom_r"></div>
	<div class="module_bottom">
		<div style="float:left"># IF PSEUDO #<a class="small_link {LEVEL}" href="{U_USER_ID}">{PSEUDO}</a>, # ENDIF # {DATE}</div>
		<div class="spacer"></div>
	</div>
		
	# IF C_NEWS_SUGGESTED #
		<div id="news_suggested">
			<b>{L_NEWS_SUGGESTED}</b><br />
			<ul class="bb_ul">
				# START suggested #
					<li class="bb_li"><a href="{suggested.URL}">{suggested.TITLE}</a></li>
				# END suggested #
			</ul>
		</div>
	# ENDIF #

	# IF C_NEWS_NAVIGATION_LINKS #
	<div class="navigation_link">
		# IF C_PREVIOUS_NEWS #
		<span style="float:left">
			<a href="{U_PREVIOUS_NEWS}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/left.png" alt="" class="valign_middle" /></a>
			<a href="{U_PREVIOUS_NEWS}">{PREVIOUS_NEWS}</a>
		</span>
		# ENDIF #
		# IF C_NEXT_NEWS #
		<span style="float:right">
			<a href="{U_NEXT_NEWS}">{NEXT_NEWS}</a>
			<a href="{U_NEXT_NEWS}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/right.png" alt="" class="valign_middle" /></a>
		</span>
		# ENDIF #
		<div class="spacer"></div>
	</div>
	# ENDIF #

	# INCLUDE COMMENTS #	
</div>
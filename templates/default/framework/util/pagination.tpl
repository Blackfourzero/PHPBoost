<ul class="pagination # IF C_FULL #pagination-block# ENDIF #">
	# START page #
	<li>
		# IF page.C_PREVIOUS #
		<a href="{page.URL}" rel="prev" aria-label="{L_PREVIOUS_PAGE}" class="prev-page">&laquo;</a>
		# ENDIF #

		# IF page.NAME #
		<a href="{page.URL}"# IF page.C_CURRENT_PAGE # class="current-page"# ENDIF #>{page.NAME}</a>
		# ENDIF #

		# IF page.C_NEXT #
		<a href="{page.URL}" rel="next" aria-label="{L_NEXT_PAGE}" class="next-page">&raquo;</a>
		# ENDIF #
	</li>
	# END page #
</ul>

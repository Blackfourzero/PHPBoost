<table id="table">
	# IF C_SUBSCRIBERS #
	<thead>
		<tr>
			<th>
				<a href="{SORT_PSEUDO_TOP}" aria-label="${LangLoader::get_message('sort.asc', 'common')}"><i class="fa fa-table-sort-up" aria-hidden="true" title="${LangLoader::get_message('sort.asc', 'common')}"></i></a>
				{@subscribers.pseudo}
				<a href="{SORT_PSEUDO_BOTTOM}" aria-label="${LangLoader::get_message('sort.desc', 'common')}"><i class="fa fa-table-sort-down" aria-hidden="true" title="${LangLoader::get_message('sort.desc', 'common')}"></i></a>
			</th>
			<th>
				{@subscribers.mail}
			</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		# START subscribers_list #
		<tr>
			<td>
				{subscribers_list.PSEUDO}
			</td>
			<td>
				{subscribers_list.MAIL}
			</td>
			<td>
				# IF subscribers_list.C_AUTH_MODO #
					# IF subscribers_list.C_EDIT #
					<a href="{subscribers_list.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true" title="${LangLoader::get_message('edit', 'common')}"></i></a>
					# ENDIF #
					<a href="{subscribers_list.U_DELETE}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete" aria-hidden="true" title="${LangLoader::get_message('delete', 'common')}"></i></a>
				# ENDIF #
			</td>
		</tr>
		# END subscribers_list #
	</tbody>
	# ELSE #
	<tbody>
		<tr>
			<td colspan="3">
				<span class="text-strong">{@subscribers.no_users}</span>
			</td>
		</tr>
	</tbody>
	# ENDIF #
	# IF C_PAGINATION #
	<tfoot>
		<tr>
			<td colspan="3">
				# INCLUDE PAGINATION #
			</td>
		</tr>
	</tfoot>
	# ENDIF #
</table>

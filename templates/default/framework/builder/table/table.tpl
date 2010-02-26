# IF C_FILTERS #
<!--<div-->
<!--	id="show_filters_{TABLE_ID}"-->
<!--	onclick="toggleTableFilters();">-->
<!--	SHOW_FILTERS-->
<!--</div>-->
<!--<div-->
<!--	id="hide_filters_{TABLE_ID}"-->
<!--	onclick="toggleTableFilters();"-->
<!--	style="display:none;">-->
<!--	HIDE_FILTERS-->
<!--</div>-->
<div id="filters_{TABLE_ID}" style="border:1px #aaa solid;">
	# INCLUDE filters #
</div>
# ENDIF #
<table
	class="module_table # IF C_CSS_CLASSES #{CSS_CLASSES}# ENDIF #"
	# IF C_CSS_STYLE # style="{CSS_STYLE}"# ENDIF #>
	# IF C_CAPTION #
	<caption>
		<a href="{U_TABLE_DEFAULT_OPIONS}" title="{E_CAPTION}">{E_CAPTION}</a>
	</caption>
	# ENDIF #
	<thead>
		<tr>
			# START header_column #
			<th
			# IF header_column.C_CSS_CLASSES # class="{header_column.CSS_CLASSES}"# ENDIF #
			# IF header_column.C_CSS_STYLE # style="{header_column.CSS_STYLE}"# ENDIF #>
				
				# IF header_column.C_SORTABLE #
				<a href="{header_column.U_SORT_DESC}" title="{EL_DESCENDING}">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="{EL_DESCENDING}" />
				</a>
				# ENDIF #
				{header_column.NAME}
				# IF header_column.C_SORTABLE #
				<a href="{header_column.U_SORT_ASC}" title="{EL_ASCENDING}">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="{EL_ASCENDING}" />
				</a>
				# ENDIF #
			</th>
			# END header_column #
		</tr>
	</thead>
	# IF C_PAGINATION_ACTIVATED #
	<tfoot>
	    <tr>
	      	<td colspan="{NUMBER_OF_COLUMNS}" class="row2">
	      		<div style="float:left;">
	      			{NUMBER_OF_ELEMENTS}
      			</div>
      			# IF C_NB_ROWS_OPTIONS #
	      		<div style="float:right;padding:0 10px;">
	      			<select name="nbItemsPerPage" onchange="window.location=this.value">
	      				# START nbItemsOption #
	      				<option value="{nbItemsOption.URL}"
	      					# IF nbItemsOption.C_SELECTED # selected="selected"# END IF #>
	      					{nbItemsOption.VALUE}
      					</option>
	      				# END nbItemsOption #
	      			</select>
      			</div>
      			# END IF #
	      		<div style="float:right;">
	      			# INCLUDE pagination #
      			</div>
			</td>
	    </tr>
    </tfoot>
    # ENDIF #
	<tbody>
		# IF C_PAGINATION_ACTIVATED #
	    <tr>
	      	<td colspan="{NUMBER_OF_COLUMNS}" class="row2">
	      		<div style="float:left;">
	      			{NUMBER_OF_ELEMENTS}
      			</div>
      			# IF C_NB_ROWS_OPTIONS #
	      		<div style="float:right;padding:0 10px;">
	      			<select name="nbItemsPerPage" onchange="window.location=this.value">
	      				# START nbItemsOption #
	      				<option value="{nbItemsOption.URL}"
	      					# IF nbItemsOption.C_SELECTED # selected="selected"# END IF #>
	      					{nbItemsOption.VALUE}
      					</option>
	      				# END nbItemsOption #
	      			</select>
      			</div>
      			# END IF #
	      		<div style="float:right;">
	      			# INCLUDE pagination #
      			</div>
			</td>
	    </tr>
	    # ENDIF #
		# START row #
		<tr
		# IF row.C_CSS_CLASSES # class="{row.CSS_CLASSES}"# ENDIF #
		# IF row.C_CSS_STYLE # style="{row.CSS_STYLE}"# ENDIF #>
			# START row.cell #
			<td
			# IF row.cell.C_COLSPAN #colspan="{row.cell.COLSPAN}"# ENDIF #
			# IF row.cell.C_CSS_CLASSES # class="{row.cell.CSS_CLASSES}"# ENDIF #
			# IF row.cell.C_CSS_STYLE # style="{row.cell.CSS_STYLE}"# ENDIF #>
				{row.cell.VALUE}
			</td>
			# END row.cell #
		</tr>
		# END row #
	</tbody>
</table>
# IF C_FILTERS #
<script type="text/javascript">
<!--
function toggleTableFilters() {
	Effect.toggle('show_filters_{TABLE_ID}', 'appear', { duration: 0 });
	Effect.toggle('hide_filters_{TABLE_ID}', 'appear', { duration: 0 });
	Effect.toggle('filters_{TABLE_ID}', 'appear', { duration: 0.5 });
}

function {SUBMIT_FUNCTION}() {
	var filtersList = '';
	var filters = new Array();
	# START filterElt #
	var filterObject = {
		formId: {filterElt.J_FORM_ID},
		tableId: {filterElt.J_TABLE_ID},
		mode: 'equals'
	};
	filters.push(filterObject);
	# END filterElt #
	for (var i = 0; i < filters.length; i++) {
		var filter = filters[i];
		var domFilter = $(filter.formId);
		if (domFilter) {
			var filterValue = $F(domFilter);
			filtersList += filter.mode + '-' + filter.tableId + '-' + filterValue + ',';
			//window.alert(filter.tableId + ' = ' + filterValue);
		} else {
			window.alert('element ' + filter.formId + ' not found');
		}
	}
	if (filtersList.charAt(filtersList.length - 1) == ',') {
		filtersList = filtersList.substr(0, filtersList.length - 1);
	}
	var submitUrl = {J_SUBMIT_URL};
	window.alert('URL: ' + submitUrl + ',filters:{' + filtersList + '}');
	//submitUrl = '?url=/table&t42=page:1,filters:{equals-login-Horn}';
	window.location = submitUrl;
	return false;
}
-->
</script>
# ENDIF #
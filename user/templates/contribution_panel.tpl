	# IF C_CONTRIBUTION_LIST #
		<section id="module-user-contribution-list">
			<header>
				<h1>{L_CONTRIBUTION_PANEL}</h1>
			</header>
			<article class="content">
				<header>
					<h2>{L_CONTRIBUTION_LIST}</h2>
				</header>

				# IF C_NO_CONTRIBUTION #
					<div class="message-helper bgc success">{L_NO_CONTRIBUTION_TO_DISPLAY}</div>
				# ELSE #
					<div class="responsive-table">
						<table class="table">
							<thead>
								<tr>
									<th>
										<span class="html-table-header-sortable# IF C_ORDER_ENTITLED_ASC # sort-active# ENDIF #">
											<a href="{U_ORDER_ENTITLED_ASC}" aria-label="${LangLoader::get_message('sort.asc', 'common')}">
												<i class="fa fa-caret-up" aria-hidden="true"></i>
											</a>
										</span>
										{L_ENTITLED}
										<span class="html-table-header-sortable# IF C_ORDER_ENTITLED_DESC # sort-active# ENDIF #">
											<a href="{U_ORDER_ENTITLED_DESC}" aria-label="${LangLoader::get_message('sort.desc', 'common')}">
												<i class="fa fa-caret-down" aria-hidden="true"></i>
											</a>
										</span>
									</th>
									<th>
										<span class="html-table-header-sortable# IF C_ORDER_MODULE_ASC # sort-active# ENDIF #">
											<a href="{U_ORDER_MODULE_ASC}" aria-label="${LangLoader::get_message('sort.asc', 'common')}">
												<i class="fa fa-caret-up" aria-hidden="true"></i>
											</a>
										</span>
										{L_MODULE}
										<span class="html-table-header-sortable# IF C_ORDER_MODULE_DESC # sort-active# ENDIF #">
											<a href="{U_ORDER_MODULE_DESC}" aria-label="${LangLoader::get_message('sort.desc', 'common')}">
												<i class="fa fa-caret-down" aria-hidden="true"></i>
											</a>
										</span>
									</th>
									<th>
										<span class="html-table-header-sortable# IF C_ORDER_STATUS_ASC # sort-active# ENDIF #">
											<a href="{U_ORDER_STATUS_ASC}" aria-label="${LangLoader::get_message('sort.asc', 'common')}">
												<i class="fa fa-caret-up" aria-hidden="true"></i>
											</a>
										</span>
										{L_STATUS}
										<span class="html-table-header-sortable# IF C_ORDER_STATUS_DESC # sort-active# ENDIF #">
											<a href="{U_ORDER_STATUS_DESC}" aria-label="${LangLoader::get_message('sort.desc', 'common')}">
												<i class="fa fa-caret-down" aria-hidden="true"></i>
											</a>
										</span>
									</th>
									<th>
										<span class="html-table-header-sortable# IF C_ORDER_CREATION_DATE_ASC # sort-active# ENDIF #">
											<a href="{U_ORDER_CREATION_DATE_ASC}" aria-label="${LangLoader::get_message('sort.asc', 'common')}">
												<i class="fa fa-caret-up" aria-hidden="true"></i>
											</a>
										</span>
										{L_CREATION_DATE}
										<span class="html-table-header-sortable# IF C_ORDER_CREATION_DATE_DESC # sort-active# ENDIF #">
											<a href="{U_ORDER_CREATION_DATE_DESC}" aria-label="${LangLoader::get_message('sort.desc', 'common')}">
												<i class="fa fa-caret-down" aria-hidden="true"></i>
											</a>
										</span>
									</th>
									<th>
										<span class="html-table-header-sortable# IF C_ORDER_FIXING_DATE_ASC # sort-active# ENDIF #">
											<a href="{U_ORDER_FIXING_DATE_ASC}" aria-label="${LangLoader::get_message('sort.asc', 'common')}">
												<i class="fa fa-caret-up" aria-hidden="true"></i>
											</a>
										</span>
										{L_FIXING_DATE}
										<span class="html-table-header-sortable# IF C_ORDER_FIXING_DATE_DESC # sort-active# ENDIF #">
											<a href="{U_ORDER_FIXING_DATE_DESC}" aria-label="${LangLoader::get_message('sort.desc', 'common')}">
												<i class="fa fa-caret-down" aria-hidden="true"></i>
											</a>
										</span>
									</th>
									<th>
										<span class="html-table-header-sortable# IF C_ORDER_POSTER_ASC # sort-active# ENDIF #">
											<a href="{U_ORDER_POSTER_ASC}" aria-label="${LangLoader::get_message('sort.asc', 'common')}">
												<i class="fa fa-caret-up" aria-hidden="true"></i>
											</a>
										</span>
										{L_POSTER}
										<span class="html-table-header-sortable# IF C_ORDER_POSTER_DESC # sort-active# ENDIF #">
											<a href="{U_ORDER_POSTER_DESC}" aria-label="${LangLoader::get_message('sort.desc', 'common')}">
												<i class="fa fa-caret-down" aria-hidden="true"></i>
											</a>
										</span>
									</th>
									<th>
										<span class="html-table-header-sortable# IF C_ORDER_FIXER_ASC # sort-active# ENDIF #">
											<a href="{U_ORDER_FIXER_ASC}" aria-label="${LangLoader::get_message('sort.asc', 'common')}">
												<i class="fa fa-caret-up" aria-hidden="true"></i>
											</a>
										</span>
										{L_FIXER}
										<span class="html-table-header-sortable# IF C_ORDER_FIXER_DESC # sort-active# ENDIF #">
											<a href="{U_ORDER_FIXER_DESC}" aria-label="${LangLoader::get_message('sort.desc', 'common')}">
												<i class="fa fa-caret-down" aria-hidden="true"></i>
											</a>
										</span>
									</th>
								</tr>
							</thead>
							<tbody>
								# START contributions #
									<tr>
										<td>
											<a href="{contributions.U_CONSULT}">{contributions.ENTITLED}</a>
										</td>
										<td >
											{contributions.MODULE}
										</td>
										# IF contributions.C_FIXED #
											<td class="bgc success">
												{contributions.STATUS}
											</td>
										# ELSE #
											# IF contributions.C_PROCESSING #
												<td class="bgc question">
													{contributions.STATUS}
												</td>
											# ELSE #
												<td class="bgc error">
													{contributions.STATUS}
												</td>
											# ENDIF #
										# ENDIF #
										<td >
											{contributions.CREATION_DATE}
										</td>
										<td >
											# IF contributions.C_FIXED #
												{contributions.FIXING_DATE}
											# ELSE #
												-
											# ENDIF #
										</td>
										<td >
											<a href="{contributions.U_POSTER_PROFILE}" class="{contributions.POSTER_LEVEL_CLASS}" # IF contributions.C_POSTER_GROUP_COLOR # style="color:{contributions.POSTER_GROUP_COLOR}" # ENDIF #>{contributions.POSTER}</a>
										</td>
										<td >
											# IF contributions.C_FIXED #
												<a href="{contributions.U_FIXER_PROFILE}" class="{contributions.FIXER_LEVEL_CLASS}" # IF contributions.C_FIXER_GROUP_COLOR # style="color:{contributions.FIXER_GROUP_COLOR}" # ENDIF #>{contributions.FIXER}</a>
											# ELSE #
												-
											# ENDIF #
										</td>
									</tr>
								# END contributions #
							</tbody>
							# IF C_PAGINATION #
								<tfoot>
									<tr>
										<td colspan="7">
											# INCLUDE PAGINATION #
										</td>
									</tr>
								</tfoot>
							# ENDIF #
						</table>
					</div>
				# ENDIF #
			</article>
			<hr>
			<article class="content">
				<header>
					<h2>{L_CONTRIBUTE}</h2>
				</header>

				# IF NOT C_NO_MODULE_IN_WHICH_CONTRIBUTE #
					<p>{L_CONTRIBUTE_EXPLAIN}</p>

					# START row #
						<div class="cell-flex cell-tile cell-columns-{row.MODULES_PER_ROW}">
							# START row.module #
								<div class="cell">
									<div class="cell-header">
										<div class="cell-name"><a href="{row.module.U_MODULE_LINK}">{row.module.MODULE_NAME}</a></div>
										<img src="{PATH_TO_ROOT}/{row.module.MODULE_ID}/{row.module.MODULE_ID}_mini.png" alt="{row.module.LINK_TITLE}" />
									</div>
								</div>
							# END row.module #
						</div>
					# END row #
				# ELSE #
					<div class="message-helper bgc warning">{L_NO_MODULE_IN_WHICH_CONTRIBUTE}</div>
				# ENDIF #
			</article>
			<footer></footer>
		</section>
	# ENDIF #

	# IF C_CONSULT_CONTRIBUTION #
		<section id="module-user-consult-contribution">
			<header>
				# IF C_WRITE_AUTH #
					<div class="align-right controls">
						<a href="{U_UPDATE}" aria-label="{L_UPDATE}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
						<a href="{U_DELETE}" aria-label="{L_DELETE}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
					</div>
				# ENDIF #
				<h1>{ENTITLED}</h1>
			</header>
			<article class="content">
				# IF C_WRITE_AUTH #
					# IF C_UNPROCESSED_CONTRIBUTION #
						<div class="cell-flex cell-columns-3">
							<div class="cell">
								<div class="cell-body">
									<div class="cell-content align-center">
										<a href="{FIXING_URL}">
											<i class="fa fa-wrench fa-2x"aria-hidden="true"></i>
											<span class="d-block">{L_PROCESS_CONTRIBUTION}</span>
										</a>
									</div>
								</div>
							</div>
							<div class="cell">
								<div class="cell-body">
									<div class="cell-content align-center">
										<a href="{U_UPDATE}">
											<i class="fa fa-check fa-2x success"aria-hidden="true"></i>
											<span class="d-block">{L_CHANGE_CONTRIBUTION}</span>
										</a>
									</div>
								</div>
							</div>
							<div class="cell">
								<div class="cell-body">
									<div class="cell-content align-center">
										<a href="{U_DELETE}" data-confirmation="delete-element">
											<i class="far fa-trash-alt fa-2x error"aria-hidden="true"></i>
											<span class="d-block">{L_DELETE_CONTRIBUTION}</span>
										</a>
									</div>
								</div>
							</div>
						</div>
					# ENDIF #
				# ENDIF #
			</article>
			<article class="content">
				<header>
					<h2>{L_CONTRIBUTION_DETAILS}</h2>
				</header>
				<div class="cell cell-tile">
					<div class="cell-list">
						<ul>
							<li class="li-stretch">
								<span class="text-strong">{L_ENTITLED}</span>
								<span>{ENTITLED}</span>
							</li>
							<li>
								<span class="text-strong">{L_DESCRIPTION}</span>
								<span>{DESCRIPTION}</span>
							</li>
							<li class="li-stretch">
								<span class="text-strong">{L_STATUS}</span>
								<span>{STATUS}</span>
							</li>
							<li class="li-stretch">
								<span class="text-strong">{L_CONTRIBUTOR}</span>
								<span><a href="{U_CONTRIBUTOR_PROFILE}" class="{CONTRIBUTOR_LEVEL_CLASS}" # IF C_CONTRIBUTOR_GROUP_COLOR # style="color:{CONTRIBUTOR_GROUP_COLOR}" # ENDIF #>{CONTRIBUTOR}</a></span>
							</li>
							<li class="li-stretch">
								<span class="text-strong">{L_CREATION_DATE}</span>
								<span>{CREATION_DATE}</span>
							</li>
							# IF C_CONTRIBUTION_FIXED #
							<li class="li-stretch">
								<span class="text-strong">{L_FIXER}</span>
								<span><a href="{U_FIXER_PROFILE}" class="{FIXER_LEVEL_CLASS}" # IF C_FIXER_GROUP_COLOR # style="color:{FIXER_GROUP_COLOR}" # ENDIF #>{FIXER}</a></span>
							</li>
							<li class="li-stretch">
								<span class="text-strong">{L_FIXING_DATE}</span>
								<span>{FIXING_DATE}</span>
							</li>
							# ENDIF #
							<li class="li-stretch">
								<span class="text-strong">{L_MODULE}</span>
								<span>{MODULE}</span>
							</li>
						</ul>
					</div>
				</div>

				{COMMENTS}

			</article>
			<footer></footer>
		</section>
	# ENDIF #

	# IF C_EDIT_CONTRIBUTION #
		<section id="module-user-edit-contribution">
			<header>
				<h1>{ENTITLED}</h1>
			</header>
			<div class="content">
				<form action="contribution_panel.php" method="post">
					<fieldset>
						<legend>{L_CONTRIBUTION}</legend>
						<div class="form-element">
							<label for="entitled">{L_ENTITLED}</label>
							<div class="form-field">
								<input type="text" name="entitled" id="entitled" value="{ENTITLED}">
							</div>
						</div>
						<div class="form-element form-element-textarea">
							<label for="contents">{L_DESCRIPTION}</label>
							{EDITOR}
							<div class="form-field-textarea">
								<textarea rows="15" id="contents" name="contents">{DESCRIPTION}</textarea>
							</div>
						</div>
						<div class="form-element">
							<label for="status">{L_STATUS}</label>
							<div class="form-field"><select name="status" id="status">
									<option value="0"{EVENT_STATUS_UNREAD_SELECTED}>{L_CONTRIBUTION_STATUS_UNREAD}</option>
									<option value="1"{EVENT_STATUS_BEING_PROCESSED_SELECTED}>{L_CONTRIBUTION_STATUS_BEING_PROCESSED}</option>
									<option value="2"{EVENT_STATUS_PROCESSED_SELECTED}>{L_CONTRIBUTION_STATUS_PROCESSED}</option>
								</select>
							</div>
						</div>
					</fieldset>
					<fieldset class="fieldset-submit">
						<input type="hidden" name="idedit" value="{CONTRIBUTION_ID}">
						<input type="hidden" name="token" value="{TOKEN}">
						<button type="submit" class="button submit" value="true">{L_SUBMIT}</button>
						<button type="button" class="button preview-button" name="preview" onclick="XMLHttpRequest_preview();">{L_PREVIEW}</button>
						<button type="reset" class="button reset-button">{L_RESET}</button>
					</fieldset>
				</form>
			</div>
			<footer></footer>
		</section>
	# ENDIF #

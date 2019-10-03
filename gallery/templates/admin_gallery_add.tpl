		# IF C_IMG #
		<script>
			function unselect_all_pictures() {
				# START list #
				jQuery('#' + '{list.ID}activ').prop('checked', false);
				# END list #
				jQuery('#change_all_pictures_selection_top').attr('onclick', "select_all_pictures();return false;");
				jQuery('#change_all_pictures_selection_top').text("{L_SELECT_ALL_PICTURES}");
				jQuery('#change_all_pictures_selection_bottom').attr('onclick', "select_all_pictures();return false;");
				jQuery('#change_all_pictures_selection_bottom').text("{L_SELECT_ALL_PICTURES}");
			};

			function select_all_pictures() {
				# START list #
				jQuery('#' + '{list.ID}activ').prop('checked', 'checked');
				# END list #
				jQuery('#change_all_pictures_selection_top').attr('onclick', "unselect_all_pictures();return false;");
				jQuery('#change_all_pictures_selection_top').text("{L_UNSELECT_ALL_PICTURES}");
				jQuery('#change_all_pictures_selection_bottom').attr('onclick', "unselect_all_pictures();return false;");
				jQuery('#change_all_pictures_selection_bottom').text("{L_UNSELECT_ALL_PICTURES}");
			};
		</script>
		# ENDIF #

		<nav id="admin-quick-menu">
			<a href="" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
				<i class="fa fa-bars" aria-hidden="true"></i> {L_GALLERY_MANAGEMENT}
			</a>
			<ul>
				<li>
					<a href="${Url::to_rel('/gallery')}" class="quick-link">${LangLoader::get_message('home', 'main')}</a>
				</li>
				<li>
					<a href="admin_gallery.php" class="quick-link">{L_GALLERY_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_gallery_add.php" class="quick-link">{L_GALLERY_PICS_ADD}</a>
				</li>
				<li>
					<a href="admin_gallery_config.php" class="quick-link">{L_GALLERY_CONFIG}</a>
				</li>
				<li>
					<a href="${relative_url(GalleryUrlBuilder::documentation())}" class="quick-link">${LangLoader::get_message('module.documentation', 'admin-modules-common')}</a>
				</li>
			</ul>
		</nav>

		<div id="admin-contents">

			# INCLUDE message_helper #

			<form action="admin_gallery_add.php" method="post" enctype="multipart/form-data" class="fieldset-content">
				<fieldset>
					<legend>{L_ADD_IMG}</legend>
					<div class="fieldset-inset">
						<div class="form-element full-field">
							# START image_up #
								<div class="center">
									<strong>{image_up.L_SUCCESS_UPLOAD}</strong> ${LangLoader::get_message('in', 'common')} <a href="{image_up.U_CAT}">{image_up.CATNAME}</a>
									<div class="spacer"></div>
									<strong>{image_up.NAME}</strong>
									<div class="spacer"></div>
									<a href="{image_up.U_IMG}"><img src="pics/{image_up.PATH}" alt="{image_up.NAME}" /></a>
									<div class="spacer"></div>
								</div>
							# END image_up #
						</div>

						<div class="form-element half-field">
							<label for="category">${LangLoader::get_message('form.category', 'common')}</label>
							<div class="form-field">
								<select name="idcat_post" id="category">
									{CATEGORIES}
								</select>
							</div>
						</div>
						<div class="form-element full-field">
							<label for="gallery">{L_UPLOAD_IMG}</label>
							<div class="form-field">
								<div class="dnd-area">
									<div class="dnd-dropzone">
										<label for="gallery" class="dnd-label">${LangLoader::get_message('drag.and.drop.files', 'main')} <p></p></label>
										<input type="file" name="gallery[]" id="gallery" class="ufiles" />
									</div>
									<div class="ready-to-load">
										<button type="button" class="clear-list">${LangLoader::get_message('clear.list', 'main')}</button>
										<span class="fa-stack fa-lg">
											<i class="far fa-file fa-stack-2x "></i>
											<strong class="fa-stack-1x files-nbr"></strong>
										</span>
									</div>
									<div class="modal-container">
										<button class="upload-help" data-trigger data-target="upload-helper"><i class="fa fa-question"></i></button>
										<div id="upload-helper" class="modal modal-animation">
											<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
											<div class="content-panel">
												<h3>${LangLoader::get_message('upload.helper', 'main')}</h3>
												<p><strong>${LangLoader::get_message('allowed.extensions', 'main')} :</strong> "{ALLOWED_EXTENSIONS}"</p>
												<p><strong>{L_WIDTH_MAX} :</strong> {MAX_WIDTH} {L_UNIT_PX}</p>
												<p><strong>{L_HEIGHT_MAX} :</strong> {MAX_HEIGHT} {L_UNIT_PX}</p>
												<p><strong>${LangLoader::get_message('max.file.size', 'main')} :</strong> {MAX_FILE_SIZE_TEXT}</p>
											</div>
										</div>
									</div>
								</div>
								<ul class="ulist"></ul>
							</div>
						</div>
					</div>
				</fieldset>

				<fieldset class="fieldset-submit">
					<legend>{L_UPLOAD_IMG}</legend>
					<div class="fieldset-inset">
						<input type="hidden" name="max_file_size" value="2000000">
						<input type="hidden" name="token" value="{TOKEN}">
						<button type="submit" name="" value="true" class="submit">{L_UPLOAD_IMG}</button>
					</div>
				</fieldset>
			</form>

			<form action="admin_gallery_add.php" method="post">
				# IF C_IMG #
				<div class="center"><a href="" onclick="unselect_all_pictures();return false;" id="change_all_pictures_selection_top" class="smaller">{L_UNSELECT_ALL_PICTURES}</a></div>
				<table class="table">
					<thead>
						<tr>
							<th colspan="4">{L_IMG_DISPO_GALLERY}</th>
						</tr>
					</thead>
					<tbody>
						# START list #
						# IF list.C_DISPLAY_TR_START #<tr># ENDIF #
							<td class="valign-bottom">
								<div class="smaller">
									<div class="thumnails-list-container">
									<img src="pics/thumbnails/{list.NAME}" alt="{list.NAME}" />
									</div>
									<div class="spacer"></div>
									<div>
										{L_NAME}
										<div class="spacer"></div>
										<input type="text" name="{list.ID}name" value="{list.NAME}">
										<input type="hidden" name="{list.ID}uniq" value="{list.UNIQ_NAME}">
									</div>
									<div class="spacer"></div>
									<div>
										${LangLoader::get_message('form.category', 'common')}
										<div class="spacer"></div>
											<select name="{list.ID}cat" id="{list.ID}cat" class="select-cat">
												{list.CATEGORIES}
											</select>
									</div>
									<div class="spacer"></div>
									<div class="right">
										{L_SELECT} <input type="checkbox" checked="checked" id="{list.ID}activ" name="{list.ID}activ" value="1">
										<div class="spacer"></div>
										{L_DELETE} <input type="checkbox" name="{list.ID}del" value="1">
									</div>
								</div>
							</td>
						# IF list.C_DISPLAY_TR_END #</tr># ENDIF #
						# END list #

						# START end_td_pics #
							<td style="width:{end_td_pics.COLUMN_WIDTH_PICS}%;padding:0">&nbsp;</td>

						# IF end_td_pics.C_DISPLAY_TR_END #</tr># ENDIF #
						# END end_td_pics #
					</tbody>
				</table>
				<div class="center"><a href="" onclick="unselect_all_pictures();return false;" id="change_all_pictures_selection_bottom" class="smaller">{L_UNSELECT_ALL_PICTURES}</a></div>

				<div class="spacer"></div>

				<div class="form-element">
					<label for="root_cat">{L_GLOBAL_CAT_SELECTION} <span class="field-description">{L_GLOBAL_CAT_SELECTION_EXPLAIN}</span></label>
					<div class="form-field">
						<select name="root_cat" id="root_cat">
							{ROOT_CATEGORIES}
						</select>
						<script>
						jQuery('#root_cat').on('change', function() {
							root_value = jQuery('#root_cat').val();
							# START list #
							jQuery('#' + '{list.ID}cat').val(root_value);
							# END list #
						});
						</script>
					</div>
				</div>

				<fieldset class="fieldset-submit">
					<legend>{L_SUBMIT}</legend>
					<div class="fieldset-inset">
						<input type="hidden" name="nbr_pics" value="{NBR_PICS}">
						<input type="hidden" name="token" value="{TOKEN}">
						<button type="submit" name="valid" value="true" class="submit">{L_SUBMIT}</button>
					# ELSE #
						<div class="message-helper notice">{L_NO_IMG}</div>
					# ENDIF #
					</div>
				</fieldset>
			</form>
		</div>
		<script>
			jQuery('#gallery').dndfiles({
				multiple: true,
				maxFileSize: '{MAX_FILE_SIZE}',
				maxFilesSize: '-1',
				maxWidth: '{MAX_WIDTH}',
				maxHeight: '{MAX_HEIGHT}',
				allowedExtensions: ["{ALLOWED_EXTENSIONS}"],
				warningText: ${escapejs(LangLoader::get_message('warning.upload.disabled', 'main'))},
				warningExtension: ${escapejs(LangLoader::get_message('warning.upload.extension', 'main'))},
				warningFileSize: ${escapejs(LangLoader::get_message('warning.upload.file.size', 'main'))},
				warningFilesNbr: ${escapejs(LangLoader::get_message('warning.upload.files.nbr', 'main'))},
				warningFileDim: ${escapejs(LangLoader::get_message('warning.upload.file.dim', 'main'))},
			});
		</script>

		# INCLUDE forum_top #
		
		<script type='text/javascript'>
		<!--
		function check_form_post(){
			# IF C_BBCODE_TINYMCE_MODE #
				tinyMCE.triggerSave();
			# ENDIF #
			
			if(document.getElementById('contents').value == "") {
				alert("{L_REQUIRE_TEXT}");
				return false;
			}
		}
		-->
		</script>	
		
		<div class="module_position">
			<div class="module_top_l"></div>
			<div class="module_top_r"></div>
			<div class="module_top"><a href="index.php{SID}">{L_FORUM_INDEX}</a> &raquo; {U_FORUM_CAT} &raquo; {U_TITLE_T} <span style="font-weight:normal"><em>{DESC}</em></span></div>
			<div class="module_contents">
				<form action="{U_ACTION}" method="post" onsubmit="return check_form_post();">
					# INCLUDE message_helper #

					# IF C_FORUM_PREVIEW_MSG #
					<div class="module_position">
						<div class="module_top_l"></div>
						<div class="module_top_r"></div>
						<div class="module_top">
							<span style="float:left;">{L_PREVIEW}</span>
							<span style="float:right;"></span>&nbsp;
						</div>
					</div>	
					<div class="msg_position">
						<div class="msg_container">
							<div class="msg_pseudo_mbr"></div>
							<div class="msg_top_row">
								<div style="float:left;">&nbsp;&nbsp;<i class="icon-anchor"></i> {DATE}</div>
								<div style="float:right;"><i class="icon-quote-right"></i>&nbsp;&nbsp;</div>
							</div>
							<div class="msg_contents_container">
								<div class="msg_info_mbr">
								</div>
								<div class="msg_contents">
									<div class="msg_contents_overflow">
										{CONTENTS}
									</div>
								</div>
							</div>
						</div>	
						<div class="msg_sign">
							<hr />
							<span style="float:left;">
								<span class="basic-button smaller">MP</span>
							</span>
							<span style="float:right;font-size:10px;">
							</span>&nbsp;
						</div>	
					</div>
					<div class="msg_position">
						<div class="msg_bottom_l"></div>
						<div class="msg_bottom_r"></div>
						<div class="msg_bottom">&nbsp;</div>
					</div>
					<br /><br />
					# ENDIF #
					
					<div class="fieldset-content">
						<fieldset>
							<legend>{L_EDIT_MESSAGE}</legend>
							<p>{L_REQUIRE}</p>
							<div class="form-element-textarea">
								<label for="contents">* {L_MESSAGE}</label>
								{KERNEL_EDITOR}
								<textarea rows="25" cols="66" id="contents" name="contents">{CONTENTS}</textarea>
							</div>
						</fieldset>
						
						<fieldset class="fieldset-submit">
						<legend>{L_SUBMIT}</legend>
							<input type="hidden" name="p_update" value="{P_UPDATE}">
							<button type="submit" name="edit_msg" value="true">{L_SUBMIT}</button>
							<button onclick="XMLHttpRequest_preview();" type="button">{L_PREVIEW}</button>
							<button type="reset" value="true">{L_RESET}</button>
						</fieldset>
					</div>		
				</form>
			</div>
			<div class="module_bottom_l"></div>
			<div class="module_bottom_r"></div>
			<div class="module_bottom"><a href="index.php{SID}">{L_FORUM_INDEX}</a> &raquo; {U_FORUM_CAT} &raquo; {U_TITLE_T} <span style="font-weight:normal"><em>{DESC}</em></span></div>
		</div>
		
		# INCLUDE forum_bottom #
		
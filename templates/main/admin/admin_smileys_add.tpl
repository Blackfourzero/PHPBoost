		<script type="text/javascript">
		<!--
		function check_form(){
			if(document.getElementById('code_smiley').value == "") {
				alert("{L_REQUIRE_CODE}");
				return false;
		    }
			if(document.getElementById('url_smiley').value == "" || document.getElementById('url_smiley').value == "--") {
				alert("{L_REQUIRE_URL}");
				return false;
		    }
			
			return true;
		}

		function img_smiley(smiley_url)
		{
			if( document.getElementById('img_smiley') )
				document.getElementById('img_smiley').innerHTML = '<img src="../images/smileys/' + smiley_url + '" alt="" />';
		}
		-->
		</script>
		
		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_ADD_SMILEY}</li>
				<li>
					<a href="admin_smileys.php"><img src="../templates/{THEME}/images/admin/smileys.png" alt="" /></a>
					<br />
					<a href="admin_smileys.php" class="quick_link">{L_SMILEY_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_smileys_add.php"><img src="../templates/{THEME}/images/admin/smileys.png" alt="" /></a>
					<br />
					<a href="admin_smileys_add.php" class="quick_link">{L_ADD_SMILEY}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
			# START error_handler #
			<div class="error_handler_position">
				<span id="errorh"></span>
				<div class="{error_handler.CLASS}" style="width:500px;margin:auto;padding:15px;">
					<img src="../templates/{THEME}/images/{error_handler.IMG}.png" alt="" style="float:left;padding-right:6px;" /> {error_handler.L_ERROR}
					<br />	
				</div>
			</div>
			# END error_handler #
			
			<form action="admin_smileys_add.php" method="post" action="" enctype="multipart/form-data" class="fieldset_content">
				<fieldset>
					<legend>{L_UPLOAD_SMILEY}</legend>
					<dl>
						<dt><label for="upload_smiley">{L_EXPLAIN_UPLOAD_IMG}</label></dt>
						<dd><label><input type="file" name="upload_smiley" id="upload_smiley" size="30" class="submit" />
						<input type="hidden" name="max_file_size" value="2000000" /></label></dd>
					</dl>
				</fieldset>			
				<fieldset class="fieldset_submit">
					<legend>{L_UPLOAD}</legend>
					<input type="submit" value="{L_UPLOAD}" class="submit" />				
				</fieldset>
			</form>
							
			<form action="admin_smileys_add.php" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
				<legend>{L_ADD_SMILEY}</legend>
					<dl>
						<dt><label for="code_smiley">* {L_SMILEY_CODE}</label></dt>
						<dd><label><input type="text" size="40" maxlength="100" id="code_smiley" name="code_smiley" value="{CODE_SMILEY}" class="text" /> </label></dd>
					</dl>
					<dl>
						<dt><label for="code_smiley">* {L_SMILEY_AVAILABLE}</label></dt>
						<dd><label>
							<select name="url_smiley" id="url_smiley" onChange="img_smiley(this.options[selectedIndex].value)">
								# START select #						
									{select.URL_SMILEY}						
								# END select #
							</select>
							<span id="img_smiley"></span>
						</label></dd>
					</dl>
				</fieldset>			
				<fieldset class="fieldset_submit">
					<legend>{L_ADD}</legend>
					<input type="submit" name="add" value="{L_ADD}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />				
				</fieldset>	
			</form>	
		</div>
		
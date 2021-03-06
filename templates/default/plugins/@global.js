// Search string into string
	function strpos(haystack, needle)
	{
		var i = haystack.indexOf(needle, 0); // returns -1
		return i >= 0 ? i : false;
	}

// BBCode block: Hide
	// Add the informations : icon to hide/text to reveal content
	jQuery(document).ready(function(){
		var IDCODE = 1;
		jQuery('.formatter-hide').each( function(){
			if ( jQuery(this).hasClass('no-js') )
			{
				jQuery(this).attr('id','formatter-hide-container-' + IDCODE);
				jQuery(this).removeClass('no-js');
				jQuery(this).attr('onClick', 'bb_hide(' + IDCODE + ', 1, event);');
				jQuery(this).children('.formatter-content').before('<span id="formatter-hide-message-' + IDCODE + '" class="formatter-hide-message">' + L_HIDE_MESSAGE + '</span>');
				jQuery(this).children('.formatter-content').before('<span id="formatter-hide-close-button-' + IDCODE + '" class="formatter-hide-close-button pinned error" aria-label="' + L_HIDE_HIDEBLOCK + '" onclick="bb_hide(' + IDCODE + ', 0, event);"><i class="fa fa-times"></i></span>');
				IDCODE = IDCODE + 1;
			}
		} );
	} );

	// Hide/show content
	function bb_hide(idcode, show, event)
	{
		var idcode = (typeof idcode !== 'undefined') ? idcode : 0;
		var show = (typeof show !== 'undefined') ? show : 0;

		event.stopPropagation();
		jQuery('#formatter-hide-container-' + idcode).toggleClass('formatter-show');
		if (show == 1)
		{
			jQuery('#formatter-hide-container-' + idcode).removeAttr('onClick');
		}
		else
		{
			jQuery('#formatter-hide-container-' + idcode).attr('onClick', 'bb_hide(' + idcode + ', 1, event);');
		}
	}

// BBCode block: Code
	// Add button "Copy to clipboard" on Coding block
	jQuery(document).ready(function(){
		var IDCODE = 1;
		jQuery('.formatter-code').each( function(){
			if ( !jQuery(this).children('.formatter-content').hasClass('copy-code-content') )
			{
				jQuery(this).prepend('<span id="copy-code-' + IDCODE + '" class="copy-code" aria-label="' + L_COPYTOCLIPBOARD + '" onclick="copy_code_clipboard(' + IDCODE + ')"><i class="far fa-clone fa-lg"></i></span>');
				jQuery(this).children('.formatter-content').attr("id", 'copy-code-' + IDCODE + '-content');
				jQuery(this).children('.formatter-content').addClass('copy-code-content');
				IDCODE = IDCODE + 1;
			}
		} );
	} );

	// Function copy_code_clipboard
	//
	// Description :
	// This function copy the content of your specific selection to clipboard.
	//
	// parameters : one
	// {idcode} correspond to the ID selector you want to select.
	//  - if it's a number : ID selector is 'copy-code-{idcode}-content'
	//  - if it's a string : ID selector is '{idcode}'
	//
	// Return : -
	//
	// Comments :
	// if container is an HTMLTextAreaElement, we use select() function of TextArea element instead of specific SelectElement function
	//
	function copy_code_clipboard(idcode)
	{
		if ( Number.isInteger(idcode) )
			idcode = 'copy-code-' + idcode + '-content';

		var ElementtoCopy = document.getElementById( idcode );

		if (ElementtoCopy instanceof HTMLTextAreaElement)
			ElementtoCopy.select();
		else
			SelectElement(ElementtoCopy);

		try {
			var successful = document.execCommand('copy');
		}
		catch(err) {
			alert('Your browser do not authorize this operation');
		}
	}

	//Function SelectElement
	//
	// Description :
	// The content will be selected on your page as if you had selected it with your mouse
	//
	// parameters : one
	// {element} correspond to the element you want to select
	//
	// Return : -
	//
	// Comments : -
	//
	function SelectElement(element) {
		var range = document.createRange();
		range.selectNodeContents(element);

		var selection = window.getSelection();
		selection.removeAllRanges();
		selection.addRange(range);
	}

//Function copy_to_clipboard
//
// Description :
// This function copy the content of parameter to clipboard.
//
// parameters : one
// {tocopy} correspond to the content you want to copy.
//
// Return : -
//
// Comments : -
//
function copy_to_clipboard(tocopy)
{
	var dummy = $('<input>').val(tocopy).appendTo('body').select()

	try {
		var successful = document.execCommand('copy');
	}
	catch(err) {
		alert('Your browser do not authorize this operation');
	}
}

// Open submenu
	// Function open_submenu
	// for links submenu, in HTML onclick attribute
	//
	// Description :
	// This function add CSS Class to the specified CSS ID
	//
	// parameters : three
	// {myid} correspond to the specific element you want to add your CSS class
	// {myclass} correspond to the name of CSS class you want to add to your specific element.
	// {closeother} correspond to the name of CSS class you want to add to your specific element.
	//
	// Return : -
	//
	// Comments : if {myclass} is missing, we use CSS class "opened"
	// Comments : if {closeother} is defined, we close every elements with {closeother} CSS class
	//
	function open_submenu(myid, myclass, closeother)
	{
		var myclass = (typeof myclass !== 'undefined') ? myclass : "opened";
		var closeother = (typeof closeother !== 'undefined') ? closeother : false;

		if (closeother == false)
			jQuery('#' + myid).toggleClass(myclass);
		else {
			if (jQuery('#' + myid).hasClass(myclass))
				jQuery('.' + closeother).removeClass(myclass);
			else {
				jQuery('.' + closeother).removeClass(myclass);
				jQuery('#' + myid).addClass(myclass);
			}
		}
	}

	// Function opensubmenu
	// for content submenu in javascript script
	//
	// Description :
	// This function add CSS Class to the specified CSS ID to open a submenu
	//
	// options : four
	// {osmCloseExcept} correspond to the specific element you doesn't want to close on click.
	// {osmCloseButton} correspond to the specific button for closed submenu.
	// {osmTarget} correspond to the name of CSS class of you element you want to add a specific CSS class.
	// {osmClass} correspond to the name of CSS class you want to add to your specific element.
	//
	// Return : -
	//
	// Comments :
	//   - if {osmClass} is missing, ".opened" CSS class is used
	//   - if {osmCloseButton} is missing, "a.close-button" element is used
	//   - use CSS selector "." or "#" for {osmCloseExcept} and {osmTarget}
	//   - for all children elements, use * in {osmCloseExcept} like '.myClass *'
	//
	(function($) {
		$.fn.opensubmenu = function( options ) {
			var defaults = $(this), params = $.extend({
				osmCloseExcept: '',
				osmCloseButton: 'a.close-button',
				osmTarget: '',
				osmClass: 'opened'
			}, options);

			return this.each(function() {
				$(this).on('click', function(event) {
					event.preventDefault();
					if ($(this).closest(params.osmTarget).hasClass(params.osmClass))
						$(document).find(params.osmTarget).removeClass(params.osmClass);
					else {
						$(document).find(params.osmTarget).removeClass(params.osmClass);
						$(this).closest(params.osmTarget).addClass(params.osmClass);
					}
					event.stopPropagation();
				});
				$(document).on('click',function(event) {
					if (($(event.target).is(params.osmCloseExcept) === false || $(event.target).is(params.osmCloseButton) === true)) {
						$(document).find(params.osmTarget).removeClass(params.osmClass);
					}
				});
			});
		};
	})(jQuery);

// Multiple checkboxes
	// Function multiple_checkbox_check
	//
	// Description :
	// This function check or uncheck all checkbox with specific id
	//
	// options : three
	// {status} correspond to the status we need (check or uncheck).
	// {elements_number} corresponds to the total number of elements displayed.
	// {except_element} corresponds to an element to ignore.
	//
	// Return : -
	//
	// Comments :
	//
	function multiple_checkbox_check(status, elements_number, except_element, delete_button_control = true)
	{
		var i;
		var except_element = (typeof except_element !== 'undefined') ? except_element : 0;
		for (i = 1; i <= elements_number; i++)
		{
			if ($('#multiple-checkbox-' + i)[0] && i != except_element)
				$('#multiple-checkbox-' + i)[0].checked = status;
		}
		try {
			$('.check-all')[0].checked = status;
		}
		catch (err) {}
		if (delete_button_control)
			delete_button_display(elements_number);
	}

	//Function delete_button_display
	//
	// Description :
	// This function change the data-confirmation message of the delete all button and its display
	//
	// options : one
	// {elements_number} corresponds to the total number of elements displayed.
	//
	// Return : -
	//
	// Comments :
	//
	function delete_button_display(elements_number)
	{
		var i;
		var checked_elements_number = 0;
		for (i = 1; i <= elements_number; i++)
		{
			if ($('#multiple-checkbox-' + i)[0] && $('#multiple-checkbox-' + i)[0].checked == true)
				checked_elements_number++;
		}

		try {
			if (checked_elements_number > 0) {
				$('#delete-all-button').attr("disabled", false);
				if (checked_elements_number > 1)
					$('#delete-all-button').attr("data-confirmation", "delete-elements");
				else
					$('#delete-all-button').attr("data-confirmation", "delete-element");
			} else {
				$('#delete-all-button').attr("disabled", true);
			}
			if (checked_elements_number < elements_number)
				$('.check-all')[0].checked = false;
			else if (checked_elements_number == elements_number)
				$('.check-all')[0].checked = true;
			update_data_confirmations();
		}
		catch (err) {}
	}

// Progressbar
	function change_progressbar(id_element, value, informations) {
		var progress_bar_el = jQuery('#' + id_element).children('.progressbar').css('width', value + '%');

		if (informations) {
			jQuery('#' + id_element).children('.progressbar-infos').text(informations);
		}
		else {
			jQuery('#' + id_element).children('.progressbar-infos').text(value + '%');
		}
	}

// XMLHttpRequest
	// Ajax preparation function
	function xmlhttprequest_init(filename)
	{
		var xhr_object = null;
		if (window.XMLHttpRequest) // Firefox
			xhr_object = new XMLHttpRequest();
		else if (window.ActiveXObject) // Internet Explorer
			xhr_object = new ActiveXObject("Microsoft.XMLHTTP");

		xhr_object.open('POST', filename, true);

		return xhr_object;
	}

	// Ajax send function
	function xmlhttprequest_sender(xhr_object, data)
	{
		xhr_object.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xhr_object.send(data);
	}

	// Escape string variables in xmlhttprequest requests
	function escape_xmlhttprequest(contents)
	{
		contents = contents.replace(/\+/g, '%2B');
		contents = contents.replace(/&/g, '%26');

		return contents;
	}

	// Member search function
	function XMLHttpRequest_search_members(searchid, theme, insert_mode, alert_empty_login)
	{
		var login = jQuery('#login' + searchid).val();
		if( login != "" )
		{
			if (jQuery('#search_img' + searchid))
				jQuery('#search_img' + searchid).append('<i class="fa fa-spinner fa-spin"></i>');

			jQuery.ajax({
				url: PATH_TO_ROOT + '/kernel/framework/ajax/member_xmlhttprequest.php?' + insert_mode + '=1',
				type: "post",
				dataType: "html",
				data: {'login': login, 'divid' : searchid, 'token' : TOKEN},
				success: function(returnData){
					if (jQuery('#search_img' + searchid))
						jQuery('#search_img' + searchid).children("i").remove();

					if (jQuery("#xmlhttprequest-result-search" + searchid))
						jQuery("#xmlhttprequest-result-search" + searchid).html(returnData);

					jQuery("#xmlhttprequest-result-search" + searchid).fadeIn();
				},
				error: function(e){
					jQuery('#search_img' + searchid).children("i").remove();
				}
			});
		}
		else
			alert(alert_empty_login);
	}

// Check if a function name exists
	function functionExists(function_name)
	{
		// https://kvz.io/
		// + original by: Kevin van Zonneveld (https://kvz.io/)
		// + improved by: Steve Clay
		// + improved by: Legaev Andrey
		// * example 1: function_exists('isFinite');
		// * returns 1: true
		if (typeof function_name == 'string')
		{
			return (typeof window[function_name] == 'function');
		}
		else
		{
			return (function_name instanceof Function);
		}
	}

// Includes synchronously a js file
	function include(file)
	{
		if (window.document.getElementsByTagName)
		{
			script = window.document.createElement("script");
			script.type = "text/javascript";
			script.src = file;
			document.documentElement.firstChild.appendChild(script);
		}
	}

// FlowPlayer
	// Display the video player with the right url, width and height
	playerflowPlayerRequired = false;
	function insertMoviePlayer(id)
	{
		if (!playerflowPlayerRequired)
		{
			include(PATH_TO_ROOT + '/kernel/lib/flash/flowplayer/flowplayer.js');
			playerflowPlayerRequired = true;
		}
		flowPlayerDisplay(id);
	}

	// Build the player after javascript parsing
	function flowPlayerDisplay(id)
	{
		// Build the flowplayer
		// Wait for parsing if function doesn't exist
		if (!functionExists('flowplayer'))
		{
			setTimeout('flowPlayerDisplay(\'' + id + '\')', 100);
			return;
		}
		// Start flowplayer
		flowplayer(id, PATH_TO_ROOT + '/kernel/lib/flash/flowplayer/flowplayer.swf', {
			clip: {
				url: jQuery('#' + id).attr('href'),
				autoPlay: false
			}
		});
	}

// Scroll position management (scroll-to-top + cookie-bar)
	function scroll_to( position ) {
		if ( position > 800) {
			jQuery('#scroll-to-top').fadeIn();
		} else {
			jQuery('#scroll-to-top').fadeOut();
		}

		if ( position > 1) {
			jQuery('#cookie-bar-container').addClass('fixed');
		}
		else {
			jQuery('#cookie-bar-container').removeClass('fixed');
		}

		if ( position > 800 || ($(document).height() == $(window.top).height())) {
			jQuery('#scroll-to-bottom').fadeOut();
		} else {
			jQuery('#scroll-to-bottom').fadeIn();
		}
	}

	jQuery(document).ready(function(){
		scroll_to($(this).scrollTop());

		jQuery(window.top).scroll(function(){
			scroll_to($(this).scrollTop());
		});

		// Scroll to Top or Bottom
		jQuery('#scroll-to-top').on('click',function(){
			jQuery('html, body').animate({scrollTop : 0},1200);
			return false;
		});
		jQuery('#scroll-to-bottom').on('click',function(){
			jQuery('html, body').animate({scrollTop: $(document).height()-$(window.top).height()},1200);
			return false;
		});
	});

// Cookies, Cookiebar and BBCode management
	// Send cookie to client
	function sendCookie(name, value, delay)
	{
		var delay = (typeof delay !== 'undefined') ? delay : 1; // Default validity: 1 month
		var date = new Date();
		date.setMonth(date.getMonth() + delay);
		document.cookie = name + '=' + value + '; Expires=' + date.toGMTString() + '; Path=/';
	}

	// Retrieve cookie value
	function getCookie(name)
	{
		start = document.cookie.indexOf(name + "=");
		if( start >= 0 )
		{
			start += name.length + 1;
			end = document.cookie.indexOf(';', start);

			if( end < 0 )
				end = document.cookie.length;

			return document.cookie.substring(start, end);
		}
		return '';
	}

	// Delete cookie.
	function eraseCookie(name) {
		sendCookie(name,"",-1);
	}

// set cell's height to width (setInterval needed because of the display:hidden)
	setInterval(function() {
		jQuery('.square-cell').each(function(){
			var cell_width = jQuery(this).outerWidth();
			jQuery(this).outerHeight(cell_width + 'px');
		});
	}, 1);

// colorSurround add a colored square to the element and color its borders if it has.
	jQuery.fn.extend ({
		colorSurround: function() {
			return this.each(function(){
				var color = jQuery(this).data('color-surround');
				jQuery(this).css('border-color', color);
				jQuery(this).prepend('<span style="background-color: ' + color + ';" class="data-color-surround"></span>')
			})
		}
	});

// Scroll to anchor on .sticky-menu
	jQuery('.sticky-menu').each(function(){
		jQuery('.sticky-menu .cssmenu-title').click(function(){
			var targetId = jQuery(this).attr("href"),
				hash = targetId.substring(targetId.indexOf('#'));
			if(hash != null || hash != targetId) {
				if (parseInt($(window).width()) < 769)
					menuOffset = jQuery('.sticky-menu > .cssmenu > ul > li > .cssmenu-title').innerHeight();
				else
					menuOffset = jQuery('.sticky-menu > .cssmenu').innerHeight();
				history.pushState('', '', hash);
				jQuery('html, body').animate({scrollTop:jQuery(hash).offset().top - menuOffset}, 'slow');
			}
		});
	});

# IF C_FLOATING #
<div id="message-helper-{ID}" class="message-helper {MESSAGE_CSS_CLASS}" style="display: none;">
	# IF NOT C_TIMEOUT #
	<a id="message-helper-button-{ID}" class="message-helper-button {MESSAGE_CSS_CLASS}" aria-label="${LangLoader::get_message('message.close_ephemeral_message', 'status-messages-common')}"><i class="fa fa-close-message" aria-hidden="true"></i></a>
	# ENDIF #
	{MESSAGE_CONTENT}
</div>
<script>
<!--

	/**
	 *  hide the message-helper named by {element}.
	 *  @param {jQuery object} element
	 */
	function hide_message_helper(element)
	{
		$(element).fadeTo('slow', 0);
		$(element).fadeOut('fast', 0);
		$(element).addClass('hide');
		hide_floating_message_container();
	}

	/**
	 *  Check if we can hide the floating message-helper container.
	 *  @param none
	 */
	function hide_floating_message_container()
	{
		var elements = $(".floating-message-container").children();
		var last = true;

		for (var i=0; i < elements.length; i++)
		{
			if ($(elements[i]).hasClass('hide') == false)
				last=false;
		}

		if (last == true)
			$(".floating-message-container").removeClass("active");
	}

	/**
	 *  Display the message-helper into the floating message container.
	 *  @param none
	 */
	$(function(){
		if ($(".floating-message-container").length == 0)
			$('<div class="floating-message-container"></div>').appendTo('body');

		$(".floating-message-container").addClass("active");

		$( $("#message-helper-{ID}") ).appendTo( $(".floating-message-container") );
		$("#message-helper-{ID}").fadeTo("fast", 1);

		# IF C_TIMEOUT #
		setTimeout('hide_message_helper("#message-helper-{ID}");', {TIMEOUT});
		# ENDIF #

		$('#message-helper-button-{ID}').on('click',function() { hide_message_helper($(this).parent()); });
	});
-->
</script>
# ELSE #
<div id="message-helper-{ID}" class="message-helper {MESSAGE_CSS_CLASS}">{MESSAGE_CONTENT}</div>
# ENDIF #

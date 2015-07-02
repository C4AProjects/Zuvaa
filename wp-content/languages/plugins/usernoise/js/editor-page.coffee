jQuery ($) ->
	usernoise_reply = new QTags("usernoise_reply", "replybody", "replywrapper", "more,fullscreen")  unless typeof QTags is "undefined"
	$("#un-reply-submit").click ->
		$("#un-reply-loader").show()
		$.post ajaxurl,
			action: "un_feedback_reply"
			message: $("#replybody").val()
			id: $("#post_ID").val()
			subject: $("#subject").val()
		, (response) ->
			$("#un-reply-loader").hide()
			$("#replybody").val ""
			alert response
			return

		return

	return

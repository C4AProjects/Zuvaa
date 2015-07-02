jQuery ($) ->
	$("a#button").click ->
		setTimeout (->
			$("#usernoise__feedback_button_icon").chosen template: (text, templateData) ->
				"<i class='" + templateData.icon + "'></i>" + text

			return
		), 300
		return

	return

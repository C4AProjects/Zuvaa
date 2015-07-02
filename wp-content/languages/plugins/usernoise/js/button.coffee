jQuery ($) ->
	return  if navigator and navigator.appVersion and (navigator.appVersion.indexOf("MSIE 6.0") isnt -1 or navigator.appVersion.indexOf("MSIE 7.0") isnt -1)
	usernoiseButton.button = new usernoise.UsernoiseButton()
	usernoise.window = show: usernoiseButton.button.showWindow
	$(".un-feedback-form").each ->
		new usernoise.FeedbackForm($(this))
		return

	is_mobile_device = =>
		(window.innerWidth <= 800 && window.innerHeight <= 600)

	usernoiseButton.button.show() if usernoiseButton.showButton && !(usernoiseButton.disableOnMobiles && is_mobile_device())
	$(document).bind "sent#feedbackform#window.un", ->
		closeOverlay = ->
			$("#un-thankyou").find("a").unbind "click"
			$("#un-thankyou").fadeOut "fast", ->
				$("#un-overlay").fadeOut "fast", ->
					$("#un-overlay").remove()
					return

				return

			return
		$overlay = $("<div id=\"un-overlay\" />").appendTo($("body"))
		$("#un-overlay").click(closeOverlay).fadeIn "fast", ->
			$("#un-thankyou").fadeIn("fast", ->
				setTimeout closeOverlay, 5000
				return
			).find("#un-feedback-close").click closeOverlay
			return

		return

	try
		$("#" + usernoiseButton.custom_button_id).click ->
			usernoise.window.show()
			false

	catch err
		alert "It looks like you entere wrong HTML ID value for custom Usernoise feedback button."
	if jQuery.on
		jQuery.on 'click', 'a[rel=usernoise], button[rel=usernoise], a[href="#usernoise"]', ->
			usernoise.window.show()
			false
	$("a[rel=usernoise]").click ->
		usernoise.window.show()
		false


	$("button[rel=usernoise]").click ->
		usernoise.window.show()
		false

	$("a[href=\"#usernoise\"]").click ->
		usernoise.window.show()
		false

	return

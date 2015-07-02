jQuery ($) ->
	usernoise.window = new usernoise.Window()
	usernoise.feedbackForm = new usernoise.FeedbackForm($(".un-feedback-form"))
	usernoise.thankYouScreen = new usernoise.ThankYouScreen()
	$("#window #un-feedback-wrapper").resize ->
		$("#window").css
			"margin-top": "-" + $("#window").height() / 2 + "px"
			"margin-left": "-" + $("#window").width() / 2 + "px"

		return

	$("#window-close").click ->
		usernoise.window.hide()
		false

	$(document).bind "sent#feedbackform#window.un", ->
		$("#un-feedback-form-wrapper").fadeOut "fast", ->
			usernoise.thankYouScreen.show()
			return

		return

	$(document).click (event) ->
		usernoise.window.hide()  if event.target.parentNode is document
		return

	return

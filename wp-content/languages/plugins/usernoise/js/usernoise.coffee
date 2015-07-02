window.usernoise = {}
jQuery ($) ->
	UsernoiseButton = ->
		self = this
		self.show = ->
			$button = $("<a href=\"#\" id=\"un-button\" />").appendTo($("body"))
			property = undefined
			$button.html usernoiseButton.text
			$button.addClass usernoiseButton["class"]
			$button.attr "style", usernoiseButton.style
			$button.click (event) ->
				self.showWindow()
				event.preventDefault()
				false

			if browser.msie7
				$button.css "margin-top", "-" + $button.width() / 2 + "px"  if $button.is(".un-left") or $button.is(".un-right")
				$button.addClass "ie7"
			else if browser.msie8
				$button.css "right", "-" + $button.outerWidth() + "px"  if $button.is(".un-right")
				$button.css "margin-top", "-" + $button.width() / 2 + "px"  if $button.is(".un-left") or $button.is(".un-right")
				$button.addClass "ie8"
			else
				$button.addClass "css3"
			$button.css "margin-left", "-" + ($("#un-button").width() / 2) + "px"  if $button.is(".un-bottom") or $button.is(".un-top")
			if $button.is(".un-left")
				property = "left"
			else if $button.is(".un-right")
				property = "right"
			else if $button.is(".un-bottom")
				property = "bottom"
			else
				property = "top"
			$button.css "margin-top", ($button.width() / 2) + "px"  if $button.is(".un-left.css3")
			$button.css "margin-top", "-" + ($button.width() / 2) + "px"  if $button.is(".un-right.css3")
			propOnStart = {}
			propOnIn = opacity: 1
			propOnOut = opacity: 0.96
			propOnStart[property] = "+=40px"
			propOnIn[property] = "+=3px"
			propOnOut[property] = "-=3px"
			$button.animate(propOnStart).hover (->
				$button.animate propOnIn, 100
				return
			), ->
				$button.animate propOnOut, 100
				return

			return

		self.showWindow = ->
			$overlay = $("<div id=\"un-overlay\" />").prependTo($("body"))
			$overlay.fadeIn "fast"
			$loading = $("<div id=\"un-loading\" />").prependTo($("body"))
			$iframe = $("<iframe id=\"un-iframe\" marginheight=\"0\" marginwidth=\"0\" frameborder=\"0\" allowtransparency=\"true\" />").prependTo($("body"))
			$iframe.css "opacity", 0
			$iframe.load ->
				$loading.fadeOut "fast", ->
					$loading.remove()
					return

				$iframe.css "opacity", 0
				$iframe.animate
					opacity: 1
				, "fast"
				return

			$iframe.attr "src", usernoiseButton.windowUrl
			return

		self.hideWindow = ->
			$("#un-overlay").fadeOut ->
				$("#un-overlay").remove()
				return

			$("#un-loading").remove()
			$("#un-iframe").fadeOut "fast", ->
				$("#un-iframe").remove()
				return

			return

		return
	FeedbackForm = ($form) ->
		selectTypeHandler = ->
			$selector = $(this).parent()
			$selector.find("a").removeClass "selected"
			$(this).addClass "selected"
			type = $(this).attr("data-type")
			$selector.find("input[type=hidden]").val type
			$(document).trigger "typeselected#feedbackform#window.un", type
			false
		submitHandler = ->
			data = $form.unSerializeObject()
			data.referer = window.parent.document.location.href  if window.parent.document
			$(document).trigger "submitting#feedbackform#window.un", data
			self.lock()
			$form.find(".loader").show()
			self.errors.hide()
			$.post $form.attr("action"), data, (response) ->
				$form.find(".loader").hide()
				self.unlock()
				response = usernoise.helpers.parseJSON(response)
				if response.success
					$("#un-thankyou").height $("#un-feedback-wrapper").height() + "px"
					$form.find("textarea").val("").trigger "blur"
					$(document).trigger "sent#feedbackform#window.un"
				else
					self.errors.show response.errors
				return

			false
		self = this
		self.form = $form
		$form.find(".text").unAutoPlaceholder()
		$form.find(".un-types-wrapper a").click selectTypeHandler
		$form.find(".un-types-wrapper a:first-child").click()
		$form.submit submitHandler
		$.extend self,
			unlock: ->
				$(document).trigger "unlocking#feedbackform#window.un"
				$form.find("input, select, textarea").removeAttr "disabled"
				$form.find(".un-types-wrapper a").click selectTypeHandler
				return

			lock: ->
				$form.find("*").unbind "click"
				$(document).trigger "locking#feedbackform#window.un"
				$form.find("input, select, textarea").attr "disabled", "disabled"
				return

			errors: new Errors($form.find(".un-feedback-errors-wrapper"))
			selectedType: ->
				type = $("#types-wrapper a.selected").attr("id")
				(if type then type.replace("un-type-", "") else null)

		$(document).trigger "created#feedbackform#window.un", self
		return
	Errors = ($errorsWrapper) ->
		self = this
		$errors = $errorsWrapper.find(".un-errors")
		$.extend self,
			show: (errors) ->
				$("#window").addClass "transitionEnabled"
				$(errors).each (index, error) ->
					$errors.append $("<p />").text(error)
					return

				$errorsWrapper.fadeIn "fast"
				return

			hide: (errors) ->
				$errors.html ""
				$errorsWrapper.fadeOut "fast", ->
					$errorsWrapper.hide()
					return

				return

		return
	ThankYouScreen = ->
		self = this
		$screen = $screen
		$.extend self,
			show: ->
				$("#un-thankyou").show()
				$("#un-feedback-close").click ->
					usernoise.window.hide()
					false

				return

		return
	UsernoiseWindow = (windowSelector) ->
		detectBrowser = ->
			return  unless $("#wrapper").hasClass("un")
			$("#wrapper").addClass "un"
			$("#wrapper").addClass "un-ie7"  if browser.msie7
			$("#wrapper").addClass "un-ie8"  if browser.msie8
			return
		showThankYouHandler = (event, html) ->
			self.thankYouScreen = new ThankYouScreen($window.find(".thank-you-screen"))
			self.thankYouScreen.show html
			return
		self = this
		$window = $("#window")
		detectBrowser()
		$.extend self,
			hide: ->
				window.parent.usernoiseButton.button.hideWindow()
				return

			adjustSize: ->
				$window.css
					"margin-top": "-" + $window.height() / 2 + "px"
					"margin-left": "-" + $window.width() / 2 + "px"

				return

		self.adjustSize()
		return
	browser = {}
	if navigator and navigator.appVersion
		browser.msie6 = navigator.appVersion.indexOf("MSIE 6.0") isnt -1
		browser.msie7 = navigator.appVersion.indexOf("MSIE 7.0") isnt -1
		browser.msie8 = navigator.appVersion.indexOf("MSIE 8.0") isnt -1
	return  if browser.msie6 or browser.msie7
	usernoise.UsernoiseButton = UsernoiseButton
	usernoise.FeedbackForm = FeedbackForm
	usernoise.Errors = Errors
	usernoise.ThankYouScreen = ThankYouScreen
	$.fn.unAutoPlaceholder = ->
		$(this).each ->
			$(this).attr "data-default", $(this).val()
			$(this).focus ->
				if $(this).val() is $(this).attr("data-default")
					$(this).val ""
					$(this).addClass "text-normal"
					$(this).removeClass "text-empty"
				return

			$(this).blur ->
				if $(this).val() is ""
					$(this).val $(this).attr("data-default")
					$(this).removeClass "text-normal"
					$(this).addClass "text-empty"
				return

			return

		return

	$.fn.unSerializeObject = ->
		o = {}
		a = @serializeArray()
		$.each a, ->
			if o[@name]
				o[@name] = [o[@name]]  unless o[@name].push
				o[@name].push @value or ""
			else
				o[@name] = @value or ""
			return

		o

	usernoise.helpers = parseJSON: (json) ->
		(if $.parseJSON then $.parseJSON(json) else eval_("(" + json + ")"))

	usernoise.Window = UsernoiseWindow
	return

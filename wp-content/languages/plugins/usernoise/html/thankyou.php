<div id="un-thankyou" style="display: none;">
	<h2><?php echo un_get_option(UN_THANKYOU_TITLE, __('Thank you', 'usernoise')) ?></h2>
	<p>
		<?php echo un_get_option(UN_THANKYOU_TEXT, __('Your feedback has been received.', 'usernoise')) ?>
	</p>
	<a href="#" id="un-feedback-close"><img src="<?php echo usernoise_url('/images/ok.png')?>" id="thankyou-image" alt="Close" width="32" height="32"/></a>
</div>

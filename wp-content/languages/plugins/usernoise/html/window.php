<!DOCTYPE html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=<?php bloginfo('charset') ?>">
	<link rel="stylesheet" href="<?php echo esc_attr(usernoise_url('/css/window.css?v=' . UN_VERSION )) ?>" type="text/css">
	<link rel="stylesheet" href="<?php echo esc_attr(usernoise_url('/css/form.css?v=' . UN_VERSION)) ?>" type="text/css">
	<link rel="stylesheet" href="<?php echo esc_attr(usernoise_url('/vendor/font-awesome/css/font-awesome.min.css?v=' . UN_VERSION )) ?>" type="text/css">
	<link rel="stylesheet" href="<?php echo esc_attr(usernoise_url('/css/fixes.css?v=' . UN_VERSION)) ?>" type="text/css">
	<script src="<?php bloginfo('wpurl') ?>/wp-includes/js/jquery/jquery.js"></script>
	<script src="<?php echo esc_attr(usernoise_url('/vendor/jquery.resize.js')) ?>"></script>
	<script>var usernoise = {};</script>
	<script src="<?php echo esc_attr(usernoise_url('/js/usernoise.js?v=' . UN_VERSION)) ?>"></script>
	<script src="<?php echo esc_attr(usernoise_url('/js/window.js?v=' . UN_VERSION)) ?>"></script>
	<?php do_action('un_head') ?>
</head>
<body class="<?php echo $body_class ?>">
	<div id="window" <?php un_window_class() ?>>
		<a id="window-close" href="#" title="<?php _e('Close', 'usernoise'); ?>"><?php _e('Close', 'usernoise') ?></a>
		<div id="viewport" class="clearfix">
			<?php do_action('before_feedback_wrapper') ?>
			<div id="un-feedback-wrapper">
				<div id="un-feedback-form-wrapper">
					<h2>
					<?php echo un_get_option(UN_FEEDBACK_FORM_TITLE, __('Feedback', 'usernoise')) ?>
					<?php if (current_user_can('edit_others_posts')): ?>
						<a class="un-button-settings" id="un-button-settings" href="<?php echo admin_url('options-general.php?page=usernoise')?>">
							<?php echo strtolower(__('Settings', 'usernoise')) ?></a>
						<?php endif ?>
					</h2>
					<p><?php echo un_feedback_form_text() ?></p>
					<?php do_action('un_fedback_form_before') ?>
					<?php do_action('un_feedback_form') ?>
					<?php do_action('un_feedback_form_after')?>
				</div>
				<?php include('thankyou.php') ?>
			</div>
			<?php do_action('un_after_feedback_wrapper')?>
		</div>
	</div>
</body>
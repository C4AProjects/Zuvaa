<?php
class UN_Form{
	function __construct(){
		add_action('un_feedback_form', array($this, 'feedback_form'));
	}

	function feedback_form($action){
		global $un_h; ?>
		<form action="<?php echo esc_attr(un_ajax_url('feedback_form_submit')) ?>" method="post" class="un-feedback-form">
			<?php if (un_get_option(UN_FEEDBACK_FORM_SHOW_TYPE)): ?>
				<div class="un-types-wrapper">
					<?php $tags = get_terms(FEEDBACK_TYPE, array('un_orderby_meta' => 'position', 'hide_empty' => false)) ?>
					<?php foreach($tags as $tag): ?>
						<a href="#" class="un-feedback-type" data-type="<?php echo $tag->slug ?>"><?php if (!un_get_option(UN_DISABLE_ICONS)): ?><i class="<?php echo un_get_term_meta($tag->term_id, 'icon') ?>"></i><?php endif ?><?php echo esc_html(__($tag->name, 'usernoise'))?></a>
					<?php endforeach ?>
					<?php if (isset($tags[0])): ?>
						<?php $slug = $tags[0] ?>
						<?php $slug = $slug->slug ?>
					<?php else: ?>
						<?php $slug = null ?>
					<?php endif ?>
					<?php $un_h->hidden_field('type', $slug)?>
				</div>
			<?php endif ?>
			<?php $un_h->textarea('description', un_get_option(UN_FEEDBACK_TEXTAREA_PLACEHOLDER), array('id' => 'un-description', 'class' => 'text text-empty'))?>
			<?php if (un_get_option(UN_FEEDBACK_FORM_SHOW_SUMMARY)): ?>
				<?php $un_h->text_field('title', un_get_option(UN_FEEDBACK_SUMMARY_PLACEHOLDER), array('id' => 'un-title', 'class' => 'text text-empty'))?>
			<?php endif ?>
			<?php if (un_get_option(UN_FEEDBACK_FORM_SHOW_EMAIL)): ?>
				<?php $un_h->text_field('email', un_feedback_email_placeholder(), array('id' => 'un-email', 'class' => 'text text-empty'))?>
			<?php endif ?>
			<?php if (un_get_option(UN_FEEDBACK_FORM_SHOW_NAME)): ?>
				<?php $un_h->text_field('name', un_feedback_user_name_placeholder(), array('id' => 'un-name', 'class' => 'text text-empty'))?>
			<?php endif ?>
			<?php do_action('un_feedback_form_body') ?>
			<input type="submit" class="un-submit" value="<?php echo esc_attr(un_submit_feedback_button_text()) ?>" id="un-feedback-submit">
			&nbsp;<img src="<?php echo usernoise_url('/images/loader.gif') ?>" id="un-feedback-loader" class="loader" style="display: none;">
			<div class="un-feedback-errors-wrapper" style="display: none;">
				<div class="un-errors"></div>
			</div>
		</form>
		<?php
	}
}
new UN_Form;
?>

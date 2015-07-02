<?php

add_action( 'post_submitbox_misc_actions', 'userpro_edit_restrict' );
add_action( 'save_post', 'save_userpro_edit_restrict' );

function userpro_edit_restrict() {
    global $post;
	echo '<div class="misc-pub-section misc-pub-section-last" style="border-top: 1px solid #eee;">';
	wp_nonce_field( plugin_basename(__FILE__), 'userpro_edit_restrict_nonce' );
	$val = get_post_meta( $post->ID, '_userpro_edit_restrict', true ) ? get_post_meta( $post->ID, '_userpro_edit_restrict', true ) : 'none';
	echo '<input type="radio" name="userpro_edit_restrict" id="userpro_edit_restrict-none" value="none" '.checked($val,'none',false).' /> <label for="userpro_edit_restrict-none" class="select-it">'.__('No restriction','userpro').'</label><br />';
	echo '<input type="radio" name="userpro_edit_restrict" id="userpro_edit_restrict-true" value="true" '.checked($val,'true',false).'/> <label for="userpro_edit_restrict-true" class="select-it">'.__('Restricted to logged in members only','userpro').'</label>';
	echo '</div>';
}

function save_userpro_edit_restrict($post_id) {

    if (!isset($_POST['post_type']) )
        return $post_id;

    if ( !wp_verify_nonce( $_POST['userpro_edit_restrict_nonce'], plugin_basename(__FILE__) ) )
        return $post_id;

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
        return $post_id;

    if ( 'post' == $_POST['post_type'] && !current_user_can( 'edit_post', $post_id ) )
        return $post_id;
    
    if (!isset($_POST['userpro_edit_restrict']))
        return $post_id;
    else {
        $mydata = $_POST['userpro_edit_restrict'];
        update_post_meta( $post_id, '_userpro_edit_restrict', $_POST['userpro_edit_restrict'], get_post_meta( $post_id, '_userpro_edit_restrict', true ) );
    }

}
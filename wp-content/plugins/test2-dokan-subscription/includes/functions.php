<?php

/**
 * Get a sellers remaining product count
 *
 * @param  int $user_id
 * @return int
 */
function dps_user_remaining_product( $user_id ) {
    $remaining_product = (int) get_user_meta( $user_id, 'product_no_with_pack', true );

    return $remaining_product;
}
<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<tr>
	<td><input type="text" name="product_addon_option_label[<?php echo $loop; ?>][]" value="<?php echo esc_attr($option['label']) ?>" placeholder="<?php _e('Label', 'woocommerce-product-addons'); ?>" /></td>
	<td class="price_column"><input type="text" name="product_addon_option_price[<?php echo $loop; ?>][]" value="<?php echo esc_attr( wc_format_localized_price( $option['price'] ) ); ?>" placeholder="0.00" class="wc_input_price" /></td>

	<td class="minmax_column"><input type="number" name="product_addon_option_min[<?php echo $loop; ?>][]" value="<?php echo esc_attr( $option['min'] ) ?>" placeholder="N/A" min="0" step="any" /></td>

	<td class="minmax_column"><input type="number" name="product_addon_option_max[<?php echo $loop; ?>][]" value="<?php echo esc_attr( $option['max'] ) ?>" placeholder="N/A" min="0" step="any" /></td>

	<td class="actions"><button type="button" class="remove_addon_option button">x</button></td>
</tr>
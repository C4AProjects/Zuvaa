<hr>

<div class="form-group">
    <label class="col-md-4 control-label" for=""><?php _e( 'Enable Shipping?', 'dokan-shipping' ); ?></label>
    <div class="col-md-6">
        <?php dokan_post_input_box( $post->ID, '_dps_ship_enable', array('label' => __( 'Enable shipping options', 'dokan-shipping' ) ), 'checkbox' ); ?>
    </div>
</div>

<div class="form-group">
    <label class="col-md-4 control-label" for="_backorders"><?php _e( 'Processing Time', 'dokan-shipping' ); ?></label>
    <div class="col-md-5">
        <?php dokan_post_input_box(  $post->ID, '_dps_pt',
            array(
                'class' => 'form-control col-sm-5',
                'options' => $this->get_processing_times()
            ),
            'select' ); ?>
    </div>
</div>

<?php
$country_obj = new WC_Countries();
$countries = $country_obj->countries;
$from = get_post_meta( $post->ID, '_dps_from', true );
$rates = get_post_meta( $post->ID, '_dps_rates', true );
?>

<div class="form-group">
    <label class="col-md-4 control-label" for="_backorders"><?php _e( 'Ships from:', 'dokan-shipping' ); ?></label>

    <div class="col-md-5">
        <select name="_dps_from" class="form-control">
            <?php $this->country_dropdown( $countries, $from ); ?>
        </select>
    </div>
</div>

<div class="form-group">
    <label class="col-md-4 control-label">&nbsp;</label>
    <div class="col-md-8">

        <table class="dps-shipping-table table">
            <thead>
                <tr>
                    <th width="60%"><?php _e( 'Ships To:', 'dokan-shipping') ?></th>
                    <th><?php _e( 'Cost', 'dokan-shipping' ); ?></th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>

                <?php
                if ( $rates ) {
                    foreach ($rates as $country => $rate) {
                        ?>

                        <tr class="dps-shipping-location">
                            <td width="60%">
                                <select name="_dps_to[]" class="form-control">
                                    <?php $this->country_dropdown( $countries, $country, true ); ?>
                                </select>
                            </td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon"><?php echo get_woocommerce_currency_symbol(); ?></span>
                                    <input type="text" placeholder="9.99" class="form-control" name="_dps_to_price[]" value="<?php echo esc_attr( $rate ); ?>">
                                </div>
                            </td>
                            <td>
                                <a class="remove dps-remove" href="#"><span><?php _e( 'remove', 'dokan-shipping' ); ?></span></a>
                            </td>
                        </tr>

                        <?php
                    }
                } else {
                    ?>

                    <tr class="dps-shipping-location">
                        <td>
                            <select name="_dps_to[]" class="form-control">
                                <?php $this->country_dropdown( $countries, '', true ); ?>
                            </select>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-addon"><?php echo get_woocommerce_currency_symbol(); ?></span>
                                <input type="text" placeholder="9.99" class="form-control" name="_dps_to_price[]">
                            </div>
                        </td>
                        <td>
                            <a class="remove dps-remove" href="#"><span><?php _e( 'remove', 'dokan-shipping' ); ?></span></a>
                        </td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>

        <a href="#" class="btn btn-default dps-shipping-add"><?php _e( 'Add Location', 'dokan-shipping' ); ?></a>

    </div>
</div>

<div class="form-group">
    <label class="col-md-4 control-label" for=""><?php _e( 'Shipping Policy', 'dokan-shipping' ); ?></label>
    <div class="col-md-6">
        <?php dokan_post_input_box( $post->ID, '_dps_ship_policy', array( 'placeholder' => __( 'Enter your shipping policy...', 'dokan-shipping' ) ), 'textarea' ); ?>
    </div>
</div>

<div class="form-group">
    <label class="col-md-4 control-label" for=""><?php _e( 'Refund Policy', 'dokan-shipping' ); ?></label>
    <div class="col-md-6">
        <?php dokan_post_input_box( $post->ID, '_dps_refund_policy', array( 'placeholder' => __( 'Enter your refund policy...', 'dokan-shipping' ) ), 'textarea' ); ?>
    </div>
</div>

<script type="text/javascript">

    jQuery(function($){
        $('a.dps-shipping-add').on('click', function(e) {
            e.preventDefault();

            var row = $('tr.dps-shipping-location').first().clone().appendTo($('table.dps-shipping-table'));
            row.find('input,select').val('');
            row.find('a.dps-remove').show();
        });

        $('table.dps-shipping-table').on('click', 'a.dps-remove', function(e) {
            e.preventDefault();

            $(this).closest('tr').remove();
        });

        $('tr.dps-shipping-location').first().find('a.dps-remove').hide();
    });

</script>

<style>
    .dps-remove {
        background: none repeat scroll 0 0 #DC5D5D;
        border-radius: 500px;
        color: #FF0000;
        display: block;
        height: 20px;
        width: 20px;
        text-align: center;
    }

    .dps-remove:before {
        content: "x";
        color: #fff;
    }

    .dps-remove span {
        display: none;
    }
</style>
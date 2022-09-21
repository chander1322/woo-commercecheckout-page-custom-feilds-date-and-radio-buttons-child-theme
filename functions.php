<?php
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );
add_theme_support( 'post-formats', array ( 'aside', 'gallery', 'quote', 'image', 'video' ) );
function enqueue_parent_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
    wp_enqueue_script('jquery-balloon', get_stylesheet_directory_uri().'/assets/js/jquery-3.6.1.min.js');
    wp_enqueue_script('my_script', get_stylesheet_directory_uri() .'/assets/js/script.js');
}

add_action( 'woocommerce_checkout_after_customer_details', 'display_extra_fields_after_billing_address' , 10, 1 );
function display_extra_fields_after_billing_address () { ?>
    <h3>Delivery Options <sup>*</sup></h3>
    <p><input type="date" name="delivery_date" value="Select Date" required /> Select Date</p>
    <p><input type="radio" id="morning" name="delivery_option" value="Morning Delivery(7 to 10)" required /> Morning Delivery(7 to 10)</p>
    <p><input type="radio" id="evening" name="delivery_option" value="Evening Delivery(7 to 10)" /> Evening Delivery(7 to 10)</p>
    <p><input type="radio" id= "custom_time" class='times' name="delivery_option" value="choose custom time" /> Select Time</p>
    <div id="showTime" style="display:none"><input type="time" id='choose_time'  value="choose custom time" /> Select Custom Time</div>
  <?php 
}

add_action( 'woocommerce_checkout_update_order_meta', 'add_delivery_option_to_order' , 10, 1);
function add_delivery_option_to_order ( $order_id ) {
    if ( isset( $_POST ['delivery_option'] ) &&  '' != $_POST ['delivery_option'] ) {
        add_post_meta( $order_id, '_delivery_option',  sanitize_text_field( $_POST ['delivery_option'] ) );
    }
    if ( isset( $_POST ['delivery_date'] ) &&  '' != $_POST ['delivery_date'] ) {
        add_post_meta( $order_id, '_delivery_date',  sanitize_text_field( $_POST ['delivery_date'] ) );
    }
}

/*
    Include selected option in notification emails.
*/
// add_filter( 'woocommerce_email_order_meta_fields', 'add_delivery_option_to_emails' , 10, 3 );
// function add_delivery_option_to_emails ( $fields, $sent_to_admin, $order ) {
	
//     if( version_compare( get_option( 'woocommerce_version' ), '3.0.0', '>=' ) ) {            
// 	$order_id = $order->get_id();
//     } else {
// 	$order_id = $order->id;
//     }

//     $delivery_option = get_post_meta( $order_id, '_delivery_option', true );

//     if ( '' != $delivery_option ) {
// 	$fields[ 'Delivery Date' ] = array(
// 	    'label' => __( 'Delivery Option', 'delivery_option' ),
// 	    'value' => $delivery_option,
// 	);
//     }
//     return $fields;
// }

/*
    Validation nag.
    Remove this if the fields are to be optional.
*/
// function action_woocommerce_after_checkout_validation( $data, $errors ) { 
//     if ( empty( $_POST['delivery_option'] ) ) :
//         $errors->add( 'required-field', __( 'You have not chosen a delivery option.', 'woocommerce' ) );
//     endif;
// }
// add_action( 'woocommerce_after_checkout_validation', 'action_woocommerce_after_checkout_validation', 10, 2 );


/*
    Display field value on the order edit page.
*/
add_action( 'woocommerce_admin_order_data_after_billing_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );
function my_custom_checkout_field_display_admin_order_meta($order) {
    echo '<p><strong>'.__('Delivery Time').':</strong> <br/>' . get_post_meta( $order->get_id(), '_delivery_option', true ) . '</p>';
    echo  '<p><b>Delivery Date:</b><br>'.get_post_meta( $order->get_id(), '_delivery_date', true ) . '</p>';
}


function vicodemedia_display_order_data( $order_id ){  ?>
         <h2><?php _e( 'Delivery Information' ); ?></h2>
         <table class="shop_table shop_table_responsive additional_info">
             <tbody>
                 <tr>
                     <th><?php _e( 'Your Delivery Date:' ); ?></th>
                     <td><?php echo get_post_meta( $order_id, '_delivery_date', true ); ?></td>
                 </tr>
                 <tr>
                     <th><?php _e( 'Your Delivery Time:' ); ?></th>
                     <td><?php echo get_post_meta( $order_id, '_delivery_option', true ); ?></td>
                 </tr>
             
             </tbody>
         </table>
     <?php }
     add_action( 'woocommerce_thankyou', 'vicodemedia_display_order_data', 20 );
     add_action( 'woocommerce_view_order', 'vicodemedia_display_order_data', 20 );
?>
    <?php
?>
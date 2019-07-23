<?php
/**
 * User Invoices
 * return html
 */

global $current_user, $wp_roles,$userdata,$post,$paged;
$dir_obj	= new DocDirect_Scripts();
$user_identity	= $current_user->ID;
$url_identity	= $user_identity;

docdirect_init_popover_script();//Popover Scripts

if( isset( $_GET['identity'] ) && !empty( $_GET['identity'] ) ){
	$url_identity	= $_GET['identity'];
}

if (function_exists('fw_get_db_settings_option')) {
	$currency_select = fw_get_db_settings_option('currency_select');
	$currency_sign = fw_get_db_settings_option('currency_sign');
	$paypal_enable = fw_get_db_settings_option('paypal_enable');
	$authorize_enable = fw_get_db_settings_option('authorize_enable');
} else{
	$currency_select = 'USD';
	$currency_sign = '$';
	$paypal_enable = '';
	$authorize_enable = '';
}

if( empty( $currency_select ) ){
	$currency_select = 'USD';
	$currency_sign = '$';
}
?>

<div id="tg-dashboard-invoice" class="tg-dashboard-invoice tg-haslayout">
  <table class="col-md-12 table-striped ">
    <thead class="cf">
      <tr>
        <th><?php esc_html_e('Invoice ID','docdirect');?></th>
        <th class="numeric"><?php esc_html_e('Amount','docdirect');?></th>
      </tr>
    </thead>
    <tbody>
      <?php 
        
		if (empty($paged)) $paged = 1;
		$limit = get_option('posts_per_page');
		$show_posts    = get_option('posts_per_page') ? get_option('posts_per_page') : '-1';           
		$args = array('posts_per_page' => '-1', 
					  'post_type' => 'docdirectinvoices', 
					  'orderby' => 'ID', 
					  'post_status' => 'publish'
				);
		 
		$meta_query_args = array();
		$meta_query_args = array('relation' => 'AND',);
		$meta_query_args[] = array(
								'key' 	   => 'user_identity',
								'value' 	 => $url_identity,
								'compare'   => '=',
								'type'	  => 'NUMERIC'
							);
		$args['meta_query'] = $meta_query_args;
		
		$query 		= new WP_Query( $args );
		$count_post = $query->post_count;   
	
		$args = array('posts_per_page' => $show_posts, 
					  'post_type' => 'docdirectinvoices', 
					  'orderby' => 'ID',
					  'order' => 'DESC',
					  'paged' => $paged, 
					  'post_status' => 'publish'
				);
		$args['meta_query'] = $meta_query_args;
		$query 		= new WP_Query($args);
			 
			
		if( $query->have_posts() ):
		 while($query->have_posts()) : $query->the_post();
			global $post;
			$transaction_id = fw_get_db_post_option($post->ID, 'txn_id', true);
			$order_status = fw_get_db_post_option($post->ID, 'transaction_status', true);
			$package = fw_get_db_post_option($post->ID, 'item_name', true);
			$price = fw_get_db_post_option($post->ID, 'payment_gross', true);
			$payment_date = fw_get_db_post_option($post->ID, 'purchase_on', true);
			$payment_user = fw_get_db_post_option($post->ID, 'user_identity', true);
			$mc_currency = fw_get_db_post_option($post->ID, 'mc_currency', true);
			$payment_method = fw_get_db_post_option($post->ID, 'payment_method', true);
			$first_name = fw_get_db_post_option($post->ID, 'first_name', true);
			$last_name = fw_get_db_post_option($post->ID, 'last_name', true);
			$full_address = fw_get_db_post_option($post->ID, 'full_address', true);
			
			$payemnt_username	= 'NILL';
			if( !empty( $first_name ) || !empty( $last_name ) ){
				$payemnt_username	= $first_name.' '.$last_name;
			}
		?>
      <tr>
        <td data-title="Code">
        	<a href="javascript:;"><i class="fa fa-print"></i><?php echo esc_attr( $transaction_id );?> </a>
        </td>
        <td data-title="Change %" class="numeric">
			<?php echo esc_attr( $mc_currency.$price );?>
            <a data-placement="top" data-toggle="popover" data-trigger="focus" data-container="body" data-placement="top" type="button" data-html="true" href="javascript:;" id="login"><i class="fa fa-info-circle" aria-hidden="true"></i></a></li>
            <div id="popover-content" class="hide">
            	<div class="invoice-complete-info">
                    <div class="item-data">
                        <span class="invoice-head"><?php esc_html_e('Invoice ID','docdirect');?>:&nbsp;</span>
                        <span class="invoice-desc"><?php echo esc_attr( $transaction_id );?></span>
                    </div>
                    <div class="item-data">
                        <span class="invoice-head"><?php esc_html_e('Package Name','docdirect');?>:&nbsp;</span>
                        <span class="invoice-desc"><?php echo esc_attr( ucwords($package) );?></span>
                    </div>
                    <div class="item-data">
                        <span class="invoice-head"><?php esc_html_e('Amount','docdirect');?>:&nbsp;</span>
                        <span class="invoice-desc"><?php echo esc_attr( $mc_currency.$price );?></span>
                    </div>
                    <div class="item-data">
                        <span class="invoice-head"><?php esc_html_e('Payment Method','docdirect');?>:&nbsp;</span>
                        <span class="invoice-desc"><?php echo esc_attr( ucwords($payment_method) );?></span>
                    </div>
                    <div class="item-data">
                        <span class="invoice-head"><?php esc_html_e('Payment Status','docdirect');?>:&nbsp;</span>
                        <span class="invoice-desc"><?php echo esc_attr( ucwords($order_status) );?></span>
                    </div>
                    <div class="item-data">
                        <span class="invoice-head"><?php esc_html_e('Payment Date','docdirect');?>:&nbsp;</span>
                        <span class="invoice-desc"><?php echo esc_attr( date_i18n('d M, Y',strtotime( $payment_date ) ) );?></span>
                    </div>
                    <?php if( isset( $payemnt_username ) && !empty( $payemnt_username ) ) {?>
                    <div class="item-data">
                        <span class="invoice-head"><?php esc_html_e('Payer Name','docdirect');?>:&nbsp;</span>
                        <span class="invoice-desc"><?php echo esc_attr( $payemnt_username );?></span>
                    </div>
                    <?php }?>
                    <?php if( isset( $full_address ) && !empty( $full_address ) ) {?>
                        <div class="item-data">
                            <span class="invoice-head"><?php esc_html_e('Address','docdirect');?>:&nbsp;</span>
                            <span class="invoice-desc"><?php echo esc_attr( $full_address );?></span>
                        </div>
                    <?php }?>
            	</div>
                <script>
					jQuery(document).ready(function(e) {
						jQuery("[data-toggle=popover]").popover({
							html: true, 
							content: function() {
								  return jQuery('#popover-content').html();
								}
						});
					});
				</script>
            </div>
        </td>
      </tr>
      <?php 
	  endwhile; wp_reset_postdata(); 
	  else:
	  ?>
      <tr>
      	<td colspan="5">
			<?php DoctorDirectory_NotificationsHelper::informations(esc_html__('No payement made yet.','docdirect'));?>
        </td>
      </tr>
     <?php endif;?>
    </tbody>
  </table>
  <div class="col-md-xs-12">
  	<?php 
		if( $count_post > $limit ) {
			docdirect_prepare_pagination($count_post,$limit);
		}
	?>
  </div>
</div>
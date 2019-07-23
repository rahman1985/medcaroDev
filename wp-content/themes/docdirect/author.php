<?php
/**
 * The template for displaying user detail
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Doctor Directory
 */
global $current_user, $wp_roles,$userdata,$post,$wp_query,$current_user;
$current_author_profile = $wp_query->get_queried_object();
do_action('docdirect_update_profile_hits',$current_author_profile->ID); //Update Profile Hits
docdirect_set_user_views($current_author_profile->ID); //Update profile views
get_header();//Include Headers

if ( apply_filters( 'docdirect_is_visitor', $current_author_profile->ID ) === false ) {	
$avatar = apply_filters(
				'docdirect_get_user_avatar_filter',
				 docdirect_get_user_avatar(array('width'=>365,'height'=>365), $current_author_profile->ID),
				 array('width'=>365,'height'=>365) //size width,height
			);


$banner	= docdirect_get_user_banner(array('width'=>1920,'height'=>450), $current_author_profile->ID);

$current_date 	  = date('Y-m-d H:i:s');
$current_string	= strtotime( $current_date );
$featured_string   = $current_author_profile->user_featured;
$user_gallery	  = $current_author_profile->user_gallery;
$directory_type	= $current_author_profile->directory_type;
$contact_form	  = $current_author_profile->contact_form;
$uni_flag 		  = rand(1,9999);
$enable_login      = '';
$user_profile_specialities = '';
$education_switch  = '';

$facebook	  = isset( $current_author_profile->facebook ) ? $current_author_profile->facebook : '';
$twitter	   = isset( $current_author_profile->twitter ) ? $current_author_profile->twitter : '';
$linkedin	  = isset( $current_author_profile->linkedin ) ? $current_author_profile->linkedin : '';
$pinterest	 = isset( $current_author_profile->pinterest ) ? $current_author_profile->pinterest : '';
$google_plus   = isset( $current_author_profile->google_plus ) ? $current_author_profile->google_plus : '';
$instagram	 = isset( $current_author_profile->instagram ) ? $current_author_profile->instagram : '';
$tumblr	    = isset( $current_author_profile->tumblr ) ? $current_author_profile->tumblr : '';
$skype	  	 = isset( $current_author_profile->skype ) ? $current_author_profile->skype : '';
$professional_statements	  	 = isset( $current_author_profile->professional_statements ) ? $current_author_profile->professional_statements : '';

$schedule_time_format  = isset( $current_author_profile->time_format ) ? $current_author_profile->time_format : '12hour';

if(function_exists('fw_get_db_settings_option')) {
	$enable_login = fw_get_db_settings_option('enable_login', $default_value = null);
	$dir_map_marker    = fw_get_db_post_option($directory_type, 'dir_map_marker', true);
	$dir_map_marker_default = fw_get_db_settings_option('dir_map_marker');
	$education_switch    = fw_get_db_post_option($directory_type, 'education', true);
	$claims_switch    = fw_get_db_post_option($directory_type, 'claims', true);
	$experience_switch    = fw_get_db_post_option($directory_type, 'experience', true);
	$price_switch    = fw_get_db_post_option($directory_type, 'price_list', true);
	$reviews_switch    = fw_get_db_post_option($directory_type, 'reviews', true);
	$awards_switch    = fw_get_db_post_option($directory_type, 'awards', true);
	$user_profile_specialities    = fw_get_db_post_option($directory_type, 'user_profile_specialities', true);
	$theme_type = fw_get_db_settings_option('theme_type');
	$theme_color = fw_get_db_settings_option('theme_color');
}

if( !empty( $dir_map_marker['url'] ) ){
	$dir_map_marker  = $dir_map_marker['url'];
} else{
	if( !empty( $dir_map_marker_default['url'] ) ){
		$dir_map_marker	 = $dir_map_marker_default['url'];
	} else{
		$dir_map_marker	 	   = get_template_directory_uri().'/images/map-marker.png';
	}
}

$privacy		= docdirect_get_privacy_settings($current_author_profile->ID); //Privacy settings

docdirect_enque_map_library();//init Map
docdirect_enque_rating_library();//rating
wp_enqueue_script('intlTelInput');
wp_enqueue_style('intlTelInput');

$apointmentClass	= 'appointment-disabled';
if( !empty( $privacy['appointments'] )
	&& 
	$privacy['appointments'] == 'on'
 ) {
	$apointmentClass	= 'appointment-enabled';
	if( function_exists('docdirect_init_stripe_script') ) {
		//Strip Init
		docdirect_init_stripe_script();
	}
	
	if( isset( $current_user->ID ) 
	 && 
		$current_user->ID != $current_author_profile->ID
	){
		$apointmentClass	= 'appointment-enabled';
	} else{
		$apointmentClass	= 'appointment-disabled';
	}
}

$review_data	= docdirect_get_everage_rating ( $current_author_profile->ID );
docdirect_init_dir_map();//init Map
docdirect_enque_map_library();//init Map
$banner_parallax	= '';
if( !empty( $banner ) ){
	$banner_parallax	= 'data-appear-top-offset="600" data-parallax="scroll" data-image-src="'.$banner.'"';
}

//rating star color
if ( isset( $theme_type) && $theme_type === 'custom') {
	if ( !empty( $theme_color ) ) {
		$rating_color	= $theme_color;
	} else{
		$rating_color	= '#7dbb00';
	}
} else {
	$rating_color	= '#7dbb00';
}

$is_profile_visible	= 'yes';
if( apply_filters('docdirect_get_packages_setting','default') === 'custom' ){
	$package_expiry    = get_user_meta( $current_author_profile->ID, 'user_current_package_expiry', true);
	$current_package   = get_user_meta( $current_author_profile->ID, 'user_current_package', true);
	$current_date	= date('Y-m-d H:i:s');
	if( !empty( $package_expiry ) && $package_expiry >  strtotime($current_date) ){
		$is_profile_visible	= 'yes';
	} else{
		$is_profile_visible	= 'no';
	}
}
	
if( isset( $is_profile_visible ) && $is_profile_visible === 'yes' )	{
?>
	<div id="tg-userbanner" class="tg-userbanner tg-haslayout parallax-window" <?php echo ($banner_parallax);?>>
		<div class="container">
			<div class="row">
			<div class="col-sm-12 col-xs-12">
				<div class="tg-userbanner-content">

					<h1><?php echo esc_attr( $current_author_profile->first_name.' '.$current_author_profile->last_name );?></h1>
					<?php if( !empty( $current_author_profile->tagline ) ) {?>
					<span><?php echo esc_attr( $current_author_profile->tagline );?></span>
					<?php }?>
					<ul class="tg-likestars">
						<li><?php docdirect_get_rating_stars($review_data,'echo');?></li>
						<li><?php docdirect_get_wishlist_button($current_author_profile->ID,true);?></li>
						<li><span><?php echo intval( docdirect_get_user_views($current_author_profile->ID) );?>&nbsp;<?php esc_html_e('view(s)','docdirect');?></span></li> 

					</ul>

				</div>

			  </div>
			</div>
		</div>
	</div>
	<div class="container">
	  <div class="row">
		<div class="tg-userdetail <?php echo sanitize_html_class( $apointmentClass );?>">
		  <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
			<aside id="tg-sidebar" class="tg-sidebar">
			  <div class="tg-widget tg-widgetuserdetail">
				<figure class="tg-userimg"> 
					<a href="<?php echo esc_url(get_author_posts_url($current_author_profile->ID));?>"><img src="<?php echo esc_attr( $avatar );?>" alt="<?php echo esc_attr( $current_author_profile->first_name.' '.$current_author_profile->last_name );?>"></a>
				  <figcaption>
					<ul class="tg-featureverified">
					  <?php if( isset( $featured_string ) && $featured_string > $current_string ){?>
							<li class="tg-featuresicon"><a href="javascript:;"><i class="fa fa-bolt"></i><span><?php esc_html_e('featured','docdirect');?></span></a></li>
					  <?php }?>
					  <?php docdirect_get_verified_tag(true,$current_author_profile->ID,'simple');?>
					</ul>
				  </figcaption>
				</figure>

			</div>
				 <!-- <div class="tg-widget tg-widgetuserdetail">
				<div class="tg-usercontactinfo">
				  <h3><?php esc_html_e('Contact Details','docdirect');?></h3>
				  <ul class="tg-doccontactinfo">
					<?php if( !empty( $current_author_profile->user_address ) ) {?>
						<li> <i class="fa fa-map-marker"></i> <address><?php echo esc_attr( $current_author_profile->user_address );?></address> </li>
					<?php }?>
					<?php if( !empty( $current_author_profile->user_email ) 
							  &&
							  !empty( $privacy['email'] )
							  && 
							  $privacy['email'] == 'on'
					) {?>
						<li><i class="fa fa-envelope-o"></i><a href="mailto:<?php echo esc_attr( $current_author_profile->user_email );?>?subject:<?php esc_html_e('Hello','docdirect');?>"><?php echo esc_attr( $current_author_profile->user_email );?></a></li>
					<?php }?>
					<?php if( !empty( $current_author_profile->phone_number ) 
							  &&
							  !empty( $privacy['phone'] )
							  && 
							  $privacy['phone'] == 'on'
					) {?>
						<li> <i class="fa fa-phone"></i> <span><?php echo esc_attr( $current_author_profile->phone_number );?></span> </li>
					<?php }?>
					<?php if( !empty( $current_author_profile->fax ) ) {?>
						<li><i class="fa fa-fax"></i> <span><?php echo esc_attr( $current_author_profile->fax );?></span> </li>
					<?php }?>
					<?php if( !empty( $current_author_profile->skype ) ) {?> 
						<li><i class="fa fa-skype"></i><span><?php echo esc_attr( $current_author_profile->skype );?></span></li>
					<?php }?>
					<?php if( !empty( $current_author_profile->user_url ) ) {?>
						<li><i class="fa fa-link"></i><a href="<?php echo esc_url( $current_author_profile->user_url );?>" target="_blank"><?php echo docdirect_parse_url( $current_author_profile->user_url);?></a></li>
					<?php }?>
				  </ul>
				  <?php 
					if(  !empty( $facebook ) 
						 || !empty( $facebook ) 
						 || !empty( $twitter ) 
						 || !empty( $linkedin ) 
						 || !empty( $pinterest ) 
						 || !empty( $google_plus ) 
						 || !empty( $instagram ) 
						 || !empty( $tumblr ) 
						 || !empty( $skype ) 
					){?>
					<ul class="tg-socialicon-v2">
						<?php if(  !empty( $facebook ) ) {?>
							<li class="tg-facebook"><a href="<?php echo esc_url($facebook);?>"><i class="fa fa-facebook-f"></i></a></li>
						<?php }?>
						<?php if(  !empty( $twitter ) ) {?>
						<li class="tg-twitter"><a href="<?php echo esc_url($twitter);?>"><i class="fa fa-twitter"></i></a></li>
						<?php }?>
						<?php if(  !empty( $linkedin ) ) {?>
						<li class="tg-linkedin"><a href="<?php echo esc_url($linkedin);?>"><i class="fa fa-linkedin"></i></a></li>
						<?php }?>
						<?php if(  !empty( $pinterest ) ) {?>
						<li class="tg-pinterest"><a href="<?php echo esc_url($pinterest);?>"><i class="fa fa-pinterest-p"></i></a></li>
						<?php }?>
						<?php if(  !empty( $google_plus ) ) {?>
						<li class="tg-googleplus"><a href="<?php echo esc_url($google_plus);?>"><i class="fa fa-google-plus"></i></a></li>
						<?php }?>
						<?php if(  !empty( $instagram ) ) {?>
						<li class="tg-instagram"><a href="<?php echo esc_url($instagram);?>"><i class="fa fa-instagram"></i></a></li>
						<?php }?>
						<?php if(  !empty( $tumblr ) ) {?>
						<li class="tg-tumblr"><a href="<?php echo esc_url($tumblr);?>"><i class="fa fa-tumblr"></i></a></li>
						<?php }?>
						<?php if(  !empty( $skype ) ) {?>
						<li class="tg-skype"><a href="skype:<?php echo esc_attr($skype);?>?call"><i class="fa fa-skype"></i></a></li>
						<?php }?>
					</ul>
					<?php }?>
					<?php if( !empty( $current_author_profile->user_address ) ){?>
						<a class="tg-btn tg-btn-lg" href="http://maps.google.com/maps?saddr=&amp;daddr=<?php echo esc_attr( $current_author_profile->user_address );?>" target="_blank"><?php esc_html_e('get directions','docdirect');?></a>
					<?php }?>
					<?php
					if( apply_filters('docdirect_is_setting_enabled',$current_author_profile->ID,'schedules' ) === true ){
						if( !empty( $privacy['opening_hours'] )
							  && 
							  $privacy['opening_hours'] == 'on'
						 ) {?>
						<div class="tg-userschedule">
						<h3><?php esc_html_e('Schedule','docdirect');?></h3>
						<ul>
						<?php 
							$week_array	= docdirect_get_week_array();
							$db_schedules	= array();
							if( isset( $current_author_profile->schedules ) && !empty( $current_author_profile->schedules ) ){
								$db_schedules	= $current_author_profile->schedules;
							}

							//Time format
							if( isset( $schedule_time_format ) && $schedule_time_format === '24hour' ){
								$time_format	= 'G:i';
							} else{
								$time_format	= 'g:i A';
							}

							$date_prefix	= date('D');
							if( isset( $week_array ) && !empty( $week_array ) ) {
							foreach( $week_array as $key => $value ){
								$start_time_formate	 = '';
								$end_time_formate	   = '';
								$start_time  = $db_schedules[$key.'_start'];
								$end_time	= $db_schedules[$key.'_end'];

								if( !empty( $start_time ) ){
									$start_time_formate	= date_i18n( $time_format, strtotime( $start_time ) );
								}


								if( isset( $end_time ) && !empty( $end_time ) ){ 
									$end_time_formate	= date_i18n( $time_format, strtotime( $end_time ) );
									$end_time_formate	= docdirect_date_24midnight($time_format,strtotime( $end_time ));
								}

								//Active day
								$active	= '';
								if( strtolower( $date_prefix ) == $key ){
									$active	= 'current';
								}

								//
								if( !empty( $start_time_formate ) && $end_time_formate ) {
									$data_key	= $start_time_formate.' - '.$end_time_formate;
								} else if( !empty( $start_time_formate ) ){
									$data_key	= $start_time_formate;
								} else if( !empty( $end_time_formate ) ){
									$data_key	= $end_time_formate;
								} else{
									$data_key	= esc_html__('Closed','docdirect');
								}
							?>
							<li class="<?php echo sanitize_html_class( $active );?>"><a href="javascript:;" data-type="<?php echo esc_attr( $data_key );?>"><span><?php echo esc_attr( $value );?></span><em><?php echo esc_attr( $data_key );?></em></a></li>

						<?php }}?>
					</ul>
					</div>
					<?php }}?>
				  <?php docdirect_prepare_profile_social_sharing($avatar,$current_author_profile->ID,$current_author_profile->description);?>
				  <?php 
					if( !empty( $privacy['contact_form'] )
					  && 
						$privacy['contact_form'] == 'on'
					) {
				   ?>
				  <div class="tg-usercontatnow">
					<h3><?php esc_html_e('contact now','docdirect');?></h3>
					<div class="tg-widgetcontent doc-contact">
						<form class="contact_form tg-usercontactform">
							<fieldset>
								<div class="row">
									<div class="col-sm-12 col-xs-12">
										<div class="form-group">
											<input type="text" name="username" placeholder="<?php esc_attr_e('Name','docdirect');?>" class="form-control">
										</div>
									</div>
									<div class="col-sm-12 col-xs-12">
										<div class="form-group">
											<input type="email" name="useremail" placeholder="<?php esc_attr_e('Email','docdirect');?>" class="form-control">
										</div>
									</div>
									<div class="col-sm-12 col-xs-12">
										<div class="form-group">
											<input type="text" name="userphone" placeholder="<?php esc_attr_e('Number','docdirect');?>" class="form-control">
										</div>
									</div>
									<div class="col-sm-12 col-xs-12">
										<div class="form-group">
											<input type="text" name="usersubject" placeholder="<?php esc_attr_e('Subject','docdirect');?>" class="form-control">
										</div>
									</div>
									<div class="col-sm-12 col-xs-12">
										<div class="form-group">
											<textarea name="user_description" placeholder="<?php esc_attr_e('Message','docdirect');?>" class="form-control"></textarea>
										</div>
									</div>	
									<div class="col-sm-12 col-xs-12">
										<input type="hidden" name="email_to" value="<?php echo esc_attr( $current_author_profile->user_email );?>" class="form-control">
										<button class="tg-btn contact_me" type="submit"><?php esc_html_e('Send','docdirect');?></button>
										<?php wp_nonce_field('docdirect_contact_me', 'user_security'); ?>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				  </div>
				  <?php }?>
				</div>
			  </div> -->
			  <?php 
				if( !empty( $claims_switch )
					&&
					$claims_switch === 'enable'

				){
					if( isset( $current_user->ID ) 
						&& 
							$current_user->ID != $current_author_profile->ID
						&&
							is_user_logged_in()        
					){
					?>
					<!-- <div class="claim-box tg-widget tg-claimreport">
						<div class="tg-widgetcontent doc-claim">
							<h3><?php esc_html_e('Claim/Report This User','docdirect');?></h3>
							<form class="tg-haslayout claim_form tg-claimform">
								<fieldset>
									<div class="form-group">
										<input type="text" name="subject" placeholder="<?php esc_attr_e('Subject*','docdirect');?>" class="form-control">
									</div>
									<div class="form-group">
										<textarea name="report" placeholder="<?php esc_attr_e('Report Detail','docdirect');?>" class="form-control"></textarea>
									</div>
									<button class="tg-btn report_now" type="submit"><?php esc_html_e('report now','docdirect');?></button>
									<?php wp_nonce_field('docdirect_claim', 'security'); ?>
									<input type="hidden" name="user_to" class="user_to" value="<?php echo esc_attr( $current_author_profile->ID );?>" />
								</fieldset>
							</form>
						</div>
					</div> -->
					<?php } else if( $current_user->ID != $current_author_profile->ID ){?>
						<div class="claim-box">
							<a class="tg-btn tg-btn-lg"data-toggle="modal" data-target=".tg-user-modal" href="javascript:;">
								<i class="fa fa-exclamation-triangle"></i>
								<?php esc_html_e('Claim This User','docdirect');?>
							</a>
						</div>
			  <?php }}?>
			  <?php if (is_active_sidebar('user-page-sidebar')) {?>
				  <div class="tg-doctors-list tg-haslayout">
					<?php dynamic_sidebar('user-page-sidebar'); ?>
				  </div>
			   <?php }?>
			</aside>
		  </div>
		  <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
			<div class="tg-haslayout">
			  <div class="tg-userbanner-content">
					<h1><?php echo esc_attr( $current_author_profile->first_name.' '.$current_author_profile->last_name );?></h1>
					<?php if( !empty( $current_author_profile->tagline ) ) {?>
					<span><?php echo esc_attr( $current_author_profile->tagline );?></span>

					<?php }?>
					<ul class="tg-likestars">
						<?php if( isset( $reviews_switch ) && $reviews_switch === 'enable' ){?>
							<li><?php docdirect_get_rating_stars($review_data,'echo');?></li>
						<?php }?>
						<li><?php docdirect_get_wishlist_button($current_author_profile->ID,true);?></li>
						<li><span><?php echo intval( docdirect_get_user_views($current_author_profile->ID) );?>&nbsp;<?php esc_html_e('view(s)','docdirect');?></span></li> 
					</ul>
					<!-- <?php 
					if( apply_filters('docdirect_is_setting_enabled',$current_author_profile->ID,'appointments' ) === true ){ 
						 if( !empty( $privacy['appointments'] )
							  && 
							  $privacy['appointments'] == 'on'
						 ) {
							 if( isset( $current_user->ID ) 
								 && 
									$current_user->ID != $current_author_profile->ID
								 &&
									is_user_logged_in()
							 ){
							?>
								<button class="tg-btn tg-btn-lg make-appointment-btn" type="button" data-toggle="modal" data-target=".tg-appointmentpopup"><?php esc_html_e('MAKE AN APPOINTMENT!','docdirect');?></button>
							<?php 
							}  else if( $current_user->ID != $current_author_profile->ID ){?>
								<button class="tg-btn tg-btn-lg make-appointment-btn" type="button" data-toggle="modal" data-target=".tg-user-modal"><?php esc_html_e('MAKE AN APPOINTMENT!','docdirect');?></button>
					<?php }}}?> -->
									<div class="details_user">
				<!-- <h3><?php esc_html_e('Contact Details','docdirect');?></h3> -->
				  <ul class="tg-doccontactinfo">
					<?php if( !empty( $current_author_profile->user_address ) ) {?>
						<li> <i class="fa fa-map-marker"></i> <address><?php echo esc_attr( $current_author_profile->user_address );?></address> </li>
					<?php }?>
					<?php if( !empty( $current_author_profile->user_email ) 
							  &&
							  !empty( $privacy['email'] )
							  && 
							  $privacy['email'] == 'on'
					) {?>
						<li><i class="fa fa-envelope-o"></i><a href="mailto:<?php echo esc_attr( $current_author_profile->user_email );?>?subject:<?php esc_html_e('Hello','docdirect');?>"><?php echo esc_attr( $current_author_profile->user_email );?></a></li>
					<?php }?>
					<?php if( !empty( $current_author_profile->phone_number ) 
							  &&
							  !empty( $privacy['phone'] )
							  && 
							  $privacy['phone'] == 'on'
					) {?>
						<li> <i class="fa fa-phone"></i> <span><?php echo esc_attr( $current_author_profile->phone_number );?></span> </li>
					<?php }?>
					<?php if( !empty( $current_author_profile->fax ) ) {?>
						<li><i class="fa fa-fax"></i> <span><?php echo esc_attr( $current_author_profile->fax );?></span> </li>
					<?php }?>
					<!-- <?php if( !empty( $current_author_profile->skype ) ) {?> 
						<li><i class="fa fa-skype"></i><span><?php echo esc_attr( $current_author_profile->skype );?></span></li>
					<?php }?> -->
					<!-- <?php if( !empty( $current_author_profile->user_url ) ) {?>
						<li><i class="fa fa-link"></i><a href="<?php echo esc_url( $current_author_profile->user_url );?>" target="_blank"><?php echo docdirect_parse_url( $current_author_profile->user_url);?></a></li>
					<?php }?> -->
				  </ul>
				  <?php 
					if(  !empty( $facebook ) 
						 || !empty( $facebook ) 
						 || !empty( $twitter ) 
						 || !empty( $linkedin ) 
						 || !empty( $pinterest ) 
						 || !empty( $google_plus ) 
						 || !empty( $instagram ) 
						 || !empty( $tumblr ) 
						 || !empty( $skype ) 
					){?>
					<ul class="tg-socialicon-v2">
						<?php if(  !empty( $facebook ) ) {?>
							<li class="tg-facebook"><a href="<?php echo esc_url($facebook);?>"><i class="fa fa-facebook-f"></i></a></li>
						<?php }?>
						<?php if(  !empty( $twitter ) ) {?>
						<li class="tg-twitter"><a href="<?php echo esc_url($twitter);?>"><i class="fa fa-twitter"></i></a></li>
						<?php }?>
						<?php if(  !empty( $linkedin ) ) {?>
						<li class="tg-linkedin"><a href="<?php echo esc_url($linkedin);?>"><i class="fa fa-linkedin"></i></a></li>
						<?php }?>
						<!-- <?php if(  !empty( $pinterest ) ) {?>
						<li class="tg-pinterest"><a href="<?php echo esc_url($pinterest);?>"><i class="fa fa-pinterest-p"></i></a></li>
						<?php }?> -->
						<?php if(  !empty( $google_plus ) ) {?>
						<li class="tg-googleplus"><a href="<?php echo esc_url($google_plus);?>"><i class="fa fa-google-plus"></i></a></li>
						<?php }?>
						<!-- <?php if(  !empty( $instagram ) ) {?>
						<li class="tg-instagram"><a href="<?php echo esc_url($instagram);?>"><i class="fa fa-instagram"></i></a></li>
						<?php }?>
						<?php if(  !empty( $tumblr ) ) {?>
						<li class="tg-tumblr"><a href="<?php echo esc_url($tumblr);?>"><i class="fa fa-tumblr"></i></a></li>
						<?php }?> -->
						<?php if(  !empty( $skype ) ) {?>
						<li class="tg-skype"><a href="skype:<?php echo esc_attr($skype);?>?call"><i class="fa fa-skype"></i></a></li>
						<?php }?>
					</ul>
					<?php }?>
				</div>
				</div>
			  <?php if( !empty( $current_author_profile->latitude ) && !empty( $current_author_profile->longitude ) ) {?>
			  <div class="tg-section-map">
			  <div id="map_canvas" class="tg-location-map tg-haslayout"></div>
			  <?php do_action('docdirect_map_controls');?>
			  <?php
				$directories	= array();
				$directories_array	= array();
				$directories['status']	= 'found';
				$directories_array['latitude']	= $current_author_profile->latitude;
				$directories_array['longitude']	= $current_author_profile->longitude;
				$directories_array['title']	= $current_author_profile->display_name;
				$directories_array['name']	 = $current_author_profile->first_name.' '.$current_author_profile->last_name;
				$directories_array['email']	 		 = $current_author_profile->user_email;
				$directories_array['phone_number']	 = $current_author_profile->phone_number;
				$directories_array['address']	 = $current_author_profile->user_address;
				$directories_array['group']	= '';
				$directories_array['icon']	 	   = $dir_map_marker;
				$avatar = apply_filters(
											'docdirect_get_user_avatar_filter',
											 docdirect_get_user_avatar(array('width'=>150,'height'=>150), $current_author_profile->ID),
											 array('width'=>150,'height'=>150) //size width,height
										);

				$infoBox	= '<div class="tg-mapmarker">';
				$infoBox	.= '<figure><img width="60" heigt="60" src="'.esc_url( $avatar ).'" alt="'.esc_attr__('User','docdirect').'"></figure>';
				$infoBox	.= '<div class="tg-mapmarkercontent">';
				$infoBox	.= '<h3><a href="'.get_author_posts_url($current_author_profile->ID).'">'.$directories_array['name'].'</a></h3>';

				if( !empty( $current_author_profile->tagline ) ) {
					$infoBox	.= '<span>'.$current_author_profile->tagline.'</span>';
				}


				$infoBox	.= '<ul class="tg-likestars">';

				if( isset( $reviews_switch ) && $reviews_switch === 'enable' && !empty( $review_data )){
					$infoBox	.= '<li>'.docdirect_get_rating_stars($review_data,'return','hide').'</li>';
				}
				$infoBox	.= '<li>'.docdirect_get_wishlist_button($current_author_profile->ID,false).'</li>';
				$infoBox	.= '<li>'.docdirect_get_user_views($current_author_profile->ID).'&nbsp;'.esc_html__('view(s)','docdirect').'</li>';

				$infoBox	.= '</ul>';
				$infoBox	.= '</div>';

				$directories_array['html']['content']	= $infoBox;
				$directories['users_list'][]	= $directories_array;
			   ?>
			   <script>
					jQuery(document).ready(function() {
						docdirect_init_detail_map_script(<?php echo json_encode( $directories );?>);
					});	
			  </script>
			  <?php if( !empty( $current_author_profile->user_address ) ){?>
						<a class="tg-btn tg-btn-lg" href="http://maps.google.com/maps?saddr=&amp;daddr=<?php echo esc_attr( $current_author_profile->user_address );?>" target="_blank"><?php esc_html_e('get directions','docdirect');?></a>
					<?php }?>
			  </div> 
			  <?php }?>

			  <?php if (is_active_sidebar('user-page-top')) {?>
				  <div class="tg-doctors-list tg-haslayout user-ad-top">
					<?php dynamic_sidebar('user-page-top'); ?>
				  </div>
			  <?php }?>
			  <!-- <div class="tg-userschedule">
						<h3><?php esc_html_e('Schedule','docdirect');?></h3>
						<ul>
						<?php 
							$week_array	= docdirect_get_week_array();
							$db_schedules	= array();
							if( isset( $current_author_profile->schedules ) && !empty( $current_author_profile->schedules ) ){
								$db_schedules	= $current_author_profile->schedules;
							}

							//Time format
							if( isset( $schedule_time_format ) && $schedule_time_format === '24hour' ){
								$time_format	= 'G:i';
							} else{
								$time_format	= 'g:i A';
							}

							$date_prefix	= date('D');
							if( isset( $week_array ) && !empty( $week_array ) ) {
							foreach( $week_array as $key => $value ){
								$start_time_formate	 = '';
								$end_time_formate	   = '';
								$start_time  = $db_schedules[$key.'_start'];
								$end_time	= $db_schedules[$key.'_end'];

								if( !empty( $start_time ) ){
									$start_time_formate	= date_i18n( $time_format, strtotime( $start_time ) );
								}


								if( isset( $end_time ) && !empty( $end_time ) ){ 
									$end_time_formate	= date_i18n( $time_format, strtotime( $end_time ) );
									$end_time_formate	= docdirect_date_24midnight($time_format,strtotime( $end_time ));
								}

								//Active day
								$active	= '';
								if( strtolower( $date_prefix ) == $key ){
									$active	= 'current';
								}

								//
								if( !empty( $start_time_formate ) && $end_time_formate ) {
									$data_key	= $start_time_formate.' - '.$end_time_formate;
								} else if( !empty( $start_time_formate ) ){
									$data_key	= $start_time_formate;
								} else if( !empty( $end_time_formate ) ){
									$data_key	= $end_time_formate;
								} else{
									$data_key	= esc_html__('Closed','docdirect');
								}
							?>
							<li class="<?php echo sanitize_html_class( $active );?>"><a href="javascript:;" data-type="<?php echo esc_attr( $data_key );?>"><span><?php echo esc_attr( $value );?></span><em><?php echo esc_attr( $data_key );?></em></a></li>

						<?php }}?>
					</ul>
					</div> -->
			 </div>
		  
			</div>
			<?php 
$user = wp_get_current_user()->user_login;
//print_r($user) ;
?>
			<?php //print_r($current_author_profile); ?>
			<?php //echo $directory_type; ?>
			<?php if ($directory_type == '123') { ?>
				<div class="col-md-12">
				<div class="tg-btn contact_me col-md-2"><a href="https://www.medcaro.com/dev/product-tag/<?php echo esc_attr( $current_author_profile->user_login ); ?>"> Store</a></div>
				<div class="col-md-1"></div>
				<?php if ($user == $current_author_profile->user_login) {?>
					<div class="tg-btn contact_me col-md-2"><a href="https://www.medcaro.com/dev/add-product/">Add Product</a></div>
				<?php } ?>
			
		</div>
			<?php } ?>
			
			<div class="tg-usercontactinfo">
				  
					
					<?php
					if( apply_filters('docdirect_is_setting_enabled',$current_author_profile->ID,'schedules' ) === true ){
						if( !empty( $privacy['opening_hours'] )
							  && 
							  $privacy['opening_hours'] == 'on'
						 ) {?>
						<div class="tg-userschedule">
						<h3><?php esc_html_e('Schedule','docdirect');?></h3>
						<!-- Table -->
						<!-- <table class="scheduleTable">

						</table> -->
						<!-- Table -->
						<ul class="schedule_list">
						<?php 
							$week_array	= docdirect_get_week_array();
							$db_schedules	= array();
							if( isset( $current_author_profile->schedules ) && !empty( $current_author_profile->schedules ) ){
								$db_schedules	= $current_author_profile->schedules;
							}

							//Time format
							if( isset( $schedule_time_format ) && $schedule_time_format === '24hour' ){
								$time_format	= 'G:i';
							} else{
								$time_format	= 'g:i A';
							}

							$date_prefix	= date('D');
							if( isset( $week_array ) && !empty( $week_array ) ) {
							foreach( $week_array as $key => $value ){
								$start_time_formate	 = '';
								$end_time_formate	   = '';
								$start_time  = $db_schedules[$key.'_start'];
								$end_time	= $db_schedules[$key.'_end'];

								if( !empty( $start_time ) ){
									$start_time_formate	= date_i18n( $time_format, strtotime( $start_time ) );
								}


								if( isset( $end_time ) && !empty( $end_time ) ){ 
									$end_time_formate	= date_i18n( $time_format, strtotime( $end_time ) );
									$end_time_formate	= docdirect_date_24midnight($time_format,strtotime( $end_time ));
								}

								//Active day
								$active	= '';
								if( strtolower( $date_prefix ) == $key ){
									$active	= 'current';
								}

								//
								if( !empty( $start_time_formate ) && $end_time_formate ) {
									$data_key	= $start_time_formate.' - '.$end_time_formate;
								} else if( !empty( $start_time_formate ) ){
									$data_key	= $start_time_formate;
								} else if( !empty( $end_time_formate ) ){
									$data_key	= $end_time_formate;
								} else{
									$data_key	= esc_html__('Closed','docdirect');
								}
							?>
							<li class="<?php echo sanitize_html_class( $active );?>"><a href="javascript:;" data-type="<?php echo esc_attr( $data_key );?>"><span><?php echo esc_attr( $value );?></span><em><?php echo esc_attr( $data_key );?></em></a></li>

						<?php }}?>
					</ul>
					</div>
					<?php }}?>
				  <!-- <?php docdirect_prepare_profile_social_sharing($avatar,$current_author_profile->ID,$current_author_profile->description);?> -->
				  <?php 
					 if( apply_filters('docdirect_is_setting_enabled',$current_author_profile->ID,'appointments' ) === true ){ 
						 if( !empty( $privacy['appointments'] )
							  && 
							  $privacy['appointments'] == 'on'
						 ) {
							 if( isset( $current_user->ID ) 
								 && 
									$current_user->ID != $current_author_profile->ID
								 &&
									is_user_logged_in()
							 ){
							?>
								<button class="tg-btn tg-btn-lg make-appointment-btn" type="button" data-toggle="modal" data-target=".tg-appointmentpopup"><?php esc_html_e('MAKE AN APPOINTMENT!','docdirect');?></button>
							<?php 
							}  else if( $current_user->ID != $current_author_profile->ID ){?>
								<button class="tg-btn tg-btn-lg make-appointment-btn" type="button" data-toggle="modal" data-target=".tg-user-modal"><?php esc_html_e('MAKE AN APPOINTMENT!','docdirect');?></button>
					<?php }}}?>
					<?php if ($directory_type == '127') { ?>
<!-- Start of Async Drift Code -->
<script>
"use strict";

!function() {
  var t = window.driftt = window.drift = window.driftt || [];
  if (!t.init) {
    if (t.invoked) return void (window.console && console.error && console.error("Drift snippet included twice."));
    t.invoked = !0, t.methods = [ "identify", "config", "track", "reset", "debug", "show", "ping", "page", "hide", "off", "on" ], 
    t.factory = function(e) {
      return function() {
        var n = Array.prototype.slice.call(arguments);
        return n.unshift(e), t.push(n), t;
      };
    }, t.methods.forEach(function(e) {
      t[e] = t.factory(e);
    }), t.load = function(t) {
      var e = 3e5, n = Math.ceil(new Date() / e) * e, o = document.createElement("script");
      o.type = "text/javascript", o.async = !0, o.crossorigin = "anonymous", o.src = "https://js.driftt.com/include/" + n + "/" + t + ".js";
      var i = document.getElementsByTagName("script")[0];
      i.parentNode.insertBefore(o, i);
    };
  }
}();
drift.SNIPPET_VERSION = '0.3.1';
drift.load('4kvwnxpua55p');
</script>
<!-- End of Async Drift Code -->
						<?php } ?>
						<div>
	<?php if ($user == $current_author_profile->user_login) {?>
					<div class="tg-btn contact_me col-md-2"><a href="https://www.medcaro.com/dev/add-blog/">Add Blog</a></div>
				<?php } ?>
			</div>
					<?php if ($directory_type == '127') { ?>
				<div class="col-md-12" style="padding: 0px !important;">
				<!-- <div class="col-md-6" style="padding: 0px !important;">
					<div id="otEmbedContainer" style="width:500px; height:300px"></div> 
					<script src="https://tokbox.com/embed/embed/ot-embed.js?embedId=2dbab29d-e404-436d-9d42-12c7d404eb2c&amp;room=DEFAULT_ROOM"></script>
				</div> -->
		</div>
			<?php } ?>
				  <?php 
					if( !empty( $privacy['contact_form'] )
					  && 
						$privacy['contact_form'] == 'on'
					) {
				   ?>
				  <div class="tg-usercontatnow">
					<h3><?php esc_html_e('Contact Now','docdirect');?></h3>
					<div class="tg-widgetcontent doc-contact">
						<form class="contact_form tg-usercontactform">
							<fieldset>
								<div class="row">
									<div class="col-sm-12 col-xs-12">
										<div class="form-group">
											<input type="text" name="username" placeholder="<?php esc_attr_e('Name','docdirect');?>" class="form-control">
										</div>
									</div>
									<div class="col-sm-12 col-xs-12">
										<div class="form-group">
											<input type="email" name="useremail" placeholder="<?php esc_attr_e('Email','docdirect');?>" class="form-control">
										</div>
									</div>
									<div class="col-sm-12 col-xs-12">
										<div class="form-group">
											<input type="text" name="userphone" placeholder="<?php esc_attr_e('Number','docdirect');?>" class="form-control">
										</div>
									</div>
									<div class="col-sm-12 col-xs-12">
										<div class="form-group">
											<input type="text" name="usersubject" placeholder="<?php esc_attr_e('Subject','docdirect');?>" class="form-control">
										</div>
									</div>
									<div class="col-sm-12 col-xs-12">
										<div class="form-group">
											<textarea name="user_description" placeholder="<?php esc_attr_e('Message','docdirect');?>" class="form-control"></textarea>
										</div>
									</div>	
									<div class="col-sm-12 col-xs-12">
										<input type="hidden" name="email_to" value="<?php echo esc_attr( $current_author_profile->user_email );?>" class="form-control">
										<button class="tg-btn contact_me" type="submit"><?php esc_html_e('Send','docdirect');?></button>
										<?php wp_nonce_field('docdirect_contact_me', 'user_security'); ?>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				  </div>
				  <?php }?>
				</div>
				 <div class="tg-aboutuser">
				<div class="tg-userheading">
				  <h2><?php esc_html_e('About','docdirect');?>&nbsp;<?php echo esc_attr( $current_author_profile->first_name.' '.$current_author_profile->last_name );?></h2>

				</div>
				<?php if( !empty( $current_author_profile->description ) ) {?>
				  <div class="tg-description">
					<p><?php echo wpautop( $current_author_profile->description );?></p>
				  </div>
				<?php }?>
				<?php if( !empty( $professional_statements ) ){?>
					<div class="professional-statements">
						<?php echo do_shortcode( nl2br( $professional_statements));?>
					</div>
				<?php }?>
			  </div>

			  <!--Languages-->
			  <?php  if( !empty( $current_author_profile->languages ) ) {?>
				  <div class="tg-honourawards tg-listview-v3 user-section-style">
					<div class="tg-userheading">
					  <h2><?php esc_html_e('Languages','docdirect');?></h2>
					</div>
					<div class="tg-doctor-profile">
						  <ul class="tg-tags">
							<?php 
							if( !empty( $current_author_profile->languages ) ) {
								$languages	= docdirect_prepare_languages();
								$user_languages	 = array();
								foreach( $current_author_profile->languages as $key => $value ){
								?>
							<li><a href="javascript:;" class="tg-btn"><?php echo esc_attr( $languages[$key] );?></a></li>
							<?php }} else{?>
							 <li><a href="javascript:;" class="tg-btn"><?php esc_html_e( 'No Languages selected yet.','docdirect' );?></a></li>
							<?php }?>
						  </ul>
					  </div>
				  </div>
			  <?php }?>

			  <!--Specialities-->
			  <?php if( !empty( $current_author_profile->user_profile_specialities ) ) {?>
				  <div class="tg-honourawards tg-listview-v3 user-section-style sp-icon-wrap">
					<div class="tg-userheading">
					  <h2><?php esc_html_e('Specialties','docdirect');?></h2>
					</div>
					<div class="tg-doctor-profile">
						  <ul class="tg-tags">
							  <?php
								do_action('enqueue_unyson_icon_css');														
								foreach( $current_author_profile->user_profile_specialities as $key => $value ){
									$get_speciality_term = get_term_by('slug', $key, 'specialities');
									$speciality_title = '';
									$term_id = '';
									if (!empty($get_speciality_term)) {
										$speciality_title = $get_speciality_term->name;
										$term_id = $get_speciality_term->term_id;
									}

									$speciality_meta = array();
									if (function_exists('fw_get_db_term_option')) {
										$speciality_meta = fw_get_db_term_option($term_id, 'specialities');
									}

									$speciality_icon = array();
									if (!empty($speciality_meta['icon']['icon-class'])) {
										$speciality_icon = $speciality_meta['icon']['icon-class'];
									}
								 ?>
								<li>
									<div class="specialities-wrap">
										<span class="icon-sp">
											<?php 
											if ( isset($speciality_meta['icon']['type']) && $speciality_meta['icon']['type'] === 'icon-font') {
												if (!empty($speciality_icon)) { ?>
													<i class="<?php echo esc_attr($speciality_icon); ?>"></i>
												<?php 
												}
											} else if ( isset($speciality_meta['icon']['type']) && $speciality_meta['icon']['type'] === 'custom-upload') {
												if (!empty($speciality_meta['icon']['url'])) {
												?>
												<img src="<?php echo esc_url($speciality_meta['icon']['url']);?>">
											<?php }}?>
										</span>                    		
										<span><?php echo esc_attr( $value );?></span>
									</div>
								</li>
							  <?php }?>
						  </ul>
					  </div>
				  </div>
			  <?php }?>

			  <!--In network Insurance-->
			  <?php  
			  if( !empty( $current_author_profile->insurance ) ) {
				if( apply_filters('docdirect_is_setting_enabled',$current_author_profile->ID,'insurance' ) === true ){?>
				<div class="tg-innetworkinsurrance tg-tagsstyle tg-listview-v3 user-section-style">
					<div class="tg-userheading">
						<h2><?php esc_html_e('In-Network Insurance','docdirect');?><?php if( !empty( $insurance_hint ) ){?><span><a class="info-tooltip" href="javascript:;" data-toggle="tooltip" data-placement="right" title="<?php echo esc_attr( $insurance_hint );?>"><i class="fa fa-info-circle" aria-hidden="true"></i></a></span><?php }?></h2>
					</div>
					<div class="see-more-info">
						<p><a href="javascript:;"><?php esc_html_e('See which insurance(s) covers your care.','docdirect');?>
						<span><i class="fa fa-plus"></i></span></a></p>
					</div>
					<ul class="elm-display-none insurance-wrap">
						<?php
						foreach( $current_author_profile->insurance as $key => $value ){
							$insurance	    = get_term_by( 'slug', $value, 'insurance');
							if( !empty( $insurance ) ) {
								$insurance_logo = get_term_meta( $insurance->term_id, 'insurance_logo', true );
								if( !empty( $insurance->name ) ){
							?>
							<li>
								<span><?php echo esc_attr( $insurance->name );?></span>
								<?php if( !empty( $insurance_logo ) ) {?>
									<span class="insurance_logo"><img src="<?php echo esc_url( $insurance_logo );?>"></span>
								<?php }?>
							</li>
						<?php }}}?>
					</ul>
				</div>
			  <?php }}?>

			  <!--Experience-->
			  <?php 
			  if( isset( $experience_switch ) 
				  && $experience_switch === 'enable' 
				  && !empty( $current_author_profile->experience )
			  ){?>
			  <div class="tg-userexperience">
				<div class="tg-userheading">
				  <h2><i class="fa fa-briefcase"></i><?php esc_html_e('Experience','docdirect');?></h2>
				</div>
				<ul>
				<?php 
					foreach( $current_author_profile->experience as $key => $value ){
						$start_year	= '';
						$end_year	= '';
						$period	= '';
						if( !empty( $value['start_date'] ) || !empty( $value['end_date'] ) ){
							if( !empty( $value['start_date'] ) ){
								$start_year	= date_i18n('M, Y',strtotime( $value['start_date']));
							}

							if( !empty( $value['end_date'] ) ){
								$end_year	= date_i18n('M, Y',strtotime( $value['end_date']));
							} else{
								$end_year	= esc_html__('Current','docdirect');
							} 


							if( !empty( $start_year ) || !empty( $end_year ) ){
								$period	= '('.$start_year.'&nbsp;-&nbsp;'.$end_year.')';
							}
						}
					?>
					<li>
						<div class="tg-dotstyletitle">
						  <h3><?php echo esc_attr( $value['title'] );?>&nbsp;&nbsp;<?php echo esc_attr( $period );?></h3>
						  <span><?php echo esc_attr( $value['company'] );?></span>
						</div>
						<div class="tg-description">
						  <p><?php echo esc_attr( $value['description'] );?></p>
						</div>
				   </li>
				 <?php }?>
				</ul>
			  </div>
			  <?php }?>


			  <!--Education-->
			  <?php 
			  if( isset( $education_switch ) 
				  && $education_switch === 'enable'
				  && !empty( $current_author_profile->education )
			  ){?>
			  <div class="tg-userexperience tg-userqualification">
				<div class="tg-userheading">
				  <h2><i class="fa fa-graduation-cap"></i><?php esc_html_e('Education','docdirect');?></h2>
				</div>
				<ul>
				<?php 
					foreach( $current_author_profile->education as $key => $value ){
						$start_year	= '';
						$end_year	= '';
						$period	= '';
						if( !empty( $value['start_date'] ) || !empty( $value['end_date'] ) ){
							if( !empty( $value['start_date'] ) ){
								$start_year	= date_i18n('M, Y',strtotime( $value['start_date']));
							}

							if( !empty( $value['end_date'] ) ){
								$end_year	= date_i18n('M, Y',strtotime( $value['end_date']));
							} else{
								$end_year	= esc_html__('Current','docdirect');
							} 

							if( !empty( $start_year ) || !empty( $end_year ) ){
								$period	= '('.$start_year.'&nbsp;-&nbsp;'.$end_year.')';
							}
						}
					?>
					<li>
						<div class="tg-dotstyletitle">
						  <h3><?php echo esc_attr( $value['title'] );?><strong>&nbsp;&nbsp;</strong><?php echo esc_attr( $period );?></h3>
						  <span><?php echo esc_attr( $value['institute'] );?></span> 
						</div>
						<div class="tg-description">
						  <p><?php echo esc_attr( $value['description'] );?></p>
						</div>
				   </li>
				  <?php }?>
				</ul>
			  </div>
			  <?php }?>

			  <!--Awards-->
			  <?php 
			  if( isset( $awards_switch ) 
				  && $awards_switch === 'enable' 
				  && !empty( $current_author_profile->awards )
			  ){?>
			  <div class="tg-userexperience tg-honourawards">
				<div class="tg-userheading">
				  <h2><i class="fa fa-trophy"></i><?php esc_html_e('Honours & Awards','docdirect');?></h2>
				</div>
				<ul>
					<?php 
					if( !empty( $current_author_profile->awards ) ) {
						foreach( $current_author_profile->awards as $key => $value ){
							$period	= '';
							if( !empty( $value['date'] ) ){
								if( !empty( $value['date'] ) ){
									$period	= '('.date_i18n(get_option('date_format'),strtotime( $value['date'])).')';
								}
							}
						?>
						<li>
							<div class="tg-dotstyletitle">
							  <h3><?php echo esc_attr( $value['name'] );?>&nbsp;&nbsp;<?php echo esc_attr( $period );?></h3>
							</div> 
							<div class="tg-description">
							  <p><?php echo esc_attr( $value['description'] );?></p>
							</div>
						</li>
					   <?php }
					  } else{?>
						<li><p><?php esc_html_e('No awards added yet.','docdirect');?></p></li>
					  <?php }?>
				</ul>
			  </div>
			  <?php }?>

			  <!--Prices List-->
			  <?php 
			  if( isset( $price_switch ) 
				  && $price_switch === 'enable' 
				  && !empty( $current_author_profile->prices_list )
			  ){?>
				<div class="prices-list-wrap">
					<div class="tg-companyfeaturebox tg-services">
					  <div class="tg-userheading">
						<h2><i class="fa fa-money" aria-hidden="true"></i><?php esc_html_e('Prices/Services List','docdirect');?></h2>
					  </div>
					  <div id="tg-accordion" class="tg-accordion">
						<?php 
						foreach( $current_author_profile->prices_list as $key => $value ){
							if( !empty( $value['title'] ) ){
							?>
							<div class="tg-service tg-panel">
							  <div class="tg-accordionheading">
								<h4><span><?php echo esc_attr( $value['title'] );?></span><span><?php echo esc_attr( $value['price'] );?></span></h4>
							  </div>
							  <div class="tg-panelcontent" style="display: none;">
								<div class="tg-description">
								  <p><?php echo esc_attr( $value['description'] );?></p>
								</div>
							  </div>
							</div>
						<?php }}?>
					  </div>
					</div>
				</div>	
			  <?php }?>

			  <?php

			  if ( ! empty( $current_author_profile->teams_data ) && apply_filters('docdirect_do_check_teams',$current_author_profile->ID ) === true   ) {
				  if( apply_filters('docdirect_is_setting_enabled',$current_author_profile->ID,'team' ) === true ){
				  $limit = 500;
				  if (empty($paged)) $paged = 1;
				  $offset = ($paged - 1) * $limit;
				  $teams    = $current_author_profile->teams_data;
				  $teams    = !empty($teams) && is_array( $teams ) ? $teams : array();
			  ?>
			  <div class="our-teams-wrap">
				  <div class="tg-companyfeaturebox tg-ourteam">
					  <div class="tg-userheading">
						<h2><?php esc_html_e('Our Team','docdirect'); ?></h2>
					  </div>
					  <ul class="tg-teammembers">
						<?php 
							$total_users = (int)count($teams); //Total Users              
							$query_args	= array(
													'role'  => 'professional',
													'order' => 'DESC',
													'orderby' => 'ID',
													'include' => $teams
												 );

							$query_args['number']	= $limit;
							$query_args['offset']	= $offset;

							$user_query  = new WP_User_Query($query_args);
							if ( ! empty( $user_query->results ) ) {
							  foreach ( $user_query->results as $user ) {

								$user_link = get_author_posts_url($user->ID);
								$username = docdirect_get_username($user->ID);
								$user_email = $user->user_email;
								$avatar = apply_filters(
													'docdirect_get_user_avatar_filter',
													 docdirect_get_user_avatar(array('width'=>150,'height'=>150), $user->ID),
													 array('width'=>150,'height'=>150) //size width,height
												);
							?>
							<li data-id="<?php echo esc_attr( $user->ID );?>" id="team-<?php echo esc_attr( $user->ID );?>">
								<div class="tg-teammember">
									<figure><a href="<?php echo esc_url( $user_link );?>"><img width="60" height="60" src="<?php echo esc_url( $avatar );?>"></a></figure>
									<div class="tg-memberinfo">
										<h5><a href="<?php echo esc_url( $user_link );?>"><?php echo esc_attr( $username );?></a></h5>
										<a href="<?php echo esc_url( $user_link );?>"><?php esc_html_e('View Full Profile','docdirect'); ?></a>
									</div>
								</div>
							</li>
						 <?php }}?>
					  </ul>
				  </div>
				  <?php 
					//Pagination
					if( ( isset( $total_users ) && isset( $limit )  )
						&&
						$total_users > $limit 
					) {?>
					  <div class="tg-btnarea">
							<?php docdirect_prepare_pagination($total_users,$limit);?>
					  </div>
				  <?php }?>
			  </div>
			  <?php }}?>

			  <!--Video URL-->
			  <div class="col-md-12">
			  <div class="col-md-6">
			  <?php if( isset( $current_author_profile->video_url ) && !empty( $current_author_profile->video_url ) ) {?>
			  <div class="tg-presentationvideo">
				<div class="tg-userheading">
				  <h2><?php esc_html_e('Presentation Video','docdirect');?></h2>
				</div>
				<?php
					$height = 200;
					$width  = 368;
					$post_video = $current_author_profile->video_url;
					$url = parse_url( $post_video );
					if ($url['host'] == $_SERVER["SERVER_NAME"]) {
						echo '<div class="tg-video doc-haslayout">';
						echo do_shortcode('[video width="' . $width . '" height="' . $height . '" src="' . $post_video . '"][/video]');
						echo '</div>';
					} else {

						if ($url['host'] == 'vimeo.com' || $url['host'] == 'player.vimeo.com') {
							echo '<div class="tg-video doc-haslayout">';
							$content_exp = explode("/", $post_video);
							$content_vimo = array_pop($content_exp);
							echo '<iframe width="' . $width . '" height="' . $height . '" src="https://player.vimeo.com/video/' . $content_vimo . '" 
	></iframe>';
							echo '</div>';
						} elseif ($url['host'] == 'soundcloud.com') {
							$video = wp_oembed_get($post_video, array('height' => $height));
							$search = array('webkitallowfullscreen', 'mozallowfullscreen', 'frameborder="no"', 'scrolling="no"');
							echo '<div class="tg-video doc-haslayout">';
							$video = str_replace($search, '', $video);
							echo str_replace('&', '&amp;', $video);
							echo '</div>';
						} else {
							echo '<div class="tg-video doc-haslayout">';
							$content = str_replace(array('watch?v=', 'http://www.dailymotion.com/'), array('embed/', '//www.dailymotion.com/embed/'), $post_video);
							echo '<iframe width="' . $width . '" height="' . $height . '" src="' . $content . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
							echo '</div>';
						}
					}
				?>
			  </div>
			  <?php }?>
</div>
<div class="col-md-6">
<?php if( isset( $user_gallery ) && !empty( $user_gallery ) ){?>
			  <div class="tg-userphotogallery">
				<div class="tg-userheading">
				  <h2><?php esc_html_e('Photo Gallery','docdirect');?></h2>
				</div>
				<ul>
				  <?php 
				  foreach( $user_gallery as $key => $value ){
					  $thumbnail	= docdirect_get_image_source($value['id'],150,150);
					  $orignal	  = docdirect_get_image_source($value['id'],0,0);
					  if( !empty( $thumbnail ) ){
					?>
					<li>
						<figure>
						   <a href="<?php echo esc_url( $orignal );?>" data-rel="prettyPhoto[iframe]"><img src="<?php echo esc_url( $thumbnail );?>" alt="<?php echo esc_attr( get_the_title( $value['id'] ) );?>">
							<figcaption><span class="icon-add"></span></figcaption>
						   </a>
						</figure>
					</li>
				  <?php }}?>
				</ul>
			  </div>
			  <?php }?>	
</div>
</div>
			  <!--Video URL-->
			  

			  <!--Reviews-->
			  <?php if( isset( $reviews_switch ) && $reviews_switch === 'enable' ){?>
				  <div class="tg-userreviews">
					<!-- <div class="tg-userheading">
					  <h2><?php echo intval( apply_filters('docdirect_count_reviews',$current_author_profile->ID) );?>&nbsp;&nbsp;<?php esc_html_e('Review(s)','docdirect');?></h2> 
					</div> -->
					<!-- <?php if( !empty( $review_data['by_ratings'] ) ) {?>
					<div class="tg-ratingbox">
					  <div class="tg-averagerating">
						<h3><?php esc_html_e('Average Rating','docdirect');?></h3>
						<em><?php echo number_format((float)$review_data['average_rating'], 1, '.', '');?></em>
						<span class="tg-stars"><?php docdirect_get_rating_stars($review_data,'echo','hide');?></span>
					  </div>
					  <div id="tg-userskill" class="tg-userskill">
						<?php 
							foreach( $review_data['by_ratings'] as $key => $value ){
								$final_rate = 0;
								if( !empty( $value['rating'] ) && !empty( $value['rating'] ) ) {
									$get_sum	  = $value['rating'];
									$get_total	= $value['total'];
									$final_rate	= $get_sum/$get_total*100;
								} else{
									$final_rate	= 0;
								}

							?>
							<div class="tg-skill"> 
							  <span class="tg-skillname"><?php echo intval( $key+1 );?> <?php esc_html_e('Stars','docdirect');?></span> 
							  <span class="tg-skillpercentage"><?php echo intval($final_rate/5);?>%</span>
							  <div class="tg-skillbox">
								<div class="tg-skillholder" data-percent="<?php echo intval($final_rate/5);?>%">
								  <div class="tg-skillbar"></div>
								</div>
							  </div>
							</div>
						<?php }?>
					  </div>
					</div>
					<?php }?> -->
					<ul class="tg-reviewlisting">
					<?php if( apply_filters('docdirect_count_reviews',$current_author_profile->ID) > 0 ){
					global $paged;
					if (empty($paged)) $paged = 1;
					$show_posts    = get_option('posts_per_page') ? get_option('posts_per_page') : '-1';        

					$meta_query_args = array('relation' => 'AND',);
					$meta_query_args[] = array(
											'key' 	   => 'user_to',
											'value' 	 => $current_author_profile->ID,
											'compare'   => '=',
											'type'	  => 'NUMERIC'
										);

					$args = array('posts_per_page' => "-1", 
						'post_type' => 'docdirectreviews', 
						'order' => 'DESC', 
						'orderby' => 'ID', 
						'post_status' => 'publish', 
						'ignore_sticky_posts' => 1
					);

					$args['meta_query'] = $meta_query_args;

					$query 		= new WP_Query( $args );
					$count_post = $query->post_count;        

					//Main Query	
					$args 		= array('posts_per_page' => $show_posts, 
						'post_type' => 'docdirectreviews', 
						'paged' => $paged, 
						'order' => 'DESC', 
						'orderby' => 'ID', 
						'post_status' => 'publish', 
						'ignore_sticky_posts' => 1
					);

					$args['meta_query'] = $meta_query_args;

					$query 		= new WP_Query($args);
					if( $query->have_posts() ){
						while($query->have_posts()) : $query->the_post();
							global $post;
							$user_rating = fw_get_db_post_option($post->ID, 'user_rating', true);
							$user_from = fw_get_db_post_option($post->ID, 'user_from', true);
							$review_date  = fw_get_db_post_option($post->ID, 'review_date', true);
							$user_data 	  = get_user_by( 'id', intval( $user_from ) );

							$avatar = apply_filters(
											'docdirect_get_user_avatar_filter',
											 docdirect_get_user_avatar(array('width'=>150,'height'=>150), $user_from),
											 array('width'=>150,'height'=>150) //size width,height
										);

							$user_name	= '';
							if( !empty( $user_data ) ) {
								$user_name	= $user_data->first_name.' '.$user_data->last_name;
							}

							if( empty( $user_name ) && !empty( $user_data ) ){
								$user_name	= $user_data->user_login;
							}

							$percentage	= $user_rating*20;

						?>
						<li>
							<div class="tg-review">
							  <figure class="tg-reviewimg"> 
								<a href="<?php echo get_author_posts_url($user_from); ?>"><img src="<?php echo esc_url( $avatar );?>" alt="<?php esc_html_e('Reviewer','docdirect');?>"></a>
							  </figure>
							  <div class="tg-reviewcontet"> 
								<div class="tg-reviewhead">
								  <div class="tg-reviewheadleft">
									<h3><a href="<?php echo get_author_posts_url($user_from); ?>"><?php echo esc_attr( $user_name );?></a></h3>
									<span><?php echo human_time_diff( strtotime( $review_date ), current_time('timestamp') ) .'&nbsp;'.esc_html__('ago','docdirect'); ?></span> </div>
								  <div class="tg-reviewheadright tg-stars star-rating">
									<span style="width:<?php echo esc_attr( $percentage );?>%"></span>
								  </div>
								</div>
								<div class="tg-description">
								  <p><?php the_content();?></p>
								</div>
							  </div>
							</div>
						  </li>
						<?php 
							endwhile; wp_reset_postdata();
						}else{?>
							<li class="noreviews-found"> <?php DoctorDirectory_NotificationsHelper::informations(esc_html__('No Reviews Found.','docdirect'));;?></li>
						<?php }
					} else{?>
						<li class="noreviews-found"> <?php DoctorDirectory_NotificationsHelper::informations(esc_html__('No Reviews Found.','docdirect'));;?></li>
					<?php }?>

					</ul>
					<?php 
					if( isset( $current_user->ID ) 
						&& 
						$current_user->ID != $current_author_profile->ID 
					){?>
					<div class="tg-leaveyourreview">
					  <div class="tg-userheading">
						<h2><?php esc_html_e('Leave Your Review','docdirect');?></h2>
					  </div>
					  <?php if( apply_filters('docdirect_is_user_logged_in','check_user') === true ){?>
					  <div class="message_contact  theme-notification"></div>
					  <form class="tg-formleavereview form-review">
						<fieldset>
						  <div class="row">
							<div class="col-sm-6">
							  <div class="form-group">
								<input type="text" name="user_subject" class="form-control" placeholder="<?php esc_attr_e('Subject','docdirect');?>">
							  </div>
							</div>
							<div class="col-sm-6">
							  <div class="tg-stars"><div id="jRate"></div><span class="your-rate"><strong><?php esc_html_e('Excellent','docdirect');?></strong></span></div>
							  <script type="text/javascript">
							jQuery(function () {
								var that = this;
								var toolitup = jQuery("#jRate").jRate({
									rating: 3,
									min: 0,
									max: 5,
									precision: 1,
									startColor: "<?php echo esc_js( $rating_color );?>",
									endColor: "<?php echo esc_js( $rating_color );?>",
									backgroundColor: "#DFDFE0",
									onChange: function(rating) {
										jQuery('.user_rating').val(rating);
										if( rating == 1 ){
											jQuery('.your-rate strong').html(scripts_vars.rating_1);
										} else if( rating == 2 ){
											jQuery('.your-rate strong').html(scripts_vars.rating_2);
										} else if( rating == 3 ){
											jQuery('.your-rate strong').html(scripts_vars.rating_3);
										} else if( rating == 4 ){
											jQuery('.your-rate strong').html(scripts_vars.rating_4);
										} else if( rating == 5 ){
											jQuery('.your-rate strong').html(scripts_vars.rating_5);
										}
									},
									onSet: function(rating) {
										jQuery('.user_rating').val(rating);
										if( rating == 1 ){
											jQuery('.your-rate strong').html(scripts_vars.rating_1);
										} else if( rating == 2 ){
											jQuery('.your-rate strong').html(scripts_vars.rating_2);
										} else if( rating == 3 ){
											jQuery('.your-rate strong').html(scripts_vars.rating_3);
										} else if( rating == 4 ){
											jQuery('.your-rate strong').html(scripts_vars.rating_4);
										} else if( rating == 5 ){
											jQuery('.your-rate strong').html(scripts_vars.rating_5);
										}
									}
								});
							});
						</script>
							</div>
							<div class="col-sm-12">
							  <div class="form-group">
								<textarea class="form-control" name="user_description" placeholder="<?php esc_attr_e('Review Description *','docdirect');?>"></textarea>
							  </div>
							</div>
							<div class="col-sm-12">
							  <button class="tg-btn make-review" type="submit"><?php esc_html_e('Submit Review','docdirect');?></button>
							  <input type="hidden" name="user_rating" class="user_rating" value="" />
							  <input type="hidden" name="user_to" class="user_to" value="<?php echo esc_attr( $current_author_profile->ID );?>" />
							</div>
						  </div>
						</fieldset>
					  </form>
					  <?php } else{?>
						<span><a href="javascript:;" class="tg-btn" data-toggle="modal" data-target=".tg-user-modal"><?php esc_html_e('Please Login To add Review','docdirect');?></a></span>
				  <?php }?>
					</div>
					<?php }?>
				  </div>
			   <?php }?>
			</div>
		  </div>
		</div>
	  </div>
	</div>
<?php } else{?>
	<div class="container">
		 <?php DoctorDirectory_NotificationsHelper::informations(esc_html__('You are not allowed to view this page. This users has expired or didn\'t subscribed to any package' ,'docdirect'));?>
	</div>
<?php }?>

<?php } else{?>
	<div class="container">
         <?php DoctorDirectory_NotificationsHelper::informations(esc_html__('Oops! you are not allowed to access this page.','docdirect'));?>
    </div>
<?php }?>
<?php get_footer();?>
<?php
if( apply_filters('docdirect_is_setting_enabled',$current_author_profile->ID,'appointments' ) === true ){
	if( isset( $current_user->ID ) 
		&& 
		$current_user->ID != $current_author_profile->ID
		&&
		is_user_logged_in()
	){
	
		if( !empty( $privacy['appointments'] )
		  && 
			$privacy['appointments'] == 'on'
	 ) {
	
	?>
	<div class="modal fade tg-appointmentpopup" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	  <div class="modal-dialog modal-lg tg-modalcontent" role="document">
		<form action="#" method="post" class="appointment-form">
		  <fieldset class="booking-model-contents">
			<ul class="tg-navdocappointment" role="tablist">
			  <li class="active"><a href="javascript:;" class="bk-step-1"><?php esc_html_e('1. choose service','docdirect');?></a></li>
			  <li><a href="javascript:;" class="bk-step-2"><?php esc_html_e('2. available schedule','docdirect');?></a></li>
			  <li><a href="javascript:;" class="bk-step-3"><?php esc_html_e('3. your contact detail','docdirect');?></a></li>
			  <li><a href="javascript:;" class="bk-step-4"><?php esc_html_e('4. Payment Mode','docdirect');?></a></li>
			  <li><a href="javascript:;" class="bk-step-5"><?php esc_html_e('5. Finish','docdirect');?></a></li>
			</ul>
			<div class="tab-content tg-appointmenttabcontent" data-id="<?php echo esc_attr( $current_author_profile->ID );?>">
			  <div class="tab-pane active step-one-contents" id="one">
				<?php docdirect_get_booking_step_one($current_author_profile->ID,'echo');?>
			  </div>
			  <div class="tab-pane step-two-contents" id="two">
				<?php docdirect_get_booking_step_two_calender($current_author_profile->ID,'echo');?>
			  </div>
			  <div class="tab-pane step-three-contents" id="three"></div>
			  <div class="tab-pane step-four-contents" id="four"></div>
			  <div class="tab-pane step-five-contents" id="five"></div>
			  <div class="tg-btnbox booking-step-button">
				  <button type="button" class="tg-btn bk-step-prev"><?php esc_html_e('Previous','docdirect');?></button>
				  <button type="button" class="tg-btn bk-step-next"><?php esc_html_e('next','docdirect');?></button>
				</div>
			</div>
		  </fieldset>
		</form>
	  </div>
	</div>
<?php }}}?>
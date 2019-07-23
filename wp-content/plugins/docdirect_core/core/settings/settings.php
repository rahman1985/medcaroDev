<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://themeforest.net/user/themographics/portfolio
 * @since      1.0.0
 *
 * @package    Docdirect
 * @subpackage Docdirect/admin
 */


/**
 * @init            Blink Admin Menu init
 * @package         Tailors Online
 * @subpackage      tailors-online/admin/partials
 * @since           1.0
 * @desc            This Function Will Produce All Tabs View.
 */
if( !function_exists('docdirect_core_admin_menu') ) {
	add_action( 'admin_menu', 'docdirect_core_admin_menu' );
	function docdirect_core_admin_menu() {
		$url = admin_url();
		add_submenu_page('edit.php?post_type=directory_type', 
							 esc_html__('Settings','docdirect_core'), 
							 esc_html__('Settings','docdirect_core'), 
							 'manage_options', 
							 'docdirect_settings',
							 'docdirect_admin_page',
						 	 '',
						 	 '10'
						 );
	}
}


/**
 * @init            Settings Admin Page
 * @package         Docdirect
 * @subpackage      docdirect/admin/partials
 * @since           1.0
 * @desc            This Function Will Produce All Tabs View.
 */
if( !function_exists('docdirect_admin_page') ) {
	function docdirect_admin_page() {
	$protocol = is_ssl() ? 'https' : 'http';
	
	$packages_settings	= get_option('docdirect_packages_settings');
	
	$packages_settings	=  !empty( $packages_settings ) ? $packages_settings : 'default';
	ob_start();
?>
	<div id="tg-main" class="tg-main tg-addnew">
		<div class="wrap">
			<div id="tg-tab1s" class="tg-tabs">
				<div class="tg-tabscontent">
					<div id="tg-main" class="tg-main tg-features settings-main-wrap">
						<div class="tg-featureswelcomebox">
							<figure><img src="<?php echo DocDirectGlobalSettings::get_plugin_url();?>/core/settings/welcome/logo.jpg" alt="<?php esc_html_e('logo','docdirect_core');?>"></figure>
							<div class="tg-welcomecontent">
								<h3><?php esc_html_e('Welcome To DocDirect','docdirect_core');?></h3>
								<div class="tg-description">
									<p><?php esc_html_e('DocDirect is a purpose built Directory WordPress theme for health care and other related professions. It is designed in a way that it could be used as a engineers directory, lawyer directory, handyman directory, business services directory, veterinary directory, service provider directory, business and service finder directory, business listing or as a directory for other professionals as it has a lot of features a directory website may need (and many more!).  ','docdirect_core');?></p>
									<p><?php esc_html_e('The current template has been designed with a directory for healthcare establishments in mind. The inner pages are carefully designed to provide all the essential information any directory business would need.','docdirect_core');?></p>
									
								</div>
							</div>
						</div>
						<?php
							require_once plugin_dir_path( dirname( __FILE__ ) ) . 'settings/envato/Envato.php';

							$Envato = new Envato_marketplaces();
							
							// Getting transient
							$cachetime       = 86400;
							$transient       = 'product_data';
							$get_transient = false;
							$get_transient = get_transient($transient);
							
							if ( empty( $get_transient ) || $get_transient === false ) {
								$products	= 'https://themographics.com/products/data.json';
								$response = wp_remote_post( $products );

								$result	= wp_remote_retrieve_body($response);
								$items	= json_decode( $result );
								set_transient($transient , $items, $cachetime);
							} else{
								$items	= $get_transient;
							}
								
						?>
						<div class="tg-featurescontent">
							<div id="tg-pluginslider" class="tg-pluginslider">
								<div class="pack-items">
									<div class="pack-column">
										<div class="pack-default pack-box">
											<h3><?php esc_html_e('Default Packages','docdirect_core');?></h3>
											<div class="tg-description">
												<p><strong><?php esc_html_e('Important:','docdirect_core');?>&nbsp;</strong><?php esc_html_e('*Please note that this is the recommended settings for existing users of our theme using the versions 4.5 or earlier. If you want to make sure that your settings are not affected, please keep using this default package setting.','docdirect_core');?></p>
												<p><?php esc_html_e('If you are not using the packages and you want to reset the entire packages and user settings for different features controlled through custom packages then you can move to the custom packages settings but ensure that you have taken a full back up in case you were not clear what the effects would be when moving to the new package system available in versions after 4.5 (4.5 did not include the new package system)','docdirect_core');?></p>

												<p><?php esc_html_e('Features:','docdirect_core');?></p>

												<p><?php esc_html_e('This package will allow users to use all features and only the duration to be in featured can be controlled through default packages.','docdirect_core');?></p>
												<a class="make-it-default tg-btn <?php echo isset($packages_settings) && $packages_settings === 'default' ? 'current-active' : '';?>" data-key="default"><?php esc_html_e('Make it default','docdirect_core');?></a>
											 </div>
										</div>
										<div class="pack-restricted pack-box">
											<h3><?php esc_html_e('Custom Packages','docdirect_core');?></h3>
											<div class="tg-description">
												<p><strong><?php esc_html_e('Important:','docdirect_core');?>&nbsp;</strong><?php esc_html_e('*Please note that this is NOT the recommended settings for existing users of our theme using the versions 4.5 or earlier. If you want to make sure that your old settings are not affected, please keep using the default package settings and don\'t move to this one.','docdirect_core');?></p>
												<p><?php esc_html_e('If you are not using the old packages and you want to reset the entire packages and user settings for different features controlled through custom packages then you can move to this custom packages settings but ensure that you have taken a full back up in case you were not clear what the effects would be when moving to the new package system available in versions after 4.5 (4.5 did not include the new package system).','docdirect_core');?></p>

												<p><?php esc_html_e('Features:','docdirect_core');?></p>

												<p><?php esc_html_e('In this new packages settings, you can assign different features (listed below) to different packages.','docdirect_core');?></p>
												<ul class="tg-liststyle tg-dotliststyle tg-twocolumnslist">
													<li>Featured listing days</li>
													<li>Appointments included</li>
													<li>Profile Banner Included</li>
													<li>Insurance</li>
													<li>Favorite Listings</li>
													<li>Teams Management</li>
													<li>Opening Hours/Schedules</li>
												</ul>
												<a class="make-it-default tg-btn <?php echo isset($packages_settings) && $packages_settings === 'custom' ? 'current-active' : '';?>" data-key="custom"><?php esc_html_e('Make it default','docdirect_core');?></a>
											 </div>
										</div>
									</div>
									
								</div>
							</div>
                
							<div class="tg-twocolumns">
								<div class="tg-content">
									<div class="tg-boxarea">
										<div class="tg-title">
											<h3><?php esc_html_e('Minimum System Requirements','docdirect_core');?></h3>
										</div>
										<div class="tg-contentbox">
											<ul class="tg-liststyle tg-dotliststyle tg-twocolumnslist">
												<li>PHP version should be  > 5.3 and <= 5.6</li>
												<li>PHP Zip extension Should</li>
												<li>max_execution_time = 300</li>
												<li>max_input_time = 300</li>
												<li>memory_limit = 300</li>
												<li>post_max_size = 100M</li>
												<li>upload_max_filesize = 100M</li>
											</ul>
										</div>
									</div>
									<div class="tg-widgetbox tg-widgetboxquicklinks">
										<div class="tg-title">
											<h3><?php esc_html_e('Quick Links','docdirect_core');?></h3>
										</div>
										<ul>
											<li><a target="_blank" href="https://themographics.ticksy.com/article/10575/"><?php esc_html_e('How to translate theme','docdirect_core');?></a></li>
											<li><a target="_blank" href="https://themographics.ticksy.com/article/9998/"><?php esc_html_e('How to make login/registration page','docdirect_core');?></a></li>
											<li><a target="_blank" href="https://themographics.ticksy.com/article/10342/"><?php esc_html_e('How to Update Theme, When New Update release','docdirect_core');?></a></li>
										</ul>
										<a class="tg-btn" target="_blank" href="https://themographics.ticksy.com/articles/100004191">Get a quick help</a>
									</div>
								</div>
								<aside class="tg-sidebar">
								 <div class="tg-widgetbox tg-widgetboxquicklinks">
										<div class="tg-title">
											<h3><?php esc_html_e('Video Tutorial','tailors-online');?></h3>
										</div>
										 <figure>
											<div style="position:relative;height:0;padding-bottom:56.25%">
												<iframe src="https://www.youtube.com/embed/kPuk0aVe85U?ecver=2" width="640" height="360" frameborder="0" style="position:absolute;width:100%;height:100%;left:0" allowfullscreen></iframe>
											</div>
										</figure>
										
									</div>
									
									<?php if( !empty( $items ) ) {?>
										<div class="tg-widgetbox tg-widgetboxotherproducts">
											<div class="tg-title">
												<h3><?php esc_html_e('Our Other Products','docdirect_core');?></h3>
											</div>
											<ul>
												<?php 
													foreach( $items as $key => $product ){
														$item = $Envato->item_details($product->ID);
														if( !empty( $item->item ) ) {
													?>
													<li>
														<figure><a target="_blank" href="<?php echo esc_url( $item->url );?>"><img src="<?php echo esc_url( $item->thumbnail );?>" alt="<?php echo esc_attr( $item->item );?>"></a></figure>
														<div class="tg-themetitle">
															<h4><a target="_blank" href="<?php echo esc_url( $item->url );?>"><?php echo esc_attr( $item->item );?></a></h4>
															<a target="_blank" class="tg-btnviewdemo" href="<?php echo esc_url( $item->url );?>"><?php esc_html_e('View Demo','docdirect_core');?></a>
														</div>
													</li>
												<?php }}?>
											</ul>
										</div>
									<?php }?>
								</aside>
							</div>
							<div class="tg-socialandcopyright">
								<span class="tg-copyright"><?php echo date('Y');?>&nbsp;<?php esc_html_e('All Rights Reserved','docdirect_core');?> &copy; <a target="_blank"  href="https://themeforest.net/user/themographics/"><?php esc_html_e('Themographics','docdirect_core');?></a></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	echo ob_get_clean();
}
}


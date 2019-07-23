<?php
/**
 * @theme Functionality Files
 * @return 
 */
require_once ( get_template_directory() . '/inc/helpers/theme-setup.php'); //Theme setup
require_once ( get_template_directory() . '/inc/helpers/general-helpers.php'); //Theme functionalty
require_once ( get_template_directory() . '/inc/notifications/email_notifications.php'); //Theme functionalty
require_once ( get_template_directory() . '/inc/helpers/currencies.php'); //Currencies
require_once ( get_template_directory() . '/inc/helpers/languages.php'); //Currencies
require_once ( get_template_directory() . '/inc/base-classes/class-framework.php'); //Base Functionality
require_once ( get_template_directory() . '/inc/base-classes/class-messages-helper.php'); //For Site Notifications
require_once ( get_template_directory() . '/inc/headers/class-headers.php');
require_once ( get_template_directory() . '/inc/footers/class-footers.php');
require_once ( get_template_directory() . '/inc/subheaders/class-subheaders.php');
require_once ( get_template_directory() . '/inc/template-tags.php');
require_once ( get_template_directory() . '/inc/extras.php');
require_once ( get_template_directory() . '/inc/customizer.php');
require_once ( get_template_directory() . '/inc/constants.php');
require_once ( get_template_directory() . '/inc/jetpack.php');
require_once ( get_template_directory() . '/inc/google-fonts/google_fonts.php'); // goole fonts
require_once ( get_template_directory() . '/inc/hooks.php');
require_once ( get_template_directory() . '/plugins/install-plugin.php');
require_once ( get_template_directory() . '/framework-customizations/includes/option-types.php');
require_once ( get_template_directory() . '/directory/class-functions.php');
require_once ( get_template_directory() . '/directory/functions.php');
require_once ( get_template_directory() . '/directory/hooks.php');
require_once ( get_template_directory() . '/core/user-profile/functions.php');
require_once ( get_template_directory() . '/inc/redius-search/location_check.php');
require_once ( get_template_directory() . '/inc/widgets/init.php'); //widgets
require_once ( get_template_directory() . '/directory/bookings/functions.php'); //Booking
require_once ( get_template_directory() . '/directory/bookings/hooks.php'); //Booking
require_once ( get_template_directory() . '/directory/data-importer/importer.php'); //Dummy data importer
add_theme_support( 'woocommerce' );
add_action( 'edit_user_profile', 'user_video_chat_id' );
//Zoom Video Call ID
function user_video_chat_id($user){      
    $video_call_id = get_user_meta($user->ID, 'video_call_id', true);
    if ($user->roles[0] != "visitor"){    
?>
    <div class="tg-docschedule tg-haslayout">
        <fieldset>    
            <div class="tg-bordertop tg-haslayout">
                <div class="tg-formsection">
                    <div class="parent-heading">
                        <h2><?php esc_html_e('Video Call ID ','docdirect');?></h2>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <input class="form-control featured_days" name="video_call_id" value="<?php echo esc_attr( $video_call_id );?>" type="text" placeholder="<?php esc_attr_e('Enter Video Call ID','docdirect');?>">
                            </div>
                        </div>         
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
<?php } 
}
add_action( 'edit_user_profile_update', 'wk_save_custom_user_profile_fields' );

/**
*   @param User Id $user_id
*/
function wk_save_custom_user_profile_fields($user_id) {
    $custom_data = $_POST['video_call_id'];
    update_user_meta( $user_id, 'video_call_id', $custom_data );
}
/**
 * @Init notification Script
 * @return 
 */
if (!function_exists('load_notification_script')) {
    function load_notification_script() {
        if(is_user_logged_in()){
            $theme_version = wp_get_theme();
            wp_enqueue_script('customNotification', get_template_directory_uri() . '/js/customeNotification.js', array(), $theme_version->get( 'Version' ), true);
        }
    }
}
add_action( 'wp_enqueue_scripts', 'load_notification_script' );
?>
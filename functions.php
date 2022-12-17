<?php
/**
 * Diviv child theme for customizing some things like featuring course file download, and so on. Divi theme can be used on any theme with tutor LMS plugin with woocommerce.
 * @package Divi-Child by future WordPress
 * @version 1.0.1
 * @author Remal Mahmud (https://github.com/mahmudremal)
 * @link https://github.com/mahmudremal/Tutor-LMS-Course-managements-and-Video-streaming-dynamic-converter-using-ffmpeg
 * exec,system,passthru,shell_exec,escapeshellarg,escapeshellcmd,proc_close,proc_open,dl,popen,show_source,posix_kill,posix_mkfifo,posix_getpwuid,posix_setpgid,posix_setsid,posix_setuid,posix_setgid,posix_seteuid,posix_setegid,posix_uname
 * 
 * For customizing Divi-Course Certificate builder.
 * @issue : Certificate page doesn't properly show certificate with no footer and no scripts.
 * @how-to-solved : The tutor core file 'plugins\tutor\classes\Utils.php', there is an error on line number 8891. Problem is it is called get_footer(); that return nothing. So instead of it, I replaced this code on there.
 * if( function_exists( 'wp_footer' ) ) : wp_footer(); else : get_footer(); endif;
 * So that wordpress can check if wp_footer() exist and if so then call it to return content.
 * 
 * =============================================================================== */
 


//you can add custom functions below this line:

// Begin remove Divi Blog Module featured image crop


add_filter( 'et_pb_blog_image_width', function($width) {
	return '9999';
} );
add_filter( 'et_pb_blog_image_height', function($height) {
	return '9999';
} );
// End remove Divi Blog Module featured image crop

include_once( get_stylesheet_directory() . '/inc/class-assets.php' );new FWP_DIVI_CHILD_THEME();
require_once( get_stylesheet_directory() . '/inc/class-authentication.php' );new FWP_DIVI_CHILD_THEME_AUTHENTICATION();
if( is_admin() ) {
	require_once( get_stylesheet_directory() . '/inc/class-metabox.php' );new FWP_DIVI_CHILD_THEME_META_BOX();
}
<?php
/**
 * Free Divi Child Theme by Pee-Aye Creative
 * Functions.php
 *
 * ===== NOTES ==================================================================
 * 
 * New to Divi? Take our full Divi course: https://www.peeayecreative.com/product/beyond-the-builder-the-ultimate-divi-website-course/
 * 
 * Learn cool tricks and features with our Divi tutorials: https://www.peeayecreative.com/blog/
 * 
 * Discover our premium Divi products: https://www.peeayecreative.com/shop/
 * exec,system,passthru,shell_exec,escapeshellarg,escapeshellcmd,proc_close,proc_open,dl,popen,show_source,posix_kill,posix_mkfifo,posix_getpwuid,posix_setpgid,posix_setsid,posix_setuid,posix_setgid,posix_seteuid,posix_setegid,posix_uname
 * 
 * =============================================================================== */
 


//you can add custom functions below this line:

// Begin remove Divi Blog Module featured image crop
function pa_blog_image_width($width) {
	return '9999';
}
function pa_blog_image_height($height) {
	return '9999';
}
add_filter( 'et_pb_blog_image_width', 'pa_blog_image_width' );
add_filter( 'et_pb_blog_image_height', 'pa_blog_image_height' );
// End remove Divi Blog Module featured image crop

include_once( get_stylesheet_directory() . '/inc/class-assets.php' );
new FWP_DIVI_CHILD_THEME();


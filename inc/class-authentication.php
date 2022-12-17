<?php
/**
 * Video of the Theme.
 *
 * @package Divi-child
 */

class FWP_DIVI_CHILD_THEME_AUTHENTICATION {
	protected $options = NULL;
	public function __construct() {
    $this->setup_hook();
	}
	private function setup_hook() {
		add_filter( 'body_class', [ $this, 'body_class' ], 10, 1 );
		add_action( 'woocommerce_before_add_to_cart_form', [ $this, 'single' ], 10, 0 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ], 10, 0 );
		add_action( 'wp_footer', [ $this, 'popup' ], 10, 0 );
		add_action( 'tutor_course/loop/excerpt', [ $this, 'tutorCourseLoopExcerpt' ], 10, 0 );

		// add_action( 'woocommerce_account_dashboard', [ $this, 'woocommerce_account_dashboard' ], 10, 0 );
		// add_action( 'woocommerce_before_account_navigation', [ $this, 'woocommerce_before_account_navigation' ], 10, 0 );
	}
  public function body_class( $classes ) {
		if( $this->is_true() ) {$classes[] = 'fwp-single-product-hide';}
		return $classes;
	}
	private function is_true() {
		return ( ! is_user_logged_in() && get_post_meta( get_the_ID(), 'fwp_is_required_login', true ) );
	}
	public function enqueue() {
		wp_register_style( 'bootstrap', get_stylesheet_directory_uri() . '/css/bootstrap.min.css', [], false, 'all' );
		wp_register_style( 'authentication', get_stylesheet_directory_uri() . '/css/authentication.min.css', [], filemtime( get_stylesheet_directory() . '/css/authentication.min.css' ), 'all' );
		wp_register_script( 'bootstrap', get_stylesheet_directory_uri() . '/js/bootstrap.min.js', [], false, true );
		wp_register_script( 'authentication', get_stylesheet_directory_uri() . '/js/authentication.min.js', [ 'jquery' ], filemtime( get_stylesheet_directory() . '/js/authentication.min.js' ), true );
		if( $this->is_true() ) {
			wp_enqueue_style( 'bootstrap' );
			wp_enqueue_script( 'bootstrap' );
			wp_enqueue_style( 'authentication' );
			wp_enqueue_script( 'authentication' );
		}
	}
	public function single() {
		if( ! $this->is_true() ) {return;}
		?>
		<a href="#" class="btn btn-md" data-toggle="modal" data-target="#exampleModalCenter">PLease login to proceed.</a>
		<?php
	}
	public function popup() {
		global $wp;
		if( ! $this->is_true() ) {return;}
		?>
		<!-- Modal -->
		<div class="sign_up_modal modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						</div>
					<ul class="sign_up_tab nav nav-tabs" id="myTab" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php esc_html_e( 'Login', 'domain' ); ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?php esc_html_e( 'Register', 'domain' ); ?></a>
						</li>
				</ul>
				<div class="tab-content" id="myTabContent">
						<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
						<div class="login_form">
							<form action="<?php echo esc_url( site_url( 'wp-login.php' ) ); ?>" method="post" name="loginform">
								<div class="heading">
									<h3 class="text-center"><?php esc_html_e( 'Quick Login', 'domain' ); ?></h3>
								</div>
									<div class="form-group">
										<input class="form-control" name="log" id="user_login" class="input" value="" size="20" autocapitalize="off" autocomplete="username" placeholder="<?php esc_attr_e( 'Username / login', 'domain' ); ?>">
								</div>
								<div class="form-group">
									<input class="form-control" type="password" name="pwd" id="user_pass" value="" size="20" autocomplete="current-password" placeholder="<?php esc_attr_e( 'Password', 'domain' ); ?>">
								</div>
								<div class="form-group form-check">
									<input type="checkbox" class="form-check-input" id="exampleCheck1">
									<label class="form-check-label" for="exampleCheck1">Remember me</label>
									<a class="tdu text-thm float-right" href="#"><?php esc_html_e( 'Forgot Password?', 'domain' ); ?></a>
								</div>
								<button type="submit" class="btn btn-log btn-block btn-thm" name="wp-submit" id="wp-submit" value="Log In"><?php esc_html_e( 'Login', 'domain' ); ?></button>
								<input type="hidden" name="redirect_to" value="<?php echo esc_url( site_url( $wp->request ) ); ?>" />
								<input type="hidden" name="testcookie" value="1" />
							</form>
						</div>
						</div>
						<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
						<div class="sign_up_form">
							<div class="heading">
								<h3 class="text-center"><?php esc_html_e( 'Create New Account', 'domain' ); ?></h3>
							</div>
							<form action="<?php echo esc_url( site_url( 'wp-login.php?action=register' ) ); ?>" method="post" novalidate="novalidate" name="registerform" target="_blank">
								<div class="form-group">
										<input type="text" class="form-control" name="user_login" id="user_login" class="input" value="" size="20" autocapitalize="off" autocomplete="username" placeholder="<?php esc_attr_e( 'Name', 'domain' ); ?>">
								</div>
									<div class="form-group">
										<input type="email" class="form-control" name="user_email" id="user_email" class="input" value="" size="25" autocomplete="email" placeholder="<?php esc_attr_e( 'Email Address', 'domain' ); ?>">
								</div>
								<div class="form-group form-check">
									<input type="checkbox" class="form-check-input" id="exampleCheck2">
									<input type="hidden" na-----me="redirect_to" value="<?php echo esc_url( site_url( $wp->request ) ); ?>">
									<label class="form-check-label" for="exampleCheck2"><?php echo sprintf( __( 'By registering you confirm that you accept our %s.', 'domain' ), get_the_privacy_policy_link( '<span class="text-thm">', '</span>' ) ); ?></label>
								</div>
								<button type="submit" class="btn btn-log btn-block btn-dark" name="wp-submit" id="wp-submit" value="Register"><?php esc_html_e( 'Register', 'domain' ); ?></button>
							</form>
						</div>
						</div>
				</div>
				</div>
			</div>
		</div>
		<?php
	}

  public function tutorCourseLoopExcerpt() {
    $excerpt = get_the_excerpt();$length = 120;
    echo esc_html( substr( $excerpt, 0, $length ) . (
      ( strlen( $excerpt ) > $length ) ? '..' : ''
    ) );
  }
	public function woocommerce_before_account_navigation() {}
	public function woocommerce_account_dashboard() {
		?>
		<div class="woocommerce-dashboard-wrapper">
			<div class="woocommerce-dashboard-row">
				<?php if( function_exists( 'wc_get_customer_order_count' ) ) : ?>
					<a class="woo-single" href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>">
						<div class="woo-single-image">
							<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/icons/orders.svg' ); ?>" alt="">
						</div>
						<div class="woo-single-base">
							<?php echo esc_html( number_format_i18n( wc_get_customer_order_count( get_current_user_id() ), 0 ) ); ?>
						</div>
					</a>
				<?php endif; ?>
				<?php if( defined( 'YITH_WCWL' ) && function_exists( 'yith_wcwl_count_all_products' ) ) : ?>
					<a class="woo-single" href="<?php echo esc_url( YITH_WCWL()->get_wishlist_url() ); ?>">
						<div class="woo-single-image">
							<img src="https://www.svgrepo.com/show/210241/invoice-bill.svg" data-imagesrc="<?php // echo esc_url( get_stylesheet_directory_uri() . '/assets/icons/loved.svg' ); ?>" alt="">
						</div>
						<div class="woo-single-base">
							<?php echo esc_html( number_format_i18n( yith_wcwl_count_all_products(), 0 ) ); ?>
						</div>
					</a>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}

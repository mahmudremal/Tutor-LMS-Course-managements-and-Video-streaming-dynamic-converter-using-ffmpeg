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
		add_action( 'tutor/lesson/created', [ $this, 'tutor_lesson_created' ], 10, 1 );
		add_action( 'tutor_course_builder_after_btn_group', [ $this, 'tutor_course_builder_after_btn_group' ], 10, 2 );
		add_action( 'wp_ajax_fwp_project_seojaws_get_lessons', [ $this, 'fwp_project_seojaws_get_lessons' ], 10, 0 );

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
	public function tutor_lesson_created( $lesson_id = false ) {
		if( ! $lesson_id ) {return;}
		global $wpdb;
		$metaKeys = $wpdb->get_results( $wpdb->prepare(
			"SELECT * FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = %s;",
			$lesson_id, '_video'
		), ARRAY_A );
		foreach( $metaKeys as $i => $meta ) {
			$shortcode = $meta[ 'meta_value' ] = maybe_unserialize( $meta[ 'meta_value' ] );
			if( isset( $shortcode[ 'source_shortcode' ] ) && substr( $shortcode[ 'source_shortcode' ], 0, 17 ) == '[fwpcourselesson ' ) {
				// $meta[ 'meta_value' ][ 'source_shortcode' ] = substr( $meta[ 'meta_value' ][ 'source_shortcode' ], 1, -1 );
				// $meta[ 'meta_value' ][ 'source_shortcode' ] = str_replace( [ '-apostrophe-', '-dquatation-', '-3rdbracketsrt-', '-3rdbracketend-' ], [ "'", '"', '[', ']' ], $meta[ 'meta_value' ][ 'source_shortcode' ] );
				// $meta[ 'meta_value' ][ 'source_shortcode' ] = '[' . $meta[ 'meta_value' ][ 'source_shortcode' ] . ']';
				// update_post_meta( $meta[ 'post_id' ], '_video', $meta[ 'meta_value' ] );
				
				$runtime = isset( $meta[ 'meta_value' ][ 'runtime' ] ) ? (array) $meta[ 'meta_value' ][ 'runtime' ] : [];
				if( isset( $shortcode[ 'source_path' ] ) && ! empty( $shortcode[ 'source_path' ] ) ) {
					$scanSrc = FWP_COURSES_LESSON_ROOT . '/' . $shortcode[ 'source_path' ];
					if( is_dir( $scanSrc ) ) {
						$scanList = (array) preg_grep('~\.(' . implode( '|', FWP_COURSES_ALLOWED_VIDEO_EXTENSIONS ) . ')$~', scandir( $scanSrc ) );$firstVideo = false;
						foreach( $scanList as $videoRow ) {
							if( ! $firstVideo ) {
								$firstVideo = true;
								$vidPath = $scanSrc . '/' . $videoRow;
								$meta[ 'meta_value' ][ 'scanList' ] = $scanList;
								if( ! in_array( $videoRow, [ '.', '..', '' ] ) && ! is_dir( $vidPath ) && file_exists( $vidPath ) ) {
									// $videoInfo = $this->_get_video_attributes( $vidPath );
									$command = '/usr/bin/ffmpeg -i "' . $vidPath . '" 2>&1 | grep -o -P "(?<=Duration: ).*?(?=,)"';
									$output = shell_exec($command);
									$output = str_replace( [ "\n", '.', ',' ], [ '', ':', '' ], $output );
									$output = explode( ':', $output );
									$videoInfo = [
										// 'src'				=> $vidPath,
										'hours'			=> isset( $output[0] ) ? $output[0] : 00,
										'minutes'		=> isset( $output[1] ) ? $output[1] : 00,
										'seconds'		=> isset( $output[2] ) ? $output[2] : 00,
										'milisec'		=> isset( $output[3] ) ? $output[3] : 00
									];
									// $meta[ 'meta_value' ][ 'videoInfo' ] = $videoInfo;
									$runtime = $videoInfo;
								}
							}
						}
						$meta[ 'meta_value' ][ 'runtime' ] = $runtime;
						update_post_meta( $meta[ 'post_id' ], '_video', $meta[ 'meta_value' ] );
					}
				}
				// wp_send_json_success( $meta, 200 );
			// } else {
			// 	wp_send_json_error( $shortcode, 300 );
			}
		}
	}
	public function _get_video_attributes( $video ) {
		$codec = '';$width = 0;$height = 0; $hours = 0;$mins = 0;$secs = 0;$ms = 0;
		// /usr/bin/ffmpeg -i '20 [Ideas] Conducting A Brain Dump.mp4' 2>&1 | grep Duration | awk '{print $2}'
		// Will return like this 00:01:09.17,
		// /usr/bin/ffmpeg -i '20 [Ideas] Conducting A Brain Dump.mp4' 2>&1 | grep -o -P "(?<=Duration: ).*?(?=,)"
		// Will return like this 00:01:09.17
		$command = '/usr/bin/ffmpeg -i ' . $video . ' -vstats 2>&1';
    $output = shell_exec($command);

    $regex_sizes = "/Video: ([^,]*), ([^,]*), ([0-9]{1,4})x([0-9]{1,4})/"; // or : $regex_sizes = "/Video: ([^\r\n]*), ([^,]*), ([0-9]{1,4})x([0-9]{1,4})/"; (code from @1owk3y)
    if( preg_match( $regex_sizes, $output, $regs ) ) {
        $codec = $regs[1] ? $regs[1] : null;
        $width = $regs[3] ? $regs[3] : 00;
        $height = $regs[4] ? $regs[4] : 00;
    }

    $regex_duration = "/Duration: ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}).([0-9]{1,2})/";
    if( preg_match( $regex_duration, $output, $regs ) ) {
        $hours = $regs[1] ? $regs[1] : 00;
        $mins = $regs[2] ? $regs[2] : 00;
        $secs = $regs[3] ? $regs[3] : 00;
        $ms = $regs[4] ? $regs[4] : 00;
    }

    return array(
			// 'codec'			=> $codec,
			'width'			=> $width,
			'height'		=> $height,
			'hours'			=> $hours,
			'minutes'		=> $mins,
			'seconds'		=> $secs,
			'ms'				=> $ms,
			// 'output'		=> $output
    );
	}
	public function tutor_course_builder_after_btn_group( $topic_id, $course_id ) {
		?>
		<button class="tutor-btn tutor-btn-outline-primary tutor-btn-sm tutor-fwp-generate-autometically-btn" data-topic-id="<?php echo $topic_id; ?>" data-course-id="<?php echo $course_id; ?>" title="<?php esc_attr_e( 'To delete all lessons autometically, press `CTRL + SHIFT + K`', 'domain' ); ?>">
			<i class="tutor-icon-plus-square tutor-mr-8"></i>
			<?php esc_html_e('Auto Generate', 'domain'); ?>
		</button>
		<?php
	}
	public function fwp_project_seojaws_get_lessons() {
		if( isset( $_POST[ 'topic_path' ] ) ) {
			$scanList = $this->listAllFiles( FWP_COURSES_LESSON_ROOT . '/' . $_POST[ 'topic_path' ], true );
			$sortedList = [];
			foreach( $scanList as $i => $item ) {
				$args = explode( '/', $item );
				$sortedList[] = end( $args );
			}
			wp_send_json_success( $sortedList, 200 );
		} else {
			wp_send_json_error( __( 'Not found', 'domain' ), 400 );
		}
	}


	/**
	 * Additional Functions.
	 */
	public function listAllFiles( $dir, $dironly = false ) {
		$array = array_diff(scandir($dir), array('.', '..'));
		$sorted = [];
		foreach( $array as &$item ) {
			$item = $dir . DIRECTORY_SEPARATOR . $item;
		}
		unset($item);
		foreach( $array as $item ) {
			if( $dironly ) {
				if( is_dir( $item ) ) {
					$sorted[] = $item;
				}
			} else {
				if( is_dir($item) ) {
					$array = array_merge($array, $this->listAllFiles($item . DIRECTORY_SEPARATOR) );
				}
			}
		}
		return ( $dironly ) ? $sorted : $array;
	}
	public function moveFiles( $list ) {
		foreach( $list as $args ) {
			$file = $args[ 'basename' ] . '.mp4';
			if( file_exists( $file )&& ! is_dir( $file ) ) {
				is_dir( $args[ 'basename' ] ) || wp_mkdir_p( $args[ 'basename' ] ); // mkdir( $args[ 'basename' ], 0777, true );
				rename( $file, $args[ 'basename' ] . DIRECTORY_SEPARATOR . $file);
			}
			foreach( $args[ 'relatives' ] as $file ) {
				rename( $file, $args[ 'basename' ] . DIRECTORY_SEPARATOR . $file);
			}
		}
	}
	public function getLists() {
		$files = glob( "*" );$list = [];
		foreach( $files as $file ) {
			$basename = pathinfo( $file, PATHINFO_FILENAME );
			if( ! in_array( $file, [ '.', '..' ] ) && is_dir( $basename ) ) {
				$args = [
					'basename'	=> $basename,
					'relatives'	=> [],
					'subdir'	=> is_dir( $basename ) ? $this->listAllFiles( $basename ) : []
				];
				foreach( [ 'srt', 'vtt' ] as $ext ) {
					foreach( [ 'en' ] as $lan ) {
						$relative = $basename . '.' . $lan . '.' . $ext;
						if( file_exists( $relative ) ) {
							$args[ 'relatives' ][] = $relative;
						}
					}
				}
				$list[] = $args;
			}
		}
		return $list;
	}
	public function getVideoInfo( $files ) {
		foreach( $files as $i => $row ) {
			// if( $row[ 'basename' ] == '01 Welcome' ) {
				$filePath = __DIR__ . '/' . $row[ 'subdir' ]['4'];
				$command = '/usr/bin/ffmpeg -i "' . $row[ 'subdir' ]['4'] . '" 2>&1 | grep -o -P "(?<=Duration: ).*?(?=,)"';
				$output = shell_exec($command);
				// print_r( $filePath . "\n" );
				$output = str_replace( [ "\n", '.', ',' ], [ '', ':', '' ], $output );
				$output = explode( ':', $output );
				$videoInfo = [
					'hours'			=> isset( $output[0] ) ? $output[0] : 00,
					'minutes'		=> isset( $output[1] ) ? $output[1] : 00,
					'seconds'		=> isset( $output[2] ) ? $output[2] : 00,
					'milisec'		=> isset( $output[3] ) ? $output[3] : 00
				];
				$row['videoInfo'] = $videoInfo;
				$files[ $i ] = $row;
			// }
		}
		return $files;
	}
	
}

<?php
/**
 * Bootstraps the Theme.
 *
 * @package Divi-child
 */

class FWP_DIVI_CHILD_THEME {
	protected $version = null;
	protected $options = NULL;
	public function __construct() {
		$this->version = function_exists( 'wp_get_theme' ) ? wp_get_theme()->get( 'Version' ) : '1.1.2';
		$this->setup_hooks();
	}
	private function setup_hooks() {
		/**
		 * Actions.
		 */
		add_filter( 'body_class', [ $this, 'body_class' ], 10, 1 );
    add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ], 10, 0 );
    add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ], 10, 1 );
		add_action( 'admin_init', [ $this, 'admin_init' ], 10, 0 );
		add_action( 'init', [ $this, 'optionPage' ], 10, 0 );

		add_action( 'init',[ $this, 'frontend' ], 10, 0 );

		define( 'FWP_COURSES_PREFIX', 'fwp_courses' );
		defined( 'FWP_COURSES_REPLACE_URL_SLASH' ) || define( 'FWP_COURSES_REPLACE_URL_SLASH', '_FWPSlash_' );
		defined( 'FWP_COURSES_REPLACE_URL_INSTEAD' ) || define( 'FWP_COURSES_REPLACE_URL_INSTEAD', [ '/' ] );
		defined( 'FWP_COURSES_REPLACE_URL_REPLACE' ) || define( 'FWP_COURSES_REPLACE_URL_REPLACE', [ FWP_COURSES_REPLACE_URL_SLASH ] );
		// defined( 'FWP_COURSES_MAIN_CLASS' ) || define( 'FWP_COURSES_MAIN_CLASS', $this );
		
		defined( 'FWP_COURSES_ALLOWED_COMPRESSION_EXTENSIONS' ) || define( 'FWP_COURSES_ALLOWED_COMPRESSION_EXTENSIONS', explode( ',', $this->get_option( 'compression_extensions', '7z,zip,rar' ) ) );
		defined( 'FWP_COURSES_ALLOWED_VIDEO_EXTENSIONS' ) || define( 'FWP_COURSES_ALLOWED_VIDEO_EXTENSIONS', explode( ',', $this->get_option( 'video_extensions', 'mp4,mkv' ) ) );
		defined( 'FWP_COURSES_ALLOWED_SUBTITLE_EXTENSIONS' ) || define( 'FWP_COURSES_ALLOWED_SUBTITLE_EXTENSIONS', explode( ',', $this->get_option( 'video_st_extensions', 'srt,vtt' ) ) );
		defined( 'FWP_COURSES_ALLOWED_VIDEO_SIZES' ) || define( 'FWP_COURSES_ALLOWED_VIDEO_SIZES', $this->get_option( 'video_sizes', '1080,720,480' ) );
		// defined( 'FWP_COURSES_ALLOWED_VIDEO_SIZES' ) || define( 'FWP_COURSES_ALLOWED_VIDEO_SIZES', explode( ',', $this->get_option( 'video_sizes', '1080,720,480' ) ) );
		defined( 'FWP_COURSES_ALLOWED_ATTACHMENTS_EXTENSIONS' ) || define( 'FWP_COURSES_ALLOWED_ATTACHMENTS_EXTENSIONS', explode( ',', $this->get_option( 'attachments_extensions', 'epub,pdf,mobi,docx,fb2,html,doc,ibook,inf,azw,lit,prc,exe,pkg,pdb,ps,tr2,tr3,oxps,xps' ) ) );
		defined( 'FWP_COURSES_MAX_DOWNLOADS' ) || define( 'FWP_COURSES_MAX_DOWNLOADS', $this->get_option( 'max_downloads', 0 ) );
		defined( 'FWP_COURSES_ALLOW_SOURCE_ON_FAILCONVERT' ) || define( 'FWP_COURSES_ALLOW_SOURCE_ON_FAILCONVERT', $this->get_option( 'allow_source', false ) );
		defined( 'FWP_COURSES_ALLOW_SUBTITLE_CONVERT' ) || define( 'FWP_COURSES_ALLOW_SUBTITLE_CONVERT', $this->get_option( 'subtitle_convert', true ) );


		$possible_root = explode( '/', $_SERVER[ 'DOCUMENT_ROOT' ] );
		$possible_root[1] = isset( $possible_root[1] ) ? $possible_root[1] : 'home';$possible_root[2] = isset( $possible_root[2] ) ? $possible_root[2] : 'seojaws';$possible_root = [ $possible_root[0], $possible_root[1], $possible_root[2] ];
		defined( 'FWP_COURSES_POSSIBLE_ROOT' ) || define( 'FWP_COURSES_POSSIBLE_ROOT', implode( '/', $possible_root ) );
		defined( 'FWP_COURSES_LESSON_ROOT' ) || define( 'FWP_COURSES_LESSON_ROOT', $this->get_option( 'lesson_root', FWP_COURSES_POSSIBLE_ROOT . '/lessons' ) );
		defined( 'FWP_COURSES_FORMATED_POSITION' ) || define( 'FWP_COURSES_FORMATED_POSITION', WP_CONTENT_DIR . '/uploads/fwp-converted-videos/' );
		is_dir( FWP_COURSES_FORMATED_POSITION ) || wp_mkdir_p( FWP_COURSES_FORMATED_POSITION ); // chmod( $cache_folder, 0777 );
		defined( 'FWP_COURSES_FORMATED_POSITION_URL' ) || define( 'FWP_COURSES_FORMATED_POSITION_URL', str_replace( [ ABSPATH ], [ site_url( '/' ) ], FWP_COURSES_FORMATED_POSITION ) );

	}

	/**
	 * Add body class
	 */
	public function body_class( $classes ) {
		$class = [ 'fwp-body' ];
		
		if( is_front_page() ) {
			$class[] = 'body-front-page';
			if( isset( $_GET[ 'cert_hash' ] ) ) {$class[] = 'fwp-only-certificate';}
		}
		
		return array_merge( $classes, $class );
	}
  public function wp_enqueue_scripts() {
    // wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    // wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', [], $this->filemtime( get_stylesheet_directory() . '/style.css' ), 'all' );
    // wp_enqueue_script( 'custom-js', get_stylesheet_directory_uri() . '/js/scripts.js', [ 'jquery' ], $this->filemtime( get_stylesheet_directory() . '/js/scripts.js' ), true );
  }
  public function admin_enqueue_scripts( $hook ) {
		// if( 'edit.php' != $hook ) {return;}
    // wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', [], $this->filemtime( get_stylesheet_directory() . '/style.css' ), 'all' );
    wp_enqueue_script( 'fwp-project-divi-child', get_stylesheet_directory_uri() . '/js/backend.js', [ 'jquery' ], $this->filemtime( get_stylesheet_directory() . '/js/backend.js' ), true );
		wp_localize_script( 'fwp-project-divi-child', 'fwpSeoJawssiteConfig', [
			'ajaxUrl'								=> admin_url( 'admin-ajax.php' ),
			'postUrl'								=> admin_url( 'post.php' ),
			'ajax_nonce'						=> wp_create_nonce( 'fwp_seojaws_ajax_nonce' ),
			'confirmAutoGenerate'		=> __( 'Are you sure yo want to generate lessons autometically? Press okay to continue, cancel to stop process.', 'domain' ),
			'confirmAutoRemove'			=> __( 'You\'ve pressed CTRL + SHIFT + K. That meant you want to remove all of the lessons on currently visible topic.\nBefore we proceed, please make sure you\'re confirm what you\'ve done. This can\'t be undo.', 'domain' ),
			'selectALesson'					=> __( 'Select a Lesson', 'domain' ),
		] );
  }
  public function filemtime( $file ) {
    return ( file_exists( $file ) && ! is_dir( $file ) ) ? filemtime( $file ) : rand( 0, 999999 );
  }

	/**
	 * Metabox function on Admin side.
	 */
	public function admin_init() {
		add_action( 'add_meta_boxes', [ $this, 'metaboxes' ], 10, 0 );
		add_action( 'save_post_courses', [ $this, 'save' ], 10, 1 );
		add_action( 'admin_footer', [ $this, 'admin_footer' ], 10, 0 );
		add_action( 'admin_init', [ $this, 'webpcConvertPaths' ], 10, 0 );

		add_action( 'course-topic/after/modal_wrappers', [ $this, 'modelwrapers' ], 10, 0 );
		add_action( 'wp_ajax_fwp_tutor_shortcode_select', [ $this, 'getListAjax' ], 10,0 );
	}
	public function webpcConvertPaths() {
		$testmode = false;
		// WP_CONTENT_URL WP_CONTENT_DIR ABSPATH
    $uploadpath = ''; // WP_CONTENT_DIR . "/uploads/";
		// Custom folders in uploads
		$custom_folders = [ FWP_COURSES_FORMATED_POSITION ];  
    foreach ($custom_folders as $key => $custom_folder) {
        $paths = array();          
        $path = $uploadpath . $custom_folder;
        // Known linux problem with "." ".." in scandir()
        $folders = array_diff(scandir($path), array('..', '.'));
        foreach ($folders as $key => $folder) {
            if(is_file($folder)){
                $paths[] = $path . $folder;
            } else {
                // Known linux problem with "." ".." in scandir()
                $subfolders[$folder] = array_diff(scandir($path . $folder), array('..', '.'));
            }
        }
        foreach ($subfolders as $folder => $file) {
            foreach ($file as $key => $filename) {
                if(is_file($path . $folder . "/" . $filename)){
                    $paths[] = $path . $folder . "/" . $filename;
                } else {
                    // Known linux problem with "." ".." in scandir()
                    $subsubfolders[$folder . "/" . $filename] = array_diff(scandir($path . $folder . "/" . $filename), array('..', '.'));
                }
            }
        }
        foreach ($subsubfolders as $folder => $file) {
            foreach ($file as $key => $filename) {
                if(is_file($path . $folder . "/" . $filename)){
                    $paths[] = $path . $folder . "/" . $filename;
                }
            }
        }                
        if($testmode){
            //debug output in admin dashboard
            echo '<pre>' . print_r($paths) . '</pre>';             
        } else {
            // image checks are done in webpc 
            do_action('webpc_convert_paths', $paths);
        }
        unset($paths);      
        unset($subfolders);
        unset($subsubfolders);
    }
	}
	public function optionPage() {
		if( is_admin( ) ) {
			if( ! class_exists( 'FWP_OPTIONPAGE' ) ) {require_once( get_stylesheet_directory() . '/inc/class-option.php' );}
			$option = [
				FWP_COURSES_PREFIX	=> [
					'parent_slug'	=> 'tutor',
					'page_title'	=> __( 'Lesson setup', 'fwp-divi-child-seojaws' ),
					'menu_slug'	=> 'tutor_download_setup',
					'position' => 10,

					'sections'		=> [
						'section-general'	=> [
							'title'			=> __( 'General Settings', 'fwp-divi-child-seojaws' ),
							'text'			=> __( 'Please make sure you\'ve supplied valid informations. And make sure you\'ve compressed all folders.', 'fwp-divi-child-seojaws' ),
							'fields'		=> [
								'lesson_root'		=> [
									'id'				=> 'lesson_root',
									'title'			=> __( 'Lesson root', 'fwp-divi-child-seojaws' ),
									'placeholder'	=> 'Lesson folder path on your Root.', 'value'	=> FWP_COURSES_LESSON_ROOT,
									'text'			=> __( 'Lesson root folder is for security purpose. If you move your lessons on your File manager\'s root folder, then it will be safe and no one can download it without Purchese. Make sure you don\'t place a slash after all.', 'fwp-divi-child-seojaws' ),
								],
								'compression_extensions'		=> [
									'id'				=> 'compression_extensions',
									'title'			=> __( 'Compression Extension', 'fwp-divi-child-seojaws' ),
									'placeholder'	=> 'Compressed file extensions', 'value'	=> '7z,zip,rar',
									'text'			=> __( 'Compress your lessons and give here which formate have you use to compress. Comma saperated and don\'t use space after comma.', 'fwp-divi-child-seojaws' ),
								],
								'video_extensions'		=> [
									'id'				=> 'video_extensions',
									'title'			=> __( 'Video Extension', 'fwp-divi-child-seojaws' ),
									'placeholder'	=> 'Allowed Video file extensions', 'value'	=> 'mp4,mkv,3gp',
									'text'			=> __( 'Give here all video extensions of courses. Basically MP4 is the best formate for your video.', 'fwp-divi-child-seojaws' ),
								],
								'video_st_extensions'		=> [
									'id'				=> 'video_st_extensions',
									'title'			=> __( 'Video Subtitle Extension', 'fwp-divi-child-seojaws' ),
									'placeholder'	=> 'Allowed Video Subtitle extensions', 'value'	=> 'srt,vtt',
								],
								'video_sizes'		=> [
									'id'				=> 'video_sizes',
									'title'			=> __( 'Video Sizes', 'fwp-divi-child-seojaws' ),
									'type'			=> 'select',
									'placeholder'	=> 'Allowed Video Sizes', 'value'	=> [ '1080', '720', '480' ],
									'text'			=> __( 'Select some video resulation for frontend. Try to give around of three. More sizes could down your server ot Eat your hosting space.', 'fwp-divi-child-seojaws' ),
									'choices' => [
										'1080' => __( '1080 HQ', 'domain' ),
										'720' => __( '720', 'domain' ),
										'480' => __( '480', 'domain' ),
									],
									'attributes' => [
										'multiple' => 'multiple'
									],
									'sanitize' => true
								],
								'video_default_quality'		=> [
									'id'				=> 'video_default_quality',
									'title'			=> __( 'Default quality', 'fwp-divi-child-seojaws' ),
									'placeholder'	=> 'Default Quality', 'value'	=> '720',
									'text'			=> __( 'Default video quality that should be the defult quality on front end.', 'fwp-divi-child-seojaws' ),
								],
								'video_default_frame'		=> [
									'id'				=> 'video_default_frame','type' => 'select',
									'title'			=> __( 'Default Frame', 'fwp-divi-child-seojaws' ),
									'placeholder'	=> '320x240', 'value'	=> '426x240',
									'text'			=> __( 'Video frame size meant, all of your videos will convert with their requested quality, but this frame is used to define actual video height and width to reduce some more sizes. And all of your video will convert with this size, and then students can easily watch video without loading as possible.', 'fwp-divi-child-seojaws' ),
									'choices' => [
										'7680x4320' =>	'7680p (8K)',
										'3840x2160' =>	'2160p (4K)',
										'2560x1440' =>	'1440p',
										'1920x1080' =>	'1080p',
										'1280x720' =>	'720p',
										'854x480' =>	'480p',
										'640x360' =>	'360p',
										'426x240' =>	'240p',
									]
								],
								'max_downloads' => [
									'id'				=> 'max_downloads',
									'title'			=> __( 'Max downloads', 'fwp-divi-child-seojaws' ),
									'type'				=> 'number',
									'placeholder'	=> 'Maximum downloads', 'value'	=> 0,
									'text'			=> __( 'Maximum downloads per course. Leave it Zero (0) if you want to set Unlimited.', 'fwp-divi-child-seojaws' ),
								],
								'allow_source' => [
									'id'				=> 'allow_source',
									'title'			=> __( 'Allow Source video', 'fwp-divi-child-seojaws' ),
									'type'				=> 'checkbox', 'value'	=> true,
									'text'			=> __( 'Sometimes FFMPEG return fails to convert video file or sometimes it is converting files but files are 0 KiB. That means empty files. So, on these situation, are you wishing to gave them default source video on replace? This source video will provide from your original source location. If you deisabled it, then error page will be displayed by default.', 'fwp-divi-child-seojaws' ),
								],
								'subtitle_convert' => [
									'id'				=> 'subtitle_convert',
									'title'			=> __( 'Allow Subtitle convert', 'fwp-divi-child-seojaws' ),
									'type'				=> 'checkbox', 'value'	=> true,
									'text'			=> __( 'Javascript Video player (plyr.io) is not supporting current .srt file. So, we\'ve to convert these files form .srt ( SubRip File Format ) to .vtt ( Web Video Text Track ) formate. To avaid junk issue, there it is outputting directly without saving on server.', 'fwp-divi-child-seojaws' )
								],
								'attachments_extensions'		=> [
									'id'				=> 'attachments_extensions',
									'title'			=> __( 'Allowed Attachments', 'fwp-divi-child-seojaws' ),
									'placeholder'	=> 'Allowed attachments extensions', 'value'	=> 'epub,pdf,mobi,docx,fb2,html,doc,ibook,inf,azw,lit,prc,exe,pkg,pdb,ps,tr2,tr3,oxps,xps',
									'text'			=> sprintf(
										__( 'Some courses has extra files like PDF, HTML files, etc. So, you can easily mark them as attachments to students lesson and default value is set on only ebook formates. You can see here %s all ebook formates %s at a place. Don\'t touch it if you\'re confuse.', 'fwp-divi-child-seojaws' ),
										'<a href="https://en.wikipedia.org/wiki/Comparison_of_e-book_formats" target="_blank">', '</a>'
									),
								]
							],
						],
						'section-advanced' => [
							'title' => __( 'Advance control', 'fwp-divi-child-seojaws' ),
							'text' => __( 'Setup additional advance settings like controling player, playlist, attachemtns and so on.', 'fwp-divi-child-seojaws' ),
							'fields' => [
								'playlist_icon'		=> [
									'id'			=> 'playlist_icon',
									'title'			=> __( 'Playlist Icon', 'fwp-divi-child-seojaws' ),
									'type'			=> 'checkbox', 'value' => true,
									'text'			=> __( 'Enable playlist icon on frontend lesson.', 'fwp-divi-child-seojaws' )
								],
								'playlist_title'		=> [
									'id'			=> 'playlist_title',
									'title'			=> __( 'Playlist Title', 'fwp-divi-child-seojaws' ),
									'type'			=> 'checkbox', 'value' => true,
									'text'			=> __( 'Make it enabled visible playlist title on frontend lesson.', 'fwp-divi-child-seojaws' )
								],
								'playlist_download'		=> [
									'id'			=> 'playlist_download',
									'title'			=> __( 'Playlist Download', 'fwp-divi-child-seojaws' ),
									'type'			=> 'checkbox', 'value' => true,
									'text'			=> __( 'If you enalbe this field, then students can easily download single video from their lesson.', 'fwp-divi-child-seojaws' )
								],
								'attachments_column'		=> [
									'id'			=> 'attachments_column',
									'title'			=> __( 'Attachment columns', 'fwp-divi-child-seojaws' ),
									'type'			=> 'select', 'value' => '2',
									'choices'		=> [
										'column-0' => __( 'None', 'fwp-divi-child-seojaws' ),
										'column-1' => __( 'One column', 'fwp-divi-child-seojaws' ),
										'column-2' => __( 'Two column', 'fwp-divi-child-seojaws' ),
										'column-3' => __( 'Three column', 'fwp-divi-child-seojaws' ),
										'column-4' => __( 'Four column', 'fwp-divi-child-seojaws' )
									],
									'text'			=> __( 'If you enalbe this field, then students can easily download single video from their lesson.', 'fwp-divi-child-seojaws' )
								],
								'attachments_download'		=> [
									'id'			=> 'attachments_download',
									'title'			=> __( 'Attachment Download', 'fwp-divi-child-seojaws' ),
									'type'			=> 'checkbox', 'value' => true,
									'text'			=> __( 'What should happen, when students click over attachments link? If you enable this, then it will download those files with their woun name and else it\'ll open linkon new tab.', 'fwp-divi-child-seojaws' )
								]
							]
						]
					],
				]
			];
			$option_page = new FWP_OPTIONPAGE( $option );
		}
	}
	public function metaboxes() {
		$screens = [ 'courses' ];
		foreach ( $screens as $screen ) {
			add_meta_box(
				'fwa_course',
				__( 'Cource download Info', 'fwp-divi-child-seojaws' ),
				[ $this, 'metabox' ],
				$screen,
				'side'
			);
		}
	}
	public function save( $post_id ) {
		/**
		 * When the post is saved or updated we get $_POST available
		 * Check if the current user is authorized
		 */
		if( ! current_user_can( 'edit_post', $post_id ) ) {return;}
		/**
		 * Check if the nonce value we received is the same we created.
		 */
		if( ! isset( $_POST['fwp_certificate_nonce'] ) || ! wp_verify_nonce( $_POST['fwp_certificate_nonce'], 'fwp-certificate-nonce-field' ) ) {return;}

		if( array_key_exists( 'fwp_certificate_link', $_POST ) ) {
			update_post_meta(
				$post_id,
				'_fwp_course_status',
				( $_POST[ '_fwp_course_status' ] == 'on' ) ? true : false
			);
			update_post_meta(
				$post_id,
				'_fwp_course_download',
				$_POST[ 'fwp-certificate-url' ]
			);
			update_post_meta(
				$post_id,
				'_fwp_course',
				[
					'status' => ( $_POST[ '_fwp_course_status' ] == 'on' ) ? true : false,
					'link' => $_POST[ 'fwp_certificate_link' ],
					'url' => $_POST[ 'fwp-certificate-url' ]
				]
			);

		}
	}
	public function slugify($text, string $divider = '-') {
		// return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text)));

		$text = preg_replace('~[^\pL\d]+~u', $divider, $text);
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		$text = preg_replace('~[^-\w]+~', '', $text);
		$text = trim($text, $divider);
		$text = preg_replace('~-+~', $divider, $text);
		$text = strtolower($text);
		if (empty($text)) {
			return 'n-a';
		}
		return $text;
	}
	public function getList( $filter = false, $reScan = false ) {
		// $options = $this->get_option( 'list', [] );
		// $options = preg_grep('~\.(' . implode( '|', FWP_COURSES_ALLOWED_COMPRESSION_EXTENSIONS ) . ')$~', scandir( FWP_COURSES_LESSON_ROOT, 1 ) );
		$options = scandir( FWP_COURSES_LESSON_ROOT, 1 );
    $mainList = [];
		foreach( $options as $i => $option ) {
			if( ! in_array( $option, [ '.', '..', '' ] ) && is_dir( FWP_COURSES_LESSON_ROOT . '/' . $option ) ) {
				$mainList[] = [ $i, pathinfo( basename( $option ), PATHINFO_FILENAME ) ];
			}
		}
		if( $filter !== false ) {
			foreach( $mainList as $option ) {
				if( $option[ 1 ] == $filter ) {
					if( $reScan ) {
						if( is_dir( FWP_COURSES_LESSON_ROOT . '/' . $filter ) ) {
							$optns = scandir( FWP_COURSES_LESSON_ROOT . '/' . $filter, 0 );$optnList = [];
							foreach( $optns as $j => $optn ) {
								if( ! in_array( $optn, [ '.', '..', '' ] ) && is_dir( FWP_COURSES_LESSON_ROOT . '/' . $filter . '/' . $optn ) ) {
									$optnList[] = [ $j, $optn ];
								}
							}
							return $optnList;
						}
						return [];
					} else {
						return $option;
					}
				}
			}
		} else {
			return $mainList;
		}
	}
	public function stylesheet() {
		?>
		<style>
			.fwp-switcher{ position: relative; display: inline-block; width: 60px; height: 34px;} .fwp-switcher input.fwp-switcher-input{ opacity: 0; width: 0; height: 0;} .fwp-switchercher-slider{ position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; -webkit-transition: .4s; transition: .4s;} .fwp-switchercher-slider:before{ position: absolute; content: ""; height: 26px; width: 26px; left: 4px; bottom: 4px; background-color: white; -webkit-transition: .4s; transition: .4s;} .fwp-switcher input.fwp-switcher-input:checked + .fwp-switchercher-slider{ background-color: #2196F3;} .fwp-switcher input.fwp-switcher-input:focus + .fwp-switchercher-slider{ box-shadow: 0 0 1px #2196F3;} .fwp-switcher input.fwp-switcher-input:checked + .fwp-switchercher-slider:before{ -webkit-transform: translateX(26px); -ms-transform: translateX(26px); transform: translateX(26px);}
			#fwp-is-opened {display: none;}#fwp-is-opened.opened {display: block;}
			.fwp-flex {display: flex;flex-wrap: wrap;flex-direction: row;}.fwp-flex .fwp-col-3 {width: 25%;}.fwp-flex .fwp-col-9 {width: 75%;}.fwp-line-h-3 {line-height: 30px;}
			#fwp-certificate-url {user-select: all;-moz-user-select: all;-webkit-user-select: all;-ms-user-select: all;}
		</style>
		<?php
	}
	public function admin_footer() {
		?>
		<style>.wp-admin .update-nag.notice.notice-warning.inline.update-nag-mailster {display: none;}</style>
		<?php
	}

	/**
	 * Metabox HTML content here starting on "metabox_{post_type}.
	 */
	public function metabox( $post ) {
		$course = get_post_meta( $post->ID, '_fwp_course', true );
		$course = is_array( $course ) ? $course : [];$course[ 'status' ] = get_post_meta( $post->ID, '_fwp_course_status', true );
		$course = wp_parse_args( $course, [ 'status' => false, 'link' => '', 'url' => '' ] );
		$options = $this->getList();
		array_unshift( $options, [ 0, __( 'Select' ) ] );
		/**
		 * Use nonce for verification.
		 * This will create a hidden input field with id and name as
		 * 'fwp_certificate_nonce' and unique nonce input value.
		 */
		wp_nonce_field( 'fwp-certificate-nonce-field', 'fwp_certificate_nonce' );
		// print_r( $course );
		?>
		<div class="fwp-flex">
			<div class="fwp-col-3 fwp-line-h-3">
				<?php esc_html_e( 'Status', 'fwp-divi-child-seojaws' ); ?>:
			</div>
			<div class="fwp-col-9">
				<label class="fwp-switcher">
					<input class="fwp-switcher-input" name="_fwp_course_status" type="checkbox" <?php checked( $course[ 'status' ], true ); ?>>
					<span class="fwp-switchercher-slider"></span>
				</label>
			</div>
		</div>
		<div class="<?php echo esc_attr( ( $course[ 'status' ] ) ? 'opened' : 'closed-i' ); ?>" id="fwp-is-opened">
			<label for="fwp-certificate-link"><?php esc_html_e( 'Select course', 'fwp-divi-child-seojaws' ); ?></label>
			<select name="fwp_certificate_link" id="fwp-certificate-link" class="postbox">
				<?php
				foreach( $options as $row ) {
					?>
					<option value="<?php echo esc_attr( $this->urlencode( $row[ 1 ] ) ); ?>" <?php selected( $course[ 'link' ], $this->urlencode( $row[ 1 ] ) ); ?> <?php echo ( isset( $row[ 2 ] ) && $row[ 2 ] ) ? 'disabled' : ''; ?>><?php echo esc_html( $row[ 1 ] ); ?></option>
					<?php
				}
				?>
			</select>
			<input type="<?php echo esc_attr( ( $course[ 'status' ] ) ? 'text' : 'hidden' ); ?>" name="fwp-certificate-url" class="postbox" id="fwp-certificate-url" value="<?php echo esc_attr( $course[ 'url' ] ); ?>" onclick="this.select();" />
		</div>
		<?php
		$this->stylesheet();
	}
	public function urlencode( $url, $mode = true ) {
		$instead = FWP_COURSES_REPLACE_URL_INSTEAD;$replace = FWP_COURSES_REPLACE_URL_REPLACE;
		if( $mode ) {
			return urlencode( str_replace( $instead, $replace, $url ) );
		} else {
			return str_replace( $replace, $instead, urldecode( $url ) );
		}
	}
	public function download() {
		/**
		 * Need to verify if any product contains this Cources allowed?
		 * Then if this user has access on his download list.
		 * Then If there is any limitation or not.
		 */
		
		if( false ) :
			// ini_set('display_errors',1);
			// error_reporting(E_ALL|E_STRICT);
			// // define some variables
			// $local_file = 'filename.jpg';
			// $server_file = 'filename.jpg';
			// $ftpInfo = get_option( 'mja-wowza-ftp', [] );
			// // $ftpInfo = wp_parse_args( $ftpInfo, [
			// // 	'host' => 'www.wowzacontrol.com',
			// // 	'port' => 2121,
			// // 	'user' => 'tyddxntyna@www.wowzacontrol.com',
			// // 	'pass' => '596d2SuNaKhW'
			// // ] );
	
			// $connFtp = ftp_connect( $ftpInfo[ 'host' ], $ftpInfo[ 'port' ], 90 ) or wp_die( __( 'Couldn\'t connect with database. Please contact with site Admin.', 'fwp-divi-child-seojaws' ), __( 'Faild to connect', 'fwp-divi-child-seojaws' ) );
	
			// // login with username and password
			// $login_result = ftp_login( $connFtp, $ftpInfo[ 'user' ], $ftpInfo[ 'pass' ] );
	
			// if( $login_result ) {
			//   var_dump( ftp_nlist( $connFtp, "." ) );
			// }
	
			// $size = ftp_size( $connFtp, $server_file );
	
			// if( false && $size >= 0 ) {
			// 	header('Content-Description: File Transfer');
			// 	header('Content-Type: application/zip');
			// 	header('Content-Disposition: attachment; filename="'.basename($file).'"');
			// 	header('Expires: 0');
			// 	header('Cache-Control: must-revalidate');
			// 	header('Pragma: public');
			// 	header('Content-Length: ' . $size);
			// 	readfile( 'ftp://'.$ftpInfo[ 'user' ].':'.urlencode($ftpInfo[ 'pass' ]).'@'.$ftpInfo[ 'host' ].'/'.$file );
			// }
			
			// // $local_file = fopen( 'php://output' );
			// // readfile("php://output");
			// // try to download $server_file and save to $local_file
			// // if( ftp_get( $connFtp, $local_file, $server_file, FTP_BINARY ) ) {
			// // 	echo "Successfully written to $local_file\n";
			// // } else {
			// // 	echo "There was a problem\n";
			// // }
			// // close the connection
			// ftp_close( $connFtp );
		endif;
		/**
		 * Temporary redirecting to their link. But needs to make Compression for them.
		 */
		// wp_redirect( $list[ 2 ], 302 );
		// wp_die( print_r( $list ) );
	}
	public function getRFsize( $url, $fS = true, $useHead = true) {
    if( false !== $useHead ) {
			stream_context_set_default( [ 'http' => [ 'method' => 'HEAD' ] ] );
    }
    $head = array_change_key_case( get_headers( $url, 1 ) );
    // content-length of download (in bytes), read from Content-Length: field
    $clen = isset( $head['content-length'] ) ? $head['content-length'] : 0;

    // cannot retrieve file size, return "-1"
    if( ! $clen ) {
        return -1;
    }

    if( ! $fS ) {
        return $clen; // return size in bytes
    }

    $size = $clen;
    // switch ($clen) {
    //     case $clen < 1024:
    //         $size = $clen .' B'; break;
    //     case $clen < 1048576:
    //         $size = round($clen / 1024, 2) .' KiB'; break;
    //     case $clen < 1073741824:
    //         $size = round($clen / 1048576, 2) . ' MiB'; break;
    //     case $clen < 1099511627776:
    //         $size = round($clen / 1073741824, 2) . ' GiB'; break;
    // }

    return $size; // return formatted size
	}

	/**
	 * Code on Front end dashboard.
	 */
	public function frontend() {
		// wp_enqueue_style( 'jQuery-dataTables', 'https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css', [], null, 'all' );
		// wp_enqueue_script( 'jQuery-dataTables', 'https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js', [ 'jquery' ], null, true );

		// add_action( 'admin_post_download-course-local', [ $this, 'download' ], 10, 0 );
		// add_action( 'admin_post_nopriv_download-course-local', [ $this, 'download' ], 10, 0 ); // for Not Logged in Users.
		add_filter( 'woocommerce_account_menu_items',[ $this, 'wc_menus' ], 10, 1 );
		// add_filter( 'woocommerce_get_endpoint_url',[ $this, 'wc_url' ], 10, 4 );
		add_action( 'woocommerce_account_cources_endpoint', [ $this, 'cources' ], 10, 0 );
		add_filter( 'woocommerce_endpoint_cources_title', [ $this, 'endpointTitle' ], 10, 3 );
		
		add_rewrite_endpoint( 'cources', EP_ROOT | EP_PAGES ); // add_rewrite_endpoint( 'cources', EP_PAGES );

		add_rewrite_rule( 'mycourses/([^/]*)/([^/]*)/([^/]*)/?', 'index.php?mycourses=$matches[1]&course=$matches[2]&download=$matches[3]', 'top' );
		// add_rewrite_rule( 'mycourses/([(watch|play)]*)/([^/]*)/([^/]*)/?', 'index.php?mycourses=$matches[1]&course=$matches[2]&download=$matches[3]', 'top' );
		add_filter( 'query_vars', [ $this, 'query_vars' ], 10, 1 );
		add_filter( 'template_include', [ $this, 'template_include' ], 10, 1 );

		// add_filter( 'template_redirect', [ $this, 'template_include' ], 10, 1 );

		// add_action( 'rest_api_init', [ $this, 'restEndpoint' ], 10, 0 );

		add_filter( 'tutor_course/loop/start/button', [ $this, 'loopBtn' ], 10,2 );
		add_filter( 'tutor_course/single/start/button', [ $this, 'loopBtn' ], 10,2 );

		add_filter( 'fwp/courses/convert/video', [ $this, 'getVideo' ], 10, 1 );
		add_filter( 'fwp/courses/convert/srt2vtt', [ $this, 'srt2vtt' ], 10, 1 );

		add_shortcode( 'fwpcourselesson',[ $this, 'videoPlayer' ] );

		add_filter( 'style_loader_tag', [ $this, 'styleLoader' ], 10, 4 );

		load_plugin_textdomain( 'fwp-divi-child-seojaws', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
	public function wc_menus( $items ) {
		$items = array_slice( $items, 0, 5, true ) + array( 'cources' => 'My Courses' ) + array_slice( $items, 5, NULL, true );
		return $items;
	}
	public function wc_url( $url, $endpoint, $value, $permalink ) {
		// wp_die( print_r( [$url, $endpoint, $value, $permalink] ) );}
		if( $endpoint == 'cources' ) {
			// Used to make external URL.
			// $url = 'cources';
		}
		return $url;
	}
	public function cources() {
		// wc_get_template_part('myaccount/wishlist');
		$orders = wc_get_orders( [
			'customer_id' => get_current_user_id(),
			'limit' => -1,
			'orderby' => 'date',
			'order' => 'DESC',
			// 'status' => [ 'completed' ],
		] );
		$tableContent = $this->fetchTable( $orders );
		if( count( $tableContent ) >= 1 ) :
			?>
			<table id="example" class="cell-border" style="width:100%">
					<thead>
							<tr>
									<th><?php esc_html_e( 'Course Name', 'fwp-divi-child-seojaws' ); ?></th>
									<th><?php esc_html_e( 'Reviews', 'fwp-divi-child-seojaws' ); ?></th>
									<th><?php esc_html_e( 'Hours', 'fwp-divi-child-seojaws' ); ?></th>
									<th><?php esc_html_e( 'Completed', 'fwp-divi-child-seojaws' ); ?></th>
									<th><?php esc_html_e( 'Launch', 'fwp-divi-child-seojaws' ); ?></th>
							</tr>
					</thead>
					<tbody>
						<?php foreach( $tableContent as $table ) : ?>
							<tr>
									<td><?php echo esc_html( $table[ 'title' ] ); ?></td>
									<td><?php echo esc_html( $table[ 'review' ] ); ?></td>
									<td><?php echo esc_html( $table[ 'hours' ] ); ?></td>
									<td><?php echo esc_html( $table[ 'complete' ] ); ?></td>
									<td>
										<a href="<?php echo esc_url( $course[ 'url' ] ); ?>" class="btn btn-success"><?php esc_html_e( 'Open', 'fwp-divi-child-seojaws' ); ?></a>
									</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
							<tr>
									<th><?php esc_html_e( 'Course Name', 'fwp-divi-child-seojaws' ); ?></th>
									<th><?php esc_html_e( 'Reviews', 'fwp-divi-child-seojaws' ); ?></th>
									<th><?php esc_html_e( 'Hours', 'fwp-divi-child-seojaws' ); ?></th>
									<th><?php esc_html_e( 'Completed', 'fwp-divi-child-seojaws' ); ?></th>
									<th><?php esc_html_e( 'Launch', 'fwp-divi-child-seojaws' ); ?></th>
							</tr>
					</tfoot>
			</table>
			<style>
				.fwp-body #et-main-area #main-content #content-area #left-area {padding-right: 10px;width: 100%;}.fwp-body #et-main-area #main-content #content-area #sidebar {display: none;}.fwp-body #et-main-area #main-content #content-area #left-area .woocommerce-MyAccount-navigation {width: 20%;min-width: 250px;}.fwp-body #et-main-area #main-content #content-area #left-area .woocommerce-MyAccount-content {width: 75%;}.fwp-body #et-main-area #main-content #content-area #left-area .woocommerce-MyAccount-content table tr th:first-child {min-width: 40%;}.fwp-body #et-main-area #main-content #content-area #left-area .woocommerce-MyAccount-content table tr th:last-child {max-width: 5%;width: 40px;}.fwp-body #et-main-area #main-content #content-area #left-area .woocommerce-MyAccount-content .dataTables_length, .fwp-body #et-main-area #main-content #content-area #left-area .woocommerce-MyAccount-content .dataTables_filter {margin-bottom: 10px;}.fwp-body #et-main-area #main-content #content-area #left-area .woocommerce-MyAccount-content table thead tr th, .fwp-body #et-main-area #main-content #content-area #left-area .woocommerce-MyAccount-content table tbody tr td {text-align: center;}.fwp-body #et-main-area #main-content #content-area #left-area .woocommerce-MyAccount-content table thead tr th:first-child, .fwp-body #et-main-area #main-content #content-area #left-area .woocommerce-MyAccount-content table tbody tr td:first-child {text-align: left;}.fwp-body #et-main-area #main-content #content-area #left-area .woocommerce-MyAccount-content table thead tr th, .fwp-body #et-main-area #main-content #content-area #left-area .woocommerce-MyAccount-content table tfoot tr th {text-transform: uppercase;}
			</style>
			<script>
				jQuery(document).ready(function () {jQuery('#example').DataTable();});
			</script>
			<?php
		else :
		$this->notAvailable();
		endif;
	}
	public function endpointTitle( $title, $endpoint, $action ) {
		if( $endpoint == 'cources' ) {
			return __( 'My courses List', 'fwp-divi-child-seojaws' );
		}
		return $title;
	}
	public function notAvailable() {
		?>
		<div class="main">
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="470" height="290" viewBox="0 0 470 290"><defs><path class="fundo" id="prefix__a" d="M5.063 128.67c-2.513 15.192 5.633 31.093 17.898 38.941 5.99 3.832 13.34 6.528 16.471 13.254 4.868 10.452-3.879 22.437-13.113 28.515-9.236 6.078-20.5 10.9-24.704 21.683-2.771 7.108-1.685 15.387 1.058 22.507 10.06 26.112 39.393 37.547 65.479 36.15 26.086-1.396 50.827-12.407 76.416-18.075 87.873-19.465 180.005 24.717 267.728 4.47 13.65-3.151 27.4-8.081 37.943-17.99 11.883-11.167 18.632-28.016 19.65-45.023.97-16.225-4.34-34.495-17.744-41.806-7.834-4.273-17.196-4.1-25.7-1.774-5.43 1.483-10.767 3.808-16.369 3.848-5.601.038-11.763-3-13.386-8.808-1.707-6.107 2.182-12.41 6.642-16.577 9.072-8.474 21.203-12.707 29.441-22.126 7.927-9.063 11.264-22.574 8.574-34.716-2.692-12.141-11.326-22.538-22.188-26.715-27.683-10.645-57.844 18.377-86.152 9.873-2.101-.63-4.312-1.605-5.418-3.641-1.08-1.988-.834-4.51-.214-6.716 3.468-12.348 16.939-20.21 17.528-33.102.32-7.008-3.504-13.564-8.325-18.251-33.126-32.2-81.125 6.102-114.9 18.194-55.542 19.884-112.157 36.49-167.849 55.963-20.81 7.275-44.91 18.606-48.766 41.922z"/></defs><g fill="none" fill-rule="evenodd"><path fill="#FFF" d="M0 0H1366V800H0z" transform="translate(-448 -157)"/><g transform="translate(-448 -157) translate(448 157)"><mask id="prefix__b" fill="#fff"><use xlink:href="#prefix__a"/></mask><use fill="#F6F6F7" xlink:href="#prefix__a"/><path fill="#EDEDF0" fill-rule="nonzero" d="M-14.199 211.2H481.36V301.2H-14.199z" mask="url(#prefix__b)"/><g class="paes"><g class="pao-baixo"><path fill="#FBB965" d="M2.79 131.737s-2.073 3.155-2.479 6.868c-.406 3.713-.747 9.666 1.24 13.372 1.985 3.707 12.69 20.8 65.175 21.02 53.15.225 69.188-15.685 70.59-18.977 2.605-6.118 1.838-21.327.06-22.283-1.777-.956-44.044-3.204-72.446-4.057-28.402-.854-49.872-1.968-62.14 4.057" transform="translate(161 68)"/><path fill="#E6A95F" d="M34.648 167.758c-8.863-1.526-23.515-6.939-30.292-14.218-6.775-7.28-2.096-8.803 3.508-5.387 5.605 3.415 24.569 11.557 54.124 12.263 29.555.706 61.424-6.946 72.2-17.053 0 0 2.705-1.47 2.768 1.509.062 2.98.428 7.948-2.769 10.507-3.196 2.558-34.805 23.526-99.54 12.379" transform="translate(161 68)"/><path fill="#FFDA7F" d="M5.679 131.837c-6.522 1.646-.275 6.91 9.492 12.14 9.767 5.229 28.24 10.257 44.267 10.015 16.028-.243 37.48-.481 52.543-5.333 15.06-4.852 16.223-9.55 17.998-13.298 1.774-3.748-107.32-7.809-124.3-3.524" transform="translate(161 68)"/></g><g class="pao-cima"><path fill="#FBB868" d="M71.37 0C49.008.035-2.43.631 1.18 51.16c0 0-.018 10.84 62.825 10.84 62.844 0 72.591-9.633 73.721-11.173C142.284 44.623 147.583-.117 71.37 0" transform="translate(161 68)"/><path fill="#E6A95F" d="M34.552 61c-7.628-1.006-23.98-2.904-27.586-5.506-3.606-2.604-7.448-2.895-5.39-10.826.842-3.242 7.976-.619 11.264.839 3.289 1.458 21.239 6.047 42.989 6.673 21.75.625 57.126-1.679 67.42-5.458 9.806-3.598 13.662-7.027 15.493-5.228 2.396 2.351 1.687 8.008-4.913 12.215-6.252 3.985-27.53 7.2-49.434 7.76-21.904.56-38.604 1.012-49.843-.469" transform="translate(161 68)"/><path fill="#FFEAD4" d="M45.508 13.114c-.368.549-.54 1.598-.503 2.445.017.392.297.604.45.287.143-.297.222-.617.303-.978.087-.387.197-.735.238-1.15.042-.44-.257-.95-.488-.604M42.092 9.016c-.694.13-1.446.61-1.774 1.098-.168.248-.3.512-.317.792-.017.313.154.503.29.776.249.494 1.245.392 1.22-.162-.014-.274.33-.612.54-.817.367-.361.75-.62.923-1.075.154-.404-.413-.7-.882-.612M51.621 9.247c-.182-.409-.68-.325-.615.364.063.687.007 1.485.25 2.067.19.458.694.473.737-.25.043-.759-.109-1.592-.372-2.181M32.55 15.101c-1.206.547-1.849 1.662-1.414 2.552.188.384 1.21.504 1.46.077.188-.32.407-.629.616-.942.243-.363.63-.675.767-1.064.173-.486-.753-.93-1.43-.623M29.793 9.012c-.26-.108-.498.532-.62.942-.166.565-.205 1.033-.149 1.674.053.59.424.405.493-.048-.002.014.102-.302.138-.4.093-.247.18-.497.262-.76.113-.359.144-1.297-.124-1.408M38.384 6.056c-.737-.211-1.406.211-1.881.674-.53.514-.607 1.19-.39 1.829.167.5 1.09.632 1.326.096.127-.285.31-.53.533-.764.304-.32.72-.44.944-.848.237-.429-.053-.85-.532-.987M21.722 10.101c-.484-.28-1.16.08-1.542.378-.57.444-.957.924-1.152 1.628-.21.764.802 1.182 1.296.663.4-.42.901-.746 1.308-1.172.319-.334.594-1.205.09-1.497M23.513 15.078c-.385.414-.505 1.566-.513 2.381-.005.47.333.749.47.35.206-.592.422-1.34.517-2.047.082-.598-.253-.921-.474-.684M38.964 14.6c-.26-.324-1.293-.581-2.192-.6-.626-.012-.971.28-.65.452.459.244 1.155.57 2.063.547.56-.014.936-.205.78-.4M51.58 3.028c-.54-.1-.912.074-1.399.401-.45.304-.83.813-1.092 1.395-.344.76.386 1.437.866 1.076.662-.5 1.41-.857 1.914-1.641.255-.397.126-1.152-.29-1.23M66.234 9c-.923 0-2.062.305-2.227.708-.074.182.437.384.836.247.537-.185 1.29-.187 1.832-.364.59-.193.337-.591-.441-.591M60.589 9.375c-.101-.522-.482-.493-.556.048-.12.852.102 1.815.423 2.412.213.396.543.02.544-.494.002-.736-.283-1.302-.411-1.966M69.955 3.569c-.44-.473-1.713-.712-2.727-.479-.37.085-.24.315.044.396.601.173 1.168.408 1.848.503.49.069 1.042-.199.835-.42M73.956 10.626c-.231-.836-.735-1.255-1.316-1.507-.24-.104-.5-.147-.75-.1-.148.028-.273.063-.407.161-.032.022-.373.238-.223.161-.282.148-.382.791-.057.979.117.067.22.24.333.325.168.128.336.247.508.364.327.219.564.609.873.868.537.45 1.27-.42 1.04-1.251M66.549 15.017c-.83-.233-.486 2.056-.435 2.528.055.51.678.664.741.08.068-.628.42-2.405-.306-2.608M54.803 16.301c-.065-.347-.1-.709-.19-1.038-.107-.393-.44-.32-.532.052-.186.746-.052 2.313.405 2.636.225.16.545-.077.512-.623-.024-.375-.13-.676-.195-1.027M39.534 21.024c-.423.212-.58 1.352-.523 2.174.066.946.664 1.13.785.144.065-.538.22-1.041.203-1.612-.016-.528-.238-.82-.465-.706M15.946 21.201c-.04-.142-.134-.197-.214-.2-.311-.02-.464.621-.576 1.05-.124.468-.188.945-.14 1.461.053.562.486.699.57.088.053-.375.146-.754.233-1.107.108-.439.265-.815.127-1.292M14.918 16.274c-.067-.169-.25-.279-.46-.274-.571.015-1.05.232-1.55.61-.562.422-.976 1.023-.899 1.675.081.697.993.942 1.574.476.407-.326.746-.755 1.058-1.149.364-.462.441-.923.277-1.338M62.906 5.209c-.447-.277-1.34-.251-1.957-.083-.279.077-.57.172-.738.298-.069.051-.108.105-.15.16-.025.038-.037.076-.038.115.043.077.042.09-.003.037-.154.243.622.357.925.173.227-.051.444-.104.705-.13.521-.054 1.021-.089 1.286-.315.092-.078.088-.182-.03-.255M52.906 8.291c-.191-.24-.402-.204-.634-.28-.218-.073-.326.255-.245.491.117.34.438.509.697.497.26-.01.37-.472.182-.708M80.437 1.283c-.385-.22-.844-.327-1.272-.266-.497.071-.7.363-1.033.724-.356.388.07 1.143.54.93l-.065-.083c.095.05.192.08.295.09.177.032.31.074.477.16.373.189.702.503 1.023.78.348.301 1.738.788 1.586-.245-.141-.963-.789-1.652-1.551-2.09M78.955 8.082c-.134-.55-.259-1.126-.366-1.703-.102-.548-.457-.476-.541.05-.073.453-.057.877.01 1.331.083.548.286.874.512 1.17.11.144.276.048.357-.132.097-.215.088-.476.028-.716M87.395 8c-.77.016-1.317.338-2.032.43-.505.065-.477.525.046.56.713.047 1.359-.082 2.053-.14.468-.04 1.35.253 1.516-.164.191-.483-.906-.7-1.583-.685M81.958 14.767c-.103-.44-.306-.8-.377-1.279-.095-.644-.518-.678-.57.063-.07.998.19 1.845.53 2.34.293.426.566-.494.417-1.124M99.918 9.365c-.177-.18-.36-.23-.56-.337-.295-.16-.508.405-.225.646.181.155.805.626.863.04.012-.119-.003-.273-.078-.349M93.308 4.792c-.387-.436-.932-.682-1.466-.78-.809-.145-1.17 1.02-.47 1.477.65.427 1.772 2.34 2.503 1.097.376-.641-.178-1.356-.567-1.794M91.498 10.138c-.32.55-.428 1.334-.494 2.18-.043.546.266.928.442.494.21-.512.38-1.126.522-1.741.139-.605-.204-1.393-.47-.933M103.977 8.863c-.265-1.177-1.477-2.153-2.51-1.784-.548.195-.653 1.156-.104 1.442.294.153.53.397.762.655.326.36.549.611.988.784.564.223.992-.535.864-1.097M100.988 4.781c.03-.437-.169-.702-.568-.724-.906-.33-1.89.849-2.3 1.608-.47.873.538 1.63 1.223 1.22.683-.406 1.786-1.108 1.645-2.104M110.532 7.06c-.238-.218-.568.203-.463.619l.012.045c-.01.096-.001.204 0 .297 0 .14-.016.294-.025.434-.012.181-.043.357-.053.539-.013.245.016.45.06.612.091.33.32.515.53.304.108-.11.286-.37.335-.709.04-.276.058-.554.07-.836.024-.568-.189-1.052-.466-1.306M108.458 14.127c-.434-.548-.995-.921-1.662-1.103-.746-.203-1.116.933-.445 1.28.216.11.4.251.557.443.204.248.42.648.672.84.348.262.868.645 1.249.23.437-.478-.064-1.305-.37-1.69M117.71 13.184c-.282.276-.558.555-.852.815-.143.126-.333.256-.446.42-.108.156-.174.34-.284.489-.392.535.193 1.412.694.973.104-.091.318-.086.446-.134.16-.062.324-.11.486-.169.51-.186.872-.578 1.145-1.11.418-.816-.553-1.907-1.188-1.284M97.93 18.019c-.834-.165-1.209.791-.697 1.348.495.538 1.83 2.49 2.627 1.2.636-1.034-1.044-2.373-1.93-2.548M124.69 17.006c-.372.072-.428.396-.629.626-.202.23.139.496.376.3.22-.181.506-.403.559-.676.032-.168-.129-.285-.307-.25M115.979 19.839c-.079-.499-.153-.976-.264-1.445-.205-.86-.853-.174-.689.73.089.49.148.982.25 1.46.196.907.849.182.703-.745M78.957 24.496c.068-.31.05-.616-.02-.91-.077-.321-.14-.65-.183-1.002-.099-.82-.671-.76-.736.076-.056.71.019 1.361.23 1.918.132.348.265.461.467.377-.18.076.075.038.116.016.071-.038.117-.183.135-.33.01-.08.063-.472-.009-.145M61.924 22.403c-.057-.057-.16-.13-.189-.2-.132-.33-.73-.229-.735.1-.004.27.047.533.379.665.186.073.458.02.543-.14l.027-.053c.06-.114.083-.266-.025-.372M106.798 22.22c-.107-.292-.757-.304-.794.028-.032.293.107.618.488.731.229.068.532-.032.507-.257-.021-.186-.137-.329-.201-.502M70.884 28.197c-.13-.291-.716-.24-.83.025-.131.304-.034.606.41.754.101.033.24.034.334-.012.326-.16.181-.553.086-.767" transform="translate(161 68)"/><g class="olhos"><path fill="#633" d="M51.976 32.505c.27 2.748-1.735 5.197-4.476 5.47-2.748.274-5.199-1.732-5.476-4.48-.27-2.748 1.735-5.197 4.483-5.47 2.748-.274 5.192 1.733 5.469 4.48M93.976 28.505c.27 2.748-1.735 5.197-4.483 5.47-2.748.273-5.192-1.733-5.469-4.48-.27-2.748 1.735-5.197 4.483-5.47 2.748-.274 5.192 1.733 5.469  4.48M65.03 45.127c2.1-5.726 9.106-6.606 13.113-2.171.408.462-.277 1.204-.725.77-3.981-3.892-9.17-2.951-11.83 1.745-.187.333-.68-.002-.558-.344 " transform="translate(161 68)"/></g></g></g><g fill-rule="nonzero" stroke="#979797" stroke-linecap="round" stroke-width="1.8" class="left-sparks"><path d="M23.684 5.789L30 1.158" transform="rotate(-90 157 13)"/><path d="M0 5.789L6.316 1.158" transform="rotate(-90 157 13) matrix(-1 0 0 1 6.316 0)"/><path d="M15.789 4.632L15.789 0" transform="rotate(-90 157 13)"/></g><g fill-rule="nonzero" stroke="#979797" stroke-linecap="round" stroke-width="1.8" class="right-sparks"><path d="M23.684 5.789L30 1.158" transform="matrix(0 -1 -1 0 318 170)"/><path d="M0 5.789L6.316 1.158" transform="matrix(0 -1 -1 0 318 170) matrix(-1 0 0 1 6.316 0)"/><path d="M15.789 4.632L15.789 0" transform="matrix(0 -1 -1 0 318 170)"/></g><path fill="#4B4B62" class="path" fill-rule="nonzero" stroke="#4B4B62" stroke-width="2" d="M198.754 186c1.56 0 2.246-.703 2.246-2.3v-41.4c0-1.597-.686-2.3-2.246-2.3h-9.608c-1.56 0-2.247.703-2.247 2.3v19.678h-5.802c-1.185 0-1.934-.83-1.934-2.172V142.3c0-1.597-.686-2.3-2.246-2.3h-9.67c-1.56 0-2.247.703-2.247 2.3v22.425c0 7.283 3.244 10.606 11.355 10.606H186.9v8.369c0 1.597.687 2.3 2.247 2.3h9.608zm32.277 1c15.3 0 18.969-5.248 18.969-13.056V152.12c0-7.808-3.67-13.12-18.969-13.12-15.3 0-19.031 5.312-19.031 13.12v21.824c0 7.808 3.732 13.056 19.031 13.056zm.969-12c-4.25 0-5-1.27-5-2.986v-17.091c0-1.652.75-2.923 5-2.923 4.313 0 5 1.27 5 2.923v17.09c0 1.716-.688 2.987-5 2.987zm62.754 11c1.56 0 2.246-.703 2.246-2.3v-41.4c0-1.597-.686-2.3-2.246-2.3h-9.608c-1.56 0-2.247.703-2.247 2.3v19.678h-5.802c-1.185 0-1.934-.83-1.934-2.172V142.3c0-1.597-.686-2.3-2.246-2.3h-9.67c-1.56 0-2.247.703-2.247 2.3v22.425c0 7.283 3.244 10.606 11.355 10.606H282.9v8.369c0 1.597.687 2.3 2.247 2.3h9.608z"/></g></g></svg>
		</div>
		<style>.fundo{animation:scales 3s alternate infinite;transform-origin:center}.pao-baixo{animation:rotatepao 14s cubic-bezier(.1,.49,.41,.97) infinite;transform-origin:center}.pao-cima{animation:rotatepao 7s 1s cubic-bezier(.1,.49,.41,.97) infinite;transform-origin:center}.olhos{animation:olhos 2s alternate infinite;transform-origin:center}.left-sparks{animation:left-sparks 4s alternate infinite;transform-origin:150px 156px}.right-sparks{animation:left-sparks 4s alternate infinite;transform-origin:310px 150px}.olhos{animation:olhos 2s alternate infinite;transform-origin:center}@keyframes scales{from{transform:scale(.98)}to{transform:scale(1)}}@keyframes rotatepao{0%{transform:rotate(0deg)}50%,60%{transform:rotate(-20deg)}100%{transform:rotate(0deg)}}@keyframes olhos{0%{transform:rotateX(0deg)}100%{transform:rotateX(30deg)}}@keyframes left-sparks{0%{opacity:0}}.main{min-height:auto;margin:0 auto;width:auto;max-width:100%;display:flex;align-items:center;justify-content:center}.path{stroke-dasharray:300;stroke-dashoffset:300;animation:dash 4s alternate infinite}@keyframes dash{0%,30%{fill:4B4B62;stroke-dashoffset:0}80%,100%{fill:transparent;stroke-dashoffset:-200}}</style>
		<?php
	}
	public function fetchTable( $orders = [] ) {
		$tableContent = [];
		foreach( $orders as $order ) {
			// https://www.businessbloomer.com/woocommerce-easily-get-order-info-total-items-etc-from-order-object/
			foreach( $order->get_items() as $item_id => $item ) {
				$course = $item->get_meta( '_fwp_course', true );
				$course = is_array( $course ) ? $course : [];$course[ 'status' ] = $item->get_meta( '_fwp_course_status', true );
				$course = wp_parse_args( $course, [ 'status' => false, 'link' => '', 'url' => '' ] );
				if( $course[ 'status' ] !== true ) {
					$tableContent[] = [
						'title' => $item->get_name(),
						'review' => 0.00,
						'hours' => 0.00,
						'complete' => 0.00,
						'url' => $course[ 'url' ]
					];
				}
			}
		}
		return $tableContent;
	}
	public function get_option( $option, $default = false ) {
		if( $this->options === NULL ) {$this->options = get_option( FWP_COURSES_PREFIX, [] );}
		
		return isset( $this->options[ $option ] ) ? $this->options[ $option ] : $default;
	}

	/**
	 * Rest API ofr supplying JSON output for Applications.
	 */
	public function restPermission() {
		/**
		 * Permit if this user has access on this course.
		 */
		return true;
	}
	public function restEndpoint() {
		register_rest_route( 'certificate/v1', '/learner/(?P<id>\d+)', [
			// 'methods' => 'GET',
			'methods'             => WP_REST_Server::READABLE,
			'callback' => [ $this, 'restCertificate' ],
			'args' => [],
			'permission_callback' => [ $this, 'restPermission' ],
		] );
	}
	public function restCertificate() {
		$posts = get_posts( [
			'author' => $data['id'],
		] );
		if ( empty( $posts ) ) {
			return null;
		}
		return $posts;
		// return $posts[0]->post_title;
	}

	/**
	 * Original Query parameter for endpoint.
	 */
	public function query_vars( $query_vars  ) {
		$query_vars[] = 'mycourses';
		$query_vars[] = 'course';
		$query_vars[] = 'download';
    return $query_vars;
	}
	public function template_include( $template ) {
		$myCourses = get_query_var( 'mycourses' );
		if ( $myCourses == false || $myCourses == '' ) {
			return $template;
		} else {
			$file = get_stylesheet_directory() . '/inc/template/courses.php';
			if( file_exists( $file ) ) {
				// add_action( 'wp_head', [ $this, 'wp_head' ], 10, 0 );
				return $file;
			} else {
				return $template;
			}
		}
	}
	public function wp_head() {
		?>
    <link rel="preload" href="https://primer.tailwindui.com/_next/static/css/b3a6341d3ce236c9.css" as="style" />
    <link rel="stylesheet" href="https://primer.tailwindui.com/_next/static/css/b3a6341d3ce236c9.css" data-n-g="" />
		<!-- <script defer="" nomodule="" src="https://primer.tailwindui.com/_next/static/chunks/polyfills-c67a75d1b6f99dc8.js"></script>
    <script src="https://primer.tailwindui.com/_next/static/chunks/webpack-c80a10df72d0bdde.js" defer=""></script>
    <script src="https://primer.tailwindui.com/_next/static/chunks/framework-fe99aa755573eedd.js" defer=""></script> -->
    <script src="https://primer.tailwindui.com/_next/static/chunks/main-2e2441d83e4fe6bb.js" defer=""></script>
    <!-- <script src="https://primer.tailwindui.com/_next/static/chunks/pages/_app-2690038fc68b1931.js" defer=""></script>
    <script src="https://primer.tailwindui.com/_next/static/chunks/599-922f14290c4d382e.js" defer=""></script>
    <script src="https://primer.tailwindui.com/_next/static/chunks/pages/index-074a92b3f23ec1ea.js" defer=""></script>
    <script src="https://primer.tailwindui.com/_next/static/Lv_MMPhFVpQjATVhiswrd/_buildManifest.js" defer=""></script>
    <script src="https://primer.tailwindui.com/_next/static/Lv_MMPhFVpQjATVhiswrd/_ssgManifest.js" defer=""></script> -->
		<?php
	}

	/**
	 * Tutor Cources interaction of Download.
	 */
	public function loopBtn( $btn, $post_id ) {
		$meta = get_post_meta( $post_id, '_fwp_course', true );
		if( is_array( $meta ) && $meta[ 'status' ] === true && $meta[ 'url' ] != '' ) {

      $downloads = get_post_meta( $post_id, '_fwp_course_downloads', true );$downloads = is_array( $downloads ) ? $downloads : [];$user_id = get_current_user_id();
			if( ! isset(  $downloads[ 'u-' . $user_id ] ) ) {$downloads[ 'u-' . $user_id ] = 0;update_post_meta( $post_id, '_fwp_course_downloads', $downloads );}

			$btn .= '<a href="' . esc_url( $meta[ 'url' ] ) . '" class="tutor-btn tutor-btn-primary tutor-btn-block tutor-mt-12">
				' . $this->icons( 'zip' ) . ( is_single() ? esc_html__( 'Download full-Course', 'fwp-divi-child-seojaws' ) : esc_html__( 'Download', 'fwp-divi-child-seojaws' ) ) . '
			</a>';
		} else {
			// $btn .= print_r( get_post_meta( $post_id, '_fwp_course', true ) );
		}
		return $btn;
	}
	public function icons( $icon = false ) {
		$icons = [
			'cloud' => '<svg class="tutor-mr-8" width="25px" height="25px" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg"><path d="M45.81,19.56a9.5,9.5,0,0,0-6.26-3.86q0-.44,0-0.88a12.72,12.72,0,0,0-25.29-2,13.34,13.34,0,0,0-2.22-.18C5.42,12.67,0,17.59,0,23.64S3.64,33.88,9.36,34.39l29.09,0C43.2,34.19,48,31.1,48,25.61A9.52,9.52,0,0,0,45.81,19.56Z" fill="#aedff5"/><path d="M31.52,38.62a2,2,0,0,0-2.82-.22L26,40.69V22.92a2,2,0,0,0-4,0V40.67l-2.7-2.31a2,2,0,1,0-2.61,3l6,5.13,0.06,0,0.12,0.09,0.22,0.13,0.14,0.06,0.25,0.08,0.12,0A2,2,0,0,0,24,47h0a2,2,0,0,0,.39,0l0.11,0,0.27-.09,0.06,0,0,0a2,2,0,0,0,.42-0.26l6-5.09A2,2,0,0,0,31.52,38.62Z" fill="#ffffff"/></svg>',
			'zip' => '<svg class="tutor-mr-8" width="25px" height="25px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><path style="fill:#aedff5;" d="M511.344,274.266C511.77,268.231,512,262.143,512,256C512,114.615,397.385,0,256,0S0,114.615,0,256
			c0,117.769,79.53,216.949,187.809,246.801L511.344,274.266z"/><path style="fill:#88c5e1;" d="M511.344,274.266L314.991,77.913L119.096,434.087l68.714,68.714C209.522,508.787,232.385,512,256,512
			C391.243,512,501.976,407.125,511.344,274.266z"/><polygon style="fill:#FFFFFF;" points="278.328,333.913 255.711,77.913 119.096,77.913 119.096,311.652 "/><polygon style="fill:#E8E6E6;" points="392.904,311.652 392.904,155.826 337.252,133.565 314.991,77.913 255.711,77.913 
			256.067,333.913 "/><polygon style="fill:#FFFFFF;" points="314.991,155.826 314.991,77.913 392.904,155.826 "/><rect x="119.096" y="311.652" style="fill:#3e64de;" width="273.809" height="122.435"/><g><path style="fill:#FFFFFF;" d="M210.927,388.691h28.759v10.671h-46.771v-8.627l28.38-33.677H193.9v-10.671h45.332v8.627
			L210.927,388.691z"/><path style="fill:#FFFFFF;" d="M249.075,399.362v-52.975h13.471v52.975H249.075z"/><path style="fill:#FFFFFF;" d="M298.118,346.387c13.546,0,21.341,6.659,21.341,18.465c0,12.412-7.796,19.601-21.341,19.601h-9.612
			v14.909h-13.471v-52.975L298.118,346.387L298.118,346.387z M288.505,373.858h8.93c5.904,0,9.307-2.952,9.307-8.552
			c0-5.525-3.405-8.324-9.307-8.324h-8.93V373.858z"/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>'
		];
		if( $icon ) {
			return isset( $icons[ $icon ] ) ? $icons[ $icon ] : false;
		} else {
			return $icons;
		}
	}

	/**
	 * Video functions.
	 */
	public function getVideoDimensions( $file ) {
			$command = '/usr/bin/ffmpeg -i ' . escapeshellarg( $file ) . ' 2>&1';
			$dimensions = array();$output= true;$status = true;
			exec( $command, $output, $status );

			// print_r( [$command, $output, $status] );

			if( ! preg_match( '/Stream #(?:[0-9\.]+)(?:.*)\: Video: (?P<videocodec>.*) (?P<width>[0-9]*)x(?P<height>[0-9]*)/',implode('\n', $output), $matches ) ) {
				preg_match( '/Could not find codec parameters \(Video: (?P<videocodec>.*) (?P<width>[0-9]*)x(?P<height>[0-9]*)\)/', implode('\n', $output), $matches );
			}
			if( ! empty( $matches['width'] ) && ! empty( $matches['height'] ) ) {
				$dimensions['width'] = $matches['width'];
				$dimensions['height'] = $matches['height'];
			}
			return $dimensions;
	}
  public function getDimensions( $original_width, $original_height, $target_width, $target_height, $force_aspect = true ) {
    // Array to be returned by this function
    $target = array();
    // Target aspect ratio (width / height)
    $aspect = $target_width / $target_height;
    // Target reciprocal aspect ratio (height / width)
    $raspect = $target_height / $target_width;

    if( $original_width/$original_height !== $aspect ) {
        // Aspect ratio is different
        if( $original_width/$original_height > $aspect ) {
            // Width is the greater of the two dimensions relative to the target dimensions
            if( $original_width < $target_width ) {
                // Original video is smaller.  Scale down dimensions for conversion
                $target_width = $original_width;
                $target_height = round($raspect * $target_width);
            }
            // Calculate height from width
            $original_height = round($original_height / $original_width * $target_width);
            $original_width = $target_width;
            if( $force_aspect ) {
                // Pad top and bottom
                $dif = round(($target_height - $original_height) / 2);
                $target['padtop'] = $dif;
                $target['padbottom'] = $dif;
            }
        } else {
            // Height is the greater of the two dimensions relative to the target dimensions
            if( $original_height < $target_height ) {
                // Original video is smaller.  Scale down dimensions for conversion
                $target_height = $original_height;
                $target_width = round($aspect * $target_height);
            }
            //Calculate width from height
            $original_width = round($original_width / $original_height * $target_height);
            $original_height = $target_height;
            if( $force_aspect ) {
                // Pad left and right
                $dif = round(($target_width - $original_width) / 2);
                $target['padleft'] = $dif;
                $target['padright'] = $dif;
            }
        }
    } else {
        // The aspect ratio is the same
        if( $original_width !== $target_width ) {
            if( $original_width < $target_width ) {
                // The original video is smaller.  Use its resolution for conversion
                $target_width = $original_width;
                $target_height = $original_height;
            } else {
                // The original video is larger,  Use the target dimensions for conversion
                $original_width = $target_width;
                $original_height = $target_height;
            }
        }
    }
    if( $force_aspect ) {
        // Use the target_ vars because they contain dimensions relative to the target aspect ratio
        $target['width'] = $target_width;
        $target['height'] = $target_height;
    } else {
        // Use the original_ vars because they contain dimensions relative to the original's aspect ratio
        $target['width'] = $original_width;
        $target['height'] = $original_height;
    }
    return $target;
  }
  public function getVideo( $args = false ) {
		if( ! $args ) {return;}

		ini_set('max_execution_time', 0 );set_time_limit( 0 );

		$args = is_array( $args ) ? $args : [];
		$args = wp_parse_args( $args, [
			'src' => false, 'dest' => false, 'bitrate' => false,
			'width' => 640, 'height' => 480
		] );
		if( ! $args[ 'src' ] || ! $args[ 'dest' ] ) {return;}
		// $original = $this->getVideoDimensions( $args[ 'src' ] );

		$args[ 'bitrate' ] = $this->getBitrate( $args[ 'bitrate' ] );
		
		// $command = "/usr/bin/ffmpeg -i '" . $args[ 'src' ] . "' -b:v " . $args[ 'bitrate' ] . " -bufsize " . $args[ 'bitrate' ] . " '" . $args[ 'dest' ] . "'";
		
		$command = "/usr/bin/ffmpeg 
			-i '" . $args[ 'src' ] . "' 
			-b " . $args[ 'bitrate' ] . " 
			-minrate " . $args[ 'bitrate' ] . " 
			-maxrate " . $args[ 'bitrate' ] . " 
			-bufsize " . $args[ 'bitrate' ] . " 
			-ab 64k 
			-vcodec libx264 
			-acodec aac -strict -2 
			-ac 2 
			-ar 44100 
			-s 320x240 
			-y '" . $args[ 'dest' ] . "'";
		$command = "/usr/bin/ffmpeg -i '" . $args[ 'src' ] . "' -b " . $args[ 'bitrate' ] . " -minrate " . $args[ 'bitrate' ] . " -maxrate " . $args[ 'bitrate' ] . " -bufsize " . $args[ 'bitrate' ] . " -ab 64k -vcodec libx264 -acodec aac -strict -2 -ac 2 -ar 44100 -s " . $this->get_option( 'video_default_frame', '320x240' ) . " -y '" . $args[ 'dest' ] . "'"; // https://stackoverflow.com/questions/10908796/how-to-force-constant-bit-rate-using-ffmpeg


		$args[ 'command' ] = $command;


		// if( ! class_exists( 'FFMpeg' ) ) {require_once( get_stylesheet_directory() . '/inc/vendor/autoload.php' );}

		// $ffmpeg = FFMpeg\FFMpeg::create();
		// wp_die( 'ok Babe ' . $ffmpeg, 'Video coonverter' );
		// $video = $ffmpeg->open( $args[ 'src' ] );
		// $video->filters()->resize( new FFMpeg\Coordinate\Dimension( 320, 240 ) )->synchronize();
		// $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(10))->save('frame.jpg');
		// $video->save(new FFMpeg\Format\Video\X264(), $args[ 'dest' ] . '.webm');

		
		// escapeshellcmd( 
		exec( $command, $output, $status );
		// $output = shell_exec( $command );
		
		if( $status == 0 ) {
			return [ 'success' => true, 'getMessage' => $output, 'args' => $args, 'status' => $status ];
		} else {
			return [ 'success' => false, 'getMessage' => $output, 'args' => $args, 'status' => $status ];
		}
		/*
			if( ! $args[ 'bitrate' ] && ! empty( $args[ 'bitrate' ] ) ) {
				$target = $this->getDimensions( $original['width'], $original['height'], $args[ 'width' ], $args[ 'height' ], true );
				$command = '/usr/bin/ffmpeg -i ' . $args[ 'src' ] . ' -ab 96k -b 700k -ar 44100 -s ' . $target['width'] . 'x' . $target['height'];
				$command .= (!empty($target['padtop']) ? ' -padtop ' . $target['padtop'] : '');
				$command .= (!empty($target['padbottom']) ? ' -padbottom ' . $target['padbottom'] : '');
				$command .= (!empty($target['padleft']) ? ' -padleft ' . $target['padleft'] : '');
				$command .= (!empty($target['padright']) ? ' -padright ' . $target['padright'] : '');
				$command .= ' -acodec mp3 ' . $args[ 'dest' ] . ' 2>&1';
				exec( $command, $output, $status );
			}
		*/
  }
	public function getBitrate( $bit = false ) {
		$bitrates = [
			'240p' => '350k',
			'360p' => '700k',
			'480p' => '1200k',
			'720p' => '2500k',
			'1080p' => '5000k'
		];
		if( $bit ) {
			return isset( $bitrates[ $bit ] ) ? $bitrates[ $bit ] : $bitrates[ '720p' ];
		} else {
			return $bitrates;
		}
	}

	public function langs( $filter = false, $key = false ) {
		$langs = [
			"ab" => [ "name" => "Abkhaz", "nativeName" => "аҧсуа" ],
			"aa" => [ "name" => "Afar", "nativeName" => "Afaraf" ],
			"af" => [ "name" => "Afrikaans", "nativeName" => "Afrikaans" ],
			"ak" => [ "name" => "Akan", "nativeName" => "Akan" ],
			"sq" => [ "name" => "Albanian", "nativeName" => "Shqip" ],
			"am" => [ "name" => "Amharic", "nativeName" => "አማርኛ" ],
			"ar" => [ "name" => "Arabic", "nativeName" => "العربية" ],
			"an" => [ "name" => "Aragonese", "nativeName" => "Aragonés" ],
			"hy" => [ "name" => "Armenian", "nativeName" => "Հայերեն" ],
			"as" => [ "name" => "Assamese", "nativeName" => "অসমীয়া" ],
			"av" => [ "name" => "Avaric", "nativeName" => "авар мацӀ, магӀарул мацӀ" ],
			"ae" => [ "name" => "Avestan", "nativeName" => "avesta" ],
			"ay" => [ "name" => "Aymara", "nativeName" => "aymar aru" ],
			"az" => [ "name" => "Azerbaijani", "nativeName" => "azərbaycan dili" ],
			"bm" => [ "name" => "Bambara", "nativeName" => "bamanankan" ],
			"ba" => [ "name" => "Bashkir", "nativeName" => "башҡорт теле" ],
			"eu" => [ "name" => "Basque", "nativeName" => "euskara, euskera" ],
			"be" => [ "name" => "Belarusian", "nativeName" => "Беларуская" ],
			"bn" => [ "name" => "Bengali", "nativeName" => "বাংলা" ],
			"bh" => [ "name" => "Bihari", "nativeName" => "भोजपुरी" ],
			"bi" => [ "name" => "Bislama", "nativeName" => "Bislama" ],
			"bs" => [ "name" => "Bosnian", "nativeName" => "bosanski jezik" ],
			"br" => [ "name" => "Breton", "nativeName" => "brezhoneg" ],
			"bg" => [ "name" => "Bulgarian", "nativeName" => "български език" ],
			"my" => [ "name" => "Burmese", "nativeName" => "ဗမာစာ" ],
			"ca" => [ "name" => "Catalan; Valencian", "nativeName" => "Català" ],
			"ch" => [ "name" => "Chamorro", "nativeName" => "Chamoru" ],
			"ce" => [ "name" => "Chechen", "nativeName" => "нохчийн мотт" ],
			"ny" => [ "name" => "Chichewa; Chewa; Nyanja", "nativeName" => "chiCheŵa, chinyanja" ],
			"zh" => [ "name" => "Chinese", "nativeName" => "中文 (Zhōngwén), 汉语, 漢語" ],
			"cv" => [ "name" => "Chuvash", "nativeName" => "чӑваш чӗлхи" ],
			"kw" => [ "name" => "Cornish", "nativeName" => "Kernewek" ],
			"co" => [ "name" => "Corsican", "nativeName" => "corsu, lingua corsa" ],
			"cr" => [ "name" => "Cree", "nativeName" => "ᓀᐦᐃᔭᐍᐏᐣ" ],
			"hr" => [ "name" => "Croatian", "nativeName" => "hrvatski" ],
			"cs" => [ "name" => "Czech", "nativeName" => "česky, čeština" ],
			"da" => [ "name" => "Danish", "nativeName" => "dansk" ],
			"dv" => [ "name" => "Divehi; Dhivehi; Maldivian;", "nativeName" => "ދިވެހި" ],
			"nl" => [ "name" => "Dutch", "nativeName" => "Nederlands, Vlaams" ],
			"en_US" => [ "name" => "English", "nativeName" => "English" ],
			"eo" => [ "name" => "Esperanto", "nativeName" => "Esperanto" ],
			"et" => [ "name" => "Estonian", "nativeName" => "eesti, eesti keel" ],
			"ee" => [ "name" => "Ewe", "nativeName" => "Eʋegbe" ],
			"fo" => [ "name" => "Faroese", "nativeName" => "føroyskt" ],
			"fj" => [ "name" => "Fijian", "nativeName" => "vosa Vakaviti" ],
			"fi" => [ "name" => "Finnish", "nativeName" => "suomi, suomen kieli" ],
			"fr" => [ "name" => "French", "nativeName" => "français, langue française" ],
			"ff" => [ "name" => "Fula; Fulah; Pulaar; Pular", "nativeName" => "Fulfulde, Pulaar, Pular" ],
			"gl" => [ "name" => "Galician", "nativeName" => "Galego" ],
			"ka" => [ "name" => "Georgian", "nativeName" => "ქართული" ],
			"de" => [ "name" => "German", "nativeName" => "Deutsch" ],
			"el" => [ "name" => "Greek, Modern", "nativeName" => "Ελληνικά" ],
			"gn" => [ "name" => "Guaraní", "nativeName" => "Avañeẽ" ],
			"gu" => [ "name" => "Gujarati", "nativeName" => "ગુજરાતી" ],
			"ht" => [ "name" => "Haitian; Haitian Creole", "nativeName" => "Kreyòl ayisyen" ],
			"ha" => [ "name" => "Hausa", "nativeName" => "Hausa, هَوُسَ" ],
			"he" => [ "name" => "Hebrew (modern)", "nativeName" => "עברית" ],
			"hz" => [ "name" => "Herero", "nativeName" => "Otjiherero" ],
			"hi" => [ "name" => "Hindi", "nativeName" => "हिन्दी, हिंदी" ],
			"ho" => [ "name" => "Hiri Motu", "nativeName" => "Hiri Motu" ],
			"hu" => [ "name" => "Hungarian", "nativeName" => "Magyar" ],
			"ia" => [ "name" => "Interlingua", "nativeName" => "Interlingua" ],
			"id" => [ "name" => "Indonesian", "nativeName" => "Bahasa Indonesia" ],
			"ie" => [ "name" => "Interlingue", "nativeName" => "Originally called Occidental; then Interlingue after WWII" ],
			"ga" => [ "name" => "Irish", "nativeName" => "Gaeilge" ],
			"ig" => [ "name" => "Igbo", "nativeName" => "Asụsụ Igbo" ],
			"ik" => [ "name" => "Inupiaq", "nativeName" => "Iñupiaq, Iñupiatun" ],
			"io" => [ "name" => "Ido", "nativeName" => "Ido" ],
			"is" => [ "name" => "Icelandic", "nativeName" => "Íslenska" ],
			"it" => [ "name" => "Italian", "nativeName" => "Italiano" ],
			"iu" => [ "name" => "Inuktitut", "nativeName" => "ᐃᓄᒃᑎᑐᑦ" ],
			"ja" => [ "name" => "Japanese", "nativeName" => "日本語 (にほんご／にっぽんご)" ],
			"jv" => [ "name" => "Javanese", "nativeName" => "basa Jawa" ],
			"kl" => [ "name" => "Kalaallisut, Greenlandic", "nativeName" => "kalaallisut, kalaallit oqaasii" ],
			"kn" => [ "name" => "Kannada", "nativeName" => "ಕನ್ನಡ" ],
			"kr" => [ "name" => "Kanuri", "nativeName" => "Kanuri" ],
			"ks" => [ "name" => "Kashmiri", "nativeName" => "कश्मीरी, كشميري‎" ],
			"kk" => [ "name" => "Kazakh", "nativeName" => "Қазақ тілі" ],
			"km" => [ "name" => "Khmer", "nativeName" => "ភាសាខ្មែរ" ],
			"ki" => [ "name" => "Kikuyu, Gikuyu", "nativeName" => "Gĩkũyũ" ],
			"rw" => [ "name" => "Kinyarwanda", "nativeName" => "Ikinyarwanda" ],
			"ky" => [ "name" => "Kirghiz, Kyrgyz", "nativeName" => "кыргыз тили" ],
			"kv" => [ "name" => "Komi", "nativeName" => "коми кыв" ],
			"kg" => [ "name" => "Kongo", "nativeName" => "KiKongo" ],
			"ko" => [ "name" => "Korean", "nativeName" => "한국어 (韓國語), 조선말 (朝鮮語)" ],
			"ku" => [ "name" => "Kurdish", "nativeName" => "Kurdî, كوردی‎" ],
			"kj" => [ "name" => "Kwanyama, Kuanyama", "nativeName" => "Kuanyama" ],
			"la" => [ "name" => "Latin", "nativeName" => "latine, lingua latina" ],
			"lb" => [ "name" => "Luxembourgish, Letzeburgesch", "nativeName" => "Lëtzebuergesch" ],
			"lg" => [ "name" => "Luganda", "nativeName" => "Luganda" ],
			"li" => [ "name" => "Limburgish, Limburgan, Limburger", "nativeName" => "Limburgs" ],
			"ln" => [ "name" => "Lingala", "nativeName" => "Lingála" ],
			"lo" => [ "name" => "Lao", "nativeName" => "ພາສາລາວ" ],
			"lt" => [ "name" => "Lithuanian", "nativeName" => "lietuvių kalba" ],
			"lu" => [ "name" => "Luba-Katanga", "nativeName" => "" ],
			"lv" => [ "name" => "Latvian", "nativeName" => "latviešu valoda" ],
			"gv" => [ "name" => "Manx", "nativeName" => "Gaelg, Gailck" ],
			"mk" => [ "name" => "Macedonian", "nativeName" => "македонски јазик" ],
			"mg" => [ "name" => "Malagasy", "nativeName" => "Malagasy fiteny" ],
			"ms" => [ "name" => "Malay", "nativeName" => "bahasa Melayu, بهاس ملايو‎" ],
			"ml" => [ "name" => "Malayalam", "nativeName" => "മലയാളം" ],
			"mt" => [ "name" => "Maltese", "nativeName" => "Malti" ],
			"mi" => [ "name" => "Māori", "nativeName" => "te reo Māori" ],
			"mr" => [ "name" => "Marathi (Marāṭhī)", "nativeName" => "मराठी" ],
			"mh" => [ "name" => "Marshallese", "nativeName" => "Kajin M̧ajeļ" ],
			"mn" => [ "name" => "Mongolian", "nativeName" => "монгол" ],
			"na" => [ "name" => "Nauru", "nativeName" => "Ekakairũ Naoero" ],
			"nv" => [ "name" => "Navajo, Navaho", "nativeName" => "Diné bizaad, Dinékʼehǰí" ],
			"nb" => [ "name" => "Norwegian Bokmål", "nativeName" => "Norsk bokmål" ],
			"nd" => [ "name" => "North Ndebele", "nativeName" => "isiNdebele" ],
			"ne" => [ "name" => "Nepali", "nativeName" => "नेपाली" ],
			"ng" => [ "name" => "Ndonga", "nativeName" => "Owambo" ],
			"nn" => [ "name" => "Norwegian Nynorsk", "nativeName" => "Norsk nynorsk" ],
			"no" => [ "name" => "Norwegian", "nativeName" => "Norsk" ],
			"ii" => [ "name" => "Nuosu", "nativeName" => "ꆈꌠ꒿ Nuosuhxop" ],
			"nr" => [ "name" => "South Ndebele", "nativeName" => "isiNdebele" ],
			"oc" => [ "name" => "Occitan", "nativeName" => "Occitan" ],
			"oj" => [ "name" => "Ojibwe, Ojibwa", "nativeName" => "ᐊᓂᔑᓈᐯᒧᐎᓐ" ],
			"cu" => [ "name" => "Old Church Slavonic, Church Slavic, Church Slavonic, Old Bulgarian, Old Slavonic", "nativeName" => "ѩзыкъ словѣньскъ" ],
			"om" => [ "name" => "Oromo", "nativeName" => "Afaan Oromoo" ],
			"or" => [ "name" => "Oriya", "nativeName" => "ଓଡ଼ିଆ" ],
			"os" => [ "name" => "Ossetian, Ossetic", "nativeName" => "ирон æвзаг" ],
			"pa" => [ "name" => "Panjabi, Punjabi", "nativeName" => "ਪੰਜਾਬੀ, پنجابی‎" ],
			"pi" => [ "name" => "Pāli", "nativeName" => "पाऴि" ],
			"fa" => [ "name" => "Persian", "nativeName" => "فارسی" ],
			"pl" => [ "name" => "Polish", "nativeName" => "polski" ],
			"ps" => [ "name" => "Pashto, Pushto", "nativeName" => "پښتو" ],
			"pt" => [ "name" => "Portuguese", "nativeName" => "Português" ],
			"qu" => [ "name" => "Quechua", "nativeName" => "Runa Simi, Kichwa" ],
			"rm" => [ "name" => "Romansh", "nativeName" => "rumantsch grischun" ],
			"rn" => [ "name" => "Kirundi", "nativeName" => "kiRundi" ],
			"ro" => [ "name" => "Romanian, Moldavian, Moldovan", "nativeName" => "română" ],
			"ru" => [ "name" => "Russian", "nativeName" => "русский язык" ],
			"sa" => [ "name" => "Sanskrit (Saṁskṛta)", "nativeName" => "संस्कृतम्" ],
			"sc" => [ "name" => "Sardinian", "nativeName" => "sardu" ],
			"sd" => [ "name" => "Sindhi", "nativeName" => "सिन्धी, سنڌي، سندھی‎" ],
			"se" => [ "name" => "Northern Sami", "nativeName" => "Davvisámegiella" ],
			"sm" => [ "name" => "Samoan", "nativeName" => "gagana faa Samoa" ],
			"sg" => [ "name" => "Sango", "nativeName" => "yângâ tî sängö" ],
			"sr" => [ "name" => "Serbian", "nativeName" => "српски језик" ],
			"gd" => [ "name" => "Scottish Gaelic; Gaelic", "nativeName" => "Gàidhlig" ],
			"sn" => [ "name" => "Shona", "nativeName" => "chiShona" ],
			"si" => [ "name" => "Sinhala, Sinhalese", "nativeName" => "සිංහල" ],
			"sk" => [ "name" => "Slovak", "nativeName" => "slovenčina" ],
			"sl" => [ "name" => "Slovene", "nativeName" => "slovenščina" ],
			"so" => [ "name" => "Somali", "nativeName" => "Soomaaliga, af Soomaali" ],
			"st" => [ "name" => "Southern Sotho", "nativeName" => "Sesotho" ],
			"es" => [ "name" => "Spanish; Castilian", "nativeName" => "español, castellano" ],
			"su" => [ "name" => "Sundanese", "nativeName" => "Basa Sunda" ],
			"sw" => [ "name" => "Swahili", "nativeName" => "Kiswahili" ],
			"ss" => [ "name" => "Swati", "nativeName" => "SiSwati" ],
			"sv" => [ "name" => "Swedish", "nativeName" => "svenska" ],
			"ta" => [ "name" => "Tamil", "nativeName" => "தமிழ்" ],
			"te" => [ "name" => "Telugu", "nativeName" => "తెలుగు" ],
			"tg" => [ "name" => "Tajik", "nativeName" => "тоҷикӣ, toğikī, تاجیکی‎" ],
			"th" => [ "name" => "Thai", "nativeName" => "ไทย" ],
			"ti" => [ "name" => "Tigrinya", "nativeName" => "ትግርኛ" ],
			"bo" => [ "name" => "Tibetan Standard, Tibetan, Central", "nativeName" => "བོད་ཡིག" ],
			"tk" => [ "name" => "Turkmen", "nativeName" => "Türkmen, Түркмен" ],
			"tl" => [ "name" => "Tagalog", "nativeName" => "Wikang Tagalog, ᜏᜒᜃᜅ᜔ ᜆᜄᜎᜓᜄ᜔" ],
			"tn" => [ "name" => "Tswana", "nativeName" => "Setswana" ],
			"to" => [ "name" => "Tonga (Tonga Islands)", "nativeName" => "faka Tonga" ],
			"tr" => [ "name" => "Turkish", "nativeName" => "Türkçe" ],
			"ts" => [ "name" => "Tsonga", "nativeName" => "Xitsonga" ],
			"tt" => [ "name" => "Tatar", "nativeName" => "татарча, tatarça, تاتارچا‎" ],
			"tw" => [ "name" => "Twi", "nativeName" => "Twi" ],
			"ty" => [ "name" => "Tahitian", "nativeName" => "Reo Tahiti" ],
			"ug" => [ "name" => "Uighur, Uyghur", "nativeName" => "Uyƣurqə, ئۇيغۇرچە‎" ],
			"uk" => [ "name" => "Ukrainian", "nativeName" => "українська" ],
			"ur" => [ "name" => "Urdu", "nativeName" => "اردو" ],
			"uz" => [ "name" => "Uzbek", "nativeName" => "zbek, Ўзбек, أۇزبېك‎" ],
			"ve" => [ "name" => "Venda", "nativeName" => "Tshivenḓa" ],
			"vi" => [ "name" => "Vietnamese", "nativeName" => "Tiếng Việt" ],
			"vo" => [ "name" => "Volapük", "nativeName" => "Volapük" ],
			"wa" => [ "name" => "Walloon", "nativeName" => "Walon" ],
			"cy" => [ "name" => "Welsh", "nativeName" => "Cymraeg" ],
			"wo" => [ "name" => "Wolof", "nativeName" => "Wollof" ],
			"fy" => [ "name" => "Western Frisian", "nativeName" => "Frysk" ],
			"xh" => [ "name" => "Xhosa", "nativeName" => "isiXhosa" ],
			"yi" => [ "name" => "Yiddish", "nativeName" => "ייִדיש" ],
			"yo" => [ "name" => "Yoruba", "nativeName" => "Yorùbá" ],
			"za" => [ "name" => "Zhuang, Chuang", "nativeName" => "Saɯ cueŋƅ, Saw cuengh" ]
		];
		if( ! $key ) {
			if( $filter ) {
				return isset( $langs[ $filter ] ) ? $langs[ $filter ] : $langs[ 'en_US' ];
			} else {
				return $langs;
			}
		} else {
			return isset( $langs[ $filter ] ) ? $filter : 'en_US';
		}
	}
	public function videoPlayer( $args = [] ) {
		$args = wp_parse_args( $args, [
			'src' => '',
			'subtitle' => 'srt',
			'course' => false
		] );
		if( ! $args[ 'course' ] ) {return;}
		if( ! empty( $args[ 'src' ] ) ) {
			$args[ 'src' ] = str_replace( [ '-apostrophe-', '-dquatation-', '-3rdbracketsrt-', '-3rdbracketend-' ], [ "'", '"', '[', ']' ], $args[ 'src' ] );
		}
		$scanSrc = FWP_COURSES_LESSON_ROOT . '/' . urldecode( $args[ 'src' ] );
		if( is_dir( $scanSrc ) ) :
			$PLYR_JS = [];
			$scanList = preg_grep('~\.(' . implode( '|', FWP_COURSES_ALLOWED_VIDEO_EXTENSIONS ) . ')$~', scandir( $scanSrc ) );
			$mainList = [];
			foreach( $scanList as $i => $item ) {
				$vidPath = $scanSrc . '/' . $item;
				if( ! in_array( $item, [ '.', '..', '' ] ) && ! is_dir( $vidPath ) && file_exists( $vidPath ) ) {
					$VIDEO_JS = [
						'type' => 'video',
						'blankVideo' => 'https://cdn.plyr.io/static/blank.mp4',
						'tooltips' => [
							'controls' => true,
							'seek' => true
						],
						'storage' => [
							'enabled' => true,
							'key' => 'fwp_plyr'
						],
						'quality' => [
							'default' => $this->get_option( 'video_default_quality', '720' ),
							'options' => FWP_COURSES_ALLOWED_VIDEO_SIZES
						],
						'loop' => [
							'active' => false
						],
						'sources' => [],
						// 'sources' => [
						// 	[
						// 		'src' => '/path/to/movie.mp4',
						// 		'type' => 'video/mp4',
						// 		'size' => 720
						// 	]
						// ],
						'tracks' => [],
						// 'tracks' => [
						// 	[
						// 		'kind' => 'captions',
						// 		'label' => 'English',
						// 		'srclang' => 'en',
						// 		'src' => '/path/to/captions.en.vtt',
						// 		'default' => true
						// 	]
						// ],
					];
					$vidSrc = str_replace( [ ABSPATH ], [ '/' ], $vidPath );
					$vidExt = pathinfo( $vidSrc, PATHINFO_EXTENSION );
					$vidName = substr( $vidSrc, 0, ( 0 - ( strlen( $vidExt ) + 1 ) ) );
					$VIDEO_JS[ 'title' ] = pathinfo( $vidSrc, PATHINFO_FILENAME );
					// $video = [ $i ];$video[] = $vidSrc;$video[] = $vidName;
					foreach( FWP_COURSES_ALLOWED_VIDEO_SIZES as $size ) {
						$VIDEO_JS[ 'sources' ][] = [
							'src' => $this->videoURL( $vidName, $vidExt, $size, $args[ 'course' ] ),
							'type' => 'video/' . $vidExt,
							'size' => $size,
							'dimension' => $this->getVideoDimensions( $vidPath ),
							'videoSize' => $this->filesize( $vidSrc )
						];
					}

					$subTitles = preg_grep( '~\.(' . implode( '|', FWP_COURSES_ALLOWED_SUBTITLE_EXTENSIONS ) . ')$~', scandir( $scanSrc ) );$isFirst = true;$defaultSubTitle = false;
					foreach( $subTitles as $sTi => $subTitle ) {
						if( strtolower( pathinfo( $subTitle, PATHINFO_FILENAME ) ) == strtolower( pathinfo( $vidSrc, PATHINFO_FILENAME ) ) ) {
							$vidlang = $subTitle; // end( explode( '-', $subTitle ) );
							if( $isFirst && $this->langs( false, $vidlang ) == 'en_US' ) {
								$isFirst = false;$defaultSubTitle = true;
							}
							$VIDEO_JS[ 'tracks' ][] = [
								'kind' => 'captions',
								'label' => $this->langs( $vidlang )[ 'name' ],
								'srclang' => $this->langs( false, $vidlang ),
								'src' => $this->subTitleURL( $scanSrc . '/' . $subTitle, $args[ 'course' ] ),
								'default' => $defaultSubTitle
							];
						}
					}
					$PLYR_JS[] = $VIDEO_JS;
				}
			}

			wp_enqueue_script( 'plyr-io-video-player', get_stylesheet_directory_uri() . '/js/scripts.js', [ 'jquery' ], $this->filemtime( get_stylesheet_directory() . '/js/scripts.js' ), true );
			
			ob_start();
			
			// foreach( $mainList as $v => $video ) {}
			?>
			<div class="fluid-width-video-wrapper">
				<video controcster="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.jpg" id="fwp-custom-video-player"></video>
			</div>

			<link rel="stylesheet" href="https://cdn.plyr.io/3.7.2/plyr.css" />
			<script src="https://cdn.plyr.io/3.7.2/plyr.js"></script>
			<!-- <script src="https://cdn.plyr.io/3.7.2/plyr.polyfilled.js"></script> -->
			<script>
				window.fwpCustomVideoPlayer = new Plyr( '#fwp-custom-video-player', {
					title: 'Course video lesson'
				} );
				window.fwpCustomVideoPlayList = <?php echo json_encode( $PLYR_JS ); ?>;
				// if( window.fwpCustomVideoPlayList[0] ) {window.fwpCustomVideoPlayer.source = window.fwpCustomVideoPlayList[0];}
			</script>
			<style>#tutor-course-spotlight-playlist .tutor-course-attachments [is-playing] .tutor-icon-play-line:before {content:"\e91d";}</style>

			<!-- <script id="plyr-io-video-player" src="<?php // echo esc_url( get_stylesheet_directory_uri() . '/js/scripts.js?ver=' . $this->filemtime( get_stylesheet_directory() . '/js/scripts.js' ) ); ?>"></script> -->
			<?php
			$html_content = ob_get_clean();
			$lessonAttachments = [];
			$attachments = preg_grep( '~\.(' . implode( '|', FWP_COURSES_ALLOWED_ATTACHMENTS_EXTENSIONS ) . ')$~', scandir( $scanSrc ) );
			foreach( $attachments as $i => $item ) {
				$lessonAttachments[] = [
					'title' => $item,
					'url' => $this->attachURL( $scanSrc . '/' . $item, $args[ 'course' ] ),
					'icon' => false, 'size' => $this->filesize( $scanSrc . '/' . $item ),
				];
			}
			// echo '<pre style="display: none;">';print_r( $lessonAttachments );echo '</pre>';
			
			wp_localize_script( 'plyr-io-video-player', 'siteConfig', [
				'currentVideo' => 0,
				'attachments' => $lessonAttachments,
				'config' => [
					'playlist' => [
						'icon' => $this->get_option( 'playlist_icon', true ),
						'title' => $this->get_option( 'playlist_title', true ),
						'download' => $this->get_option( 'playlist_download', true )
					],
					'attachments' => [
						'column' => str_replace( [ 'column-' ], [ '' ], $this->get_option( 'attachments_column', 'column-2' ) ),
						'download' => $this->get_option( 'attachments_download', true )
					]
				],
				'i18n'    => [
					'playlist' => __( 'Playlist', 'fwp-divi-child-seojaws' ),
					'attachments' => __( 'Attachments', 'fwp-divi-child-seojaws' ),
					'lesson_playlist' => __( 'Lesson Playlist', 'fwp-divi-child-seojaws' ),
					'lesson_attachments' => __( 'Lesson Attachments', 'fwp-divi-child-seojaws' ),
					'size' => __( 'Size', 'fwp-divi-child-seojaws' ),
					'confirmautogenerate' => __( 'Are you sure yo want to generate lessons autometically? Press okay to continue, cancel to stop process.', 'fwp-divi-child-seojaws' ),
				]
			] );
			
		endif;
		// wp_die();
		return $html_content;
	}
	private function videoURL( $vidName, $vidExt, $size, $post_id ) {
		$path = explode( '/lessons/', $vidName );
		$path = isset( $path[1] ) ? $path[1] : $path[0];
		return site_url( '/mycourses/watch/' . $post_id . '/' . $size . FWP_COURSES_REPLACE_URL_SLASH ) . $this->urlencode( $path . '.' . $vidExt );

		// $path  = $vidName . '.' . $vidExt;
		// return str_replace( [ '/lessons/' ], [ site_url( '/mycourses/watch/' . $post_id . '/' . $size . FWP_COURSES_REPLACE_URL_SLASH ) ], $path );

		// return str_replace( [ '/lessons/' ], [ FWP_COURSES_FORMATED_POSITION_URL ], $path );
		// https://demo.seojaws.com/wp-content/uploads/fwp-converted-videos/SEO Made Simple The Complete Blueprint to SEO Success/2. Introduction to SEO/1. Introduction-480.mp4
		// https://demo.seojaws.com/mycourses/download/1551/SEO+Made+Simple+The+Complete+Blueprint+to+SEO+Success/
	}
	private function subTitleURL( $path, $post_id ) {
		$path = str_replace( [ ABSPATH . 'lessons/' ], [ '' ], $path );
		return site_url( '/mycourses/subtitle/' . $post_id . '/' . 'vtt' . FWP_COURSES_REPLACE_URL_SLASH ) . $this->urlencode( $path );
	}
	private function attachURL( $path, $post_id ) {
		$path = str_replace( [ ABSPATH . 'lessons/' ], [ '' ], $path );
		return site_url( '/mycourses/attachments/' . $post_id . '/' . pathinfo( $path, PATHINFO_EXTENSION ) . FWP_COURSES_REPLACE_URL_SLASH ) . $this->urlencode( $path );
	}
	public function filesize( $src ) {
		return ( file_exists( $src ) && ! is_dir( $src ) ) ? $this->human_filesize( filesize( $src ) ) : 0;
	}

	/**
	 * Tutor metabox hook.
	 */
	public function getListAjax() {
		$url = isset( $_POST[ 'url' ] ) ? $_POST[ 'url' ] : false;
		$json = $this->getList( urldecode( $url ), true );
		wp_send_json_success( $json, 200 );
	}
	public function modelwrapers() {
		global $post;
		$post_id = ( ! empty( $post->ID ) ) ? $post->ID : ( ! empty( get_the_ID() ) ? get_the_ID() : ( isset( $_GET[ 'post' ] ) ? $_GET[ 'post' ] : '' ) );
		$data = [
			'model_title' => __( 'Tutor Course shortcodes', 'fwp-divi-child-seojaws' ),
			'title' => 'course_links',
			'model_title' => __( 'Tutor Course Shortcodes', 'fwp-divi-child-seojaws' ),
			'summary' => __( '', 'fwp-divi-child-seojaws' ),
			'course_id' => '', 'topic_id' => '',
			'button_class' => 'tutor-fwp-copy-code-btn', 'button_id' => 'tutor-fwp-copy-code-btn', 'button_text' => __( 'Copy code', 'fwp-divi-child-seojaws' )
		];
		$courseMeta = get_post_meta( $post_id, '_fwp_course', true );$courseMeta = wp_parse_args( $courseMeta, ['link'=>'','status'=>false, 'url'=>''] );
		$courseLinks = $this->getList( urldecode( $courseMeta[ 'link' ] ), true );
		$currentCourse = ( ! empty( $courseMeta[ 'link' ] ) ) ? $courseMeta[ 'link' ] : 'link';
		// print_r( [$courseMeta, $courseLinks] );wp_die( $post_id );

		?>
		<div class="new-topic-btn-wrap">
			<button data-tutor-modal-target="fwp-tutor-links-model" class="create_new_topic_btn tutor-btn tutor-btn-primary tutor-btn-md tutor-mt-16"> 
				<i class="tutor-icon-book tutor-mr-12"></i> <?php esc_html_e( 'Course Shortcodes', 'fwp-divi-child-seojaws' ); ?>
			</button>
		</div>

		<div id="fwp-tutor-links-model" class="tutor-modal tutor-modal-scrollable <?php echo esc_attr( 'tutor-admin-design-init' ); ?>">
			<div class="tutor-modal-overlay"></div>
			<div class="tutor-modal-window">
				<div class="tutor-modal-content">
					<div class="tutor-modal-header">
						<div class="tutor-modal-title">
							<?php echo esc_html( $data[ 'model_title' ] ); ?>
						</div>

						<div class="tutor-model-header-button-group">
							<button class="tutor-iconic-btn tutor-modal-minimizer" data-tutor-modal-minimizer>
								<span class="tutor-icon-up" area-hidden="true"></span>
							</button>
							<button class="tutor-iconic-btn tutor-modal-close" data-tutor-modal-close>
								<span class="tutor-icon-times" area-hidden="true"></span>
							</button>
						</div>
					</div>

					<div class="tutor-modal-body">
						<div class="tutor-mb-32">
							<label class="tutor-form-label"><?php esc_html_e( 'Select Lessons', 'fwp-divi-child-seojaws' ); ?></label>
							<!-- <input type="text" name="topic_title" class="tutor-form-control" value="<?php echo esc_attr( ! empty( $data['title'] ) ? $data['title'] : '' ); ?>"/> -->

							<div class="tutor-input-group tutor-mb-16 tutor-mt-12 tutor-d-block">
								<div class="tutor-video-upload-wrap">
									<select id="fwp_tutor_shortcode_select-topic" name="fwp_tutor[topics]" class="tutor-form-control tutor-select-icon-primary no-tutor-dropdown" data-path="<?php echo esc_attr( urldecode( $courseMeta[ 'link' ] ) ); ?>" data-course="<?php echo esc_attr( $post_id ); ?>">
										<option value="link" data-icon="link"><?php esc_html_e( 'Select a Topic', 'tutor' ); ?></option>
										<?php
										foreach ( $courseLinks as $i => $link ) {
												$selected = selected( $i, $currentCourse );
												echo sprintf( '<option value="%s" %s data-icon="%s">%s</option>', $link[1], $selected, 'link', $link[1] );
										}
										?>
									</select>
								</div>
							</div>

							<div class="tutor-input-group tutor-mb-16 tutor-mt-12 tutor-d-block">
								<div class="tutor-video-upload-wrap">
									<select id="fwp_tutor_shortcode_select-lesson" name="fwp_tutor[lessons]" class="tutor-form-control tutor-select-icon-primary no-tutor-dropdown" data-path="" data-course="<?php echo esc_attr( $post_id ); ?>">
										<option value="link" data-icon="link"><?php esc_html_e( 'Select a Lesson', 'tutor' ); ?></option>
									</select>
								</div>
							</div>

						<div>
							<label class="tutor-form-label"><?php esc_html_e( 'Copy Shortcode', 'fwp-divi-child-seojaws' ); ?></label>
							<textarea name="topic_summery" class="tutor-form-control tutor-mb-12" id="fwp_tutor_shortcode_textarea"></textarea>
							<input type="hidden" name="topic_course_id" value="<?php echo esc_attr( $data['course_id'] ); ?>">
							<input type="hidden" name="topic_id" value="<?php echo esc_attr( $data['topic_id'] ); ?>">
							<div class="tutor-form-feedback">
								<i class="tutor-icon-circle-info-o tutor-form-feedback-icon"></i>
								<div><?php esc_html_e( 'Copy your course links from here and then paste it over Lessons video as shourtcode.', 'fwp-divi-child-seojaws' ); ?></div>
							</div>
						</div>
					</div>

					<div class="tutor-modal-footer">
						<button class="tutor-btn tutor-btn-outline-primary" data-tutor-modal-close>
							<?php esc_html_e( 'Cancel', 'fwp-divi-child-seojaws' ); ?>
						</button>

						<button type="button" class="tutor-btn tutor-btn-primary <?php echo esc_attr( ! empty( $data['button_class'] ) ? $data['button_class'] : '' ); ?>" id="<?php echo esc_attr( ! empty( $data['button_id'] ) ? $data['button_id'] : '' ); ?>">
							<?php echo esc_attr( $data['button_text'] ); ?>
						</button>
					</div>
				</div>
			</div>
		</div>
		<script>
			document.querySelector( '.tutor-modal-minimizer' ).addEventListener( 'click', function( e ) {
				e.preventDefault();
				if( typeof this.dataset.tutorModalMinimizer !== 'undefined' ) {
						document.getElementById( 'fwp-tutor-links-model' ).classList.toggle( 'fwp-model-minimize' );
						document.body.classList.toggle( 'fwp-model-scroll-enabled' );
				}
			} );
			document.querySelector( '#fwp-tutor-links-model .tutor-modal-minimizer' ).addEventListener( 'click', function( e ) {
				document.getElementById( 'fwp-tutor-links-model' ).classList.toggle( 'fwp-model-opened' );
			} );
			document.getElementById( 'tutor-fwp-copy-code-btn' ).addEventListener( 'click', function( e ) {
				e.preventDefault();
				var text = this.innerText, svg = '<i class="tutor-icon-mark"></i>';
				navigator.clipboard.writeText( document.getElementById( 'fwp_tutor_shortcode_textarea' ).value );
				this.innerHTML = svg;
				setTimeout(() => {
					this.innerHTML = text;
				}, 2000 );
			} );
		</script>
		<script>window.FWPCOURSES_LINKS = <?php echo json_encode( $courseLinks ); ?>;</script>
		<style>body.tutor-screen-course-builder #tutor-course-content-builder-root .new-topic-btn-wrap {display: -webkit-inline-box;justify-content: space-between;}</style>
		<style>
			body.wp-admin.tutor-modal-open.fwp-model-scroll-enabled {overflow: auto;}#fwp-tutor-links-model.fwp-model-opened {display: block;z-index: 999999;}#fwp-tutor-links-model.fwp-model-minimize {height: 80px;width: 180px;overflow: hidden;top: 10px;right: 10px;left: auto;}#fwp-tutor-links-model.fwp-model-minimize .tutor-modal-overlay {display: none;}#fwp-tutor-links-model.fwp-model-minimize .tutor-modal-window {height: 50px;display: block;margin-top: 0;}#fwp-tutor-links-model.fwp-model-minimize .tutor-modal-window .tutor-modal-content {width: 150px;height: 55px;}#fwp-tutor-links-model.fwp-model-minimize .tutor-modal-window .tutor-modal-header {border-bottom-right-radius: 10px;border-bottom-left-radius: 10px;transition: all .3s ease-in-out;}#fwp-tutor-links-model.fwp-model-minimize .tutor-modal-window .tutor-modal-body {visibility: hidden;opacity: 1;}#fwp-tutor-links-model.fwp-model-minimize .tutor-modal-window .tutor-modal-content .tutor-modal-header .tutor-modal-title {display: none;}#fwp-tutor-links-model.fwp-model-minimize .tutor-modal-window .tutor-modal-content .tutor-modal-header .tutor-model-header-button-group {justify-content: space-between;display: flex;width: 100%;}#fwp-tutor-links-model.fwp-model-minimize .tutor-modal-window .tutor-modal-content .tutor-modal-header .tutor-model-header-button-group .tutor-modal-close {margin-right: 0;}
			select.tutor-form-control.is-loading, .tutor-form-select.is-loading {background-image: url('<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/icons/loading.svg' ); ?>');}
		</style>
		<?php
	}
	public function styleLoader( $html, $handle, $href, $media ) {
		$expolde = explode( ',', $href );
		if( isset( $expolde[1] ) && ! empty( $expolde[1] ) ) {
			$href = str_replace( [ '/A.' ], [ '' ], $href[0] );
			$html = '<link rel="preload" href="' . $href . '" as="style" id="' . $handle . '" media="' . $media . '" onload="this.onload=null;this.rel=\'stylesheet\'">' . '<noscript>' . $html . '</noscript>';
		}
		return $html;
	}
	public function human_filesize( $bytes, $decimals = 2 ) {
		// https://www.php.net/manual/en/function.filesize.php
		// $sz = explode( '', 'BKMGTP' );
		$sizes = [ 'B' => 'Byte', 'K' => 'KiB', 'M' => 'MiB', 'G' => 'GiB', 'T' => 'TiB', 'P' => 'PiB' ];
		$sz = 'BKMGTP';
		$factor = floor( ( strlen( $bytes ) - 1 ) / 3 );
		return sprintf( "%.{$decimals}f", $bytes / pow( 1024, $factor ) ) . ' ' . $sizes[ $sz[ $factor ] ];
	}
	public function srt2vtt( $args = false ) {
		// Check command line mode
		if( ! $args || ! is_array( $args ) ) {wp_die( __( 'This script only run in command line with 2 file paths as params.\n', 'fwp-divi-child-seojaws' ) );}
		
		// Get file paths
		$srtFile = $args[ 'src' ];
		$webVttFile = $args[ 'dest' ];
		
		// Read the srt file content into an array of lines
		$fileHandle = fopen( $srtFile, 'r');
		if ($fileHandle) {
			// Assume that every line has maximum 8192 length
			// If you don't care about line length then you can omit the 8192 param
			$lines = array();
			while ( ($line = fgets( $fileHandle, 8192 ) ) !== false ) $lines[] = $line;
			if( ! feof( $fileHandle ) ) wp_die( "Error: unexpected fgets() fail\n" );
			else ($fileHandle);
		}
		
		// Convert all timestamp lines
		// The first timestamp line is 1
		$length = count($lines);
		
		for ($index = 1; $index < $length; $index++) {
			// A line is a timestamp line if the second line above it is an empty line
			if ($index === 1 || trim($lines[$index - 2]) === '') {
				$lines[$index] = str_replace(',', '.', $lines[$index]);
			}
		}

		// Insert VTT header and concatenate all lines in the new vtt file
		$header = "WEBVTT\n\n";
		if( $args[ 'dest' ] !== false ) {
			file_put_contents( $webVttFile, implode( '', $lines ) );
			return 'ok';
		} else {
			return $header . implode( '', $lines );
		}
	}
}
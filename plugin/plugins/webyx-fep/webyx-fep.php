<?php
/**
 * Plugin Name: Webyx FE Pro
 * Description: Create amazing fullpage fullscreen scrolling websites with our fast, configurable and easy extension for Elementor.
 * Requires at least: 6.0
 * Requires PHP: 7.2
 * Version: 1.2.2
 * Author: Webineer Team
 * Author URI: https://webyx.it/wfe-guide 
 * Text Domain: webyx-fep
 * @package webyx-fep
 */
  if ( ! defined( 'ABSPATH' ) ) {
    exit;
  }
  define( 'WEBYX_FEP__FILE__', __FILE__ );
  define( 'WEBYX_FEP_VERSION', '1.2.2' );
  define( 'WEBYX_FEP_PATH', plugin_dir_path( WEBYX_FEP__FILE__ ) );
  define( 'WEBYX_FEP_MINIMUM_ELEMENTOR_VERSION', '3.4.7' );
  define( 'WEBYX_FEP_WP_MIN_VERSION', '5.7' );
  define( 'WEBYX_FEP_PHP_MIN_VERSION', '7.2' );
  define( 'WEBYX_FEP_ASSET_MIN', TRUE );
  define( 'WEBYX_FEP_REVIEW_THRESHOLD_DATE', '-7 days' );
  if ( ! version_compare( PHP_VERSION, WEBYX_FEP_PHP_MIN_VERSION, '>=' ) ) {
    function webyx_fep_fail_php_version () {
      $message = sprintf( esc_html__( 'Webyx FE Pro plugin requires PHP version %s+, plugin is currently NOT RUNNING.' ), WEBYX_FEP_PHP_MIN_VERSION );
      $html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
      echo wp_kses_post( $html_message );
    }
    add_action( 'admin_notices', 'webyx_fep_fail_php_version' );
  } else if ( ! version_compare( get_bloginfo( 'version' ), WEBYX_FEP_WP_MIN_VERSION, '>=' ) ) {
    function webyx_fep_fail_wp_version () {
      $message = sprintf( esc_html__( 'Webyx FE Pro requires WordPress version %s+. Because you are using an earlier version, the plugin is currently NOT RUNNING.' ), WEBYX_FEP_WP_MIN_VERSION );
      $html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
      echo wp_kses_post( $html_message );
    }
    add_action( 'admin_notices', 'webyx_fep_fail_wp_version' );
  } else {
    if ( ! class_exists( 'Webyx_Pro_For_Elementor' ) ) {
      class Webyx_Pro_For_Elementor {
        private static $webyx_fep_instance = NULL;
        private $is_open_section = FALSE;
        private $fl = TRUE;
        private $is_open_section_scrollable_hz = FALSE;
        private $templates = array();
        private $slug = 'webyx-fep';
        private $plugin_name = 'Webyx FE Pro';
        private $nada = array();
        private $webyx_section_name = '';
        private $cubic_bezier_animation = array(
          'cubic-bezier(0.64,0,0.34,1)',
          'cubic-bezier(0,0,1,1)',
          'cubic-bezier(0.25,0.1,0.25,1)',
          'cubic-bezier(0.42,0,1,1)',
          'cubic-bezier(0,0,0.58,1)',
          'cubic-bezier(0.42,0,0.58,1)',
          'cubic-bezier(0.02,0.01,0.47,1)',
          'cubic-bezier(0,0.5,0.5,1)',
          'cubic-bezier(0.12,0,0.39,0)',
          'cubic-bezier(0.32,0,0.67,0)',
          'cubic-bezier(0.64,0,0.78,0)',
          'cubic-bezier(0.55,0,1,0.45)',
          'cubic-bezier(0.11,0,0.5,0)',
          'cubic-bezier(0.5,0,0.75,0)',
          'cubic-bezier(0.7,0,0.84,0)',
          'cubic-bezier(0.36,0,0.66,-0.56)',
          'cubic-bezier(0.61,1,0.88,1)',
          'cubic-bezier(0.33,1,0.68,1)',
          'cubic-bezier(0.22,1,0.36,1)',
          'cubic-bezier(0,0.55,0.45,1)',
          'cubic-bezier(0.5,1,0.89,1)',
          'cubic-bezier(0.25,1,0.5,1)',
          'cubic-bezier(0.16,1,0.3,1)',
          'cubic-bezier(0.34,1.56,0.64,1)',
          'cubic-bezier(0.37,0,0.63,1)',
          'cubic-bezier(0.65,0,0.35,1)',
          'cubic-bezier(0.83,0,0.17,1)',
          'cubic-bezier(0.85,0,0.15,1)',
          'cubic-bezier(0.45,0,0.55,1)',
          'cubic-bezier(0.76,0,0.24,1)',
          'cubic-bezier(0.68,-0.6,0.32,1.6)',
        );
        private $tag_name = array(
          'div',
          'section',
          'article',
          'aside',
          'header',
          'footer',
          'ul',
          'ol',
          'li',
        );
        private $cache_key = 'wfep_upd';
        private $cache_allowed = TRUE;
        private $api = 'https://webyx.it';
        private $product_id = 's7p5Dd2iS_EV3Uf86RbMGQ==';
        private $support_id = 'yDI3slxOvF-vTnE7iKo61A==';
        public function __construct () {
          $this->templates = array( 
            'templates/page-webyx-fep.php' => 'webyx FE Pro'
          );
          add_action( 
            'plugins_loaded', 
            array(
              $this, 
              'webyx_fep_on_plugins_loaded' 
            )
          );
        }
        public static function webyx_fep_get_instance () {
          if ( is_null( self::$webyx_fep_instance ) ) {
            self::$webyx_fep_instance = new self();
          }
          return self::$webyx_fep_instance;
        }
        public function webyx_fep_i18n () {
          load_plugin_textdomain( $this->slug );
        }
        public function webyx_fep_sanitize_hex_color ( $color ) {
          $color = preg_replace( '/[^0-9a-fA-F]/', '', $color );
          $length = strlen( $color );
          if ( $length === 3 || $length === 4 ) {
            $color = preg_replace( '/(.)/', '$1$1', $color );
            $length = strlen( $color );
          }
          if ( $length === 6 || $length === 8 ) {
            return $color;
          }
          return false;
        }
        public function webyx_fep_chk_intgr ( $content ) { 
          $pattern = "/data-webyx=\"webyx-fep-fl\"/";
          $chk_intgr = preg_match( $pattern, $content );
          if ( $this->fl && $chk_intgr  ) {
            $this->fl = false;
            return true;
          }
        }
        public function webyx_fep_on_plugins_loaded () {
          if ( $this->webyx_fep_is_compatible() ) {
            add_action( 
              'elementor/init', 
              array(
                $this, 
                'webyx_fep_init'
              )
            );
          }
        }
        public function webyx_fep_is_compatible () {
          if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 
              'admin_notices', 
              array( 
                $this, 
                'webyx_fep_admin_notice_missing_main_plugin'
              ) 
            );
            return false;
          }
          if ( ! version_compare( ELEMENTOR_VERSION, WEBYX_FEP_MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 
              'admin_notices', 
              array(
                $this, 
                'webyx_fep_admin_notice_minimum_elementor_version'
              )
            );
            return false;
          }
          if ( version_compare( PHP_VERSION, WEBYX_FEP_PHP_MIN_VERSION, '<' ) ) {
            add_action( 
              'admin_notices', 
              array(
                $this, 
                'webyx_fep_admin_notice_minimum_php_version' 
              )
            );
            return false;
          }
          return true;
        }
        public function webyx_fep_admin_notice_missing_main_plugin () {
          if ( isset( $_GET[ 'activate' ] ) )  {
            unset( $_GET[ 'activate' ] );
          }
          $message = sprintf(
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', $this->slug ),
            '<strong>' . esc_html__( 'Webyx FE Pro', $this->slug ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', $this->slug ) . '</strong>'
          );
          printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
        }
        public function webyx_fep_admin_notice_minimum_elementor_version () {
          if ( isset( $_GET[ 'activate' ] ) )  {
            unset( $_GET[ 'activate' ] );
          }
          $message = sprintf(
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', $this->slug ),
            '<strong>' . esc_html__( 'Webyx FE Pro', $this->slug ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', $this->slug ) . '</strong>',
            WEBYX_FEP_MINIMUM_ELEMENTOR_VERSION
          );
          printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
        }
        public function webyx_fep_admin_notice_minimum_php_version () {
          if ( isset( $_GET[ 'activate' ] ) )  {
            unset( $_GET[ 'activate' ] );
          }
          $message = sprintf(
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', $this->slug ),
            '<strong>' . esc_html__( 'Webyx FE Pro', $this->slug ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', $this->slug ) . '</strong>',
            WEBYX_FEP_PHP_MIN_VERSION
          );
          printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
        }
        public function webyx_fep_is_frontend_view () {
          return ( ! Elementor\Plugin::$instance->editor->is_edit_mode() && ! Elementor\Plugin::$instance->preview->is_preview_mode() );
        }
        public function webyx_fep_is_enable ( $ps ) {
          return( isset( $ps ) && isset( $ps[ 'webyx_enable' ] ) && $ps[ 'webyx_enable' ] === 'on' );
        }
        public function webyx_fep_init () {
          if ( $this->webyx_fep() ) {
            $this->webyx_fep_i18n();
            $this->webyx_fep_admin_enqueue_scripts();     
            $this->webyx_fep_admin_document_settings();
            $this->webyx_fep_admin_section_options();
            $this->webyx_fep_frontend_sections_content();
            $this->webyx_fep_frontend_enqueue_assets();
            $this->webyx_fep_top_admin_bar();
            $this->webyx_fep_init_menu();
            $this->webyx_fep_init_page_template();
            $this->webyx_fep_widget_editor_preview();
            $this->webyx_fep_handle_update();
          }
          $this->webyx_fep_rest_auth();
          $this->webyx_fep_handle_auth();
          $this->webyx_fep_admin_settings();
          $this->webyx_fep_deactivation();
        }
        public function webyx_fep_is_container_active () {
          $experiments_manager = \Elementor\Plugin::$instance->experiments;
		      return $is_container_active = $experiments_manager->is_feature_active( 'container' );
        }
        public function webyx_fep_deactivation () {
          register_deactivation_hook( 
            __FILE__, 
            array(
              $this,
              'webyx_fep_deactivation_route' 
            )
          );
        }
        public function webyx_fep_deactivation_route () {
          delete_transient( $this->cache_key );
        }
        public function webyx_fep_handle_auth () {
          add_action( 
            'admin_init',
            array(
              $this,
              'webyx_fep' 
            ) 
          );
        }
        public function webyx_fep () {
          if ( ! filter_var( get_option( 'webyx_fep_ak' ), FILTER_VALIDATE_BOOLEAN ) ) {
            add_action( 
              'admin_notices',
              array(
                $this,
                'webyx_fep_display_plugins_auth_notice' 
              ) 
            );
            return FALSE;
          }
          return TRUE;
        }
        public function webyx_fep_display_plugins_auth_notice () {
          global $pagenow;
          if ( 'plugins.php' === $pagenow ) {
            $plugin_info = get_plugin_data( __FILE__ , true, true );       
            $plugins_options_url = esc_url( get_admin_url() . 'options-general.php?page=webyx_fep_plugin_settings' );
            printf(
              __( '<div class="webyx-fep-review webyx-fep-wrp">
                    <p><b>%s</b> plugin requires license key to be activated. Please enter your license key!</p>
                    <div class="webyx-fep-review-btn">
                      <a href="%s" class="button button-primary webyx-fep-rate-now">Activate License!</a>
                    </div>
                  </div>', $plugin_info[ 'TextDomain' ] ), 
              sanitize_title( $plugin_info[ 'Name' ] ),
              $plugins_options_url
            );
          }
        }
        public function webyx_fep_widget_editor_preview () {
          $is_container_active = $this->webyx_fep_is_container_active();
          add_action( 
            'elementor/preview/enqueue_styles', 
            array(
              $this,
              'webyx_fep_section_enqueue_styles' 
            )
          );
          add_filter( 
            $is_container_active ? 'elementor/container/print_template' : 'elementor/section/print_template', 
            array(
              $this,
              'webyx_fep_widget_outline_js_template'
            ),
            10, 
            2 
          );
        }
        public function webyx_fep_widget_outline_js_template ( $template, $widget ) {
          global $post;
          $ps = get_post_meta( $post->ID, '_elementor_page_settings', true );
          $webyx_fep_is_enable = $this->webyx_fep_is_enable( $ps );
          $glb_mq_en = isset( $ps[ 'global_webyx_section_mq_enable' ] ) && in_array( $ps[ 'global_webyx_section_mq_enable' ], array( 'on', '' ), true ) ? $ps[ 'global_webyx_section_mq_enable' ] : '';                                    
          $glb_mq_xs = isset( $ps[ 'global_webyx_section_mq_xs' ] ) && ( isset( $ps[ 'global_webyx_section_mq_xs' ][ 'unit' ] ) && in_array( $ps[ 'global_webyx_section_mq_xs' ][ 'unit' ], array( 'px' ), true ) ) && ( isset( $ps[ 'global_webyx_section_mq_xs' ][ 'size' ] ) && filter_var( $ps[ 'global_webyx_section_mq_xs' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ) ? $ps[ 'global_webyx_section_mq_xs' ] : array( 'unit' => 'px', 'size' => 760 );
          $widget_name = $widget->get_name();
          if ( $webyx_fep_is_enable ) {
            if ( 'container' === $widget_name ) {
              ob_start(); ?>
                <# if ( settings.webyx_section_enable ) { #>
                  <# if ( 'boxed' === settings.content_width ) { #>
                    <div class="e-con-inner">
                  <# } #>
                    <# var css_dsk = '', css_mob = ''; 
                      css_dsk += `.webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-dsk-on.webyx-section-foreground-object-color .webyx-section-editor-${id}{
                          background-color:var(--section-color-dsk)
                        }
                        .webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-dsk-on.webyx-section-foreground-object-color .webyx-section-editor-${id} .webyx-section-video-editor-${id},
                        .webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-dsk-on.webyx-section-foreground-object-color .webyx-section-editor-${id} .webyx-section-video-editor-${id}-xs{
                          display:none
                        }`;
                      css_dsk += `.webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-dsk-on.webyx-section-foreground-object-image .webyx-section-editor-${id}{
                          background-image:var(--section-image-url);
                          background-size:var(--section-image-background-size);
                          background-position:var(--section-image-background-position);
                          background-repeat:var(--section-image-background-repeat);
                          background-attachment:var(--section-image-background-attachment);
                        }
                        .webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-dsk-on.webyx-section-foreground-object-image .webyx-section-editor-${id} .webyx-section-video-editor-${id},
                        .webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-dsk-on.webyx-section-foreground-object-image .webyx-section-editor-${id} .webyx-section-video-editor-${id}-xs{
                          display:none
                        }`;
                      css_dsk += `.webyx-section-video-editor-${id}-xs{
                          display:none
                        }`;
                      css_dsk += `.webyx-section-video-editor-${id}{
                          display:none
                        }`;
                      css_dsk += `.webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-dsk-on.webyx-section-foreground-object-video .webyx-section-video-editor-${id}{
                          display:block;
                        }`;
                      css_mob += `.webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-mob-on.webyx-section-foreground-object-mob-color .webyx-section-editor-${id}{
                          background-color:var(--section-color-mob)
                        }
                        .webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-mob-on.webyx-section-foreground-object-mob-color .webyx-section-editor-${id} .webyx-section-video-editor-${id},
                        .webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-mob-on.webyx-section-foreground-object-mob-color .webyx-section-editor-${id} .webyx-section-video-editor-${id}-xs{
                          display:none
                        }`;
                      css_mob += `.webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-mob-on.webyx-section-foreground-object-mob-image .webyx-section-editor-${id}{
                          background-image:var(--section-image-url-mob);
                          background-size:var(--section-image-background-size-mob);
                          background-position:var(--section-image-background-position-mob);
                          background-repeat:var(--section-image-background-repeat-mob);
                          background-attachment:var(--section-image-background-attachment-mob);
                        }
                        .webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-mob-on.webyx-section-foreground-object-mob-image .webyx-section-editor-${id} .webyx-section-video-editor-${id},
                        .webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-mob-on.webyx-section-foreground-object-mob-image .webyx-section-editor-${id} .webyx-section-video-editor-${id}-xs{
                          display:none
                        }`;
                      css_mob += `.webyx-section-video-editor-${id}{
                          display:none
                        }`;
                      css_mob += `.webyx-section-video-editor-${id}-xs{
                          display:none
                        }`;
                      css_mob += `.webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-mob-on.webyx-section-foreground-object-mob-video .webyx-section-video-editor-${id}-xs{
                          display:block;
                        }`;
                      var global_webyx_section_mq_enable = '<?php echo esc_attr( $glb_mq_en ) ?>',     
                        global_webyx_section_mq_xs_size =  <?php echo esc_attr( $glb_mq_xs[ 'size' ] ) ?>,
                        global_webyx_section_mq_xs_unit = '<?php echo esc_attr( $glb_mq_xs[ 'unit' ] ) ?>',
                        webyx_section_mq_xs = settings.webyx_section_mq_xs,     
                        webyx_section_mq_um = global_webyx_section_mq_enable ? global_webyx_section_mq_xs_unit : webyx_section_mq_xs.unit,
                        webyx_section_mq_val = `${ global_webyx_section_mq_enable ? ( global_webyx_section_mq_xs_size + 1 ) : ( webyx_section_mq_xs.size + 1 ) }${webyx_section_mq_um}`,
                        webyx_section_mq_xs_val = `${ global_webyx_section_mq_enable ? global_webyx_section_mq_xs_size : webyx_section_mq_xs.size }${webyx_section_mq_um}`,
                        css_mq = css_dsk ? `@media only screen and (min-width:${webyx_section_mq_val}){${css_dsk}}` : '',
                        css_mq_xs = css_mob ? `@media only screen and (max-width:${webyx_section_mq_xs_val}){${css_mob}}` : '',
                        css = `${css_mq}${css_mq_xs}`; #>
                    <style>{{css}}</style>
                    <div class="webyx-background-overlay webyx-background-overlay-bkg-color webyx-background-overlay-bkg-img webyx-section-editor-{{id}}">
                      <video class="webyx-video-bkg-ext webyx-section-video-editor-{{id}}-xs" style="object-fit:cover;width:100%;height:100%;position:absolute;top:0;left:0">
                        <source src="{{settings.webyx_section_background_video_mob.url}}"/>
                      </video>
                      <video class="webyx-video-bkg-ext webyx-section-video-editor-{{id}}" style="object-fit:cover;width:100%;height:100%;position:absolute;top:0;left:0">
                        <source src="{{settings.webyx_section_background_video.url}}"/>
                      </video>
                    </div> 
                    <div class="webyx-background-overlay"></div>
                    <div class="elementor-shape elementor-shape-top"></div>
                    <div class="elementor-shape elementor-shape-bottom"></div>
                    <div class="webyx-header"></div>
                    <div class="webyx-overlay-menu">
                      <div class="webyx-overlay-menu-icon-{{settings.webyx_section_type}}"><span class="webyx-scrollable"></span></div>
                      <div class="webyx-overlay-menu-icon-{{settings.webyx_section_type}}"><span class="webyx-continuous"></span></div>
                      <div class="webyx-overlay-menu-icon-{{settings.webyx_section_type}}"><span class="webyx-content-position-top"></span></div>
                      <div class="webyx-overlay-menu-icon-{{settings.webyx_section_type}}"><span class="webyx-content-position-middle"></span></div>
                      <div class="webyx-overlay-menu-icon-{{settings.webyx_section_type}}"><span class="webyx-content-position-bottom"></span></div> 
                      <div class="webyx-overlay-menu-icon-{{settings.webyx_section_type}}"><span class="webyx-content-wrapper"></span></div>
                      <div class="webyx-overlay-menu-icon-{{settings.webyx_section_type}}"><span class="webyx-background-color"></span></div>
                      <div class="webyx-overlay-menu-icon-{{settings.webyx_section_type}}"><span class="webyx-background-image"></span></div>
                      <div class="webyx-overlay-menu-icon-{{settings.webyx_section_type}}"><span class="webyx-background-video"></span></div>
                    </div>
                  <# if ( 'boxed' === settings.content_width ) { #>
                    </div>
                  <# } #>
                <# } else { #>
                  <?php echo $template ?>
                <# } #>
              <?php $template = ob_get_clean();
            }
            if ( 'section' === $widget_name ) { 
              ob_start(); ?>
                <# if ( settings.webyx_section_enable ) { #>
                  <# var css_dsk = '', css_mob = ''; 
                    css_dsk += `.webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-dsk-on.webyx-section-foreground-object-color .webyx-section-editor-${id}{
                        background-color:var(--section-color-dsk)
                      }
                      .webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-dsk-on.webyx-section-foreground-object-color .webyx-section-editor-${id} .webyx-section-video-editor-${id},
                      .webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-dsk-on.webyx-section-foreground-object-color .webyx-section-editor-${id} .webyx-section-video-editor-${id}-xs{
                        display:none
                      }`;
                    css_dsk += `.webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-dsk-on.webyx-section-foreground-object-image .webyx-section-editor-${id}{
                        background-image:var(--section-image-url);
                        background-size:var(--section-image-background-size);
                        background-position:var(--section-image-background-position);
                        background-repeat:var(--section-image-background-repeat);
                        background-attachment:var(--section-image-background-attachment);
                      }
                      .webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-dsk-on.webyx-section-foreground-object-image .webyx-section-editor-${id} .webyx-section-video-editor-${id},
                      .webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-dsk-on.webyx-section-foreground-object-image .webyx-section-editor-${id} .webyx-section-video-editor-${id}-xs{
                        display:none
                      }`;
                    css_dsk += `.webyx-section-video-editor-${id}-xs{
                        display:none
                      }`;
                    css_dsk += `.webyx-section-video-editor-${id}{
                        display:none
                      }`;
                    css_dsk += `.webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-dsk-on.webyx-section-foreground-object-video .webyx-section-video-editor-${id}{
                        display:block;
                      }`;
                    css_mob += `.webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-mob-on.webyx-section-foreground-object-mob-color .webyx-section-editor-${id}{
                        background-color:var(--section-color-mob)
                      }
                      .webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-mob-on.webyx-section-foreground-object-mob-color .webyx-section-editor-${id} .webyx-section-video-editor-${id},
                      .webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-mob-on.webyx-section-foreground-object-mob-color .webyx-section-editor-${id} .webyx-section-video-editor-${id}-xs{
                        display:none
                      }`;
                    css_mob += `.webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-mob-on.webyx-section-foreground-object-mob-image .webyx-section-editor-${id}{
                        background-image:var(--section-image-url-mob);
                        background-size:var(--section-image-background-size-mob);
                        background-position:var(--section-image-background-position-mob);
                        background-repeat:var(--section-image-background-repeat-mob);
                        background-attachment:var(--section-image-background-attachment-mob);
                      }
                      .webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-mob-on.webyx-section-foreground-object-mob-image .webyx-section-editor-${id} .webyx-section-video-editor-${id},
                      .webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-mob-on.webyx-section-foreground-object-mob-image .webyx-section-editor-${id} .webyx-section-video-editor-${id}-xs{
                        display:none
                      }`;
                    css_mob += `.webyx-section-video-editor-${id}{
                        display:none
                      }`;
                    css_mob += `.webyx-section-video-editor-${id}-xs{
                        display:none
                      }`;
                    css_mob += `.webyx-section-background-editor-on.webyx-section-background-on.webyx-section-background-mob-on.webyx-section-foreground-object-mob-video .webyx-section-video-editor-${id}-xs{
                        display:block;
                      }`;
                    var global_webyx_section_mq_enable = '<?php echo esc_attr( $glb_mq_en ) ?>',     
                      global_webyx_section_mq_xs_size =  <?php echo esc_attr( $glb_mq_xs[ 'size' ] ) ?>,
                      global_webyx_section_mq_xs_unit = '<?php echo esc_attr( $glb_mq_xs[ 'unit' ] ) ?>',
                      webyx_section_mq_xs = settings.webyx_section_mq_xs,     
                      webyx_section_mq_um = global_webyx_section_mq_enable ? global_webyx_section_mq_xs_unit : webyx_section_mq_xs.unit,
                      webyx_section_mq_val = `${ global_webyx_section_mq_enable ? ( global_webyx_section_mq_xs_size + 1 ) : ( webyx_section_mq_xs.size + 1 ) }${webyx_section_mq_um}`,
                      webyx_section_mq_xs_val = `${ global_webyx_section_mq_enable ? global_webyx_section_mq_xs_size : webyx_section_mq_xs.size }${webyx_section_mq_um}`,
                      css_mq = css_dsk ? `@media only screen and (min-width:${webyx_section_mq_val}){${css_dsk}}` : '',
                      css_mq_xs = css_mob ? `@media only screen and (max-width:${webyx_section_mq_xs_val}){${css_mob}}` : '',
                      css = `${css_mq}${css_mq_xs}`; #>
                    <style>{{css}}</style>
                    <div class="webyx-background-overlay webyx-background-overlay-bkg-color webyx-background-overlay-bkg-img webyx-section-editor-{{id}}">
                      <video class="webyx-video-bkg-ext webyx-section-video-editor-{{id}}-xs" style="object-fit:cover;width:100%;height:100%;position:absolute;top:0;left:0">
                        <source src="{{settings.webyx_section_background_video_mob.url}}"/>
                      </video>
                      <video class="webyx-video-bkg-ext webyx-section-video-editor-{{id}}" style="object-fit:cover;width:100%;height:100%;position:absolute;top:0;left:0">
                        <source src="{{settings.webyx_section_background_video.url}}"/>
                      </video>
                    </div> 
                    <div class="webyx-background-overlay"></div>
                    <div class="elementor-shape elementor-shape-top"></div>
                    <div class="elementor-shape elementor-shape-bottom"></div>
                    <div class="webyx-header"></div>
                    <div class="webyx-overlay-menu">
                      <div class="webyx-overlay-menu-icon-{{settings.webyx_section_type}}"><span class="webyx-scrollable"></span></div>
                      <div class="webyx-overlay-menu-icon-{{settings.webyx_section_type}}"><span class="webyx-continuous"></span></div>
                      <div class="webyx-overlay-menu-icon-{{settings.webyx_section_type}}"><span class="webyx-content-position-top"></span></div>
                      <div class="webyx-overlay-menu-icon-{{settings.webyx_section_type}}"><span class="webyx-content-position-middle"></span></div>
                      <div class="webyx-overlay-menu-icon-{{settings.webyx_section_type}}"><span class="webyx-content-position-bottom"></span></div> 
                      <div class="webyx-overlay-menu-icon-{{settings.webyx_section_type}}"><span class="webyx-content-wrapper"></span></div>
                      <div class="webyx-overlay-menu-icon-{{settings.webyx_section_type}}"><span class="webyx-background-color"></span></div>
                      <div class="webyx-overlay-menu-icon-{{settings.webyx_section_type}}"><span class="webyx-background-image"></span></div>
                      <div class="webyx-overlay-menu-icon-{{settings.webyx_section_type}}"><span class="webyx-background-video"></span></div>
                    </div>
                    <div class="elementor-container elementor-column-gap-{{settings.gap}}"></div>
                <# } else { #>
                  <?php echo $template ?>
                <# } #>
              <?php
              $template = ob_get_clean();
            }
          }
          return $template;
        }
        public function webyx_fep_section_enqueue_styles () {
          global $post;
          $is_frontend_view = $this->webyx_fep_is_frontend_view();
          $ps = get_post_meta( $post->ID, '_elementor_page_settings', true );
          $webyx_fep_is_enable = $this->webyx_fep_is_enable( $ps );
          if ( $webyx_fep_is_enable ) {
            wp_enqueue_style( 
              'webyx-fep-section-css', 
              plugins_url( 
                WEBYX_FEP_ASSET_MIN ? 'assets/css/webyx-section.min.css' : 'assets/css/webyx-section.css', 
                __FILE__ 
              ),
              array(),
              filemtime( 
                plugin_dir_path( __FILE__ ) . (  WEBYX_FEP_ASSET_MIN ? 'assets/css/webyx-section.min.css' : 'assets/css/webyx-section.css' ) 
              )
            );
          }
        }
        public function webyx_fep_admin_settings () {
          add_action( 
            'init',
            array( 
              $this, 
              'webyx_fep_register_settings_admin_page' 
            ), 
            10 
          );
          add_action( 
            'admin_menu',
            array( 
              $this, 
              'webyx_fep_add_setting_admin_page' 
            ), 
            10 
          );
          add_action( 
            'admin_enqueue_scripts', 
            array(
              $this,
              'webyx_fep_enqueue_scripts_settings_admin_page'
            ),
            10
          );
          add_action( 
            'plugin_action_links_' . plugin_basename( __FILE__ ),
            array(
              $this,
              'webyx_fep_add_settings_link' 
            ),
            10 
          );
          add_filter( 
            'plugin_row_meta', 
            array(
              $this,
              'webyx_fep_append_support_and_faq_links' 
            ),
            10,
            4 
          );
          if ( filter_var( get_option( 'webyx_fep_http_api_debug' ), FILTER_VALIDATE_BOOLEAN ) ) {
            add_action( 
              'http_api_debug', 
              array(
                $this,
                'webyx_fep_debug_wp_remote_post'
              ),
              10, 
              5 
            );
          }
        }
        public function webyx_fep_register_settings_admin_page () {
          register_setting(
            'webyx_fep_plugin_settings',
            'webyx_fep_lk',
            array(
              'default'      => '',
              'show_in_rest' => TRUE,
              'type'         => 'string',
            )
          );
          register_setting(
            'webyx_fep_plugin_settings',
            'webyx_fep_ak',
            array(
              'default'      => '',
              'show_in_rest' => TRUE,
              'type'         => 'string',
            )
          );
          register_setting(
            'webyx_fep_plugin_settings',
            'webyx_fep_ls',
            array(
              'default'      => '',
              'show_in_rest' => TRUE,
              'type'         => 'string',
            )
          );
          register_setting(
            'webyx_fep_plugin_settings',
            'webyx_fep_as',
            array(
              'default'      => '',
              'show_in_rest' => TRUE,
              'type'         => 'string',
            )
          );
          register_setting(
            'webyx_fep_plugin_settings',
            'webyx_fep_hide_admin_top_bar',
            array(
              'default'      => 'true',
              'show_in_rest' => TRUE,
              'type'         => 'string',
            )
          );
          register_setting(
            'webyx_fep_plugin_settings',
            'webyx_fep_http_api_debug',
            array(
              'default'      => '',
              'show_in_rest' => TRUE,
              'type'         => 'string',
            )
          );
          register_setting(
            'webyx_fep_plugin_settings',
            'webyx_fep_menu',
            array(
              'default'      => 'true',
              'show_in_rest' => TRUE,
              'type'         => 'string',
            )
          );
        }
        public function webyx_fep_add_setting_admin_page () {
          add_options_page(
            esc_html__( 'Webyx FE Pro Settings', $this->slug ),
            esc_html__( 'Webyx FE Pro Settings', $this->slug ),
            'manage_options',
            'webyx_fep_plugin_settings',
            array(
              $this,
              'webyx_fep_print_setting_admin_page'
            )
          );
        }
        public function webyx_fep_print_setting_admin_page () {
          echo '<div id="webyx-fep-settings"></div>';
        }
        public function webyx_fep_enqueue_scripts_settings_admin_page () {
          $dir = __DIR__;
          $script_asset_path = "$dir/build/index.asset.php";
          if ( ! file_exists( $script_asset_path ) ) {
            throw new Error(
              'You need to run `npm start` or `npm run build` for the "webyx-fep-plugin" block first.'
            );
          }
          $admin_js = 'build/index.js';
          wp_enqueue_script(
            'webyx-fep-settings-admin-editor',
            plugins_url( $admin_js, __FILE__ ),
            array( 'wp-api', 'wp-components', 'wp-data', 'wp-element', 'wp-i18n', 'wp-notices', 'wp-polyfill' ),
            filemtime( "$dir/$admin_js" )
          );
          $admin_css = 'build/index.css';
          wp_enqueue_style(
            'webyx-fep-settings-admin-style',
            plugins_url( $admin_css, __FILE__ ),
            array( 'wp-components' ),
            filemtime( "$dir/$admin_css" )
          );
        }
        public function webyx_fep_add_settings_link ( $links ) {
          $new_links = array(
            'Settings' => '<a href="options-general.php?page=webyx_fep_plugin_settings">' . esc_html__( 'Settings', $this->slug ) . '</a>',
          );
          $links = array_merge( $links, $new_links );
          return $links;
        }
        public function webyx_fep_append_support_and_faq_links ( $links_array, $plugin_file_name, $plugin_data, $status ) {
          if ( strpos( $plugin_file_name, basename(__FILE__) ) ) {
            $new_links = array(
              'Docs' => '<a href="https://webyx.it/wfe-guide" target="_blank">' . esc_html__( 'Docs', $this->slug ) . '</a>',
              'FAQs' => '<a href="https://webyx.it/wfe-guide#faq" target="_blank">' . esc_html__( 'FAQs', $this->slug ) . '</a>',
            );
            $links_array = array_merge( $links_array, $new_links );
          }
          return $links_array;
        }
        public function webyx_fep_debug_wp_remote_post ( $response, $context, $class, $r, $url ) {
          error_log( '------------------------------' );
          error_log( $url );
          error_log( json_encode( $response ) );
          error_log( $class );
          error_log( $context );
          error_log( json_encode( $r ) );
        }
        public function webyx_fep_top_admin_bar () {
          add_filter( 
            'show_admin_bar', 
            array( 
              $this, 
              'webyx_fep_toggle_top_admin_bar' 
            ) 
          );
        }
        public function webyx_fep_toggle_top_admin_bar ( $show_admin_bar ) {
          $page_id = get_queried_object_id();
          if ( 'page' === get_post_type( $page_id ) && 'templates/page-webyx-fep.php' === get_page_template_slug( $page_id ) ) {
            return ! get_option( 'webyx_fep_hide_admin_top_bar', 'true' );
          } else {
            return $show_admin_bar;
          }
        }
        public function webyx_fep_init_page_template () {
          add_filter(
            'theme_page_templates', 
            array( 
              $this, 
              'webyx_fep_add_new_template' 
            )
          );
          add_filter(
            'wp_insert_post_data', 
            array( 
              $this, 
              'webyx_fep_register_project_templates' 
            ) 
          );
          add_filter(
            'template_include', 
            array( 
              $this, 
              'webyx_fep_view_project_template'
            ), 
            12
          );
        }
        public function webyx_fep_add_new_template ( $posts_templates ) {
          $posts_templates = array_merge( $posts_templates, $this->templates );
          return $posts_templates;
        }
        public function webyx_fep_register_project_templates ( $atts ) {
          $cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );
          $templates = wp_get_theme()->get_page_templates();
          if ( empty( $templates ) ) {
            $templates = array();
          } 
          wp_cache_delete( $cache_key , 'themes');
          $templates = array_merge( $templates, $this->templates );
          wp_cache_add( $cache_key, $templates, 'themes', 1800 );
          return $atts;
        }
        public function webyx_fep_view_project_template ( $template ) {
          $page_id = get_queried_object_id();
          $webyx_ttfn_path = WEBYX_FEP_PATH . 'templates/page-webyx-fep.php';
          $ps = get_post_meta( $page_id, '_elementor_page_settings', true );
          $_wp_page_template = get_post_meta( $page_id, '_wp_page_template', true );
          $ctt = isset( $ps[ 'ctt' ] ) && in_array( $ps[ 'ctt' ], array( 'on', '' ), true ) ? $ps[ 'ctt' ]   : '';
          $cttfn = isset( $ps[ 'cttfn' ] ) ? $ps[ 'cttfn' ] : '';
          if ( 'on' === $ctt ) {
            if ( sanitize_text_field( $cttfn ) ) {
              $cttfn_path = get_stylesheet_directory() . '/' . sanitize_text_field( $cttfn );
              if ( file_exists( $cttfn_path ) ) {
                return $cttfn_path;
              }
            } 
            if ( file_exists( $webyx_ttfn_path ) ) {
              return $webyx_ttfn_path;
            } 
          }
          if ( 'templates/page-webyx-fep.php' === $_wp_page_template ) {
            if ( file_exists( $webyx_ttfn_path ) ) {
              return $webyx_ttfn_path;
            }
          }
          return $template;
        }
        public function webyx_fep_init_menu () {
          add_action( 
            'init',
            array( 
              $this, 
              'webyx_fep_register_menu' 
            )
          );
        }
        public function webyx_fep_register_menu () {
          remove_filter( 'walker_nav_menu_start_el', 'twenty_twenty_one_add_sub_menu_toggle', 10, 4 );
          $webyx_fep_menu = get_option( 'webyx_fep_menu', 'true' );
          add_theme_support('menus');
          if ( $webyx_fep_menu ) {
            register_nav_menu(
              'webyx-menu', __( 'Webyx Menu', get_template_directory() . '/languages' )
            );
          }
        }
        public function webyx_fep_admin_enqueue_scripts () {
          add_action(
            'elementor/editor/before_enqueue_scripts', 
            array(
              $this, 
              'webyx_fep_admin_styles'
            )
          );
          add_action(
            'elementor/editor/before_enqueue_scripts', 
            array(
              $this, 
              'webyx_fep_admin_script'
            )
          );
        }
        public function webyx_fep_admin_styles () {
          $fn = WEBYX_FEP_ASSET_MIN ? 'assets/css/webyx-admin.min.css' : 'assets/css/webyx-admin.css';
          $path = plugins_url( 
            $fn, 
            __FILE__ 
          );
          wp_register_style( 
            'webyx-fe-admin-page-options-styles', 
            $path,
            array(),
            filemtime( 
              plugin_dir_path( __FILE__ ) . $fn 
            )
          );
          wp_enqueue_style( 'webyx-fe-admin-page-options-styles' );
        }
        public function webyx_fep_admin_script () {
          $fn = WEBYX_FEP_ASSET_MIN ? 'assets/js/webyx-admin.min.js' : 'assets/js/webyx-admin.js';
          $path = plugins_url( 
            $fn, 
            __FILE__ 
          );
          wp_register_script( 
            'webyx-fe-admin-script', 
            $path,
            array(),
            filemtime( 
              plugin_dir_path( __FILE__ ) . $fn 
            )
          );
          wp_enqueue_script( 'webyx-fe-admin-script' );
        }
        public function webyx_fep_admin_document_settings () {
          add_action( 
            'elementor/element/wp-page/document_settings/after_section_end', 
            array(
              $this, 
              'webyx_fep_admin_document_setting_controls'
            ), 
            10, 
            2 
          );
        } 
        public function webyx_fep_enabled_controls ( $page ) {
          $page->start_controls_section(
            'webyx',
            array(
              'label' => esc_html__( 'WEBYX FE PRO', $this->slug ),
              'tab'   => 'webyx-fe',
            )
          );
          $page->add_control(
            'webyx_enable',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Enable Webyx', $this->slug ),
              'description'  => esc_html__( 'Enable Webyx to create cool fullpage fullscreen scrolling websites.', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control(
            'webyx_reload',
            array(
              'type'        => \Elementor\Controls_Manager::BUTTON,
              'description' => esc_html__( 'IMPORTANT: click here after you have toggled the \'Enable Webyx\' option to reload and apply the changes.', $this->slug ),
              'text'        => esc_html__( 'Apply changes', $this->slug ),
              'button_type' => 'success',
              'event'       => 'webyxReload',
              'classes'     => 'webyx-reload-button-disabled',
            )
          );
          $page->end_controls_section();
        }
        public function webyx_fep_tmp_design_controls ( $page ) {
          $page->start_controls_section(
            'webyx_template_design',
            array(
              'label'     => esc_html__( 'TEMPLATE DESIGN', $this->slug ),
              'tab'       => 'webyx-fe',
              'condition' => array(
                'webyx_enable' => 'on',
              ),
            )
          );
          $page->add_control(
            'ctt',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Custom template', $this->slug ),
              'description'  => esc_html__( 'Enable custom template.', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control(
            'cttfn',
            array(
              'type'        => \Elementor\Controls_Manager::TEXT,
              'label'       => esc_html__( 'Template file name', $this->slug ),
              'description' => esc_html__( 'You can provide your own custom template, such as a modified version of the theme template. Put the template path here if you want to use your own. If left empty or if you write something that doesn\'t exist or it is wrong, the empty Webyx predefined page template will be used.', $this->slug ),
              'default'     => esc_html__( '', $this->slug ),
              'placeholder' => esc_html__( '\template-file-name.php', $this->slug ),
              'condition' => array(
                'ctt' => 'on',
              ),
            )
          );
          $page->end_controls_section();
        }
        public function webyx_fep_view_design_controls ( $page ) {
          $is_container_active = $this->webyx_fep_is_container_active();
          $el_root = $is_container_active ? 'Container' : 'Section';
          $page->start_controls_section(
            'webyx_website_architecture',
            array(
              'label'     => esc_html__( 'VIEW DESIGN', $this->slug ),
              'tab'       => 'webyx-fe',
              'condition' => array(
                'webyx_enable' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvtype',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label_block' => true,
              'label'       => esc_html__( 'View type', $this->slug ),
              'description' => esc_html__( '', $this->slug ),
              'default'     => 'full',
              'options'     => array(
                'full'   => esc_html__( 'full page',              $this->slug ),
                'header' => esc_html__( 'full width with header', $this->slug ),
                'custom' => esc_html__( 'custom',                 $this->slug ),
              ),
            )
          );
          $page->add_control(
            'post_wvtype_full_image',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => '<img class="webyx-fep-view-type-img-card" src="' . plugin_dir_url( __FILE__ ) . 'assets/img/webyx-view-fullpage.png">',
              'content_classes' => 'webyx-fep-view-type-img',
              'condition'       => array(
                'wvtype' => 'full',
              ),
            )
          );
          $page->add_control(
            'post_wvtype_full_description',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( 'FULL PAGE Webyx view is the classic full height and width choice.', $this->slug ),
              'content_classes' => 'elementor-control-field-description',
              'condition'       => array(
                'wvtype' => 'full',
              ),
            )
          );
          $page->add_control(
            'post_wvtype_header_blk__image',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => '<img class="webyx-fep-view-type-img-card" src="' . plugin_dir_url( __FILE__ ) . 'assets/img/webyx-view-fullwidth-header-spa.png">',
              'content_classes' => 'webyx-fep-view-type-img',
              'condition'       => array(
                'wvtype'   => 'header',
                'wvhdtype' => 'blk',
              ),
            )
          );
          $page->add_control(
            'post_wvtype_header_tmp_image',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => '<img class="webyx-fep-view-type-img-card" src="' . plugin_dir_url( __FILE__ ) . 'assets/img/webyx-view-fullwidth-header-mpa.png">',
              'content_classes' => 'webyx-fep-view-type-img',
              'condition'       => array(
                'wvtype'   => 'header',
                'wvhdtype' => 'tmp',
              ),
            )
          );
          $page->add_control(
            'post_wvtype_header_description',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( 'FULL WIDTH WITH HEADER Webyx view has partial height and full width with the addition of a top header.', $this->slug ),
              'content_classes' => 'elementor-control-field-description',
              'condition'       => array(
                'wvtype' => 'header',
              ),
            )
          );
          $page->add_control(
            'post_wvtype_custom_image',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => '<img class="webyx-fep-view-type-img-card" src="' . plugin_dir_url( __FILE__ ) . 'assets/img/webyx-view-custom.png">',
              'content_classes' => 'webyx-fep-view-type-img',
              'condition'       => array(
                'wvtype' => 'custom',
              ),
            )
          );
          $page->add_control(
            'post_wvtype_custom_description',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( 'CUSTOM Webyx view allows the user to manually shape its appearance to their liking.', $this->slug ),
              'content_classes' => 'elementor-control-field-description',
              'condition'       => array(
                'wvtype' => 'custom',
              ),
            )
          );
          $page->add_control(
            'hason',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Anchors', $this->slug ),
              'description'  => esc_html__( 'Enable anchors navigation (#). IMPORTANT: you should give different anchor names (white spaces will be replaced with "-" automatically) for each ' . $el_root . '. If an anchor name is not set, the ' . $el_root . ' name will be used instead.', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control(
            'wvhdtype',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label_block' => true,
              'label'       => esc_html__( 'Header type', $this->slug ),
              'description' => esc_html__( '', $this->slug ),
              'default'     => 'blk',
              'options'     => array(
                'blk' => esc_html__( 'header single page (Webyx menu)',    $this->slug ),
                'tmp' => esc_html__( 'header multi page (WordPress menu)', $this->slug ),
              ),
              'condition'       => array(
                'wvtype' => 'header',
              ),
            )
          );
          $page->add_control(
            'post_wvhdtype_blk_description',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( 'HEADER SINGLE PAGE (WEBYX MENU) option provides a header with an internal navigation menu in Single Page mode, automatically generated by the webyx based on the number of FRONT and SIDE Sections.', $this->slug ),
              'content_classes' => 'elementor-control-field-description',
              'condition'       => array(
                'wvtype'   => 'header',
                'wvhdtype' => 'blk',
              ),
            )
          );
          $page->add_control(
            'post_wvhdtype_tmp_description',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( 'HEADER MULTI PAGE (WORDPRESS MENU) option provides a header with a WordPress navigation menu in Multi Page mode, managed directly in "Display location" in Appearance/Menus/Menu structure/Menu settings.', $this->slug ),
              'content_classes' => 'elementor-control-field-description',
              'condition'       => array(
                'wvtype'   => 'header',
                'wvhdtype' => 'tmp',
              ),
            )
          );
          $page->add_control( 
            'wvhdmqb',
            array(
              'type'        => \Elementor\Controls_Manager::SLIDER,
              'label'       => esc_html__( 'Header view breakpoint', $this->slug ),
              'description' => esc_html__( 'Enter a value that defines the threshold for switching from desktop to mobile mode in pixels (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'size_units'  => array( 
                'px', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 760,
              ),
              'condition' => array(
                'wvtype' => 'header',
              ),
            )
          );
          $page->start_controls_tabs(
            'webyx_design_view_header_tabs',
            array(
              'condition' => array(
                'wvtype' => 'header',
              ),
            )
          );
          $page->start_controls_tab(
            'webyx_design_view_header_desktop_tab',
            array(
              'label' => esc_html__( 'Desktop', $this->slug ),
            )
          );
          $page->add_control( 
            'header_desktop_popover_toggle',
            array(
              'label'        => esc_html__( 'Header', $this->slug ),
              'type'         => \Elementor\Controls_Manager::POPOVER_TOGGLE,
              'label_off'    => esc_html__( 'Header', $this->slug ),
              'label_on'     => esc_html__( 'Header', $this->slug ),
              'return_value' => 'yes',
              'default'      => 'yes',
            )
          );
          $page->start_popover();
          $page->add_control(
            'wvdhdht',
            array(
              'type'        => \Elementor\Controls_Manager::SLIDER,
              'label'       => esc_html__( 'Header height', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a height (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'size_units'  => array( 
                'px', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 50,
              ),
            )
          );
          $page->add_control(
            'wvdhdbc',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Header background colour', $this->slug ),
              'description' => esc_html__( 'Choose header background colour.', $this->slug ),
              'default'     => '#ffffff',
            )
          );
          $page->end_popover();
          $page->add_control(
            'logo_desktop_popover_toggle',
            array(
              'label'        => esc_html__( 'Logo', $this->slug ),
              'type'         => \Elementor\Controls_Manager::POPOVER_TOGGLE,
              'label_off'    => esc_html__( 'Logo', $this->slug ),
              'label_on'     => esc_html__( 'Logo', $this->slug ),
              'return_value' => 'yes',
              'default'      => 'yes',
            )
          );
          $page->start_popover();
          $page->add_control(
            'wvdlogo',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Logo', $this->slug ),
              'description'  => esc_html__( 'Enable a logo inside the header.', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control(
            'wvdlogoimg', 
            array(
              'type' => \Elementor\Controls_Manager::MEDIA,
              'label'=> esc_html__( 'Logo image', $this->slug ),
              'description' => esc_html__( 'Choose logo image. IMPORTANT: the image will be the same for desktop and mobile.', $this->slug ),
              'dynamic' => array(
                'active'     => true,
                'categories' => array(
                  \Elementor\Modules\DynamicTags\Module::MEDIA_CATEGORY,
                ),
              ),
              'default' => array(
                'url' => '',
              ),
              'media_type' => 'image',
              'condition'  => array(
                'wvdlogo' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvdlogoht',
            array(
              'type'        => \Elementor\Controls_Manager::SLIDER,
              'label'       => esc_html__( 'Logo height', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a height (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'size_units'  => array( 
                'px', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 50,
              ),
              'condition'  => array(
                'wvdlogo' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvdlogowt',
            array(
              'type'        => \Elementor\Controls_Manager::SLIDER,
              'label'       => esc_html__( 'Logo width', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a width (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'size_units'  => array( 
                'px', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 50,
              ),
              'condition'  => array(
                'wvdlogo' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvdlogohzpos',
            array(
              'type'    => \Elementor\Controls_Manager::CHOOSE,
              'label'   => esc_html__( 'Logo horizontal position', $this->slug ),
              'description'  => esc_html__( 'Choose logo horizontal position.', $this->slug ),
              'options' => array(
                'left'  => array(
                  'title' => esc_html__( 'Left', $this->slug ),
                  'icon'  => 'eicon-h-align-left',
                ),
                'right' => array(
                  'title' => esc_html__( 'Right', $this->slug ),
                  'icon'  => 'eicon-h-align-right',
                ),
              ),
              'default' => 'left',
              'toggle'  => true,
              'condition'  => array(
                'wvdlogo' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvdlogohzposv',
            array(
              'type'        => \Elementor\Controls_Manager::SLIDER,
              'label'       => esc_html__( 'Logo horizontal position', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a left/right position (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'size_units'  => array( 
                'px', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 0,
              ),
              'condition'  => array(
                'wvdlogo' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvdlogovtpos',
            array(
              'type'    => \Elementor\Controls_Manager::CHOOSE,
              'label'   => esc_html__( 'Logo vertical position', $this->slug ),
              'description'  => esc_html__( 'Choose logo vertical position.', $this->slug ),
              'options' => array(
                'top' => array(
                  'title' => esc_html__( 'Top', $this->slug ),
                  'icon'  => 'eicon-v-align-top',
                ),
                'bottom' => array(
                  'title' => esc_html__( 'Bottom', $this->slug ),
                  'icon'  => 'eicon-v-align-bottom',
                ),
              ),
              'default'   => 'top',
              'toggle'  => true,
              'condition'  => array(
                'wvdlogo' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvdlogovtposv',
            array(
              'type'        => \Elementor\Controls_Manager::SLIDER,
              'label'       => esc_html__( 'Logo vertical position', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a top/bottom position (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'size_units'  => array( 
                'px', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 0,
              ),
              'condition'  => array(
                'wvdlogo' => 'on',
              ),
            )
          );
          $page->end_popover();
          $page->add_control(
            'menu_desktop_popover_toggle',
            array(
              'label'        => esc_html__( 'Menu', $this->slug ),
              'type'         => \Elementor\Controls_Manager::POPOVER_TOGGLE,
              'label_off'    => esc_html__( 'Menu', $this->slug ),
              'label_on'     => esc_html__( 'Menu', $this->slug ),
              'return_value' => 'yes',
              'default'      => 'yes',
            )
          );
          $page->start_popover();
          $page->add_control(
            'wvdnav',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Menu', $this->slug ),
              'description'  => esc_html__( 'Enable a menu inside the header.', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control(
            'wvdnavposhz',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Horizontal position', $this->slug ),
              'description' => esc_html__( 'Choose horizontal position for the menu.', $this->slug ),
              'default'     => 'center',
              'options'     => array(
                'left'   => esc_html__( 'left',   $this->slug ),
                'center' => esc_html__( 'center', $this->slug ),
                'right'  => esc_html__( 'right',  $this->slug ),
              ),
              'condition' => array(
                'wvdnav' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvdnavmaren',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Margins', $this->slug ),
              'description'  => esc_html__( 'Enable menu margins.', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control( 
            'wvdnavmar',
            array(
              'type'        => \Elementor\Controls_Manager::DIMENSIONS,
              'label'       => esc_html__( 'Margin', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a margin to the menu (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'size_units'  => array( 
                'px', 
              ),
              'default'    => array(
                'top'      => 0,
                'right'    => 0,
                'bottom'   => 0,
                'left'     => 0,
                'unit'     => 'px',
                'isLinked' => '',
              ),
              'condition' => array(
                'wvdnav'      => 'on',
                'wvdnavmaren' => 'on',
              ),
            )
          );
          $page->add_control( 
            'wvdnavitemdropcontdim',
            array(
              'type'        => \Elementor\Controls_Manager::SLIDER,
              'label'       => esc_html__( 'Dropdown item width', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a dropdown container item menu width (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'size_units'  => array( 
                'px', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 250,
              ),
              'condition' => array(
                'wvdnav' => 'on',
              ),
            )
          );
          $page->add_control( 
            'wvdnavitemdim',
            array(
              'type'        => \Elementor\Controls_Manager::SLIDER,
              'label'       => esc_html__( 'Item height', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a item height (range from 30 to 75 pixels with a step of 1).', $this->slug ),
              'size_units'  => array( 
                'px', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 30,
                  'max'  => 75,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 50,
              ),
              'condition' => array(
                'wvdnav' => 'on',
              ),
            )
          );
          $page->add_control( 
            'wvdnavitemfontdim',
            array(
              'type'        => \Elementor\Controls_Manager::SLIDER,
              'label'       => esc_html__( 'Item font size', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a item font size (range from 12 to 32 pixels with a step of 1).', $this->slug ),
              'size_units'  => array( 
                'px', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 12,
                  'max'  => 32,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 16,
              ),
              'condition' => array(
                'wvdnav' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvdnavitembc',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Item background colour', $this->slug ),
              'description' => esc_html__( 'Choose item menu background colour.', $this->slug ),
              'default'     => '#ffffff',
              'condition'   => array(
                'wvdnav' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvdnavitembclight',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Item background colour light', $this->slug ),
              'description' => esc_html__( 'Choose item menu background colour light.', $this->slug ),
              'default'     => '#ffffff',
              'condition'   => array(
                'wvdnav' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvdnavitemtxtc',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Item colour', $this->slug ),
              'description' => esc_html__( 'Choose item menu colour.', $this->slug ),
              'default'     => '#000000',
              'condition'   => array(
                'wvdnav' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvdnavitemtxtclight',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Item colour light', $this->slug ),
              'description' => esc_html__( 'Choose item menu colour light.', $this->slug ),
              'default'     => '#000000',
              'condition'   => array(
                'wvdnav' => 'on',
              ),
            )
          );
          $page->end_popover();
          $page->end_controls_tab();
          $page->start_controls_tab(
            'webyx_design_view_header_mobile_tab',
            array(
              'label' => esc_html__( 'Mobile', $this->slug ),
            )
          );
          $page->add_control(
            'header_mobile_popover_toggle',
            array(
              'label'        => esc_html__( 'Header', $this->slug ),
              'type'         => \Elementor\Controls_Manager::POPOVER_TOGGLE,
              'label_off'    => esc_html__( 'Header', $this->slug ),
              'label_on'     => esc_html__( 'Custom', $this->slug ),
              'return_value' => 'yes',
              'default'      => 'yes',
            )
          );
          $page->start_popover();
          $page->add_control(
            'wvmhdht',
            array(
              'type'        => \Elementor\Controls_Manager::SLIDER,
              'label'       => esc_html__( 'Header height', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a height (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'size_units'  => array( 
                'px', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 50,
              ),
            )
          );
          $page->add_control(
            'wvmhdbc',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Header background colour', $this->slug ),
              'description' => esc_html__( 'Choose header background colour.', $this->slug ),
              'default'     => '#ffffff',
            )
          );
          $page->end_popover();
          $page->add_control(
            'logo_mobile_popover_toggle',
            array(
              'label'        => esc_html__( 'Logo', $this->slug ),
              'type'         => \Elementor\Controls_Manager::POPOVER_TOGGLE,
              'label_off'    => esc_html__( 'Logo', $this->slug ),
              'label_on'     => esc_html__( 'Logo', $this->slug ),
              'return_value' => 'yes',
              'default'      => 'yes',
            )
          );
          $page->start_popover();
          $page->add_control(
            'wvmlogo',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Logo', $this->slug ),
              'description'  => esc_html__( 'Enable a logo inside the header.', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control(
            'post_wvtype_logo_mob_image',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( 'IMPORTANT: the image will be the same as you selected on the desktop.', $this->slug ),
              'content_classes' => 'elementor-control-field-description',
              'condition'       => array(
                'wvmlogo' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvmlogoht',
            array(
              'type'        => \Elementor\Controls_Manager::SLIDER,
              'label'       => esc_html__( 'Logo height', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a height (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'size_units'  => array( 
                'px', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 50,
              ),
              'condition'  => array(
                'wvmlogo' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvmlogowt',
            array(
              'type'        => \Elementor\Controls_Manager::SLIDER,
              'label'       => esc_html__( 'Logo width', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a width (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'size_units'  => array( 
                'px', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 50,
              ),
              'condition'  => array(
                'wvmlogo' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvmlogohzpos',
            array(
              'type'    => \Elementor\Controls_Manager::CHOOSE,
              'label'   => esc_html__( 'Logo horizontal position', $this->slug ),
              'description'  => esc_html__( 'Choose logo horizontal position.', $this->slug ),
              'options' => array(
                'left'  => array(
                  'title' => esc_html__( 'Left', $this->slug ),
                  'icon'  => 'eicon-h-align-left',
                ),
                'right' => array(
                  'title' => esc_html__( 'Right', $this->slug ),
                  'icon'  => 'eicon-h-align-right',
                ),
              ),
              'default' => 'right',
              'toggle'  => true,
              'condition'  => array(
                'wvmlogo' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvmlogohzposv',
            array(
              'type'        => \Elementor\Controls_Manager::SLIDER,
              'label'       => esc_html__( 'Logo horizontal position', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a left/right position (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'size_units'  => array( 
                'px', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 0,
              ),
              'condition'  => array(
                'wvmlogo' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvmlogovtpos',
            array(
              'type'    => \Elementor\Controls_Manager::CHOOSE,
              'label'   => esc_html__( 'Logo vertical position', $this->slug ),
              'description'  => esc_html__( 'Choose logo vertical position.', $this->slug ),
              'options' => array(
                'top' => array(
                  'title' => esc_html__( 'Top', $this->slug ),
                  'icon'  => 'eicon-v-align-top',
                ),
                'bottom' => array(
                  'title' => esc_html__( 'Bottom', $this->slug ),
                  'icon'  => 'eicon-v-align-bottom',
                ),
              ),
              'default'   => 'top',
              'toggle'  => true,
              'condition'  => array(
                'wvmlogo' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvmlogovtposv',
            array(
              'type'        => \Elementor\Controls_Manager::SLIDER,
              'label'       => esc_html__( 'Logo vertical position', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a top/bottom position (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'size_units'  => array( 
                'px', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 0,
              ),
              'condition'  => array(
                'wvmlogo' => 'on',
              ),
            )
          );
          $page->end_popover();
          $page->add_control(
            'menu_mobile_popover_toggle',
            array(
              'label'        => esc_html__( 'Menu', $this->slug ),
              'type'         => \Elementor\Controls_Manager::POPOVER_TOGGLE,
              'label_off'    => esc_html__( 'Menu', $this->slug ),
              'label_on'     => esc_html__( 'Menu', $this->slug ),
              'return_value' => 'yes',
              'default'      => 'yes',
            )
          );
          $page->start_popover();
          $page->add_control(
            'wvmnav',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Menu', $this->slug ),
              'description'  => esc_html__( 'Enable a menu inside the header.', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control(
            'wvmnavposhz',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Horizontal position', $this->slug ),
              'description' => esc_html__( 'Choose horizontal position for the menu.', $this->slug ),
              'default'     => 'left',
              'options'     => array(
                'left'   => esc_html__( 'left',   $this->slug ),
                'center' => esc_html__( 'center', $this->slug ),
                'right'  => esc_html__( 'right',  $this->slug ),
              ),
              'condition' => array(
                'wvmnav' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvmnavbrgbc',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Burger icon background colour', $this->slug ),
              'description' => esc_html__( 'Choose burger menu icon background colour.', $this->slug ),
              'default'     => '#ffffff',
              'condition'   => array(
                'wvmnav' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvmnavbrgc',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Burger icon bars colour', $this->slug ),
              'description' => esc_html__( 'Choose burger menu icon bars colour.', $this->slug ),
              'default'     => '#000000',
              'condition'   => array(
                'wvmnav' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvmnavbrgboc',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Overlay background colour', $this->slug ),
              'description' => esc_html__( 'Choose overlay background colour.', $this->slug ),
              'default'     => '#ffffff',
              'condition'   => array(
                'wvmnav' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvmnavmaren',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Margins', $this->slug ),
              'description'  => esc_html__( 'Enable menu margins.', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control( 
            'wvmnavmar',
            array(
              'type'        => \Elementor\Controls_Manager::DIMENSIONS,
              'label'       => esc_html__( 'Margin', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a margin to the menu (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'size_units'  => array( 
                'px', 
              ),
              'default'    => array(
                'top'      => 0,
                'right'    => 0,
                'bottom'   => 0,
                'left'     => 0,
                'unit'     => 'px',
                'isLinked' => '',
              ),
              'condition' => array(
                'wvmnav'      => 'on',
                'wvmnavmaren' => 'on',
              ),
            )
          );
          $page->add_control( 
            'wvmnavitemdim',
            array(
              'type'        => \Elementor\Controls_Manager::SLIDER,
              'label'       => esc_html__( 'Item height', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a item height (range from 30 to 75 pixels with a step of 1).', $this->slug ),
              'size_units'  => array( 
                'px', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 30,
                  'max'  => 75,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 50,
              ),
              'condition' => array(
                'wvmnav' => 'on',
              ),
            )
          );
          $page->add_control( 
            'wvmnavitemfontdim',
            array(
              'type'        => \Elementor\Controls_Manager::SLIDER,
              'label'       => esc_html__( 'Item font size', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a item font size (range from 12 to 32 pixels with a step of 1).', $this->slug ),
              'size_units'  => array( 
                'px', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 12,
                  'max'  => 32,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 16,
              ),
              'condition' => array(
                'wvmnav' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvmnavitembc',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Item background colour', $this->slug ),
              'description' => esc_html__( 'Choose item menu background colour.', $this->slug ),
              'default'     => '#ffffff',
              'condition'   => array(
                'wvmnav' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvmnavitembclight',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Item background colour light', $this->slug ),
              'description' => esc_html__( 'Choose item menu background colour light.', $this->slug ),
              'default'     => '#ffffff',
              'condition'   => array(
                'wvmnav' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvmnavitemtxtc',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Item colour', $this->slug ),
              'description' => esc_html__( 'Choose item menu colour.', $this->slug ),
              'default'     => '#000000',
              'condition'   => array(
                'wvmnav' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvmnavitemtxtclight',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Item colour light', $this->slug ),
              'description' => esc_html__( 'Choose item menu colour light.', $this->slug ),
              'default'     => '#000000',
              'condition'   => array(
                'wvmnav' => 'on',
              ),
            )
          );
          $page->end_popover();
          $page->end_controls_tab();
          $page->end_controls_tabs();
          $page->add_control( 
            'wvscrlbar',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Partialization hide scrollbar', $this->slug ),
              'description'  => esc_html__( 'Hides browser\'s default scrollbar when it should be present in the Webyx view.', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition' => array(
                'wvtype' => 'custom',
              ),
            )
          );
          $page->add_control(
            'wvmqb',
            array(
              'type'        => \Elementor\Controls_Manager::SLIDER,
              'label'       => esc_html__( 'Partialization view breakpoint', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a breakpoint in pixels (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'size_units'  => array( 
                'px', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 760,
              ),
              'condition' => array(
                'wvtype' => 'custom',
              ),
            )
          );
          $page->start_controls_tabs(
            'webyx_design_view_tabs',
            array(
              'condition' => array(
                'wvtype' => 'custom',
              ),
            )
          );
          $page->start_controls_tab(
            'webyx_design_view_desktop_tab',
            array(
              'label' => esc_html__( 'Desktop', $this->slug ),
            )
          );
          $page->add_control(
            'wvdpos',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Partialization view position', $this->slug ),
              'description' => esc_html__( 'IMPORTANT: if you decide NOT to use \'px\' as unit of measure, remember to put the Webyx view inside a container with fixed measures. If you are using a custom template the Webyx view will take the width of your theme\'s margins.', $this->slug ),
              'default'     => 'static',
              'options'     => array(
                'static'   => esc_html__( 'static',   $this->slug ),
                'relative' => esc_html__( 'relative', $this->slug ),
                'absolute' => esc_html__( 'absolute', $this->slug ),
              ),
            )
          );
          $page->add_control(
            'wvdwt',
            array(
              'type'        => \Elementor\Controls_Manager::SLIDER,
              'label'       => esc_html__( 'Partialization view width', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a width (px, %, vw).', $this->slug ),
              'size_units'  => array( 
                'px', 
                '%', 
                'vw', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
                '%' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
                'vw' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => '%',
                'size' => 100,
              ),
            )
          );
          $page->add_control(
            'wvdht',
            array(
              'type'        => \Elementor\Controls_Manager::SLIDER,
              'label'       => esc_html__( 'Partialization view height', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a height (px, %, vh).', $this->slug ),
              'size_units'  => array( 
                'px', 
                '%', 
                'vh',
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
                '%' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
                'vh' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 800,
              ),
            )
          );
          $page->add_control(
            'wvdstmaren',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Partialization view margin', $this->slug ),
              'description' => esc_html__( 'Enable partialization view margin.', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition' => array(
                'epart' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvdstmar',
            array(
              'type'       => \Elementor\Controls_Manager::DIMENSIONS,
              'label'      => esc_html__( 'Partialization view margin values', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a margin (px, %).', $this->slug ),
              'size_units' => array( 
                'px', 
                '%',
              ),
              'default' => array(
                'top'      => 0,
                'right'    => 0,
                'bottom'   => 0,
                'left'     => 0,
                'unit'     => 'px',
                'isLinked' => '',
              ),
              'condition' => array(
                'wvdstmaren' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvdrelposx',
            array(
              'type'    => \Elementor\Controls_Manager::CHOOSE,
              'label'   => esc_html__( 'Partialization view horizontal position', $this->slug ),
              'description' => esc_html__( 'Enable horizontal partialization view position (left, right).', $this->slug ),
              'options' => array(
                'left' => array(
                  'title' => esc_html__( 'Left', $this->slug ),
                  'icon' => 'eicon-h-align-left',
                ),
                'right' => array(
                  'title' => esc_html__( 'Right', $this->slug ),
                  'icon' => 'eicon-h-align-right',
                ),
              ),
              'default'   => 'left',
              'toggle'    => true,
              'condition' => array(
                'wvdpos' => 'relative',
              ),
            )
          );
          $page->add_control(
            'wvdrelposxval',
            array(
              'type'       => \Elementor\Controls_Manager::SLIDER,
              'label'      => esc_html__( 'Partialization view position value', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a horizontal position (px, %, vw).', $this->slug ),
              'size_units' => array( 
                'px', 
                '%', 
                'vw',
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
                '%' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
                'vw' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 0,
              ),
              'condition' => array(
                'wvdpos' => 'relative',
              ),
            )
          );
          $page->add_control(
            'wvdrelposy',
            array(
              'type'    => \Elementor\Controls_Manager::CHOOSE,
              'label'   => esc_html__( 'Partialization view vertical position', $this->slug ),
              'description' => esc_html__( 'Enable vertical partialization view position (top, bottom).', $this->slug ),
              'options' => array(
                'top' => array(
                  'title' => esc_html__( 'Top', $this->slug ),
                  'icon'  => 'eicon-v-align-top',
                ),
                'bottom' => array(
                  'title' => esc_html__( 'Bottom', $this->slug ),
                  'icon'  => 'eicon-v-align-bottom',
                ),
              ),
              'default'   => 'top',
              'toggle'    => true,
              'condition' => array(
                'wvdpos' => 'relative',
              ),
            )
          );
          $page->add_control(
            'wvdrelposyval',
            array(
              'type'       => \Elementor\Controls_Manager::SLIDER,
              'label'      => esc_html__( 'Partialization view position value', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a vertical position (px, %, vh).', $this->slug ),
              'size_units' => array( 
                'px', 
                '%', 
                'vh',
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
                '%' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
                'vh' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 0,
              ),
              'condition' => array(
                'wvdpos' => 'relative',
              ),
            )
          );
          $page->add_control(
            'wvdabsposx',
            array(
              'type'    => \Elementor\Controls_Manager::CHOOSE,
              'label'   => esc_html__( 'Partialization view horizontal position', $this->slug ),
              'description' => esc_html__( 'Enable horizontal partialization view position (left, right).', $this->slug ),
              'options' => array(
                'left' => array(
                  'title' => esc_html__( 'Left', $this->slug ),
                  'icon'  => 'eicon-h-align-left',
                ),
                'right' => array(
                  'title' => esc_html__( 'Right', $this->slug ),
                  'icon'  => 'eicon-h-align-right',
                ),
              ),
              'default'   => 'left',
              'toggle'    => true,
              'condition' => array(
                'wvdpos' => 'absolute',
              ),
            )
          );
          $page->add_control(
            'wvdabsposxval',
            array(
              'type'       => \Elementor\Controls_Manager::SLIDER,
              'label'      => esc_html__( 'Partialization view position value', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a horizontal position (px, %, vw).', $this->slug ),
              'size_units' => array( 
                'px', 
                '%', 
                'vw',
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
                '%' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
                'vw' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 0,
              ),
              'condition' => array(
                'wvdpos' => 'absolute',
              ),
            )
          );
          $page->add_control(
            'wvdabsposy',
            array(
              'type'    => \Elementor\Controls_Manager::CHOOSE,
              'label'   => esc_html__( 'Partialization view vertical position', $this->slug ),
              'description' => esc_html__( 'Enable vertical partialization view position (top, bottom).', $this->slug ),
              'options' => array(
                'top' => array(
                  'title' => esc_html__( 'Top', $this->slug ),
                  'icon' => 'eicon-v-align-top',
                ),
                'bottom' => array(
                  'title' => esc_html__( 'Bottom', $this->slug ),
                  'icon'  => 'eicon-v-align-bottom',
                ),
              ),
              'default'   => 'top',
              'toggle'    => true,
              'condition' => array(
                'wvdpos' => 'absolute',
              ),
            )
          );
          $page->add_control(
            'wvdabsposyval',
            array(
              'type'       => \Elementor\Controls_Manager::SLIDER,
              'label'      => esc_html__( 'Partialization view position value', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a vertical position (px, %, vh).', $this->slug ),
              'size_units' => array( 
                'px', 
                '%', 
                'vh', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
                '%' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
                'vh' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 0,
              ),
              'condition' => array(
                'wvdpos' => 'absolute',
              ),
            )
          );
          $page->end_controls_tab();
          $page->start_controls_tab(
            'webyx_design_view_mobile_tab',
            array(
              'label' => esc_html__( 'Mobile', $this->slug ),
            )
          );
          $page->add_control(
            'wvmpos',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Partialization view position', $this->slug ),
              'description' => esc_html__( 'IMPORTANT: if you decide NOT to use \'px\' as unit of measure, remember to put the Webyx view inside a container with fixed measures. If you are using a custom template the Webyx view will take the width of your theme\'s margins.', $this->slug ),
              'default'     => 'static',
              'options'     => array(
                'static'   => esc_html__( 'static',   $this->slug ),
                'relative' => esc_html__( 'relative', $this->slug ),
                'absolute' => esc_html__( 'absolute', $this->slug ),
              ),
            )
          );
          $page->add_control(
            'wvmwt',
            array(
              'type'        => \Elementor\Controls_Manager::SLIDER,
              'label'       => esc_html__( 'Partialization view width', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a width (px, %, vw).', $this->slug ),
              'size_units' => array( 
                'px', 
                '%', 
                'vw', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
                '%' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
                'vw' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => '%',
                'size' => 100,
              ),
            )
          );
          $page->add_control(
            'wvmht',
            array(
              'type'        => \Elementor\Controls_Manager::SLIDER,
              'label'       => esc_html__( 'Partialization view height', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a height (px, %, vh).', $this->slug ),
              'size_units' => array( 
                'px', 
                '%', 
                'vh', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
                '%' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
                'vh' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 800,
              ),
            )
          );
          $page->add_control(
            'wvmstmaren',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Partialization view margin', $this->slug ),
              'description'  => esc_html__( 'Enable partialization view margin.', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition' => array(
                'epart' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvmstmar',
            array(
              'type'       => \Elementor\Controls_Manager::DIMENSIONS,
              'label'      => esc_html__( 'Partialization view margin values', $this->slug ),
              'description'  => esc_html__( 'Insert a value to apply a margin (px, %).', $this->slug ),
              'size_units' => array( 
                'px', 
                '%', 
              ),
              'default'    => array(
                'top'      => 0,
                'right'    => 0,
                'bottom'   => 0,
                'left'     => 0,
                'unit'     => 'px',
                'isLinked' => '',
              ),
              'condition' => array(
                'wvmstmaren' => 'on',
              ),
            )
          );
          $page->add_control(
            'wvmrelposx',
            array(
              'type'    => \Elementor\Controls_Manager::CHOOSE,
              'label'   => esc_html__( 'Partialization view horizontal position', $this->slug ),
              'description'  => esc_html__( 'Enable horizontal partialization view position (left, right).', $this->slug ),
              'options' => array(
                'left'  => array(
                  'title' => esc_html__( 'Left', $this->slug ),
                  'icon'  => 'eicon-h-align-left',
                ),
                'right' => array(
                  'title' => esc_html__( 'Right', $this->slug ),
                  'icon'  => 'eicon-h-align-right',
                ),
              ),
              'default'   => 'left',
              'toggle'    => true,
              'condition' => array(
                'wvmpos' => 'relative',
              ),
            )
          );
          $page->add_control(
            'wvmrelposxval',
            array(
              'type'       => \Elementor\Controls_Manager::SLIDER,
              'label'      => esc_html__( 'Partialization view position value', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a horizontal position (px, %, vw).', $this->slug ),
              'size_units' => array( 
                'px', 
                '%', 
                'vw', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
                '%' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
                'vw' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 0,
              ),
              'condition' => array(
                'wvmpos' => 'relative',
              ),
            )
          );
          $page->add_control(
            'wvmrelposy',
            array(
              'type'    => \Elementor\Controls_Manager::CHOOSE,
              'label'   => esc_html__( 'Partialization view vertical position', $this->slug ),
              'description'  => esc_html__( 'Enable vertical partialization view position (top, bottom).', $this->slug ),
              'options' => array(
                'top' => array(
                  'title' => esc_html__( 'Top', $this->slug ),
                  'icon'  => 'eicon-v-align-top',
                ),
                'bottom' => array(
                  'title' => esc_html__( 'Bottom', $this->slug ),
                  'icon'  => 'eicon-v-align-bottom',
                ),
              ),
              'default'   => 'top',
              'toggle'    => true,
              'condition' => array(
                'wvmpos' => 'relative',
              ),
            )
          );
          $page->add_control(
            'wvmrelposyval',
            array(
              'type'       => \Elementor\Controls_Manager::SLIDER,
              'label'      => esc_html__( 'Partialization view position value', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a vertical position (px, %, vh).', $this->slug ),
              'size_units' => array( 
                'px',
                '%', 
                'vh', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
                '%' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
                'vh' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 0,
              ),
              'condition' => array(
                'wvmpos' => 'relative',
              ),
            )
          );
          $page->add_control(
            'wvmabsposx',
            array(
              'type'    => \Elementor\Controls_Manager::CHOOSE,
              'label'   => esc_html__( 'Partialization view horizontal position', $this->slug ),
              'description'  => esc_html__( 'Enable horizontal partialization view position (left, right).', $this->slug ),
              'options' => array(
                'left' => array(
                  'title' => esc_html__( 'Left', $this->slug ),
                  'icon'  => 'eicon-h-align-left',
                ),
                'right' => array(
                  'title' => esc_html__( 'Right', $this->slug ),
                  'icon'  => 'eicon-h-align-right',
                ),
              ),
              'default'   => 'left',
              'toggle'    => true,
              'condition' => array(
                'wvmpos' => 'absolute',
              ),
            )
          );
          $page->add_control(
            'wvmabsposxval',
            array(
              'type'       => \Elementor\Controls_Manager::SLIDER,
              'label'      => esc_html__( 'Partialization view position value', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a horizontal position (px, %, vw).', $this->slug ),
              'size_units' => array( 
                'px', 
                '%', 
                'vw', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
                '%' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
                'vw' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 0,
              ),
              'condition' => array(
                'wvmpos' => 'absolute',
              ),
            )
          );
          $page->add_control(
            'wvmabsposy',
            array(
              'type'    => \Elementor\Controls_Manager::CHOOSE,
              'label'   => esc_html__( 'Partialization view vertical position', $this->slug ),
              'description'  => esc_html__( 'Enable vertical partialization view position (top, bottom).', $this->slug ),
              'options' => array(
                'top' => array(
                  'title' => esc_html__( 'Top', $this->slug ),
                  'icon'  => 'eicon-v-align-top',
                ),
                'bottom' => array(
                  'title' => esc_html__( 'Bottom', $this->slug ),
                  'icon'  => 'eicon-v-align-bottom',
                ),
              ),
              'default'   => 'top',
              'toggle'    => true,
              'condition' => array(
                'wvmpos' => 'absolute',
              ),
            )
          );
          $page->add_control(
            'wvmabsposyval',
            array(
              'type'       => \Elementor\Controls_Manager::SLIDER,
              'label'      => esc_html__( 'Partialization view position value', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a vertical position (px, %, vh).', $this->slug ),
              'size_units' => array( 
                'px', 
                '%', 
                'vh', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
                '%' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
                'vh' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 0,
              ),
              'condition' => array(
                'wvmpos' => 'absolute',
              ),
            )
          );
          $page->end_controls_tab();
          $page->end_controls_tabs();
          $page->add_control(
            'wvid',
            array(
              'type'        => \Elementor\Controls_Manager::TEXT,
              'label'       => esc_html__( 'View CSS ID', $this->slug ),
              'description' => esc_html__( 'Add your custom ID without the hash (#) symbol.', $this->slug ),
              'default'     => esc_html__( '', $this->slug ),
              'placeholder' => esc_html__( '', $this->slug ),
              'separator'   => 'before',
            )
          );
          $page->add_control(
            'wvcn',
            array(
              'type'        => \Elementor\Controls_Manager::TEXT,
              'label'       => esc_html__( 'View CSS class(es)', $this->slug ),
              'description' => esc_html__( 'Add additional CSS class(es). Separate multiple classes with spaces.', $this->slug ),
              'default'     => esc_html__( '', $this->slug ),
              'placeholder' => esc_html__( '', $this->slug ),
            )
          );
          $page->end_controls_section();
        }
        public function webyx_fep_nav_design_controls ( $page ) {
          $page->start_controls_section(
            'webyx_slide_design',
            array(
              'label'     => esc_html__( 'NAVIGATION DESIGN', $this->slug ),
              'tab'       => 'webyx-fe',
              'condition' => array(
                'webyx_enable' => 'on',
              ),
            )
          );
          $page->start_controls_tabs(
            'webyx_design_card_tabs'
          );
          $page->start_controls_tab(
            'webyx_design_vertical_card_tab',
            array(
              'label' => esc_html__( 'Vertical', $this->slug ),
            )
          );
          $page->add_control(
            'cv',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Card style', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'description'  => esc_html__( 'Enable vertical Card style for every Section. If not Panel style will be enabled.', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control(
            'cvantp',
            array(
              'type'    => \Elementor\Controls_Manager::SELECT,
              'label'   => esc_html__( 'Animation type', $this->slug ),
              'description'  => esc_html__( 'Choose wich type of animation to apply to the Sections\' vertical movements.', $this->slug ),
              'default' => 'slide',
              'options' => array(
                'toggle' => esc_html__( 'toggle', $this->slug ),
                'slide'  => esc_html__( 'slide',  $this->slug ),
                'fade'   => esc_html__( 'fade',   $this->slug ),
              ),
              'condition' => array(
                'cv' => 'on',
              ),
            )
          );
          $page->add_control(
            'pv',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Parallax', $this->slug ),
              'description'  => esc_html__( 'Creates a vertical parallax effect for Sections when scrolling and sliding.', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition'    => array(
                'cv'     => 'on',
                'cvantp' => 'slide',
              ),
            )
          ); 
          $page->add_control(
            'pvo',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Parallax percentage', $this->slug ),
              'description' => esc_html__( 'Percentage of the vertical parallax effect.', $this->slug ),
              'default'     => '40',
              'options'     => array(
                '90' => esc_html__( '10%', $this->slug ),
                '80' => esc_html__( '20%', $this->slug ),
                '70' => esc_html__( '30%', $this->slug ),
                '60' => esc_html__( '40%', $this->slug ),
                '50' => esc_html__( '50%', $this->slug ),
                '40' => esc_html__( '60%', $this->slug ),
                '30' => esc_html__( '70%', $this->slug ),
                '20' => esc_html__( '80%', $this->slug ),
                '10' => esc_html__( '90%', $this->slug ),
              ),
              'condition' => array(
                'cv'     => 'on',
                'cvantp' => 'slide',
                'pv'     => 'on',
              ),
            )
          );
          $page->end_controls_tab();
          $page->start_controls_tab(
            'webyx_design_horizontal_card_tab',
            array(
              'label' => esc_html__( 'Horizontal', $this->slug ),
            )
          );
          $page->add_control(
            'ch',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Card style', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'description'  => esc_html__( 'Enable horizontal Card style for every Section. If not Panel style will be enabled.', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control(
            'chantp',
            array(
              'type'    => \Elementor\Controls_Manager::SELECT,
              'label'   => esc_html__( 'Animation type', $this->slug ),
              'description'  => esc_html__( 'Choose wich type of animation to apply to the Sections\' horizontal movements.', $this->slug ),
              'default' => 'slide',
              'options' => array(
                'toggle' => esc_html__( 'toggle', $this->slug ),
                'slide'  => esc_html__( 'slide',  $this->slug ),
                'fade'   => esc_html__( 'fade',   $this->slug ),
              ),
              'condition' => array(
                'ch' => 'on',
              ),
            )
          );
          $page->add_control(
            'ph',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Parallax', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'description'  => esc_html__( 'Creates a horizontal parallax effect for Sections when scrolling and sliding.', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition'    => array(
                'ch'     => 'on',
                'chantp' => 'slide',
              ),
            )
          );
          $page->add_control(
            'pho',
            array(
              'type'         => \Elementor\Controls_Manager::SELECT,
              'label'        => esc_html__( 'Parallax percentage', $this->slug ),
              'description'  => esc_html__( 'Percentage of the horizontal parallax effect.', $this->slug ),
              'default'      => '40',
              'options'      => array(
                '90' => esc_html__( '10%', $this->slug ),
                '80' => esc_html__( '20%', $this->slug ),
                '70' => esc_html__( '30%', $this->slug ),
                '60' => esc_html__( '40%', $this->slug ),
                '50' => esc_html__( '50%', $this->slug ),
                '40' => esc_html__( '60%', $this->slug ),
                '30' => esc_html__( '70%', $this->slug ),
                '20' => esc_html__( '80%', $this->slug ),
                '10' => esc_html__( '90%', $this->slug ),
              ),
              'condition' => array(
                'ch'     => 'on',
                'chantp' => 'slide',
                'ph'     => 'on',
              ),
            )
          );
          $page->end_controls_tab();
          $page->end_controls_tabs();
          $page->add_control(
            'vl',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Continuous vertical', $this->slug ),
              'description'  => esc_html__( 'Enable the direct vertical passage from the first to the last Section and vice versa.', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'separator'    => 'before',
            )
          );
          $page->add_control(
            'scry',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Vertical lock', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'description'  => esc_html__( 'Vertical movement is no longer possible on every Section.', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'separator'    => 'before',
            )
          );
          $page->add_control(
            'nosi',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Normal scrolling website', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'description'  => esc_html__( 'Sections are all positioned vertically and consecutively.', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'separator'    => 'before',
            )
          );
          $page->add_control(
            'nositr',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Normal scrolling threshold', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'description'  => esc_html__( 'Define limits of the browser width/height to enable the normal scrolling website.', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition'    => array(
                'nosi' => 'on',
              ),
            )
          );
          $page->add_control(
            'nosiw',
            array(
              'type'    => \Elementor\Controls_Manager::NUMBER,
              'label'   => esc_html__( 'Width threshold', $this->slug ),
              'min'     => 0,
              'max'     => 5000,
              'step'    => 1,
              'default' => 900,
              'condition' => array(
                'nosi'   => 'on',
                'nositr' => 'on',
              ),
            )
          );
          $page->add_control(
            'nosih',
            array(
              'type'    => \Elementor\Controls_Manager::NUMBER,
              'label'   => esc_html__( 'Height threshold', $this->slug ),
              'min'     => 0,
              'max'     => 5000,
              'step'    => 1,
              'default' => 900,
              'condition' => array(
                'nosi'   => 'on',
                'nositr' => 'on',
              ),
            )
          );
          $page->add_control(
            'nosiafh',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Slide height autofill', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'description'  => esc_html__( 'Enable viewport minimum size for the Section height. IMPORTANT: you must enter some content to be able to see the Section.', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition' => array(
                'nosi' => 'on',
              ),
            )
          );
          $page->add_control(
            'nosian',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Animation', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'description'  => esc_html__( 'Enables animation management for the normal scrolling website.', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition' => array(
                'nosi' => 'on',
              ),
            )
          );
          $page->add_control(
            'vtsnosi',
            array(
              'type'    => \Elementor\Controls_Manager::NUMBER,
              'label'   => esc_html__( 'Scrolling vertical speed', $this->slug ),
              'description'  => esc_html__( 'Set the vertical scrolling animation speed from a pre-estabilished set of values (range from 300 to 1200 milliseconds with a step of 1).', $this->slug ),
              'min'     => 300,
              'max'     => 1200,
              'step'    => 1,
              'default' => 900,
              'condition' => array(
                'nosi'   => 'on',
                'nosian' => 'on',
              ),
            )
          );
          $page->add_control(
            'vtcnosi',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Scrolling vertical curve', $this->slug ),
              'description' => esc_html__( 'Set the vertical scrolling easing from a pre-estabilished set of curve types (5 types available: linear, easeout, arc, quadratic, cubic).', $this->slug ),
              'default'     => 'easeout',
              'options'     => array(
                'linear'  => esc_html__( 'linear',  $this->slug ),
                'easeout' => esc_html__( 'easeout', $this->slug ),
                'arc'     => esc_html__( 'arc',     $this->slug ),
                'quad'    => esc_html__( 'quad',    $this->slug ),
                'cube'    => esc_html__( 'cube',    $this->slug ),
              ),
              'condition' => array(
                'nosi'   => 'on',
                'nosian' => 'on',
              ),
            )
          );
          $page->end_controls_section();
        }
        public function webyx_fep_nav_easing_controls ( $page ) {
          $page->start_controls_section(
            'webyx_slide_easings',
            array(
              'label'     => esc_html__( 'NAVIGATION EASINGS', $this->slug ),
              'tab'       => 'webyx-fe',
              'condition' => array(
                'webyx_enable' => 'on',
              ),
            )
          );
          $page->start_controls_tabs(
            'webyx_easing_card_tabs'
          );
          $page->start_controls_tab(
            'webyx_easing_vertical_card_tab',
            array(
              'label' => esc_html__( 'Vertical', $this->slug ),
            )
          );
          $page->add_control(
            'vmsd',
            array(
              'type'        => \Elementor\Controls_Manager::NUMBER,
              'label'       => esc_html__( 'Speed', $this->slug ),
              'description' => esc_html__( 'Speed for the vertical scrolling transition to Section in miliseconds (range from 300 to 1200 milliseconds with a step of 1).', $this->slug ),
              'min'         => 300,
              'max'         => 1200,
              'step'        => 1,
              'default'     => 900,
            )
          );
          $page->add_control(
            'vmcd',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Easing', $this->slug ),
              'default'     => 'cubic-bezier(0.64,0,0.34,1)',
              'description' => esc_html__( 'Set the vertical animation easing from a pre-estabilished set of curve types.', $this->slug ),
              'options'     => array(
                'cubic-bezier(0.64,0,0.34,1)'      => esc_html__( 'default', $this->slug ),
                'cubic-bezier(0,0,1,1)'            => esc_html__( 'linear', $this->slug ),
                'cubic-bezier(0.25,0.1,0.25,1)'    => esc_html__( 'ease', $this->slug ),
                'cubic-bezier(0.42,0,1,1)'         => esc_html__( 'easein', $this->slug ),
                'cubic-bezier(0,0,0.58,1)'         => esc_html__( 'easeout', $this->slug ),
                'cubic-bezier(0.42,0,0.58,1)'      => esc_html__( 'easeinout', $this->slug ),
                'cubic-bezier(0.02,0.01,0.47,1)'   => esc_html__( 'swing', $this->slug ),
                'cubic-bezier(0,0.5,0.5,1)'        => esc_html__( 'arc', $this->slug ),
                'cubic-bezier(0.12,0,0.39,0)'      => esc_html__( 'easeInSine', $this->slug ),
                'cubic-bezier(0.32,0,0.67,0)'      => esc_html__( 'easeInCubic', $this->slug ),
                'cubic-bezier(0.64,0,0.78,0)'      => esc_html__( 'easeInQuint', $this->slug ),
                'cubic-bezier(0.55,0,1,0.45)'      => esc_html__( 'easeInCirc', $this->slug ),
                'cubic-bezier(0.11,0,0.5,0)'       => esc_html__( 'easeInQuad', $this->slug ),
                'cubic-bezier(0.5,0,0.75,0)'       => esc_html__( 'easeInQuart', $this->slug ),
                'cubic-bezier(0.7,0,0.84,0)'       => esc_html__( 'easeInExpo', $this->slug ),
                'cubic-bezier(0.36,0,0.66,-0.56)'  => esc_html__( 'easeInBack' , $this->slug ),
                'cubic-bezier(0.61,1,0.88,1)'      => esc_html__( 'easeOutSine', $this->slug ),
                'cubic-bezier(0.33,1,0.68,1)'      => esc_html__( 'easeOutCubic', $this->slug ),
                'cubic-bezier(0.22,1,0.36,1)'      => esc_html__( 'easeOutQuint', $this->slug ),
                'cubic-bezier(0,0.55,0.45,1)'      => esc_html__( 'easeOutCirc', $this->slug ),
                'cubic-bezier(0.5,1,0.89,1)'       => esc_html__( 'easeOutQuad', $this->slug ),
                'cubic-bezier(0.25,1,0.5,1)'       => esc_html__( 'easeOutQuart', $this->slug ),
                'cubic-bezier(0.16,1,0.3,1)'       => esc_html__( 'easeOutExpo', $this->slug ),
                'cubic-bezier(0.34,1.56,0.64,1)'   => esc_html__( 'easeOutBack', $this->slug ),
                'cubic-bezier(0.37,0,0.63,1)'      => esc_html__( 'easeInOutSine', $this->slug ),
                'cubic-bezier(0.65,0,0.35,1)'      => esc_html__( 'easeInOutCubic', $this->slug ),
                'cubic-bezier(0.83,0,0.17,1)'      => esc_html__( 'easeInOutQuint', $this->slug ),
                'cubic-bezier(0.85,0,0.15,1)'      => esc_html__( 'easeInOutCirc', $this->slug ),
                'cubic-bezier(0.45,0,0.55,1)'      => esc_html__( 'easeInOutQuad', $this->slug ),
                'cubic-bezier(0.76,0,0.24,1)'      => esc_html__( 'easeInOutQuart', $this->slug ),
                'cubic-bezier(0.68,-0.6,0.32,1.6)' => esc_html__( 'easeInOutBack',  $this->slug )
              ),
            )
          );
          $page->end_controls_tab();
          $page->start_controls_tab(
            'webyx_easing_horizontal_card_tab',
            array(
              'label' => esc_html__( 'Horizontal', $this->slug ),
            )
          );
          $page->add_control(
            'hmsd',
            array(
              'type'         => \Elementor\Controls_Manager::NUMBER,
              'label'        => esc_html__( 'Speed', $this->slug ),
              'description' => esc_html__( 'Speed for the horizontal scrolling transition to Section in miliseconds (range from 300 to 1200 milliseconds with a step of 1).', $this->slug ),
              'min'          => 300,
              'max'          => 1200,
              'step'         => 1,
              'default'      => 900,
            )
          );
          $page->add_control(
            'hmcd',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Easing', $this->slug ),
              'description' => esc_html__( 'Set the horizontal animation easing from a pre-estabilished set of curve types.', $this->slug ),
              'default'     => 'cubic-bezier(0.64,0,0.34,1)',
              'options'     => array(
                'cubic-bezier(0.64,0,0.34,1)'      => esc_html__( 'default', $this->slug ),
                'cubic-bezier(0,0,1,1)'            => esc_html__( 'linear', $this->slug ),
                'cubic-bezier(0.25,0.1,0.25,1)'    => esc_html__( 'ease', $this->slug ),
                'cubic-bezier(0.42,0,1,1)'         => esc_html__( 'easein', $this->slug ),
                'cubic-bezier(0,0,0.58,1)'         => esc_html__( 'easeout', $this->slug ),
                'cubic-bezier(0.42,0,0.58,1)'      => esc_html__( 'easeinout', $this->slug ),
                'cubic-bezier(0.02,0.01,0.47,1)'   => esc_html__( 'swing', $this->slug ),
                'cubic-bezier(0,0.5,0.5,1)'        => esc_html__( 'arc', $this->slug ),
                'cubic-bezier(0.12,0,0.39,0)'      => esc_html__( 'easeInSine', $this->slug ),
                'cubic-bezier(0.32,0,0.67,0)'      => esc_html__( 'easeInCubic', $this->slug ),
                'cubic-bezier(0.64,0,0.78,0)'      => esc_html__( 'easeInQuint', $this->slug ),
                'cubic-bezier(0.55,0,1,0.45)'      => esc_html__( 'easeInCirc', $this->slug ),
                'cubic-bezier(0.11,0,0.5,0)'       => esc_html__( 'easeInQuad', $this->slug ),
                'cubic-bezier(0.5,0,0.75,0)'       => esc_html__( 'easeInQuart', $this->slug ),
                'cubic-bezier(0.7,0,0.84,0)'       => esc_html__( 'easeInExpo', $this->slug ),
                'cubic-bezier(0.36,0,0.66,-0.56)'  => esc_html__( 'easeInBack' , $this->slug ),
                'cubic-bezier(0.61,1,0.88,1)'      => esc_html__( 'easeOutSine', $this->slug ),
                'cubic-bezier(0.33,1,0.68,1)'      => esc_html__( 'easeOutCubic', $this->slug ),
                'cubic-bezier(0.22,1,0.36,1)'      => esc_html__( 'easeOutQuint', $this->slug ),
                'cubic-bezier(0,0.55,0.45,1)'      => esc_html__( 'easeOutCirc', $this->slug ),
                'cubic-bezier(0.5,1,0.89,1)'       => esc_html__( 'easeOutQuad', $this->slug ),
                'cubic-bezier(0.25,1,0.5,1)'       => esc_html__( 'easeOutQuart', $this->slug ),
                'cubic-bezier(0.16,1,0.3,1)'       => esc_html__( 'easeOutExpo', $this->slug ),
                'cubic-bezier(0.34,1.56,0.64,1)'   => esc_html__( 'easeOutBack', $this->slug ),
                'cubic-bezier(0.37,0,0.63,1)'      => esc_html__( 'easeInOutSine', $this->slug ),
                'cubic-bezier(0.65,0,0.35,1)'      => esc_html__( 'easeInOutCubic', $this->slug ),
                'cubic-bezier(0.83,0,0.17,1)'      => esc_html__( 'easeInOutQuint', $this->slug ),
                'cubic-bezier(0.85,0,0.15,1)'      => esc_html__( 'easeInOutCirc', $this->slug ),
                'cubic-bezier(0.45,0,0.55,1)'      => esc_html__( 'easeInOutQuad', $this->slug ),
                'cubic-bezier(0.76,0,0.24,1)'      => esc_html__( 'easeInOutQuart', $this->slug ),
                'cubic-bezier(0.68,-0.6,0.32,1.6)' => esc_html__( 'easeInOutBack', $this->slug )
              ),
            )
          );
          $page->end_controls_tab();
          $page->end_controls_tabs();
          $page->end_controls_section();
        }
        public function webyx_fep_nav_arrows_controls ( $page ) {
          $page->start_controls_section(
            'webyx_navigation_arrows', 
            array(
              'label'     => esc_html__( 'NAVIGATION ARROWS', $this->slug ),
              'tab'       => 'webyx-fe',
              'condition' => array(
                'webyx_enable' => 'on',
              ),
            )
          );
          $page->add_control(
            'av',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Arrows', $this->slug ),
              'description'  => esc_html__( 'Enable navigation arrows on every Section.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control( 
            'avven',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Arrows vertical', $this->slug ),
              'description'  => esc_html__( 'Enable vertical navigation arrows on every Section.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition' => array(
                'av' => 'on',
              ),
            )
          );
          $page->add_control( 
            'avhen',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Arrows horizontal', $this->slug ),
              'description'  => esc_html__( 'Enable horizontal navigation arrows on every Section.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition' => array(
                'av' => 'on',
              ),
            )
          );
          $page->add_control(
            'avf',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Fixed arrows', $this->slug ),
              'description'  => esc_html__( 'Makes arrows persistent. If disabled arrows will vanish and reapper on mouse hover.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition'    => array(
                'av' => 'on',
              ),
            )
          );
          $page->add_control(
            'mvnatp',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Arrows type', $this->slug ),
              'description' => esc_html__( 'Choose arrows standard type or custom image.', $this->slug ),
              'default'     => 'standard',
              'options'     => array(
                'standard'  => esc_html__( 'standard',   $this->slug ),
                'image'     => esc_html__( 'custom image', $this->slug ),
              ),
              'condition' => array(
                'av' => 'on',
              ),
            )
          );
          $page->add_control(
            'mvnabkgimg', 
            array(
              'type' => \Elementor\Controls_Manager::MEDIA,
              'label'=> esc_html__( 'Choose image', $this->slug ),
              'description' => esc_html__( 'Choose arrow background image.', $this->slug ),
              'dynamic' => array(
                'active'     => true,
                'categories' => array(
                  \Elementor\Modules\DynamicTags\Module::MEDIA_CATEGORY,
                ),
              ),
              'default' => array(
                'url' => '',
              ),
              'media_type' => 'image',
              'condition'  => array(
                'av'     => 'on',
                'mvnatp' => 'image'
              ),
            )
          );
          $page->add_control(
            'mvnast',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Arrows size', $this->slug ),
              'description' => esc_html__( 'Choose navigation arrows size.', $this->slug ),
              'default'     => 'medium',
              'options' => array(
                'small'  => esc_html__( 'small',  $this->slug ),
                'medium' => esc_html__( 'medium', $this->slug ),
                'large'  => esc_html__( 'large',  $this->slug )
              ),
              'condition' => array(
                'av'     => 'on',
                'mvnatp' => 'standard'
              ),
            )
          );
          $page->add_control(
            'mvnatt',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Arrows thickness', $this->slug ),
              'description' => esc_html__( 'Choose navigation arrows thickness.', $this->slug ),
              'default'     => 'standard',
              'options'     => array(
                'thin'     => esc_html__( 'thin',     $this->slug ),
                'standard' => esc_html__( 'standard', $this->slug ),
                'thick'    => esc_html__( 'thick',    $this->slug )
              ),
              'condition' => array(
                'av'     => 'on',
                'mvnatp' => 'standard'
              ),
            )
          );
          $page->add_control(
            'mvnaad',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Arrows area', $this->slug ),
              'description' => esc_html__( 'Choose arrows dimension area type in pixels.', $this->slug ),
              'default'     => 'medium',
              'options'     => array(
                'small'  => esc_html__( 'small (80x50) pixels',   $this->slug ),
                'medium' => esc_html__( 'medium (150x70) pixels', $this->slug ),
                'large'  => esc_html__( 'large (300x90) pixels',  $this->slug ),
              ),
              'condition' => array(
                'av' => 'on',
              ),
            )
          );
          $page->add_control(
            'mvnac',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Arrows colour', 'plugin-domain' ),
              'description' => esc_html__( 'Choose navigation arrows colour.', $this->slug ),
              'default'     => '#000000',
              'condition'   => array(
                'av'     => 'on',
                'mvnatp' => 'standard'
              ),
            )
          );
          $page->add_control(
            'mvnacl',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Arrows colour light', 'plugin-domain' ),
              'description' => esc_html__( 'Choose navigation arrows colour light.', $this->slug ),
              'default'     => '#00000066',
              'condition'   => array(
                'av'     => 'on',
                'mvnatp' => 'standard'
              ),
            )
          );
          $page->add_control(
            'mvnact',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Arrows curvature', $this->slug ),
              'description'  => esc_html__( 'Enable a slight curvature to the navigation arrows aesthetics.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition'    => array(
                'av'     => 'on',
                'mvnatp' => 'standard'
              ),
            )
          );
          $page->add_control(
            'mvnaa',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Arrows background area', $this->slug ),
              'description' => esc_html__( 'Enable visible background area for every arrow.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition'    => array(
                'av'     => 'on',
                'mvnatp' => 'standard'
              ),
            )
          );
          $page->add_control(
            'mvnaac',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Arrows background area colour', $this->slug ),
              'description' => esc_html__( 'Choose navigation arrows background area colour.', $this->slug ),
              'default' => '#00000066',
              'condition' => array(
                'av'     => 'on',
                'mvnatp' => 'standard',
                'mvnaa'  => 'on',
              ),
            )
          );
          $page->add_control(
            'mvnaoc',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Arrows custom offset', $this->slug ),
              'description' => esc_html__( 'Enable custom positioning for every arrow.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition'    => array(
                'av' => 'on',
              ),
            )
          );
          $page->add_control(
            'mvnaot',
            array(
              'type'      => \Elementor\Controls_Manager::NUMBER,
              'label'     => esc_html__( 'Arrow top position offset', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a top offset (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'min'       => 0,
              'max'       => 5000,
              'step'      => 1,
              'default'   => 0,
              'condition' => array(
                'av'     => 'on',
                'mvnaoc' => 'on',
              ),
            )
          );
          $page->add_control(
            'mvnaor',
            array(
              'type'      => \Elementor\Controls_Manager::NUMBER,
              'label'     => esc_html__( 'Arrow right position offset', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a right offset (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'min'       => 0,
              'max'       => 5000,
              'step'      => 1,
              'default'   => 0,
              'condition' => array(
                'av'     => 'on',
                'mvnaoc' => 'on',
              ),
            )
          );
          $page->add_control(
            'mvnaob',
            array(
              'type'      => \Elementor\Controls_Manager::NUMBER,
              'label'     => esc_html__( 'Arrow bottom position offset', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a bottom offset (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'min'       => 0,
              'max'       => 5000,
              'step'      => 1,
              'default'   => 0,
              'condition' => array(
                'av'     => 'on',
                'mvnaoc' => 'on',
              ),
            )
          );
          $page->add_control(
            'mvnaol',
            array(
              'type'      => \Elementor\Controls_Manager::NUMBER,
              'label'     => esc_html__( 'Arrow left position offset', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a left offset (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'min'       => 0,
              'max'       => 5000,
              'step'      => 1,
              'default'   => 0,
              'condition' => array(
                'av'     => 'on',
                'mvnaoc' => 'on',
              ),
            )
          );
          $page->end_controls_section();
        }
        public function webyx_fep_nav_bullets_controls ( $page ) {
          $page->start_controls_section(
            'webyx_navigation_bullets',
            array(
              'label'     => esc_html__( 'NAVIGATION BULLETS', $this->slug ),
              'tab'       => 'webyx-fe',
              'condition' => array(
                'webyx_enable' => 'on',
              ),
            )
          );
          $page->start_controls_tabs(
            'webyx_bullets_style'
          );
          $page->start_controls_tab(
            'webyx_vertical_bullets_tab',
            array(
              'label' => esc_html__( 'Vertical', $this->slug )
            )
          );
          $page->add_control(
            'dv',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Bullets', $this->slug ),
              'description'  => esc_html__( 'Enable vertical navigation bullets on every Section.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control(
            'dvp',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Bullets position', $this->slug ),
              'description' => esc_html__( 'Choose vertical navigation bullets position.', $this->slug ),
              'default'     => 'right',
              'options'     => array(
                'left'  => esc_html__( 'left',  $this->slug ),
                'right' => esc_html__( 'right', $this->slug )
              ),
              'condition' => array(
                'dv' => 'on',
              ),
            )
          );
          $page->add_control(
            'dtvoff',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Bullets offset', $this->slug ),
              'description'  => esc_html__( 'Enable bullets offset.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition' => array(
                'dv' => 'on',
              ),
            )
          );
          $page->add_control(
            'dtvoffdsk',
            array(
              'type'      => \Elementor\Controls_Manager::NUMBER,
              'label'     => esc_html__( 'Vertical bullet offset desktop', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a vertical offset (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'min'       => 0,
              'max'       => 5000,
              'step'      => 1,
              'default'   => 0,
              'condition' => array(
                'dv'     => 'on',
                'dtvoff' => 'on',
              ),
            )
          );
          $page->add_control(
            'dtvoffmob',
            array(
              'type'      => \Elementor\Controls_Manager::NUMBER,
              'label'     => esc_html__( 'Vertical bullet offset mobile', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a vertical offset (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'min'       => 0,
              'max'       => 5000,
              'step'      => 1,
              'default'   => 0,
              'condition' => array(
                'dv'     => 'on',
                'dtvoff' => 'on',
              ),
            )
          );
          $page->add_control(
            'dtv',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Tooltips', $this->slug ),
              'description'  => esc_html__( 'Displays vertical Section name on mouse hover.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition' => array(
                'dv' => 'on',
              ),
            )
          );
          $page->add_control(
            'dtvcp',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Fixed tooltips', $this->slug ),
              'description'  => esc_html__( 'Vertical bullet tooltips are now persistent.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition' => array(
                'dv'  => 'on',
                'dtv' => 'on',
              ),
            )
          );
          $page->end_controls_tab();
          $page->start_controls_tab(
            'webyx_horizontal_bullets_tab',
            array(
              'label' => esc_html__( 'Horizontal', $this->slug ),
            )
          );
          $page->add_control(
            'dh',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Bullets', $this->slug ),
              'description'  => esc_html__( 'Enable horizontal navigation bullets on every Section.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control(
            'dhp',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Bullets position', $this->slug ),
              'description' => esc_html__( 'Choose horizontal navigation bullets position.', $this->slug ),
              'default'     => 'bottom',
              'options'     => array(
                'top'    => esc_html__( 'top',    $this->slug ),
                'bottom' => esc_html__( 'bottom', $this->slug )
              ),
              'condition' => array(
                'dh' => 'on',
              ),
            )
          );
          $page->add_control(
            'dthoff',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Bullets offset', $this->slug ),
              'description'  => esc_html__( 'Enable bullets offset.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition' => array(
                'dh' => 'on',
              ),
            )
          );
          $page->add_control(
            'dthoffdsk',
            array(
              'type'      => \Elementor\Controls_Manager::NUMBER,
              'label'     => esc_html__( 'Horizontal bullet offset desktop', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a horizontal offset (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'min'       => 0,
              'max'       => 5000,
              'step'      => 1,
              'default'   => 0,
              'condition' => array(
                'dh'     => 'on',
                'dthoff' => 'on',
              ),
            )
          );
          $page->add_control(
            'dthoffmob',
            array(
              'type'      => \Elementor\Controls_Manager::NUMBER,
              'label'     => esc_html__( 'Horizontal bullet offset mobile', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a vertical offset (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'min'       => 0,
              'max'       => 5000,
              'step'      => 1,
              'default'   => 0,
              'condition' => array(
                'dh'     => 'on',
                'dthoff' => 'on',
              ),
            )
          );
          $page->add_control(
            'dth',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Tooltips', $this->slug ),
              'description'  => esc_html__( 'Displays horizontal Section name on mouse hover.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition'    => array(
                'dh' => 'on',
              ),
            )
          );
          $page->add_control(
            'dthcp',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Fixed tooltips', $this->slug ),
              'description'  => esc_html__( 'Horizontal bullet tooltips are now persistent.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition'    => array(
                'dh'  => 'on',
                'dth' => 'on',
              ),
            )
          );
          $page->add_control(
            'dhs',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Solo bullet', $this->slug ),
              'description'  => esc_html__( 'Displays a bullet in the case of a single horizontal Section.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition'    => array(
                'dh' => 'on',
              ),
            )
          );
          $page->end_controls_tab();
          $page->end_controls_tabs();
          $page->add_control(
            'mvndbst',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Bullets type', $this->slug ),
              'description' => esc_html__( 'Choose navigation bullets type.', $this->slug ),
              'default'     => 'scale',
              'separator'   => 'before',
              'options'     => array(
                'scale'        => esc_html__( 'scale',         $this->slug ),
                'stroke'       => esc_html__( 'stroke',        $this->slug ),
                'small_stroke' => esc_html__( 'small stroke',  $this->slug ),
                'fill_in'      => esc_html__( 'fill in',       $this->slug ),
                'fill_up'      => esc_html__( 'fill up',       $this->slug ),
                'fall'         => esc_html__( 'fall',          $this->slug ),
                'puff'         => esc_html__( 'puff',          $this->slug ),
                'scale_sq'     => esc_html__( 'scale square',  $this->slug ),
                'stroke_sq'    => esc_html__( 'stroke square', $this->slug ),
                'small_stroke_sq' => esc_html__( 'small stroke square', $this->slug ),
                'fill_in_sq' => esc_html__( 'fill in square', $this->slug ),
                'fill_up_sq' => esc_html__( 'fill up square', $this->slug ),
                'fall_sq' => esc_html__( 'fall square', $this->slug ),
                'puff_sq' => esc_html__( 'puff square', $this->slug ),
                'scale_sq_rt' => esc_html__( 'scale diamond', $this->slug ),
                'stroke_sq_rt' => esc_html__( 'stroke diamond', $this->slug ),
                'small_stroke_sq_rt' => esc_html__( 'small stroke diamond', $this->slug ),
                'fill_in_sq_rt' => esc_html__( 'fill in diamond', $this->slug ),
                'fill_up_sq_rt' => esc_html__( 'fill up diamond', $this->slug ),
                'fall_sq_rt' => esc_html__( 'fall diamond', $this->slug ),
                'puff_sq_rt' => esc_html__( 'puff diamond', $this->slug ),
                'scale_line' => esc_html__( 'scale line', $this->slug ),
                'stroke_line' => esc_html__( 'stroke line', $this->slug ),
                'small_stroke_line' => esc_html__( 'small stroke line', $this->slug ),
                'fill_in_line' => esc_html__( 'fill in line', $this->slug ),
                'fill_up_line' => esc_html__( 'fill up line', $this->slug ),
                'fall_line' => esc_html__( 'fall line', $this->slug ),
                'puff_line' => esc_html__( 'puff line', $this->slug ),
              ),
            )
          );
          $page->add_control( 
            'mvndc',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Bullets colour', $this->slug ),
              'description' => esc_html__( 'Choose navigation bullets colour.', $this->slug ),
              'default'     => '#000000',
              // 'global' => [
              //   'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY,
              //   'active' => true,
              // ]
            )
          );
          $page->add_control(
            'mvndcl',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Bullets colour light', $this->slug ),
              'description' => esc_html__( 'Choose navigation bullets colour light.', $this->slug ),
              'default'     => '#00000066',
            )
          );
          $page->add_control(
            'dbkgace',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Bullets background area', $this->slug ),
              'description'  => esc_html__( 'Enable bullets background area.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control(
            'dbkgac',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Bullets background area colour', $this->slug ),
              'description' => esc_html__( 'Choose navigation bullets background area colour.', $this->slug ),
              'default'     => '#00000066',
              'condition' => array(
                'dbkgace' => 'on',
              ),
            )
          );
          $page->add_control(
            'mvndttc',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Bullet tooltip text colour', $this->slug ),
              'description' => esc_html__( 'Choose navigation bullets tooltip text colour.', $this->slug ),
              'default'     => '#000000',
            )
          );
          $page->add_control(
            'mvndttcl',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Bullet tooltip text colour light', $this->slug ),
              'description' => esc_html__( 'Choose navigation bullets tooltip text colour light.', $this->slug ),
              'default'     => '#00000066',
            )
          );
          $page->add_control(
            'mvndttace',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Bullet tooltip area', $this->slug ),
              'description'  => esc_html__( 'Enable bullet tooltip area.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control(
            'mvndttac',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Bullet tooltip area colour', $this->slug ),
              'description' => esc_html__( 'Choose navigation bullets tooltip area colour.', $this->slug ),
              'default'     => '#ffffff',
              'condition' => array(
                'mvndttace' => 'on',
              ),
            )
          );
          $page->add_control(
            'mvndtane',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Bullet animation', $this->slug ),
              'description'  => esc_html__( 'Enable bullet animation go to slides/sections.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->end_controls_section();
        }
        public function webyx_fep_nav_mw_controls ( $page ) {
          $page->start_controls_section(
            'webyx_mouse_wheel',
            array(
              'label'     => esc_html__( 'NAVIGATION MOUSE WHEEL', $this->slug ),
              'tab'       => 'webyx-fe',
              'condition' => array(
                'webyx_enable' => 'on',
              ),
            )
          );
          $page->add_control(
            'nvvw',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Mouse wheel navigation', $this->slug ),
              'description'  => esc_html__( 'Enable vertical navigation with mouse wheel.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control(
            'avvd',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Wheel icon', $this->slug ),
              'description'  => esc_html__( 'If mouse wheel icon fixed option is not enabled this icon will disappear after first vertical movement. WARNING: this icon will be shown ONLY if mouse wheel option is enabled.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition' => array(
                'nvvw' => 'on',
              ),
            )
          );
          $page->add_control(
            'nvvwofe',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Wheel vertical offset', $this->slug ),
              'description'  => esc_html__( 'Enable mouse wheel offset.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition' => array(
                'nvvw' => 'on',
                'avvd' => 'on',
              ),
            )
          );
          $page->add_control(
            'nvvwof',
            array(
              'type'        => \Elementor\Controls_Manager::SLIDER,
              'label'       => esc_html__( 'Wheel vertical bottom offset', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply to the icon from the bottom (px, %, vw).', $this->slug ),
              'size_units'  => array( 
                'px', 
                '%', 
                'vh',
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
                '%' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
                'vh' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 0,
              ),
              'condition' => array(
                'nvvw'    => 'on',
                'avvd'    => 'on',
                'nvvwofe' => 'on',
              ),
            )
          );
          $page->add_control(
            'msiwc',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Wheel icon colour', $this->slug ),
              'description' => esc_html__( 'Choose mouse icon colour.', $this->slug ),
              'default'     => '#000000',
              'condition' => array(
                'nvvw' => 'on',
                'avvd' => 'on',
              ),
            )
          );
          $page->add_control(
            'msiwbce',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Wheel icon background', $this->slug ),
              'description' => esc_html__( 'Enable mouse wheel icon background.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition' => array(
                'nvvw' => 'on',
                'avvd' => 'on',
              ),
            )
          );
          $page->add_control(
            'msiwbc',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Wheel icon background colour', $this->slug ),
              'description' => esc_html__( 'Choose wheel icon background colour.', $this->slug ),
              'default'     => '#ffffff',
              'condition' => array(
                'nvvw'    => 'on',
                'avvd'    => 'on',
                'msiwbce' => 'on',
              ),
            )
          );
          $page->add_control(
            'iwhf',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Wheel icon fixed', $this->slug ),
              'description'  => esc_html__( 'Makes wheel icon persistent if present.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition' => array(
                'nvvw' => 'on',
                'avvd' => 'on',
              ),
            )
          );
          $page->add_control(
            'mwh',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Horizontal animation', $this->slug ),
              'description'  => esc_html__( 'Enable horizontal animation during website navigation with mouse wheel or trackpad (slide, fade and toggle animations).', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition' => array(
                'nvvw' => 'on',
              ),
            )
          );
          $page->add_control(
            'hzsmooth',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Horizontal smooth animation', $this->slug ),
              'description'  => esc_html__( 'Enable a softer movement for horizontal scrolling navigation with mouse wheel, otherwise the browser\'s standard sticky behaviour will be used instead.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control(
            'hzsmoothtime',
            array(
              'type'        => \Elementor\Controls_Manager::NUMBER,
              'label'       => esc_html__( 'Smooth animation duration', $this->slug ),
              'description' => esc_html__( 'Enable a predefined time duration for the horizontal smooth animation movement (range from 0.8 to 2 with a step of 0.1).', $this->slug ),
              'min'         => 0.8,
              'max'         => 2,
              'step'        => 0.1,
              'default'     => 0.8,
              'condition' => array(
                'hzsmooth' => 'on',
              ),
            )
          );
          $page->add_control(
            'hzscrllvd',
            array(
              'type'        => \Elementor\Controls_Manager::NUMBER,
              'label'       => esc_html__( 'Horizontal scroll style velocity', $this->slug ),
              'description' => esc_html__( 'Speed for the horizontal scrolling transition to Section (range from 1 to 100 with a step of 1).', $this->slug ),
              'min'         => 1,
              'max'         => 100,
              'step'        => 1,
              'default'     => 5,
              'condition' => array(
                'nvvw' => 'on',
              ),
            )
          );
          $page->end_controls_section();
        }
        public function webyx_fep_nav_kb_controls ( $page ) {
          $page->start_controls_section(
            'webyx_navigation_keyboard',
            array(
              'label'     => esc_html__( 'NAVIGATION KEYBOARD', $this->slug ),
              'tab'       => 'webyx-fe',
              'condition' => array(
                'webyx_enable' => 'on',
              ),
            )
          );
          $page->add_control(
            'kn',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Enable keyboard navigation', $this->slug ),
              'description'  => esc_html__( 'Enable website navigation with keyboard arrows.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->end_controls_section();
        }
        public function webyx_fep_fsb_controls ( $page ) {
          $page->start_controls_section(
            'webyx_full_screen_button',
            array(
              'label'     => esc_html__( 'FULL SCREEN BUTTON', $this->slug ),
              'tab'       => 'webyx-fe',
              'condition' => array(
                'webyx_enable' => 'on',
              ),
            )
          );
          $page->add_control(
            'fsb',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Full screen button', $this->slug ),
              'description'  => esc_html__( 'Enable a button to switch to full screen display.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control(
            'fsp',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Button position', $this->slug ),
              'description' => esc_html__( 'Choose the position of the full screen button.', $this->slug ),
              'default'     => 'right',
              'options'     => array(
                'left'  => esc_html__( 'left',  $this->slug ),
                'right' => esc_html__( 'right', $this->slug ),
              ),
              'condition' => array(
                'fsb' => 'on',
              ),
            )
          );
          $page->add_control(
            'fsdt',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Button thickness', $this->slug ),
              'default'     => '4px',
              'description' => esc_html__( 'Choose full screen button dimension thickness.', $this->slug ),
              'options'     => array(
                '2px' => esc_html__( 'thin',     $this->slug ),
                '4px' => esc_html__( 'standard', $this->slug ),
                '6px' => esc_html__( 'thick',    $this->slug ),
              ),
              'condition' => array(
                'fsb' => 'on',
              ),
            )
          );
          $page->add_control(
            'fsboff',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Button custom offset', $this->slug ),
              'description'  => esc_html__( 'Enable custom positioning for the button.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition' => array(
                'fsb' => 'on',
              ),
              )
            );
          $page->add_control(
            'fsofft',
            array(
              'type'      => \Elementor\Controls_Manager::NUMBER,
              'label'     => esc_html__( 'Button top offset', $this->slug ),
              'description'  => esc_html__( 'Insert a value to apply a top offset (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'min'       => 0,
              'max'       => 5000,
              'step'      => 1,
              'default'   => 0,
              'condition' => array(
                'fsb'    => 'on',
                'fsboff' => 'on',
              ),
            )
          );
          $page->add_control(
            'fsoffr',
            array(
              'type'      => \Elementor\Controls_Manager::NUMBER,
              'label'     => esc_html__( 'Button right offset', $this->slug ),
              'description'  => esc_html__( 'Insert a value to apply a right offset (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'min'       => 0,
              'max'       => 5000,
              'step'      => 1,
              'default'   => 0,
              'condition' => array(
                'fsb'    => 'on',
                'fsp'    => 'right',
                'fsboff' => 'on',
              ),
            )
          );
          $page->add_control(
            'fsoffl',
            array(
              'type'      => \Elementor\Controls_Manager::NUMBER,
              'label'     => esc_html__( 'Button left offset', $this->slug ),
              'description'  => esc_html__( 'Insert a value to apply a left offset (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'min'       => 0,
              'max'       => 5000,
              'step'      => 1,
              'default'   => 0,
              'condition' => array(
                'fsb'    => 'on',
                'fsp'    => 'left',
                'fsboff' => 'on',
              ),
            )
          );
          $page->add_control(
            'fsc',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Button colour', $this->slug ),
              'description' => esc_html__( 'Choose button colour.', $this->slug ),
              'default'     => '#000000',
              'condition' => array(
                'fsb' => 'on',
              ),
            )
          ); 
          $page->add_control(
            'fsbce',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Button background', $this->slug ),
              'description' => esc_html__( 'Enable button background.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition' => array(
                'fsb' => 'on',
              ),
            )
          );
          $page->add_control(
            'fsbc',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Button background colour', $this->slug ),
              'description' => esc_html__( 'Choose button background colour.', $this->slug ),
              'default'     => '#ffffff00',
              'condition' => array(
                'fsb'   => 'on',
                'fsbce' => 'on',
              ),
            )
          );
          $page->end_controls_section();
        }
        public function webyx_fep_mob_controls ( $page ) {
          $is_container_active = $this->webyx_fep_is_container_active();
          $el_root = $is_container_active ? 'Container' : 'Section';
          $page->start_controls_section(
            'webyx_mobile_device',
            array(
              'label'     => esc_html__( 'MOBILE DEVICE', $this->slug ),
              'tab'       => 'webyx-fe',
              'condition' => array(
                'webyx_enable' => 'on',
              ),
            )
          );
          $page->add_control(
            'fdskm',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Force desktop mode', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'description'  => esc_html__( 'WARNING: If enabled, Sections and Slides navigation will be possible through arrows/bullets/menu and NOT through swipe/scroll.', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->start_controls_tabs(
            'webyx_mobile_device_tabs',
            array(
              'condition' => array(
                'fdskm' => '',
              ),
            )
          );
          $page->start_controls_tab(
            'vertical_mobile_device_tab',
            array(
              'label' => esc_html__( 'Vertical', $this->slug ),
            )
          );
          $page->add_control(
            'vmsm',
            array(
              'type'        => \Elementor\Controls_Manager::NUMBER,
              'label'       => esc_html__( 'Speed', $this->slug ),
              'description' => esc_html__( 'Speed for the vertical scrolling transition to Section in miliseconds on mobile device (range from 300 to 1200 milliseconds with a step of 1).', $this->slug ),
              'min'         => 300,
              'max'         => 1200,
              'step'        => 1,
              'default'     => 300,
            )
          );
          $page->add_control(
            'vmcm',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Easing', $this->slug ),
              'description' => esc_html__( 'Set the vertical animation easing from a pre-estabilished set of curve types on mobile device.', $this->slug ),
              'default'     => 'cubic-bezier(0.64,0,0.34,1)',
              'options'     => array(
                'cubic-bezier(0.64,0,0.34,1)'      => esc_html__( 'default', $this->slug ),
                'cubic-bezier(0,0,1,1)'            => esc_html__( 'linear', $this->slug ),
                'cubic-bezier(0.25,0.1,0.25,1)'    => esc_html__( 'ease', $this->slug ),
                'cubic-bezier(0.42,0,1,1)'         => esc_html__( 'easein', $this->slug ),
                'cubic-bezier(0,0,0.58,1)'         => esc_html__( 'easeout', $this->slug ),
                'cubic-bezier(0.42,0,0.58,1)'      => esc_html__( 'easeinout', $this->slug ),
                'cubic-bezier(0.02,0.01,0.47,1)'   => esc_html__( 'swing', $this->slug ),
                'cubic-bezier(0,0.5,0.5,1)'        => esc_html__( 'arc', $this->slug ),
                'cubic-bezier(0.12,0,0.39,0)'      => esc_html__( 'easeInSine', $this->slug ),
                'cubic-bezier(0.32,0,0.67,0)'      => esc_html__( 'easeInCubic', $this->slug ),
                'cubic-bezier(0.64,0,0.78,0)'      => esc_html__( 'easeInQuint', $this->slug ),
                'cubic-bezier(0.55,0,1,0.45)'      => esc_html__( 'easeInCirc', $this->slug ),
                'cubic-bezier(0.11,0,0.5,0)'       => esc_html__( 'easeInQuad', $this->slug ),
                'cubic-bezier(0.5,0,0.75,0)'       => esc_html__( 'easeInQuart', $this->slug ),
                'cubic-bezier(0.7,0,0.84,0)'       => esc_html__( 'easeInExpo', $this->slug ),
                'cubic-bezier(0.36,0,0.66,-0.56)'  => esc_html__( 'easeInBack' , $this->slug ),
                'cubic-bezier(0.61,1,0.88,1)'      => esc_html__( 'easeOutSine', $this->slug ),
                'cubic-bezier(0.33,1,0.68,1)'      => esc_html__( 'easeOutCubic', $this->slug ),
                'cubic-bezier(0.22,1,0.36,1)'      => esc_html__( 'easeOutQuint', $this->slug ),
                'cubic-bezier(0,0.55,0.45,1)'      => esc_html__( 'easeOutCirc', $this->slug ),
                'cubic-bezier(0.5,1,0.89,1)'       => esc_html__( 'easeOutQuad', $this->slug ),
                'cubic-bezier(0.25,1,0.5,1)'       => esc_html__( 'easeOutQuart', $this->slug ),
                'cubic-bezier(0.16,1,0.3,1)'       => esc_html__( 'easeOutExpo', $this->slug ),
                'cubic-bezier(0.34,1.56,0.64,1)'   => esc_html__( 'easeOutBack', $this->slug ),
                'cubic-bezier(0.37,0,0.63,1)'      => esc_html__( 'easeInOutSine', $this->slug ),
                'cubic-bezier(0.65,0,0.35,1)'      => esc_html__( 'easeInOutCubic', $this->slug ),
                'cubic-bezier(0.83,0,0.17,1)'      => esc_html__( 'easeInOutQuint', $this->slug ),
                'cubic-bezier(0.85,0,0.15,1)'      => esc_html__( 'easeInOutCirc', $this->slug ),
                'cubic-bezier(0.45,0,0.55,1)'      => esc_html__( 'easeInOutQuad', $this->slug ),
                'cubic-bezier(0.76,0,0.24,1)'      => esc_html__( 'easeInOutQuart', $this->slug ),
                'cubic-bezier(0.68,-0.6,0.32,1.6)' => esc_html__( 'easeInOutBack', $this->slug ),
              ),
            )
          );
          $page->add_control(
            'thrY',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Threshold', $this->slug ),
              'description' => esc_html__( 'Threshold value in pixels for y-axis to fire vertical movement.', $this->slug ),
              'default'     => '50',
              'options'     => array(
                '10'  => esc_html__( '10 px',  $this->slug ),
                '25'  => esc_html__( '25 px',  $this->slug ),
                '50'  => esc_html__( '50 px',  $this->slug ),
                '75'  => esc_html__( '75 px',  $this->slug ),
                '100' => esc_html__( '100 px', $this->slug ),
              ),
            )
          );
          $page->end_controls_tab();
          $page->start_controls_tab(
            'webyx_horizontal_mobile_device_tab',
            array(
              'label' => esc_html__( 'Horizontal', $this->slug ),
            )
          );
          $page->add_control(
            'hmsm',
            array(
              'type'        => \Elementor\Controls_Manager::NUMBER,
              'label'       => esc_html__( 'Speed', $this->slug ),
              'description' => esc_html__( 'Speed for the horizontal scrolling transition to Section in miliseconds on mobile device (range from 300 to 1200 milliseconds with a step of 1).', $this->slug ),
              'min'         => 300,
              'max'         => 1200,
              'step'        => 1,
              'default'     => 300,
            )
          );
          $page->add_control(
            'hmcm',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Easing', $this->slug ),
              'description' => esc_html__( 'Set the horizontal animation easing from a pre-estabilished set of curve types on mobile device.', $this->slug ),
              'default'     => 'cubic-bezier(0.64,0,0.34,1)',
              'options'     => array(
                'cubic-bezier(0.64,0,0.34,1)'      => esc_html__( 'default', $this->slug ),
                'cubic-bezier(0,0,1,1)'            => esc_html__( 'linear', $this->slug ),
                'cubic-bezier(0.25,0.1,0.25,1)'    => esc_html__( 'ease', $this->slug ),
                'cubic-bezier(0.42,0,1,1)'         => esc_html__( 'easein', $this->slug ),
                'cubic-bezier(0,0,0.58,1)'         => esc_html__( 'easeout', $this->slug ),
                'cubic-bezier(0.42,0,0.58,1)'      => esc_html__( 'easeinout', $this->slug ),
                'cubic-bezier(0.02,0.01,0.47,1)'   => esc_html__( 'swing', $this->slug ),
                'cubic-bezier(0,0.5,0.5,1)'        => esc_html__( 'arc', $this->slug ),
                'cubic-bezier(0.12,0,0.39,0)'      => esc_html__( 'easeInSine', $this->slug ),
                'cubic-bezier(0.32,0,0.67,0)'      => esc_html__( 'easeInCubic', $this->slug ),
                'cubic-bezier(0.64,0,0.78,0)'      => esc_html__( 'easeInQuint', $this->slug ),
                'cubic-bezier(0.55,0,1,0.45)'      => esc_html__( 'easeInCirc', $this->slug ),
                'cubic-bezier(0.11,0,0.5,0)'       => esc_html__( 'easeInQuad', $this->slug ),
                'cubic-bezier(0.5,0,0.75,0)'       => esc_html__( 'easeInQuart', $this->slug ),
                'cubic-bezier(0.7,0,0.84,0)'       => esc_html__( 'easeInExpo', $this->slug ),
                'cubic-bezier(0.36,0,0.66,-0.56)'  => esc_html__( 'easeInBack' , $this->slug ),
                'cubic-bezier(0.61,1,0.88,1)'      => esc_html__( 'easeOutSine', $this->slug ),
                'cubic-bezier(0.33,1,0.68,1)'      => esc_html__( 'easeOutCubic', $this->slug ),
                'cubic-bezier(0.22,1,0.36,1)'      => esc_html__( 'easeOutQuint', $this->slug ),
                'cubic-bezier(0,0.55,0.45,1)'      => esc_html__( 'easeOutCirc', $this->slug ),
                'cubic-bezier(0.5,1,0.89,1)'       => esc_html__( 'easeOutQuad', $this->slug ),
                'cubic-bezier(0.25,1,0.5,1)'       => esc_html__( 'easeOutQuart', $this->slug ),
                'cubic-bezier(0.16,1,0.3,1)'       => esc_html__( 'easeOutExpo', $this->slug ),
                'cubic-bezier(0.34,1.56,0.64,1)'   => esc_html__( 'easeOutBack', $this->slug ),
                'cubic-bezier(0.37,0,0.63,1)'      => esc_html__( 'easeInOutSine', $this->slug ),
                'cubic-bezier(0.65,0,0.35,1)'      => esc_html__( 'easeInOutCubic', $this->slug ),
                'cubic-bezier(0.83,0,0.17,1)'      => esc_html__( 'easeInOutQuint', $this->slug ),
                'cubic-bezier(0.85,0,0.15,1)'      => esc_html__( 'easeInOutCirc', $this->slug ),
                'cubic-bezier(0.45,0,0.55,1)'      => esc_html__( 'easeInOutQuad', $this->slug ),
                'cubic-bezier(0.76,0,0.24,1)'      => esc_html__( 'easeInOutQuart', $this->slug ),
                'cubic-bezier(0.68,-0.6,0.32,1.6)' => esc_html__( 'easeInOutBack', $this->slug ),
              ),
            )
          );
          $page->add_control(
            'thrX',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Threshold', $this->slug ),
              'description' => esc_html__( 'Threshold value in pixels for x-axis to fire horizontal movement.', $this->slug ),
              'default'     => '25',
              'options' => array(
                '10'  => esc_html__( '10 px',  $this->slug ),
                '25'  => esc_html__( '25 px',  $this->slug ),
                '50'  => esc_html__( '50 px',  $this->slug ),
                '75'  => esc_html__( '75 px',  $this->slug ),
                '100' => esc_html__( '100 px', $this->slug ),
              ),
            )
          );
          $page->end_controls_tab();
          $page->end_controls_tabs();
          $page->add_control(
            'swx',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Horizontal swipe lock', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'description'  => esc_html__( 'WARNING: disables side swipe on every Section. If enabled side Sections will not be reachable.', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'separator'    => 'before',
            )
          );
          $page->add_control(
            'hzscrllstyle',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label_block' => true,
              'label'       => esc_html__( 'Horizontal scrolling gesture', $this->slug ),
              'description' => esc_html__( 'Set the horizontal scroll gesture for the ' . $el_root . ' that has the horizontal scrolling style enabled. IMPORTANT: when horizontal smooth animation option is enabled the horizontal scrolling gesture selection will be forced to vertical (vertical scroll).', $this->slug ),
              'default'     => 'vt',
              'options'     => array(
                'vt'   => esc_html__( 'vertical (vertical scroll)',  $this->slug ),
                'hz'   => esc_html__( 'classic (horizontal scroll)', $this->slug ),
                'vthz' => esc_html__( 'All (vertical and classic)',  $this->slug ),
              ),
            )
          );
          $page->add_control(
            'hzscrllvm',
            array(
              'type'        => \Elementor\Controls_Manager::NUMBER,
              'label'       => esc_html__( 'Horizontal scroll style velocity', $this->slug ),
              'description' => esc_html__( 'Speed for the horizontal scrolling transition to Section (range from 1 to 100 with a step of 1).', $this->slug ),
              'min'         => 1,
              'max'         => 100,
              'step'        => 1,
              'default'     => 2,
            )
          );
          $page->end_controls_section();
        }
        public function webyx_fep_scrlb_controls ( $page ) {
          $is_container_active = $this->webyx_fep_is_container_active();
          $el_root = $is_container_active ? 'Container' : 'Section';
          $page->start_controls_section(
            'webyx_scrollbar',
            array(
              'label'     => esc_html__( 'SCROLLBAR', $this->slug ),
              'tab'       => 'webyx-fe',
              'condition' => array(
                'webyx_enable' => 'on',
              ),
            )
          );
          $page->add_control(
            'scrlbd',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Hide scrollbar', $this->slug ),
              'description'  => esc_html__( 'Hides browser\'s default scrollbar in Sections when it should be present.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control(
            'scrlreset',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Scroll reset position', $this->slug ),
              'description'  => esc_html__( 'Scrolls back the content of the '. $el_root . ' with scroll bar when leaving it so it will always be at the start.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->end_controls_section();
        }
        public function webyx_fep_bkga_controls( $page ) {
          $page->start_controls_section(
            'webyx_background_audio',
            array(
              'label'     => esc_html__( 'BACKGROUND AUDIO', $this->slug ),
              'tab'       => 'webyx-fe',
              'condition' => array(
                'webyx_enable' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioPage',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Background audio', $this->slug ),
              'description'  => esc_html__( 'Enable background audio.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control(
            'bkgAudioAutoClosed',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Audio player auto closing', $this->slug ),
              'description'  => esc_html__( 'Audio auto closing of the player button.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition' => array(
                'bkgAudioPage' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioAutoClosedTimer',
            array(
              'type'        => \Elementor\Controls_Manager::NUMBER,
              'label'       => esc_html__( 'Audio player auto closing', $this->slug ),
              'description' => esc_html__( 'Enter a value that defines the audio player auto closing timer (range from 5 to 20 seconds with a step of 1).', $this->slug ),
              'min'         => 5,
              'max'         => 20,
              'step'        => 1,
              'default'     => 5,
              'condition' => array(
                'bkgAudioPage'       => 'on',
                'bkgAudioAutoClosed' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioMQXs',
            array(
              'type'        => \Elementor\Controls_Manager::NUMBER,
              'label'       => esc_html__( 'Media queries breakpoint', $this->slug ),
              'description' => esc_html__( 'Enter a value that defines the threshold for switching from desktop to mobile mode in pixels (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'min'         => 0,
              'max'         => 5000,
              'step'        => 1,
              'default'     => 760,
              'condition' => array(
                'bkgAudioPage' => 'on',
              ),
            )
          );
          $page->start_controls_tabs(
            'webyx_background_audio_tabs',
            array(
              'condition' => array(
                'bkgAudioPage' => 'on',
              ),
            )
          );
          $page->start_controls_tab(
            'webyx_background_audio_desktop_tab',
            array(
              'label' => esc_html__( 'Desktop', $this->slug ),
            )
          );
          $page->add_control(
            'bkgAudioPageDsk',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Background audio', $this->slug ),
              'description'  => esc_html__( 'Enable desktop background audio.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control(
            'bkgAudioUrl', 
            array(
              'type' => \Elementor\Controls_Manager::MEDIA,
              'label'=> esc_html__( 'Choose audio file', $this->slug ),
              'description'  => esc_html__( 'Choose background audio.', $this->slug ),
              'dynamic' => array(
                'active' => true,
                'categories' => array(
                  \Elementor\Modules\DynamicTags\Module::MEDIA_CATEGORY,
                ),
              ),
              'media_type' => 'audio',
              'default' => array(
                'url' => '',
              ),
              'condition' => array(
                'bkgAudioPageDsk' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioPreload',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Audio preload', $this->slug ),
              'description' => esc_html__( 'It specifies that the browser should or should not load the entire audio when the page loads. NOTE: The preload attribute is ignored if autoplay is present.', $this->slug ),
              'default'     => 'auto',
              'options'     => array(
                'none'     => esc_html__( 'none',     $this->slug ),
                'auto'     => esc_html__( 'auto',     $this->slug ),
                'metadata' => esc_html__( 'metadata', $this->slug ),
              ),
              'condition' => array(
                'bkgAudioPageDsk' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioControls',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Controls', $this->slug ),
              'description'  => esc_html__( 'It specifies that audio controls should be displayed.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition' => array(
                'bkgAudioPageDsk' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioMuted',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Muted', $this->slug ),
              'description'  => esc_html__( 'It specifies that the audio should be muted.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition' => array(
                'bkgAudioPageDsk' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioAutoplay',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Autoplay', $this->slug ),
              'description'  => esc_html__( 'The audio will be played as soon as it\'s playable. IMPORTANT: you must activate muted option to let your audio start playing automatically (but muted). NOTE: Chromium browsers do not allow autoplay in most cases.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition'    => array(
                'bkgAudioPageDsk' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioLoop',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Loop', $this->slug ),
              'description'  => esc_html__( 'It specifies that the audio will start over again, every time it is finished.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition'    => array(
                'bkgAudioPageDsk' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioBtnIcon',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Audio button icon', $this->slug ),
              'description'  => esc_html__( 'Audio button icon.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition'    => array(
                'bkgAudioPageDsk' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioBtnSize',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Audio button size', $this->slug ),
              'description' => esc_html__( 'Choose audio button size.', $this->slug ),
              'default'     => 'medium',
              'options'     => array(
                'small'  => esc_html__( 'small',  $this->slug ),
                'medium' => esc_html__( 'medium', $this->slug ),
                'large'  => esc_html__( 'large',  $this->slug ),
              ),
              'condition' => array(
                'bkgAudioPageDsk' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioBtnBkg',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Audio button background', $this->slug ),
              'description' => esc_html__( 'Choose audio player button background.', $this->slug ),
              'default'     => 'color',
              'options'     => array(
                'color' => esc_html__( 'color', $this->slug ),
                'image' => esc_html__( 'image', $this->slug ),
              ),
              'condition' => array(
                'bkgAudioPageDsk' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioBtnColor',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Background colour', $this->slug ),
              'description' => esc_html__( 'Choose audio button background colour.', $this->slug ),
              'default'     => '#9933cc',
              'condition' => array(
                'bkgAudioPageDsk' => 'on',
                'bkgAudioBtnBkg'  => 'color',
              ),
            )
          );
          $page->add_control(
            'bkgAudioBtnColorLight',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Background colour light', $this->slug ),
              'description' => esc_html__( 'Choose audio button background colour light.', $this->slug ),
              'default'     => '#bb64ea',
              'condition' => array(
                'bkgAudioPageDsk' => 'on',
                'bkgAudioBtnBkg'  => 'color',
              ),
            )
          );
          $page->add_control(
            'bkgAudioBtnUrlImage', 
            array(
              'type' => \Elementor\Controls_Manager::MEDIA,
              'label'=> esc_html__( 'Choose image', $this->slug ),
              'description' => esc_html__( 'Choose audio button background image.', $this->slug ),
              'dynamic' => array(
                'active' => true,
                'categories' => array(
                  \Elementor\Modules\DynamicTags\Module::MEDIA_CATEGORY,
                ),
              ),
              'default' => array(
                'url' => '',
              ),
              'media_type'  => 'image',
              'condition' => array(
                'bkgAudioPageDsk' => 'on',
                'bkgAudioBtnBkg'  => 'image',
              ),
            )
          );
          $page->add_control(
            'bkgAudioPositionHz',
            array(
              'type'    => \Elementor\Controls_Manager::CHOOSE,
              'label'   => esc_html__( 'Horizontal position', $this->slug ),
              'description'  => esc_html__( 'Choose audio player horizontal position.', $this->slug ),
              'options' => array(
                'left' => array(
                  'title' => esc_html__( 'Left', $this->slug ),
                  'icon'  => 'eicon-h-align-left',
                ),
                'right' => array(
                  'title' => esc_html__( 'Right', $this->slug ),
                  'icon'  => 'eicon-h-align-right',
                ),
              ),
              'default'   => 'left',
              'toggle'    => false,
              'condition' => array(
                'bkgAudioPageDsk' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioPositionHzVal',
            array(
              'type'       => \Elementor\Controls_Manager::SLIDER,
              'label'      => esc_html__( 'Horizontal position value', $this->slug ),
              'description'  => esc_html__( 'Insert a value to apply to horizontal position (px, %, vw).', $this->slug ),
              'size_units' => array( 
                'px', 
                '%', 
                'vw', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
                '%' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
                'vw' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 25,
              ),
              'condition' => array(
                'bkgAudioPageDsk' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioPositionVt',
            array(
              'type'    => \Elementor\Controls_Manager::CHOOSE,
              'label'   => esc_html__( 'Vertical position', $this->slug ),
              'description' => esc_html__( 'Choose audio player vertical position.', $this->slug ),
              'options' => array(
                'top' => array(
                  'title' => esc_html__( 'Top', $this->slug ),
                  'icon'  => 'eicon-v-align-top',
                ),
                'bottom' => array(
                  'title' => esc_html__( 'Bottom', $this->slug ),
                  'icon'  => 'eicon-v-align-bottom',
                ),
              ),
              'default'   => 'bottom',
              'toggle'    => false,
              'condition' => array(
                'bkgAudioPageDsk' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioPositionVtVal',
            array(
              'type'       => \Elementor\Controls_Manager::SLIDER,
              'label'      => esc_html__( 'Vertical position value', $this->slug ),
              'description'  => esc_html__( 'Insert a value to apply to vertical position (px, %, vh).', $this->slug ),
              'size_units' => array( 
                'px', 
                '%', 
                'vh', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
                '%' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
                'vh' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 25,
              ),
              'condition' => array(
                'bkgAudioPageDsk' => 'on',
              ),
            )
          );
          $page->end_controls_tab();
          $page->start_controls_tab(
            'webyx_background_audio_mobile_tab',
            array(
              'label' => esc_html__( 'Mobile', $this->slug ),
            )
          );
          $page->add_control(
            'bkgAudioPageMob',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Background audio', $this->slug ),
              'description'  => esc_html__( 'Enable mobile background audio.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control(
            'bkgAudioUrlXs', 
            array(
              'type' => \Elementor\Controls_Manager::MEDIA,
              'label'=> esc_html__( 'Choose audio file', $this->slug ),
              'description'  => esc_html__( 'Choose background audio.', $this->slug ),
              'dynamic' => array(
                'active' => true,
                'categories' => array(
                  \Elementor\Modules\DynamicTags\Module::MEDIA_CATEGORY,
                ),
              ),
              'media_type' => 'audio',
              'default' => array(
                'url' => '',
              ),
              'condition' => array(
                'bkgAudioPageMob' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioPreloadXs',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Audio preload', $this->slug ),
              'description' => esc_html__( 'It specifies that the browser should or should not load the entire audio when the page loads. NOTE: The preload attribute is ignored if autoplay is present.', $this->slug ),
              'default'     => 'auto',
              'options'     => array(
                'none'     => esc_html__( 'none',     $this->slug ),
                'auto'     => esc_html__( 'auto',     $this->slug ),
                'metadata' => esc_html__( 'metadata', $this->slug ),
              ),
              'condition' => array(
                'bkgAudioPageMob' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioControlsXs',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Controls', $this->slug ),
              'description'  => esc_html__( 'It specifies that audio controls should be displayed.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition' => array(
                'bkgAudioPageMob' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioMutedXs',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Muted', $this->slug ),
              'description'  => esc_html__( 'It specifies that the audio should be muted.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition' => array(
                'bkgAudioPageMob' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioAutoplayXs',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Autoplay', $this->slug ),
              'description'  => esc_html__( 'The audio will be played as soon as it\'s playable. IMPORTANT: you must activate muted option to let your audio start playing automatically (but muted). NOTE: Chromium browsers do not allow autoplay in most cases.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition'    => array(
                'bkgAudioPageMob' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioLoopXs',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Loop', $this->slug ),
              'description'  => esc_html__( 'It specifies that the audio will start over again, every time it is finished.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition'    => array(
                'bkgAudioPageMob' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioBtnIconXs',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Audio button icon', $this->slug ),
              'description'  => esc_html__( 'Audio button icon.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition'    => array(
                'bkgAudioPageMob' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioBtnSizeXs',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Audio button size', $this->slug ),
              'description' => esc_html__( 'Choose audio button size.', $this->slug ),
              'default'     => 'medium',
              'options'     => array(
                'small'  => esc_html__( 'small',  $this->slug ),
                'medium' => esc_html__( 'medium', $this->slug ),
                'large'  => esc_html__( 'large',  $this->slug ),
              ),
              'condition' => array(
                'bkgAudioPageMob' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioBtnBkgXs',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Audio button background', $this->slug ),
              'description' => esc_html__( 'Choose audio player button background.', $this->slug ),
              'default'     => 'color',
              'options'     => array(
                'color' => esc_html__( 'color', $this->slug ),
                'image' => esc_html__( 'image', $this->slug ),
              ),
              'condition' => array(
                'bkgAudioPageMob' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioBtnColorXs',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Background colour', $this->slug ),
              'description' => esc_html__( 'Choose audio button background colour.', $this->slug ),
              'default'     => '#9933cc',
              'condition'   => array(
                'bkgAudioPageMob'  => 'on',
                'bkgAudioBtnBkgXs' => 'color',
              ),
            )
          );
          $page->add_control(
            'bkgAudioBtnColorLightXs',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Background colour light', $this->slug ),
              'description' => esc_html__( 'Choose audio button background colour light.', $this->slug ),
              'default'     => '#bb64ea',
              'condition'   => array(
                'bkgAudioPageMob'  => 'on',
                'bkgAudioBtnBkgXs' => 'color',
              ),
            )
          );
          $page->add_control(
            'bkgAudioBtnUrlImageXs', 
            array(
              'type' => \Elementor\Controls_Manager::MEDIA,
              'label'=> esc_html__( 'Choose image', $this->slug ),
              'description' => esc_html__( 'Choose audio button background image.', $this->slug ),
              'dynamic' => array(
                'active' => true,
                'categories' => array(
                  \Elementor\Modules\DynamicTags\Module::MEDIA_CATEGORY,
                ),
              ),
              'default' => array(
                'url' => '',
              ),
              'media_type'  => 'image',
              'condition' => array(
                'bkgAudioPageMob'  => 'on',
                'bkgAudioBtnBkgXs' => 'image',
              ),
            )
          );
          $page->add_control(
            'bkgAudioPositionHzXs',
            array(
              'type'    => \Elementor\Controls_Manager::CHOOSE,
              'label'   => esc_html__( 'Horizontal position', $this->slug ),
              'description'  => esc_html__( 'Choose audio player horizontal position.', $this->slug ),
              'options' => array(
                'left' => array(
                  'title' => esc_html__( 'Left', $this->slug ),
                  'icon'  => 'eicon-h-align-left',
                ),
                'right' => array(
                  'title' => esc_html__( 'Right', $this->slug ),
                  'icon'  => 'eicon-h-align-right',
                ),
              ),
              'default'   => 'left',
              'toggle'    => false,
              'condition' => array(
                'bkgAudioPageMob' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioPositionHzValXs',
            array(
              'type'       => \Elementor\Controls_Manager::SLIDER,
              'label'      => esc_html__( 'Horizontal position value', $this->slug ),
              'description'  => esc_html__( 'Insert a value to apply to horizontal position (px, %, vw).', $this->slug ),
              'size_units' => array( 
                'px', 
                '%', 
                'vw', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
                '%' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
                'vw' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 25,
              ),
              'condition' => array(
                'bkgAudioPageMob' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioPositionVtXs',
            array(
              'type'    => \Elementor\Controls_Manager::CHOOSE,
              'label'   => esc_html__( 'Vertical position', $this->slug ),
              'description' => esc_html__( 'Choose audio player vertical position.', $this->slug ),
              'options' => array(
                'top' => array(
                  'title' => esc_html__( 'Top', $this->slug ),
                  'icon'  => 'eicon-v-align-top',
                ),
                'bottom' => array(
                  'title' => esc_html__( 'Bottom', $this->slug ),
                  'icon'  => 'eicon-v-align-bottom',
                ),
              ),
              'default'   => 'bottom',
              'toggle'    => false,
              'condition' => array(
                'bkgAudioPageMob' => 'on',
              ),
            )
          );
          $page->add_control(
            'bkgAudioPositionVtValXs',
            array(
              'type'       => \Elementor\Controls_Manager::SLIDER,
              'label'      => esc_html__( 'Vertical position value', $this->slug ),
              'description'  => esc_html__( 'Insert a value to apply to vertical position (px, %, vh).', $this->slug ),
              'size_units' => array( 
                'px', 
                '%', 
                'vh', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
                '%' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
                'vh' => array(
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 25,
              ),
              'condition' => array(
                'bkgAudioPageMob' => 'on',
              ),
            )
          );
          $page->end_controls_tab();
          $page->end_controls_tabs();
          $page->end_controls_section();
        }
        public function webyx_fep_splash_controls ( $page ) {
          $page->start_controls_section(
            'webyx_loading_splash_screen',
            array(
              'label'     => esc_html__( 'LOADING SPLASH SCREEN', $this->slug ),
              'tab'       => 'webyx-fe',
              'condition' => array(
                'webyx_enable' => 'on',
              ),
            )
          );
          $page->add_control(
            'ils',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Loading splash screen', $this->slug ),
              'description'  => esc_html__( 'Enable an initial loading splash screen.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control(
            'ilst',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Splash screen type', $this->slug ),
              'description' => esc_html__( 'Choose splash screen type.', $this->slug ),
              'default'     => 'default',
              'options'     => array(
                'default' => esc_html__( 'default', $this->slug ),
                'custom'  => esc_html__( 'custom', $this->slug ),
              ),
              'condition' => array(
                'ils' => 'on',
              ),
            )
          );
          $page->add_control(
            'ilsbc',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Splash screen background colour', $this->slug ),
              'description' => esc_html__( 'Choose splash screen background colour.', $this->slug ),
              'default'     => '#9933cc',
              'condition'   => array(
                'ils'  => 'on',
                'ilst' => 'default',
              ),
            )
          );
          $page->add_control(
            'ilssbc',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Spinner background colour', $this->slug ),
              'description' => esc_html__( 'Choose spinner background colour.', $this->slug ),
              'default'     => '#ffffff',
              'condition'   => array(
                'ils'  => 'on',
                'ilst' => 'default',
              ),
            )
          );
          $page->add_control(
            'ilscmt',
            array(
              'type'        => \Elementor\Controls_Manager::TEXT,
              'label'       => esc_html__( 'Initial loading message', $this->slug ),
              'description' => esc_html__( 'This text will be displayed on the splash screen.', $this->slug ),
              'default'     => esc_html__( '', $this->slug ),
              'placeholder' => esc_html__( 'initial message', $this->slug ),
              'condition'   => array(
                'ils'  => 'on',
                'ilst' => 'custom',
              ),
            )
          );
          $page->add_control(
            'ilscmtc',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Splash screen text colour', $this->slug ),
              'description' => esc_html__( 'Choose splash screen text colour.', $this->slug ),
              'default'     => '#000000',
              'condition'   => array(
                'ils'  => 'on',
                'ilst' => 'custom',
              ),
            )
          );
          $page->add_control(
            'ilscbc',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Background colour', $this->slug ),
              'description' => esc_html__( 'Choose splash screen background colour.', $this->slug ),
              'default'     => '#ffffff',
              'condition'   => array(
                'ils'  => 'on',
                'ilst' => 'custom',
              ),
            )
          );
          $page->add_control(
            'ilscbiurl', 
            array(
              'type' => \Elementor\Controls_Manager::MEDIA,
              'label'=> esc_html__( 'Choose image', $this->slug ),
              'description' => esc_html__( 'Choose splash screen background image.', $this->slug ),
              'dynamic' => array(
                'active' => true,
                'categories' => array(
                  \Elementor\Modules\DynamicTags\Module::MEDIA_CATEGORY,
                ),
              ),
              'default' => array(
                'url' => '',
              ),
              'media_type'  => 'image',
              'render_type' => 'none',
              'condition'   => array(
                'ils'  => 'on',
                'ilst' => 'custom',
              ),
            )
          );
          $page->add_control(
            'ilsctmen',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Enable time duration', $this->slug ),
              'description'  => esc_html__( 'Enable a predefined time duration for the loading splash screen to be shown.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition'   => array(
                'ils'  => 'on',
              ),
            )
          );
          $page->add_control(
            'ilscsi',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Always visible', $this->slug ),
              'description'  => esc_html__( 'The loading splash screen will be visible indefinitely. NOTE: use this option for developing reasons only, then disable it when you are satisfied with the result.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition'   => array(
                'ils'      => 'on',
                'ilsctmen' => 'on',
              ),
            )
          );
          $page->add_control(
            'ilsctm',
            array(
              'type'        => \Elementor\Controls_Manager::NUMBER,
              'label'       => esc_html__( 'Time duration', $this->slug ),
              'description' => esc_html__( 'Enter a value for the time duration (range from 1 to 10 seconds with a step of 0.1).', $this->slug ),
              'min'         => 1,
              'max'         => 10,
              'step'        => 0.1,
              'default'     => 1,
              'condition'   => array(
                'ils'      => 'on',
                'ilsctmen' => 'on',
                'ilscsi'   => '',
              ),
            )
          );
          $page->end_controls_section();
        }
        public function webyx_fep_custom_css_controls ( $page ) {
          $page->start_controls_section(
            'webyx_custom_css',
            array(
              'label'     => esc_html__( 'CUSTOM CSS', $this->slug ),
              'tab'       => 'webyx-fe',
              'condition' => array(
                'webyx_enable' => 'on',
              ),
            )
          );
          $page->add_control(
            'ccss',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Custom CSS', $this->slug ),
              'description'  => esc_html__( 'Open a pop-up window where you can enter your CSS code for the page.', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control(
            'ccssp',
            array(
              'type'        => \Elementor\Controls_Manager::CODE,
              'description' => esc_html__( 'Enter your CSS code for the page.', $this->slug ),
              'language'    => 'css',
              'rows'        => 20,
              'default'     => '',
              'condition'   => array(
                'ccss' => 'on',
              ),
            )
          );
          $page->end_controls_section();
        }
        public function webyx_fep_evhks_controls( $page ) {
          $page->start_controls_section(
            'webyx_event_hooks',
            array(
              'label'     => esc_html__( 'EVENT HOOKS', $this->slug ),
              'tab'       => 'webyx-fe',
              'condition' => array(
                'webyx_enable' => 'on',
              ),
            )
          );
          $page->add_control(
            'hkse',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Hooks', $this->slug ),
              'description'  => esc_html__( 'Here you can add your custom JS code on Webyx Sections events.', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control(
            'obl',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'On before leave', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'separator'    => 'before',
              'condition'    => array(
                'hkse' => 'on',
              ),
            )
          );
          $page->add_control(
            'pre_oblc',
            array(
              'type' => \Elementor\Controls_Manager::RAW_HTML,
              'raw' => esc_html__( 'This event is triggered before each time you are going directly to another Section. The JS code you will enter below will be executed inside the function.', $this->slug ),
              'content_classes' => 'elementor-control-field-description',
              'condition'       => array(
                'hkse'  => 'on',
                'obl' => 'on',
              ),
            )
          );
          $page->add_control(
            'pre_func_obsslc',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( 'function ( webyx ) {', $this->slug ),
              'content_classes' => 'elementor-control-field-description webyx-pp-code-func',
              'condition'       => array(
                'hkse' => 'on',
                'obl'  => 'on',
              ),
            )
          );
          $page->add_control(
            'oblc',
            array(
              'type'      => \Elementor\Controls_Manager::CODE,
              'language'  => 'javascript',
              'rows'      => 20,
              'default'   => '',
              'condition' => array(
                'hkse' => 'on',
                'obl'  => 'on',
              ),
            )
          );
          $page->add_control(
            'post_func_oblc',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( '}', $this->slug ),
              'content_classes' => 'elementor-control-field-description webyx-pp-code-func',
              'condition'       => array(
                'hkse' => 'on',
                'obl'  => 'on',
              ),
            )
          );
          $page->add_control(
            'oae',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'On after enter', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition'    => array(
                'hkse' => 'on',
              ),
            )
          );
          $page->add_control(
            'pre_oaec',
            array(
              'type' => \Elementor\Controls_Manager::RAW_HTML,
              'raw' => esc_html__( 'This event is triggered each time after going to another Section is completed. The JS code you will enter below will be executed inside the function.', $this->slug ),
              'content_classes' => 'elementor-control-field-description',
              'condition'       => array(
                'hkse' => 'on',
                'oae'  => 'on',
              ),
            )
          );
          $page->add_control(
            'pre_func_oaec',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( 'function ( webyx ) {', $this->slug ),
              'content_classes' => 'elementor-control-field-description webyx-pp-code-func',
              'condition'       => array(
                'hkse' => 'on',
                'oae'  => 'on',
              ),
            )
          );
          $page->add_control(
            'oaec',
            array(
              'type'      => \Elementor\Controls_Manager::CODE,
              'language'  => 'javascript',
              'rows'      => 20,
              'default'   => '',
              'condition' => array(
                'hkse' => 'on',
                'oae'  => 'on',
              ),
            )
          );
          $page->add_control(
            'post_func_oaec',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( '}', $this->slug ),
              'content_classes' => 'elementor-control-field-description webyx-pp-code-func',
              'condition'       => array(
                'hkse' => 'on',
                'oae'  => 'on',
              ),
            )
          );
          $page->add_control(
            'oblya',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'On before leave Y axis', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'separator'    => 'before',
              'condition' => array(
                'hkse' => 'on',
              ),
            )
          );
          $page->add_control(
            'pre_oblyac',
            array(
              'type' => \Elementor\Controls_Manager::RAW_HTML,
              'raw' => esc_html__( 'This event is triggered before each time you are going to another Section in Y axis. The JS code you will enter below will be executed inside the function.', $this->slug ),
              'content_classes' => 'elementor-control-field-description',
              'condition'   => array(
                'hkse'  => 'on',
                'oblya' => 'on',
              ),
            )
          );
          $page->add_control(
            'pre_func_oblyac',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( 'function ( iCurY, iTrgY,  iCurX, webyx ) {', $this->slug ),
              'content_classes' => 'elementor-control-field-description webyx-pp-code-func',
              'condition'       => array(
                'hkse'  => 'on',
                'oblya' => 'on',
              ),
            )
          );
          $page->add_control(
            'oblyac',
            array(
              'type'      => \Elementor\Controls_Manager::CODE,
              'language'  => 'javascript',
              'rows'      => 20,
              'default'   => '',
              'condition' => array(
                'hkse'  => 'on',
                'oblya' => 'on',
              ),
            )
          );
          $page->add_control(
            'post_func_obgtstrc',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( '}', $this->slug ),
              'content_classes' => 'elementor-control-field-description webyx-pp-code-func',
              'condition'       => array(
                'hkse'  => 'on',
                'oblya' => 'on',
              ),
            )
          );
          $page->add_control(
            'oaeya',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'On after enter Y axis', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition'    => array(
                'hkse' => 'on',
              ),
            )
          );
          $page->add_control(
            'pre_oaeyac',
            array(
              'type' => \Elementor\Controls_Manager::RAW_HTML,
              'raw' => esc_html__( 'This event is triggered each time after going to another Section is completed in Y axis. The JS code you will enter below will be executed inside the function.', $this->slug ),
              'content_classes' => 'elementor-control-field-description',
              'condition'   => array(
                'hkse'  => 'on',
                'oaeya' => 'on',
              ),
            )
          );
          $page->add_control(
            'pre_func_oaeyac',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( 'function ( iCurY, iPrevY, iPrevX, webyx ) {', $this->slug ),
              'content_classes' => 'elementor-control-field-description webyx-pp-code-func',
              'condition'       => array(
                'hkse'  => 'on',
                'oaeya' => 'on',
              ),
            )
          );
          $page->add_control(
            'oaeyac',
            array(
              'type'      => \Elementor\Controls_Manager::CODE,
              'language'  => 'javascript',
              'rows'      => 20,
              'default'   => '',
              'condition' => array(
                'hkse'  => 'on',
                'oaeya' => 'on',
              ),
            )
          );
          $page->add_control(
            'post_func_oaeyac',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( '}', $this->slug ),
              'content_classes' => 'elementor-control-field-description webyx-pp-code-func',
              'condition'       => array(
                'hkse'  => 'on',
                'oaeya' => 'on',
              ),
            )
          );
          $page->add_control(
            'oblxa',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'On before leave X axis', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition'    => array(
                'hkse' => 'on',
              ),
            )
          );
          $page->add_control(
            'pre_oblxac',
            array(
              'type' => \Elementor\Controls_Manager::RAW_HTML,
              'raw' => esc_html__( 'This event is triggered before each time you are going to another Section in X axis. The JS code you will enter below will be executed inside the function.', $this->slug ),
              'content_classes' => 'elementor-control-field-description',
              'condition'       => array(
                'hkse'  => 'on',
                'oblxa' => 'on',
              ),
            )
          );
          $page->add_control(
            'pre_func_oblxac',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( 'function ( iCurX, iTrgX, iCurY, webyx ) {', $this->slug ),
              'content_classes' => 'elementor-control-field-description webyx-pp-code-func',
              'condition'       => array(
                'hkse'  => 'on',
                'oblxa' => 'on',
              ),
            )
          );
          $page->add_control(
            'oblxac',
            array(
              'type'      => \Elementor\Controls_Manager::CODE,
              'language'  => 'javascript',
              'rows'      => 20,
              'default'   => '',
              'condition' => array(
                'hkse'  => 'on',
                'oblxa' => 'on',
              ),
            )
          );
          $page->add_control(
            'post_func_oblxac',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( '}', $this->slug ),
              'content_classes' => 'elementor-control-field-description webyx-pp-code-func',
              'condition'       => array(
                'hkse'  => 'on',
                'oblxa' => 'on',
              ),
            )
          );
          $page->add_control(
            'oaexa',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'On after enter X axis', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'condition' => array(
                'hkse' => 'on',
              ),
            )
          );
          $page->add_control(
            'pre_oaexac',
            array(
              'type' => \Elementor\Controls_Manager::RAW_HTML,
              'raw' => esc_html__( 'This event is triggered each time after going to another Section is completed in X axis. The JS code you will enter below will be executed inside the function.', $this->slug ),
              'content_classes' => 'elementor-control-field-description',
              'condition'       => array(
                'hkse'  => 'on',
                'oaexa' => 'on',
              ),
            )
          );
          $page->add_control(
            'pre_func_oaexac',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( 'function ( iCurX, iPrevX, iCurY, webyx ) {', $this->slug ),
              'content_classes' => 'elementor-control-field-description webyx-pp-code-func',
              'condition'       => array(
                'hkse'  => 'on',
                'oaexa' => 'on',
              ),
            )
          );
          $page->add_control(
            'oaexac',
            array(
              'type'      => \Elementor\Controls_Manager::CODE,
              'language'  => 'javascript',
              'rows'      => 20,
              'default'   => '',
              'condition' => array(
                'hkse'  => 'on',
                'oaexa' => 'on',
              ),
            )
          );
          $page->add_control(
            'post_func_oaexac',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( '}', $this->slug ),
              'content_classes' => 'elementor-control-field-description webyx-pp-code-func',
              'condition'       => array(
                'hkse'  => 'on',
                'oaexa' => 'on',
              ),
            )
          );

          $page->add_control(
            'oalw',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'On after load Webyx', $this->slug ),
              'label_on'     => esc_html__( 'on', $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'separator'    => 'before',
              'condition'    => array(
                'hkse' => 'on',
              ),
            )
          );
          $page->add_control(
            'pre_oalw',
            array(
              'type' => \Elementor\Controls_Manager::RAW_HTML,
              'raw' => esc_html__( 'This event is triggered after Webyx has been loaded. The JS code you will enter below will be executed inside the function.', $this->slug ),
              'content_classes' => 'elementor-control-field-description',
              'condition'       => array(
                'hkse'  => 'on',
                'oalw' => 'on',
              ),
            )
          );
          $page->add_control(
            'pre_func_oalw',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( 'function ( webyx ) {', $this->slug ),
              'content_classes' => 'elementor-control-field-description webyx-pp-code-func',
              'condition'       => array(
                'hkse' => 'on',
                'oalw'  => 'on',
              ),
            )
          );
          $page->add_control(
            'oalwc',
            array(
              'type'      => \Elementor\Controls_Manager::CODE,
              'language'  => 'javascript',
              'rows'      => 20,
              'default'   => '',
              'condition' => array(
                'hkse' => 'on',
                'oalw'  => 'on',
              ),
            )
          );
          $page->add_control(
            'post_func_oalwc',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( '}', $this->slug ),
              'content_classes' => 'elementor-control-field-description webyx-pp-code-func',
              'condition'       => array(
                'hkse' => 'on',
                'oalw'  => 'on',
              ),
            )
          );
          $page->end_controls_section();
        }
        public function webyx_fep_glb_controls ( $page ) {
          $page->start_controls_section(
            'webyx_global_settings',
            array(
              'label'     => esc_html__( 'GLOBAL SETTINGS', $this->slug ),
              'tab'       => 'webyx-fe',
              'condition' => array(
                'webyx_enable' => 'on',
              ),
            )
          );
          $page->add_control(
            'global_webyx_section_mq_enable',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Global media query breakpoint', $this->slug ),
              'description'  => esc_html__( 'Here you can set a global media query breakpoint for every option that has a settable media query. IMPORTANT: this value overwrites every other present in every media query.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
            )
          );
          $page->add_control(
            'global_webyx_section_mq_xs',
            array(
              'type'       => \Elementor\Controls_Manager::SLIDER,
              'label'      => esc_html__( 'Media query breakpoint', $this->slug ),
              'description'  => esc_html__( 'Enter a value that defines the threshold for switching from desktop to mobile mode in pixels (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
              'size_units' => array( 
                'px', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 760,
              ),
              'condition' => array(
                'global_webyx_section_mq_enable' => 'on',
              ),
            )
          );
          $page->end_controls_section();
        }
        public function webyx_fep_admin_document_setting_controls ( \Elementor\Core\DocumentTypes\Page $page ) {
          if ( isset( $page ) && $page->get_id() > '' ) {
            $post_type = get_post_type( $page->get_id() );
            if ( 'page' == $post_type || 'revision' == $post_type ) {
              \Elementor\Controls_Manager::add_tab(
                'webyx-fe',
                __( 'WEBYX FE PRO', $this->slug )
              );
              $this->webyx_fep_enabled_controls( $page );
              $this->webyx_fep_tmp_design_controls( $page );
              $this->webyx_fep_view_design_controls( $page );
              $this->webyx_fep_nav_design_controls( $page );
              $this->webyx_fep_nav_easing_controls( $page );
              $this->webyx_fep_nav_arrows_controls( $page );
              $this->webyx_fep_nav_bullets_controls( $page );
              $this->webyx_fep_nav_mw_controls( $page );
              $this->webyx_fep_nav_kb_controls( $page );
              $this->webyx_fep_fsb_controls( $page );
              $this->webyx_fep_mob_controls( $page );
              $this->webyx_fep_scrlb_controls( $page );
              $this->webyx_fep_bkga_controls( $page );
              $this->webyx_fep_splash_controls( $page );
              $this->webyx_fep_custom_css_controls( $page );
              $this->webyx_fep_evhks_controls( $page );
              $this->webyx_fep_glb_controls( $page );
            }
          }
        }
        public function webyx_fep_admin_section_options () {
          $is_container_active = $this->webyx_fep_is_container_active();
          add_action( 
            $is_container_active ? 'elementor/element/container/section_layout_container/before_section_start' : 'elementor/element/section/section_layout/before_section_start', 
            array( 
              $this, 
              'webyx_fep_admin_section_options_controls'
            ), 
            10, 
            2 
          );
        }
        public function webyx_fep_admin_section_options_controls ( \Elementor\Element_Base $element, $args ) {
          global $post;
          $ps = get_post_meta( $post->ID, '_elementor_page_settings', true );
          $webyx_fep_is_enable = $this->webyx_fep_is_enable( $ps );
          $is_container_active = $this->webyx_fep_is_container_active();
          $el_root = $is_container_active ? 'Container' : 'Section';
          if ( $webyx_fep_is_enable ) {
            $element->start_controls_section(
              'webyx-section',
              array(
                'tab'           => \Elementor\Controls_Manager::TAB_LAYOUT,
                'label'         => esc_html__( 'Webyx FE PRO ' . $el_root, $this->slug ),
                'hide_in_inner' => true,
              )
            );
            $element->add_control(
              'webyx_section_enable',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( 'Enable Webyx ' . $el_root, $this->slug ),
                'description'  => esc_html__( 'Enable to set this ' . $el_root . ' as a Webyx ' . $el_root . ' that will be wrapped and managed by the plugin. It will be considered a Webyx ' . $el_root . '. IMPORTANT: for Webyx to function properly, you must keep the root element active. In particular, pay attention if you are using Containers: those nested inside the root element MUST necessarily have this option deactivated, otherwise the system will not work correctly.', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'render_type'  => 'template',
                'default'      => '',
                'prefix_class' => 'webyx-section-',
              )
            );
            $element->add_control(
              'webyx_section_is_inner',
              array(
                'label' => esc_html__( 'View', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => 'on',
                'condition'   => array(
                  'webyx_section_enable' => '',
                ),
              )
            );
            $element->add_control(
              'webyx_section_hide_in_frontend',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( 'Hide ' . $el_root, $this->slug ),
                'description'  => esc_html__( 'Enable to set this ' . $el_root . ' NOT visible in the actual website page.', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => '',
                'prefix_class' => 'webyx-section-hide-',
              )
            );
            $element->add_control(
              'webyx_section_type',
              array(
                'type'        => \Elementor\Controls_Manager::SELECT,
                'label'       => esc_html__( $el_root . ' type', $this->slug ),
                'description' => esc_html__( 'A HEADER ' . $el_root . ' is a place where you can create your website header (menu, logo, etc) directly with Elementor widgets. This only works with the Full Page design type option. ' . 'FRONT ' . $el_root . 's are the first ones shown in the rows. SIDE ' . $el_root . 's are positioned laterally to the FRONT ones. All are shown in order from top to bottom. IMPORTANT: the very first ' . $el_root . ' of your website MUST be a Front ' . $el_root . ' or a HEADER ' . $el_root . ' followed by a Front ' . $el_root . '.', $this->slug ),
                'default'     => 'front',
                'options'     => array(
                  'front'  => esc_html__( 'front',  $this->slug ),
                  'side'   => esc_html__( 'side',   $this->slug ),
                  'header' => esc_html__( 'header', $this->slug ),
                ),
                'prefix_class' => 'webyx-section-',
                'classes'      => 'elementor-control-direction-ltr',
                'condition'    => array(
                  'webyx_section_enable' => 'on',
                ),
              )
            );
            $element->add_control(
              'webyx_section_name',
              array(
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label'       => esc_html__( $el_root . ' name', $this->slug ),
                'description' => esc_html__( 'Insert ' . $el_root . ' name. IMPORTANT: you should give different titles for each ' . $el_root . ' otherwise some features may have problems.', $this->slug ),
                'default'     => esc_html__( $el_root, $this->slug ),
                'placeholder' => esc_html__( $el_root . ' name', $this->slug ),
                'condition'   => array(
                  'webyx_section_enable' => 'on',
                  'webyx_section_type!'  => 'header',
                ),
                'selectors' => array(
                  '{{WRAPPER}}.webyx-section-name-editor-on .webyx-header::before' => 'content: "{{VALUE}}"'
                )
              )
            );
            $element->add_control(
              'webyx_section_anchor_name',
              array(
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label'       => esc_html__( 'Anchor name (#)', $this->slug ),
                'description' => esc_html__( 'Insert ' . $el_root . ' anchor name. IMPORTANT: you should give different anchor names (white spaces will be replaced with "-" automatically) for each ' . $el_root . ' otherwise some features may have problems. Remember to enable the Anchors option in View Design tab.', $this->slug ),
                'default'     => esc_html__( '', $this->slug ),
                'placeholder' => esc_html__( 'anchor-name', $this->slug ),
                'condition'   => array(
                  'webyx_section_enable' => 'on',
                  'webyx_section_type!'  => 'header',
                ),
                'render_type' => 'none',
              )
            );
            $element->add_control(
              'webyx_section_tag_name',
              array(
                'type'        => \Elementor\Controls_Manager::SELECT,
                'label'       => esc_html__( $el_root . ' tag type', $this->slug ),
                'description' => esc_html__( 'Select HTML tag name. This parameter changes the ' . $el_root  . ' HTML tag to the specified tag.', $this->slug ),
                'default'     => 'div',
                'options'     => array(
                  'div'     => esc_html__( 'div',     $this->slug ),
                  'section' => esc_html__( 'section', $this->slug ),
                  'article' => esc_html__( 'article', $this->slug ),
                  'aside'   => esc_html__( 'aside',   $this->slug ),
                  'header'  => esc_html__( 'header',  $this->slug ),
                  'footer'  => esc_html__( 'footer',  $this->slug ),
                  'ul'      => esc_html__( 'ul',      $this->slug ),
                  'ol'      => esc_html__( 'ol',      $this->slug ),
                  'li'      => esc_html__( 'li',      $this->slug ),
                ),
                'render_type' => 'none',
                'condition'   => array(
                  'webyx_section_enable' => 'on',
                ),
              )
            );
            $element->add_control(
              'webyx_section_group_name_enable',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( $el_root . ' group', $this->slug ),
                'description'  => esc_html__( 'Enable ' . $el_root . ' group within the Webyx single page menu option.', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => '',
                'render_type'  => 'none',
                'condition'   => array(
                  'webyx_section_enable' => 'on',
                  'webyx_section_type'   => 'front',
                  'webyx_section_type!'  => 'header',
                ),
              )
            );
            $element->add_control(
              'webyx_section_group_name',
              array(
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label'       => esc_html__( $el_root . ' group name', $this->slug ),
                'description' => esc_html__( 'Insert ' . $el_root . ' group name. You should give different group name for each ' . $el_root . ' otherwise some features may have problems. IMPORTANT: this name will be used exclusively to indicate the parent level of a group of ' . $el_root .  's' . ' within the Webyx single page menu option.', $this->slug ),
                'default'     => esc_html__( '', $this->slug ),
                'placeholder' => esc_html__( '', $this->slug ),
                'condition'   => array(
                  'webyx_section_enable'            => 'on',
                  'webyx_section_type'              => 'front',
                  'webyx_section_type!'             => 'header',
                  'webyx_section_group_name_enable' => 'on',
                ),
                'render_type' => 'none',
              )
            );
            $element->add_control(
              'webyx_section_mq_xs',
              array(
                'type'        => \Elementor\Controls_Manager::SLIDER,
                'label'       => esc_html__( $el_root . ' Media queries', $this->slug ),
                'description' => esc_html__( 'Enter a value that defines the threshold for switching from desktop to mobile mode in pixels (range from 0 to 5000 pixels with a step of 1). This option is used for margins and paddings in the ' . $el_root . ' Wrapper Content option and backgrounds of the ' . $el_root . 's (i.e. you can have a background colour in Desktop mode and an image in Mobile mode).', $this->slug ),
                'size_units' => array( 
                  'px', 
                ),
                'range' => array(
                  'px' => array(
                    'min'  => 0,
                    'max'  => 5000,
                    'step' => 1,
                  ),
                ),
                'default' => array(
                  'unit' => 'px',
                  'size' => 760,
                ),
                'separator'   => 'before',
                'render_type' => 'none',
                'condition'   => array(
                  'webyx_section_enable' => 'on',
                  'webyx_section_type!'  => 'header',
                ),
              )
            );
            $element->add_control(
              'webyx_section_continuous_carousel',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( 'Continuous horizontal', $this->slug ),
                'description'  => esc_html__( 'Enable the direct horizontal passage from the first to the last ' . $el_root . ' and vice versa. For FRONT ' . $el_root . 's ONLY.', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => '',
                'separator'    => 'before',
                'condition'    => array(
                  'webyx_section_enable' => 'on',
                  'webyx_section_type'   => 'front',
                  'webyx_section_type!'  => 'header',
                ),
                'prefix_class' => 'webyx-continuous-',
              )
            );
            $element->add_control(
              'webyx_section_scrollable_hz',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( 'Horizontal scrolling', $this->slug ),
                'description'  => esc_html__( 'The ' . $el_root . ' content navigation method change from vertical to horizontal scrolling (right to left and vice versa) to reveal content from the sides of the viewport. IMPORTANT: you can activate this option on the FRONT Section ONLY. With this option all following side ' . $el_root . 's are put inside a unique ' . $el_root . ' and positioned side by side one with the other.', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => '',
                'separator'    => 'before',
                'condition'    => array(
                  'webyx_section_enable' => 'on',
                  'webyx_section_type'   => 'front',
                  'webyx_section_type!'  => 'header',
                ),
                'prefix_class' => 'webyx-scrollable-hz-',
              )
            );
            $element->add_control(
              'webyx_section_scrollable',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( 'Scrolling content', $this->slug ),
                'description'  => esc_html__( 'Viewport exceeding ' . $el_root . ' content will be displayed through scrolling.', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => '',
                'separator'    => 'before',
                'condition'    => array(
                  'webyx_section_enable' => 'on',
                  'webyx_section_type!'  => 'header',
                ),
                'prefix_class' => 'webyx-scrollable-',
              )
            );
            $element->add_control(
              'webyx_section_cont_pos_enable',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( $el_root . ' content management', $this->slug ),
                'description'  => esc_html__( 'Enable content position management in the current ' . $el_root . '. If you are using Containers place the content using their properties, and keep this check disabled.', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => '',
                'separator'    => 'before',
                'condition'    => array(
                  'webyx_section_enable' => 'on',
                  'webyx_section_type!'  => 'header',
                ),
                'prefix_class' => 'webyx-content-position-',
              )
            );
            $element->add_control(
              'webyx_section_cont_pos',
              array(
                'type'        => \Elementor\Controls_Manager::SELECT,
                'label'       => esc_html__( 'Content position', $this->slug ),
                'description' => esc_html__( 'Select general content position in the current ' . $el_root . '.', $this->slug ),
                'default'     => 'middle',
                'options'     => array(
                  'top'    => esc_html__( 'top',    $this->slug ),
                  'middle' => esc_html__( 'middle', $this->slug ),
                  'bottom' => esc_html__( 'bottom', $this->slug ),
                ),
                'condition' => array(
                  'webyx_section_enable'          => 'on',
                  'webyx_section_cont_pos_enable' => 'on',
                  'webyx_section_type!'           => 'header',
                ),
                'prefix_class' => 'webyx-content-position-on-',
              )
            );
            $element->add_control(
              'webyx_section_wrapper_cnt',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( 'Wrapper content', $this->slug ),
                'description'  => esc_html__( 'Enable element wrapper for the ' . $el_root . '\'s content. If you are using Containers, uses a container itself as a collector.', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => '',
                'separator'    => 'before',
                'condition'    => array(
                  'webyx_section_enable' => 'on',
                  'webyx_section_type!'  => 'header',
                ),
                'prefix_class' => 'webyx-content-wrapper-',
              )
            );
            $element->add_control(
              'webyx_section_wrapper_cnt_classes',
              array(
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label'       => esc_html__( 'Wrapper CSS class(es)', $this->slug ),
                'description' => esc_html__( 'Separate multiple classes with spaces.', $this->slug ),
                'default'     => esc_html__( '', $this->slug ),
                'placeholder' => esc_html__( 'class name', $this->slug ),
                'condition'   => array(
                  'webyx_section_enable'      => 'on',
                  'webyx_section_wrapper_cnt' => 'on',
                  'webyx_section_type!'       => 'header',
                ),
                'render_type' => 'none',
              )
            );
            $element->start_controls_tabs(
              'webyx_section_wrapper_cnt_tabs',
              array(
                'condition' => array(
                  'webyx_section_enable'      => 'on',
                  'webyx_section_wrapper_cnt' => 'on',
                  'webyx_section_type!'       => 'header',
                ),
              )
            );
            $element->start_controls_tab(
              'webyx_section_wrapper_cnt_desktop_tabs',
              array(
                'label' => esc_html__( 'Desktop', $this->slug ),
              )
            );
            $element->add_control(
              'webyx_section_wrapper_cnt_margin_enable_dsk',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( 'Wrapper margin', $this->slug ),
                'description'  => esc_html__( 'Enable wrapper margin for the ' . $el_root  . '\'s content.', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => '',
                'render_type'  => 'none',
                'condition'    => array(
                  'webyx_section_enable'      => 'on',
                  'webyx_section_wrapper_cnt' => 'on',
                ),
              )
            );
            $element->add_control(
              'webyx_section_wrapper_cnt_margin_dsk',
              array(
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'label'      => esc_html__( 'Wrapper margin values', $this->slug ),
                'description'  => esc_html__( 'Insert a value to apply a margin (px, %, vw, vh).', $this->slug ),
                'size_units' => array( 
                  'px', 
                  '%',
                  'vw',
                  'vh', 
                ),
                'default'    => array(
                  'top'      => 0,
                  'right'    => 0,
                  'bottom'   => 0,
                  'left'     => 0,
                  'unit'     => 'px',
                  'isLinked' => '',
                ),
                'render_type'  => 'none',
                'condition' => array(
                  'webyx_section_enable'                        => 'on',
                  'webyx_section_wrapper_cnt'                   => 'on',
                  'webyx_section_wrapper_cnt_margin_enable_dsk' => 'on',
                ),
              )
            );
            $element->add_control(
              'webyx_section_wrapper_cnt_padding_enable_dsk',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( 'Wrapper padding', $this->slug ),
                'description'  => esc_html__( 'Enable wrapper padding for the ' . $el_root . '\'s content.', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => '',
                'render_type'  => 'none',
                'condition'    => array(
                  'webyx_section_enable'      => 'on',
                  'webyx_section_wrapper_cnt' => 'on',
                ),
              )
            );
            $element->add_control(
              'webyx_section_wrapper_cnt_padding_dsk',
              array(
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'label'      => esc_html__( 'Wrapper padding values', $this->slug ),
                'description'  => esc_html__( 'Insert a value to apply a padding (px, %, vw, vh).', $this->slug ),
                'size_units' => array( 
                  'px', 
                  '%',
                  'vw',
                  'vh', 
                ),
                'default'    => array(
                  'top'      => 0,
                  'right'    => 0,
                  'bottom'   => 0,
                  'left'     => 0,
                  'unit'     => 'px',
                  'isLinked' => '',
                ),
                'render_type' => 'none',
                'condition' => array(
                  'webyx_section_enable'                         => 'on',
                  'webyx_section_wrapper_cnt'                    => 'on',
                  'webyx_section_wrapper_cnt_padding_enable_dsk' => 'on',
                ),
              )
            );
            $element->end_controls_tab();
            $element->start_controls_tab(
              'webyx_section_wrapper_cnt_mobile_tabs',
              array(
                'label' => esc_html__( 'Mobile', $this->slug ),
              )
            );
            $element->add_control(
              'webyx_section_wrapper_cnt_margin_enable_mob',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( 'Wrapper margin', $this->slug ),
                'description'  => esc_html__( 'Enable wrapper margin for the ' . $el_root . '\'s content.', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => '',
                'render_type'  => 'none',
                'condition'    => array(
                  'webyx_section_enable'      => 'on',
                  'webyx_section_wrapper_cnt' => 'on',
                ),
              )
            );
            $element->add_control(
              'webyx_section_wrapper_cnt_margin_mob',
              array(
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'label'      => esc_html__( 'Wrapper margin values', $this->slug ),
                'description'  => esc_html__( 'Insert a value to apply a margin (px, %, vw, vh).', $this->slug ),
                'size_units' => array( 
                  'px', 
                  '%',
                  'vw',
                  'vh', 
                ),
                'default'    => array(
                  'top'      => 0,
                  'right'    => 0,
                  'bottom'   => 0,
                  'left'     => 0,
                  'unit'     => 'px',
                  'isLinked' => '',
                ),
                'render_type' => 'none',
                'condition' => array(
                  'webyx_section_enable'                        => 'on',
                  'webyx_section_wrapper_cnt'                   => 'on',
                  'webyx_section_wrapper_cnt_margin_enable_mob' => 'on',
                ),
              )
            );
            $element->add_control(
              'webyx_section_wrapper_cnt_padding_enable_mob',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( 'Wrapper padding', $this->slug ),
                'description'  => esc_html__( 'Enable wrapper padding for the ' . $el_root . '\'s content.', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => '',
                'render_type'  => 'none',
                'condition'    => array(
                  'webyx_section_enable'      => 'on',
                  'webyx_section_wrapper_cnt' => 'on',
                ),
              )
            );
            $element->add_control(
              'webyx_section_wrapper_cnt_padding_mob',
              array(
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'label'      => esc_html__( 'Wrapper padding values', $this->slug ),
                'description'  => esc_html__( 'Insert a value to apply a padding (px, %, vw, vh).', $this->slug ),
                'size_units' => array( 
                  'px', 
                  '%',
                  'vw',
                  'vh', 
                ),
                'default'    => array(
                  'top'      => 0,
                  'right'    => 0,
                  'bottom'   => 0,
                  'left'     => 0,
                  'unit'     => 'px',
                  'isLinked' => '',
                ),
                'render_type' => 'none',
                'condition' => array(
                  'webyx_section_enable'                         => 'on',
                  'webyx_section_wrapper_cnt'                    => 'on',
                  'webyx_section_wrapper_cnt_padding_enable_mob' => 'on',
                ),
              )
            );
            $element->end_controls_tab();
            $element->end_controls_tabs();
            $element->add_control(
              'webyx_section_background',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( 'Background', $this->slug ),
                'description'  => esc_html__( 'Enable ' . $el_root . ' background.', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => '',
                'separator'    => 'before',
                'condition'    => array(
                  'webyx_section_enable' => 'on',
                  'webyx_section_type!'  => 'header',
                ),
                'prefix_class' => 'webyx-section-background-',
              )
            );
            $element->start_controls_tabs(
              'webyx_section_background_tabs',
              array(
                'condition' => array(
                  'webyx_section_enable'     => 'on',
                  'webyx_section_background' => 'on',
                  'webyx_section_type!'      => 'header',
                ),
              )
            );
            $element->start_controls_tab(
              'webyx_section_background_desktop_tabs',
              array(
                'label' => esc_html__( 'Desktop', $this->slug ),
              )
            );
            $element->add_control(
              'webyx_section_background_dsk',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( 'Background', $this->slug ),
                'description'  => esc_html__( 'Enable desktop ' . $el_root . ' background.', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => '',
                'condition'    => array(
                  'webyx_section_enable' => 'on',
                ),
                'prefix_class' => 'webyx-section-background-dsk-',
              )
            );
            $element->add_control(
              'webyx_section_foreground_object',
              array(
                'type'        => \Elementor\Controls_Manager::SELECT,
                'label'       => esc_html__( 'Foreground object', $this->slug ),
                'description' => esc_html__( 'Select foreground object. Through the Foreground Object it is possible to keep background settings set at the same time, guaranteeing the possibility of being able to switch from one to the other quickly. In the Free version, on the other hand, background image prevails over background colour.', $this->slug ),
                'default'     => 'color',
                'options'     => array(
                  'color' => esc_html__( 'colour', $this->slug ),
                  'image' => esc_html__( 'image',  $this->slug ),
                  'video' => esc_html__( 'video',  $this->slug ),
                ),
                'condition' => array(
                  'webyx_section_enable'         => 'on',
                  'webyx_section_background'     => 'on',
                  'webyx_section_background_dsk' => 'on',
                ),
                'prefix_class' => 'webyx-section-foreground-object-',
              )
            );
            $element->add_control(
              'webyx_section_background_colour',
              array(
                'type'        => \Elementor\Controls_Manager::COLOR,
                'label'       => esc_html__( 'Background colour', $this->slug ),
                'description' => esc_html__( 'Choose ' . $el_root . ' background colour.', $this->slug ),
                'default'     => '#ffffff',
                'condition'   => array(
                  'webyx_section_background_dsk'    => 'on',
                  'webyx_section_foreground_object' => 'color',
                ),
                'selectors' => array(
                  '{{WRAPPER}} .webyx-background-overlay-bkg-color' => '--section-color-dsk: {{VALUE}};',
                ),
              )
            );
            $element->add_control(
              'webyx_section_background_image', 
              array(
                'type' => \Elementor\Controls_Manager::MEDIA,
                'label'=> esc_html__( 'Choose image', $this->slug ),
                'description' => esc_html__( 'Choose ' . $el_root . ' background image.', $this->slug ),
                'dynamic' => array(
                  'active'     => true,
                  'categories' => array(
                    \Elementor\Modules\DynamicTags\Module::MEDIA_CATEGORY,
                  ),
                ),
                'default' => array(
                  'url' => '',
                ),
                'media_type' => 'image',
                'condition'  => array(
                  'webyx_section_background_dsk'    => 'on',
                  'webyx_section_foreground_object' => 'image',
                ),
                'selectors' => array(
                  '{{WRAPPER}} .webyx-background-overlay-bkg-img' => '--section-image-url: url({{URL}});',
                ),
              )
            );
            $element->add_control(
              'webyx_section_background_image_size',
              array(
                'type'        => \Elementor\Controls_Manager::SELECT,
                'label'       => esc_html__( 'Image size', $this->slug ),
                'description' => esc_html__( 'Select image size.', $this->slug ),
                'default'     => 'cover',
                'options'     => array(
                  'auto'    => esc_html__( 'auto',    $this->slug ),
                  'cover'   => esc_html__( 'cover',   $this->slug ),
                  'contain' => esc_html__( 'contain', $this->slug ),
                ),
                'condition'   => array(
                  'webyx_section_background_dsk'    => 'on',
                  'webyx_section_foreground_object' => 'image',
                ),
                'selectors' => array(
                  '{{WRAPPER}} .webyx-background-overlay-bkg-img' => '--section-image-background-size: {{VALUE}};',
                ),
              )
            );
            $element->add_control(
              'webyx_section_background_image_position',
              array(
                'type'        => \Elementor\Controls_Manager::SELECT,
                'label'       => esc_html__( 'Image position', $this->slug ),
                'description' => esc_html__( 'Select image position.', $this->slug ),
                'default'     => 'center center',
                'options'     => array(
                  'left top'      => esc_html__( 'left top',      $this->slug ),
                  'left center'   => esc_html__( 'left center',   $this->slug ),
                  'left bottom'   => esc_html__( 'left bottom',   $this->slug ),
                  'right top'     => esc_html__( 'right top',     $this->slug ),
                  'right center'  => esc_html__( 'right center',  $this->slug ),
                  'right bottom'  => esc_html__( 'right bottom',  $this->slug ),
                  'center top'    => esc_html__( 'center top',    $this->slug ),
                  'center center' => esc_html__( 'center center', $this->slug ),
                  'center bottom' => esc_html__( 'center bottom', $this->slug ),
                ),
                'condition'   => array(
                  'webyx_section_background_dsk'    => 'on',
                  'webyx_section_foreground_object' => 'image',
                ),
                'selectors' => array(
                  '{{WRAPPER}} .webyx-background-overlay-bkg-img' => '--section-image-background-position: {{VALUE}};',
                ),
              )
            );
            $element->add_control(
              'webyx_section_background_image_repeat',
              array(
                'type'        => \Elementor\Controls_Manager::SELECT,
                'label'       => esc_html__( 'Image repeat', $this->slug ),
                'description' => esc_html__( 'Select image repeat.', $this->slug ),
                'default'     => 'no-repeat',
                'options'     => array(
                  'repeat'    => esc_html__( 'repeat',    $this->slug ),
                  'no-repeat' => esc_html__( 'no-repeat', $this->slug ),
                ),
                'condition'   => array(
                  'webyx_section_background_dsk'    => 'on',
                  'webyx_section_foreground_object' => 'image',
                ),
                'selectors' => array(
                  '{{WRAPPER}} .webyx-background-overlay-bkg-img' => '--section-image-background-repeat: {{VALUE}};',
                ),
              )
            );
            $element->add_control(
              'webyx_section_background_image_attachment',
              array(
                'type'        => \Elementor\Controls_Manager::SELECT,
                'label'       => esc_html__( 'Image attachment', $this->slug ),
                'description' => esc_html__( 'Select image attachment.', $this->slug ),
                'default'     => 'scroll',
                'options'     => array(
                  'scroll' => esc_html__( 'scroll', $this->slug ),
                  'fixed'  => esc_html__( 'fixed',  $this->slug ),
                ),
                'condition'   => array(
                  'webyx_section_background_dsk'    => 'on',
                  'webyx_section_foreground_object' => 'image', 
                ),
                'selectors' => array(
                  '{{WRAPPER}} .webyx-background-overlay-bkg-img' => '--section-image-background-attachment: {{VALUE}};',
                ),
              )
            );
            $element->add_control(
              'webyx_section_background_video', 
              array(
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'label'   => esc_html__( 'Choose video', $this->slug ),
                'description' => esc_html__( 'Choose ' . $el_root . ' background video.', $this->slug ),
                'dynamic' => array(
                  'active' => true,
                  'categories' => array(
                    \Elementor\Modules\DynamicTags\Module::MEDIA_CATEGORY,
                  ),
                ),
                'default' => array(
                  'url' => '',
                ),
                'media_type' => 'video',
                'condition'  => array(
                  'webyx_section_background_dsk'    => 'on',
                  'webyx_section_foreground_object' => 'video',
                ),
              )
            );
            $element->add_control(
              'webyx_section_background_video_poster_enable',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( 'Enable poster image', $this->slug ),
                'description' => esc_html__( 'Enable ' . $el_root . ' poster image.', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => '',
                'condition'    => array(
                  'webyx_section_background_dsk'    => 'on',
                  'webyx_section_foreground_object' => 'video',
                ),
                'render_type'  => 'none',
              )
            );
            $element->add_control(
              'webyx_section_background_video_poster', 
              array(
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'label'   => esc_html__( 'Choose poster image', $this->slug ),
                'description' => esc_html__( 'Choose ' . $el_root . ' poster image.', $this->slug ),
                'dynamic' => array(
                  'active' => true,
                  'categories' => array(
                    \Elementor\Modules\DynamicTags\Module::MEDIA_CATEGORY,
                  ),
                ),
                'media_type' => 'image',
                'default'    => array(
                  'url' => '',
                ),
                'condition' => array(
                  'webyx_section_background_dsk'                 => 'on',
                  'webyx_section_foreground_object'              => 'video',
                  'webyx_section_background_video_poster_enable' => 'on',
                ),
                'render_type' => 'none',
              )
            );
            $element->add_control(
              'webyx_section_background_video_preload',
              array(
                'type'        => \Elementor\Controls_Manager::SELECT,
                'label'       => esc_html__( 'Video preload', $this->slug ),
                'description' => esc_html__( 'It specifies that the browser should or should not load the entire video when the page loads.', $this->slug ),
                'default'     => 'auto',
                'options'     => array(
                  'none'     => esc_html__( 'none',     $this->slug ),
                  'auto'     => esc_html__( 'auto',     $this->slug ),
                  'metadata' => esc_html__( 'metadata', $this->slug ),
                ),
                'condition'   => array(
                  'webyx_section_background_dsk'    => 'on',
                  'webyx_section_foreground_object' => 'video',
                ),
                'render_type' => 'none',
              )
            );
            $element->add_control(
              'webyx_section_background_video_controls',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( 'Video controls', $this->slug ),
                'description'  => esc_html__( 'It specifies that video controls should be displayed.', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => '',
                'condition'    => array(
                  'webyx_section_background_dsk'    => 'on',
                  'webyx_section_foreground_object' => 'video',
                ),
                'render_type' => 'none',
              )
            );
            $element->add_control(
              'webyx_section_background_video_muted',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( 'Video muted', $this->slug ),
                'description'  => esc_html__( 'It specifies that the audio output of the video should be muted.', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => 'on',
                'condition'    => array(
                  'webyx_section_background_dsk'    => 'on',
                  'webyx_section_foreground_object' => 'video',
                ),
                'render_type'  => 'none',
              )
            );
            $element->add_control(
              'webyx_section_background_video_autoplay',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( 'Video autoplay', $this->slug ),
                'description'  => esc_html__( 'The video will be played as soon as it\'s playable. IMPORTANT: you must activate muted option to let your video start playing automatically (but muted).', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => '',
                'condition'    => array(
                  'webyx_section_background_dsk'    => 'on',
                  'webyx_section_foreground_object' => 'video',
                ),
                'render_type' => 'none',
              )
            );
            $element->add_control(
              'webyx_section_background_video_loop',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( 'Video loop', $this->slug ),
                'description'  => esc_html__( 'It specifies that the video will start over again, every time it is finished.', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => '',
                'condition'    => array(
                  'webyx_section_background_dsk'    => 'on',
                  'webyx_section_foreground_object' => 'video',
                ),
                'render_type' => 'none',
              )
            );
            $element->end_controls_tab();
            $element->start_controls_tab(
              'webyx_section_background_mobile_tabs',
              array(
                'label' => esc_html__( 'Mobile', $this->slug ),
              )
            );
            $element->add_control(
              'webyx_section_background_mob',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( 'Background', $this->slug ),
                'description'  => esc_html__( 'Enable mobile ' . $el_root . ' background.', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => '',
                'condition'    => array(
                  'webyx_section_enable' => 'on',
                ),
                'prefix_class' => 'webyx-section-background-mob-',
              )
            );
            $element->add_control(
              'webyx_section_foreground_object_mob',
              array(
                'type'        => \Elementor\Controls_Manager::SELECT,
                'label'       => esc_html__( 'Foreground object', $this->slug ),
                'description' => esc_html__( 'Select foreground object. Through the Foreground Object it is possible to keep background settings set at the same time, guaranteeing the possibility of being able to switch from one to the other quickly. In the Free version, on the other hand, background image prevails over background colour.', $this->slug ),
                'default'     => 'color',
                'options'     => array(
                  'color' => esc_html__( 'color', $this->slug ),
                  'image' => esc_html__( 'image', $this->slug ),
                  'video' => esc_html__( 'video', $this->slug ),
                ),
                'condition' => array(
                  'webyx_section_enable'         => 'on',
                  'webyx_section_background'     => 'on',
                  'webyx_section_background_mob' => 'on',
                ),
                'prefix_class' => 'webyx-section-foreground-object-mob-',
              )
            );
            $element->add_control(
              'webyx_section_background_colour_mob',
              array(
                'type'        => \Elementor\Controls_Manager::COLOR,
                'label'       => esc_html__( 'Background colour', $this->slug ),
                'description' => esc_html__( 'Choose ' . $el_root . ' background colour.', $this->slug ),
                'default'     => '#ffffff',
                'condition'   => array(
                  'webyx_section_background_mob'        => 'on',
                  'webyx_section_foreground_object_mob' => 'color',
                ),
                'selectors' => [
                  '{{WRAPPER}} .webyx-background-overlay-bkg-color' => '--section-color-mob: {{VALUE}};',
                ],
              )
            );
            $element->add_control(
              'webyx_section_background_image_mob', 
              array(
                'type' => \Elementor\Controls_Manager::MEDIA,
                'label'=> esc_html__( 'Choose image', $this->slug ),
                'description' => esc_html__( 'Choose ' . $el_root . ' background image.', $this->slug ),
                'dynamic' => array(
                  'active'     => true,
                  'categories' => array(
                    \Elementor\Modules\DynamicTags\Module::MEDIA_CATEGORY,
                  ),
                ),
                'default' => array(
                  'url' => '',
                ),
                'media_type' => 'image',
                'condition'  => array(
                  'webyx_section_background_mob'        => 'on',
                  'webyx_section_foreground_object_mob' => 'image',
                ),
                'selectors' => array(
                  '{{WRAPPER}} .webyx-background-overlay-bkg-img' => '--section-image-url-mob: url({{URL}});',
                ),
              )
            );
            $element->add_control(
              'webyx_section_background_image_size_mob',
              array(
                'type'        => \Elementor\Controls_Manager::SELECT,
                'label'       => esc_html__( 'Image size', $this->slug ),
                'description' => esc_html__( 'Select image size.', $this->slug ),
                'default'     => 'cover',
                'options'     => array(
                  'auto'    => esc_html__( 'auto',    $this->slug ),
                  'cover'   => esc_html__( 'cover',   $this->slug ),
                  'contain' => esc_html__( 'contain', $this->slug ),
                ),
                'condition'   => array(
                  'webyx_section_background_mob'        => 'on',
                  'webyx_section_foreground_object_mob' => 'image',
                ),
                'selectors' => array(
                  '{{WRAPPER}} .webyx-background-overlay-bkg-img' => '--section-image-background-size-mob: {{VALUE}};',
                ),
              )
            );
            $element->add_control(
              'webyx_section_background_image_position_mob',
              array(
                'type'        => \Elementor\Controls_Manager::SELECT,
                'label'       => esc_html__( 'Image position', $this->slug ),
                'description' => esc_html__( 'Select image position.', $this->slug ),
                'default'     => 'center center',
                'options'     => array(
                  'left top'      => esc_html__( 'left top',      $this->slug ),
                  'left center'   => esc_html__( 'left center',   $this->slug ),
                  'left bottom'   => esc_html__( 'left bottom',   $this->slug ),
                  'right top'     => esc_html__( 'right top',     $this->slug ),
                  'right center'  => esc_html__( 'right center',  $this->slug ),
                  'right bottom'  => esc_html__( 'right bottom',  $this->slug ),
                  'center top'    => esc_html__( 'center top',    $this->slug ),
                  'center center' => esc_html__( 'center center', $this->slug ),
                  'center bottom' => esc_html__( 'center bottom', $this->slug ),
                ),
                'condition'   => array(
                  'webyx_section_background_mob'        => 'on',
                  'webyx_section_foreground_object_mob' => 'image',
                ),
                'selectors' => array(
                  '{{WRAPPER}} .webyx-background-overlay-bkg-img' => '--section-image-background-position-mob: {{VALUE}};',
                ),
              )
            );
            $element->add_control(
              'webyx_section_background_image_repeat_mob',
              array(
                'type'        => \Elementor\Controls_Manager::SELECT,
                'label'       => esc_html__( 'Image repeat', $this->slug ),
                'description' => esc_html__( 'Select image repeat.', $this->slug ),
                'default'     => 'no-repeat',
                'options'     => array(
                  'repeat'    => esc_html__( 'repeat',    $this->slug ),
                  'no-repeat' => esc_html__( 'no-repeat', $this->slug ),
                ),
                'condition'   => array(
                  'webyx_section_background_mob'        => 'on',
                  'webyx_section_foreground_object_mob' => 'image',
                ),
                'selectors' => array(
                  '{{WRAPPER}} .webyx-background-overlay-bkg-img' => '--section-image-background-repeat-mob: {{VALUE}};',
                ),
              )
            );
            $element->add_control(
              'webyx_section_background_image_attachment_mob',
              array(
                'type'        => \Elementor\Controls_Manager::SELECT,
                'label'       => esc_html__( 'Image attachment', $this->slug ),
                'description' => esc_html__( 'Select image attachment.', $this->slug ),
                'default'     => 'scroll',
                'options'     => array(
                  'scroll' => esc_html__( 'scroll', $this->slug ),
                  'fixed'  => esc_html__( 'fixed',  $this->slug ),
                ),
                'condition'   => array(
                  'webyx_section_background_mob'        => 'on',
                  'webyx_section_foreground_object_mob' => 'image',
                ),
                'selectors' => array(
                  '{{WRAPPER}} .webyx-background-overlay-bkg-img' => '--section-image-background-attachment-mob: {{VALUE}};',
                ),
              )
            );
            $element->add_control(
              'webyx_section_background_video_mob', 
              array(
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'label'   => esc_html__( 'Choose video', $this->slug ),
                'description' => esc_html__( 'Choose ' . $el_root . ' background video.', $this->slug ),
                'dynamic' => array(
                  'active' => true,
                  'categories' => array(
                    \Elementor\Modules\DynamicTags\Module::MEDIA_CATEGORY,
                  ),
                ),
                'default' => array(
                  'url' => '',
                ),
                'media_type' => 'video',
                'condition'   => array(
                  'webyx_section_background_mob'        => 'on',
                  'webyx_section_foreground_object_mob' => 'video',
                ),
              )
            );
            $element->add_control(
              'webyx_section_background_video_poster_enable_mob',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( 'Enable poster image', $this->slug ),
                'description' => esc_html__( 'Enable ' . $el_root . ' poster image.', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => '',
                'condition'   => array(
                  'webyx_section_background_mob'        => 'on',
                  'webyx_section_foreground_object_mob' => 'video',
                ),
                'render_type' => 'none',
              )
            );
            $element->add_control(
              'webyx_section_background_video_poster_mob', 
              array(
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'label'   => esc_html__( 'Choose poster image', $this->slug ),
                'description' => esc_html__( 'Choose ' . $el_root . ' poster image.', $this->slug ),
                'dynamic' => array(
                  'active' => true,
                  'categories' => array(
                    \Elementor\Modules\DynamicTags\Module::MEDIA_CATEGORY,
                  ),
                ),
                'media_type' => 'image',
                'default'    => array(
                  'url' => '',
                ),
                'condition' => array(
                  'webyx_section_background_mob'                     => 'on',
                  'webyx_section_foreground_object_mob'              => 'video',
                  'webyx_section_background_video_poster_enable_mob' => 'on',
                ),
                'render_type' => 'none',
              )
            );
            $element->add_control(
              'webyx_section_background_video_preload_mob',
              array(
                'type'        => \Elementor\Controls_Manager::SELECT,
                'label'       => esc_html__( 'Video preload', $this->slug ),
                'description' => esc_html__( 'It specifies that the browser should or should not load the entire video when the page loads.', $this->slug ),
                'default'     => 'auto',
                'options'     => array(
                  'none'     => esc_html__( 'none',     $this->slug ),
                  'auto'     => esc_html__( 'auto',     $this->slug ),
                  'metadata' => esc_html__( 'metadata', $this->slug ),
                ),
                'condition'   => array(
                  'webyx_section_background_mob'        => 'on',
                  'webyx_section_foreground_object_mob' => 'video',
                ),
                'render_type' => 'none',
              )
            );
            $element->add_control(
              'webyx_section_background_video_controls_mob',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( 'Video controls', $this->slug ),
                'description'  => esc_html__( 'It specifies that video controls should be displayed. NOTE: when entering \'landscape\' orientation some devices automatically bring the video to the foreground causing it to become unresponsive to the Controls option. Simply go back to \'portrait\' orientation to be able to interact with the video again.', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => '',
                'condition'    => array(
                  'webyx_section_background_mob'        => 'on',
                  'webyx_section_foreground_object_mob' => 'video',
                ),
                'render_type' => 'none',
              )
            );
            $element->add_control(
              'webyx_section_background_video_muted_mob',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( 'Video muted', $this->slug ),
                'description'  => esc_html__( 'It specifies that the audio output of the video should be muted.', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => 'on',
                'condition'    => array(
                  'webyx_section_background_mob'        => 'on',
                  'webyx_section_foreground_object_mob' => 'video',
                ),
                'render_type' => 'none',
              )
            );
            $element->add_control(
              'webyx_section_background_video_autoplay_mob',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( 'Video autoplay', $this->slug ),
                'description'  => esc_html__( 'The video will be played as soon as it\'s playable. IMPORTANT: you must activate muted option to let your video start playing automatically (but muted).', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => '',
                'condition'    => array(
                  'webyx_section_background_mob'        => 'on',
                  'webyx_section_foreground_object_mob' => 'video',
                ),
                'render_type' => 'none',
              )
            );
            $element->add_control(
              'webyx_section_background_video_loop_mob',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( 'Video loop', $this->slug ),
                'description'  => esc_html__( 'It specifies that the video will start over again, every time it is finished.', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => '',
                'condition'    => array(
                  'webyx_section_background_mob'        => 'on',
                  'webyx_section_foreground_object_mob' => 'video',
                ),
                'render_type' => 'none',
              )
            );
            $element->end_controls_tab();
            $element->end_controls_tabs();
            $element->add_control(
              'webyx_section_name_editor',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( $el_root . ' name preview', $this->slug ),
                'description'  => esc_html__( 'Toggle hide/show ' . $el_root . ' name in Elementor\'s preview.', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => 'on',
                'separator'    => 'before',
                'condition'    => array(
                  'webyx_section_enable' => 'on',
                  'webyx_section_type!'  => 'header',
                ),
                'prefix_class' => 'webyx-section-name-editor-',
              )
            );
            $element->add_control(
              'webyx_section_icon_editor',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( $el_root . ' icon preview', $this->slug ),
                'description'  => esc_html__( 'Toggle hide/show ' . $el_root . ' icons in Elementor\'s preview.', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => 'on',
                'condition'    => array(
                  'webyx_section_enable' => 'on',
                  'webyx_section_type!'  => 'header',
                ),
                'prefix_class' => 'webyx-section-icon-editor-',
              )
            );
            $element->add_control(
              'webyx_section_background_editor',
              array(
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label'        => esc_html__( $el_root . ' background preview', $this->slug ),
                'description'  => esc_html__( 'Toggle hide/show ' . $el_root . ' background in Elementor\'s preview.', $this->slug ),
                'label_on'     => esc_html__( 'on',  $this->slug ),
                'label_off'    => esc_html__( 'off', $this->slug ),
                'return_value' => 'on',
                'default'      => 'on',
                'condition'    => array(
                  'webyx_section_enable' => 'on',
                  'webyx_section_type!'  => 'header',
                ),
                'prefix_class' => 'webyx-section-background-editor-',
              )
            );
            $element->end_controls_section();
          }
        }
        public function webyx_fep_frontend_sections_content () {
          $is_container_active = $this->webyx_fep_is_container_active();
          add_action( 
            $is_container_active ? 'elementor/frontend/container/before_render' : 'elementor/frontend/section/before_render', 
            array(
              $this, 
              'webyx_fep_frontend_section_before_render'
            ) 
          );
          add_action( 
            $is_container_active ? 'elementor/frontend/container/after_render' : 'elementor/frontend/section/after_render',  
            array( 
              $this, 
              'webyx_fep_frontend_section_after_render' 
            )
          );
          add_filter( 
            'elementor/frontend/the_content', 
            array(
              $this, 
              'webyx_fep_frontend_sections_container'
            ) 
          );
          add_filter( 
            $is_container_active ? 'elementor/frontend/container/should_render' : 'elementor/frontend/section/should_render', 
            array(
              $this, 
              'webyx_fep_frontend_sections_should_render'
            ),
            10, 2
          );
        }
        public function webyx_fep_frontend_sections_should_render ( $bool, $element ) {
          global $post;
          $ps = get_post_meta( $post->ID, '_elementor_page_settings', true );
          $webyx_fep_is_enable  = $this->webyx_fep_is_enable( $ps );
          $s = $element->get_settings_for_display();
          $is_container_active = $this->webyx_fep_is_container_active();
          $webyx_section_enable = isset( $s[ 'webyx_section_enable' ] ) && in_array( $s[ 'webyx_section_enable' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_enable' ] : '';
          $webyx_section_hide_in_frontend = isset( $s[ 'webyx_section_hide_in_frontend' ] ) && in_array( $s[ 'webyx_section_hide_in_frontend' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_hide_in_frontend' ] : '';
          if ( $is_container_active ) { // Cnt
            $is_inner = isset( $s[ 'webyx_section_is_inner' ] );
            if ( $webyx_fep_is_enable ) {
              if ( 'on' === $webyx_section_enable && '' === $webyx_section_hide_in_frontend ) { 
                $this->webyx_fep_get_nada( $s );
                $bool = true;
              } else {
                if ( $is_inner && '' === $webyx_section_hide_in_frontend ) {
                  $bool = true;
                } else {
                  $bool = false;
                }
              }
            }
          } else {
            $is_inner = $element->get_raw_data()[ 'isInner' ];
            if ( $webyx_fep_is_enable && ! $is_inner ) {
              if ( 'on' === $webyx_section_enable && '' === $webyx_section_hide_in_frontend ) {
                $this->webyx_fep_get_nada( $s );
                $bool = true;
              } else {
                $bool = false;
              }
            } 
          }
          return $bool;
        }
        public function webyx_fep_get_nada ( $s ) {
          $webyx_section_name = isset( $s[ 'webyx_section_name' ] ) && '' !== $s[ 'webyx_section_name' ] ? $s[ 'webyx_section_name' ] : 'Section';
          $webyx_section_type = isset( $s[ 'webyx_section_type' ] ) && in_array( $s[ 'webyx_section_type' ], array( 'front', 'side', 'header' ), true ) ? $s[ 'webyx_section_type' ] : 'front';
          $webyx_section_group_name_enable = isset( $s[ 'webyx_section_group_name_enable' ] ) && in_array( $s[ 'webyx_section_group_name_enable' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_group_name_enable' ] : '';
          $webyx_section_group_name = isset( $s[ 'webyx_section_group_name' ] ) ? $s[ 'webyx_section_group_name' ] : '';
          $webyx_section_name_menu = 'on' === $webyx_section_group_name_enable && $webyx_section_group_name ? $webyx_section_group_name : $webyx_section_name;
          if ( 'front' === $webyx_section_type ) {
            $this->webyx_section_name = $webyx_section_name_menu;
            $this->nada[ $webyx_section_name_menu ] = array( 
              $webyx_section_name,
            );
          } elseif ( 'side' === $webyx_section_type ) {
            if ( $this->is_open_section_scrollable_hz ) {
              return;
            }
            $this->nada[ $this->webyx_section_name ][] = $webyx_section_name_menu;
          }
        }
        public function webyx_fep_get_global_elmnt_control_id ( $s, $el_control_id ) {
          $global_control_id = NULL;
          if ( isset( $s[ '__globals__' ] ) && ! empty( $s[ '__globals__' ][ $el_control_id ] ) ) {
            $q = $s[ '__globals__' ][ $el_control_id ];
            preg_match( "/&?id=([^&]+)/", $q, $matches );
            $global_control_id = $matches ? $matches[ 1 ] : NULL;
          } 
          return $global_control_id;
        }
        public function webyx_fep_get_global_elmnt_css_var_format ( $global_control_id ) {
          $global_css_var_format = NULL;
          if ( $global_control_id ) {
            $global_css_var_format = 'var(--e-global-color-' . $global_control_id . ')';
          }
          return $global_css_var_format;
        }
        public function webyx_fep_get_global_elmnt_control_value ( $s, $el_control_id, $default ) {
          $global_control_id = $this->webyx_fep_get_global_elmnt_control_id( $s, $el_control_id );
          $global_control_value = NULL;
          if ( $global_control_id ) {
            $global_control_value = $this->webyx_fep_get_global_elmnt_css_var_format( $global_control_id );
          } else {
            if ( isset( $s[ $el_control_id ] ) && $this->webyx_fep_sanitize_hex_color( $s[ $el_control_id ] ) ) {
              $global_control_value = $s[ $el_control_id ];
            } else {
              $global_control_value = $default;
            }
          }
          return $global_control_value;
        }
        public function webyx_fep_frontend_header_validated ( $ps ) {
          $wvtype = isset( $ps[ 'wvtype' ] ) && in_array( $ps[ 'wvtype' ], array( 'full', 'header', 'custom' ), true ) ? $ps[ 'wvtype' ] : 'full';
          if ( 'header' === $wvtype ) {
            $wvhdtype = isset( $ps[ 'wvhdtype' ] ) && in_array( $ps[ 'wvhdtype' ], array( 'blk', 'tmp' ), true ) ? $ps[ 'wvhdtype' ] : 'blk';
            $wvdnav = isset( $ps[ 'wvdnav' ] ) && in_array( $ps[ 'wvdnav' ], array( 'on', '' ), true ) ? $ps[ 'wvdnav' ] : '';
            $wvmnav = isset( $ps[ 'wvmnav' ] ) && in_array( $ps[ 'wvmnav' ], array( 'on', '' ), true ) ? $ps[ 'wvmnav' ] : '';
            $wvdlogo = isset( $ps[ 'wvdlogo' ] ) && in_array( $ps[ 'wvdlogo' ], array( 'on', '' ), true ) ? $ps[ 'wvdlogo' ] : '';
            $wvmlogo = isset( $ps[ 'wvmlogo' ] ) && in_array( $ps[ 'wvmlogo' ], array( 'on', '' ), true ) ? $ps[ 'wvmlogo' ] : '';
            $wvdlogoimg = isset( $ps[ 'wvdlogoimg' ] ) ? $ps[ 'wvdlogoimg' ] : array( 'url' => '' );
            $o_hd = '<header class="webyx-header">';
            $c_hd = '</header>';
            $nv = '';
            $logo = '';
            if ( 'on' === $wvdlogo || 'on' === $wvmlogo ) {
              $o_logo = '<div class="webyx-logo-wrapper">';
              $c_logo = '</div>';
              $o_logo_a = '<a href ="' . esc_url( home_url( '/' ) ) . '">';
              $c_logo_a = '</a>';
              if ( isset( $wvdlogoimg[ 'url' ] ) && '' !== $wvdlogoimg[ 'url' ] ) {
                $oc_logo_img = '<img class="webyx-logo-img" src="' . esc_url( $wvdlogoimg[ 'url' ] ) . '"/>';
                $logo = $o_logo . $o_logo_a . $oc_logo_img . $c_logo_a . $c_logo;
              }
            }
            if ( 'on' === $wvdnav || 'on' === $wvmnav ) {
              $o_nv = '<nav data-headertype="' . esc_attr( $wvhdtype ) . '" id="webyx-nav" class="webyx-nav webyx-nav-hide">';
              $c_nv = '</nav>';
              $icon_menu = '<div id="webyx-toggle-btn" class="webyx-toggle-btn"><div class="webyx-bar"></div><div class="webyx-bar"></div><div class="webyx-bar"></div></div>';
              $menu_cnt = 'blk' === $wvhdtype ? $this->webyx_fep_frontend_block_menu( $ps ) : $this->webyx_fep_frontend_tmp_menu();
              $nv = $o_nv . $icon_menu . $menu_cnt . $c_nv;
            }
            return $o_hd . $logo . $nv . $c_hd;
          }
          return '';
        }
        public function webyx_fep_frontend_block_menu ( $ps ) {
          $nada = $this->nada;
          if ( count( $this->nada ) ) {
            $o_mn_cnt = '<div class="menu-webyx-menu-container">';
            $c_mn_cnt = '</div>';
            $o_mn_items = '<ul id="webyx-nav-container" class="webyx-nav-container webyx-scrollbar-menu">';
            $c_mn_items = '</ul>';
            $item_sections = '';
            foreach ( $nada as $item_section_name => $item_section_val ) {
              $o_mn_item = '<li data-section="' . esc_attr( $item_section_name ) . '" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children webyx-main-menu-section-anchor">';
              $c_mn_item = '</li>';
              $oc_mn_item_span_name = '<span class="webyx-menu-sec-spa">' . esc_html( $item_section_name ) . '</span>';
              $oc_mn_item_span_arw  = '<span ariaHidden="true" class="webyx-menu-arrow"></span>';
              $o_mn_sub_items = '<ul class="sub-menu">';
              $c_mn_sub_items = '</ul>';
              $item_slides = '';
              foreach ( $item_section_val as $sub_item_slide_name ) {
                $o_mn_sub_item = '<li class="menu-item menu-item-type-post_type menu-item-object-page">';
                $c_mn_sub_item = '</li>';
                $oc_mn_sub_item_a = '<a class="menu-item-anchor-header-blk" data-section="' . esc_attr( $item_section_name ) . '" data-slide="' . esc_attr( $sub_item_slide_name ) . '" href="#' . esc_attr( $item_section_name ) . '/' . esc_attr( $sub_item_slide_name ) . '">' . esc_html( $sub_item_slide_name ) . '</a>';
                $item_slides .= $o_mn_sub_item . $oc_mn_sub_item_a . $c_mn_sub_item;
              }
              $item_sections .= $o_mn_item . $oc_mn_item_span_name . $oc_mn_item_span_arw . $o_mn_sub_items . $item_slides . $c_mn_sub_items . $c_mn_item;
            }
            return $o_mn_cnt . $o_mn_items . $item_sections . $c_mn_items . $c_mn_cnt;
          }
        }
        public function webyx_fep_frontend_tmp_menu () {
          $menu_cnt = '';
          if ( has_nav_menu( 'webyx-menu' ) ) {
            $menu_cnt = wp_nav_menu( 
              array( 
                'menu'=> get_nav_menu_locations()[ 'webyx-menu' ],
                'container_class' => 'menu-webyx-menu-container',
                'items_wrap' => '<ul id="webyx-nav-container" class="webyx-nav-container webyx-scrollbar-menu">%3$s</ul>',
                'after' => '<span aria-hidden="true" class="webyx-menu-arrow"></span>',
                'echo'=> false,
              ) 
            );
          }
          return $menu_cnt;
        }
        public function webyx_fep_sanitize_html_classes ( $classes, $return_format = 'input' ) {
          if ( 'input' === $return_format ) {
            $return_format = is_array( $classes ) ? 'array' : 'string';
          }
          $classes = is_array( $classes ) ? $classes : explode( ' ', $classes );
          $sanitized_classes = array_map( 'sanitize_html_class', $classes );
          if ( 'array' === $return_format ) {
            return $sanitized_classes;
          } else {
            return implode( ' ', $sanitized_classes );
          }
        }
        public function webyx_fep_frontend_sections_container ( $content ) {
          global $post;
          $ps = get_post_meta( $post->ID, '_elementor_page_settings', true );
          $webyx_fep_is_enable = $this->webyx_fep_is_enable( $ps );
          if ( $webyx_fep_is_enable && $this->webyx_fep_chk_intgr ( $content ) ) {
            $wvid = isset( $ps[ 'wvid' ] ) && '' !== $ps[ 'wvid' ] ? $ps[ 'wvid' ] : '';
            $wvcn = isset( $ps[ 'wvcn' ] ) && '' !== $ps[ 'wvcn' ]? 'webyx-view ' . $ps[ 'wvcn' ] : 'webyx-view';
            $vl = isset( $ps[ 'vl' ] ) && in_array( $ps[ 'vl' ], array( 'on', '' ), true ) && 'on' === $ps[ 'vl' ] ? 'data-loop' : '';
            $wvscrlb = isset( $ps[ 'wvscrlbar' ] ) && in_array( $ps[ 'wvscrlbar' ], array( 'on', '' ), true ) && 'on' === $ps[ 'wvscrlbar' ] ? ' webyx-hide-scrollbar' : '';
            $webyx_fep_hd_css_validated = $this->webyx_fep_hd_css_validated( $ps );
            $webyx_header_validated = $this->webyx_fep_frontend_header_validated( $ps );
            $webyx_fep_sp_screen = $this->webyx_fep_get_splash_screen_validated( $ps );
            $webyx_fep_settings = $this->webyx_fep_get_settings_validated( $ps );
            $webyx_fep_hooks = $this->webyx_fep_get_event_hooks( $ps );
            $webyx_fep_css = $this->webyx_fep_print_css_validated( $ps );
            $webyx_fep_audio = $this->webyx_fep_get_audio_player_validated( $ps );
            return '<div class="webyx-fw-hd">' . $webyx_fep_hd_css_validated . $webyx_header_validated . '<div ' . ( $wvid ? 'id="' . esc_attr( $wvid ) . '" ' : '' ) . 'class="' . $this->webyx_fep_sanitize_html_classes( $wvcn ) . '"><div class="webyx-webyx" ' . esc_attr( $vl ) . '>' . $webyx_fep_sp_screen . $webyx_fep_audio . '<div class="webyx-webyx-wrp' . sanitize_html_class( $wvscrlb ) . '">' . $content . '</div></div></div>' . $webyx_fep_settings . $webyx_fep_hooks . $webyx_fep_css . '</div>';
          }
          return $content;
        }
        public function webyx_fep_frontend_section_before_render ( \Elementor\Element_Base $element ) {
          global $post;
          $s = $element->get_settings_for_display();
          $ps = get_post_meta( $post->ID, '_elementor_page_settings', true );
          $element_id = $element->get_id();
          $webyx_fep_is_enable = $this->webyx_fep_is_enable( $ps );
          $webyx_section_is_inner = isset( $s[ 'webyx_section_is_inner' ] );
          $is_container_active = $this->webyx_fep_is_container_active();
          $is_inner = $is_container_active ? $webyx_section_is_inner : $element->get_raw_data()[ 'isInner' ];
          $cn_bkg = 'webyx-section-' . $element_id;
          $cn_bkg_video = 'webyx-video-section-' . $element_id;
          $cn_wrp_cnt = 'webyx-wrapper-cnt-section-' . $element_id;
          $webyx_section_enable = isset( $s[ 'webyx_section_enable' ] ) && in_array( $s[ 'webyx_section_enable' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_enable' ] : '';
          $webyx_section_hide_in_frontend = isset( $s[ 'webyx_section_hide_in_frontend' ] ) && in_array( $s[ 'webyx_section_hide_in_frontend' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_hide_in_frontend' ] : '';
          if ( $webyx_fep_is_enable && 'on' === $webyx_section_enable && '' === $webyx_section_hide_in_frontend && ! $is_inner ) {
            $ws_type = isset( $s[ 'webyx_section_type' ] ) && in_array( $s[ 'webyx_section_type' ], array( 'front', 'side', 'header' ), true ) ? $s[ 'webyx_section_type' ] : 'front';
            $ws_tag_name = isset( $s[ 'webyx_section_tag_name' ] ) && in_array( $s[ 'webyx_section_tag_name' ], $this->tag_name, true ) ? $s[ 'webyx_section_tag_name' ] : 'div';
            if ( 'front' === $ws_type || 'side' === $ws_type ) {
              $nosi = isset( $ps ) && isset( $ps[ 'nosi' ] ) && in_array( $ps[ 'nosi' ], array( 'on', '' ), true ) ? $ps[ 'nosi' ] : '';
              $hzsmooth = isset( $ps ) && isset( $ps[ 'hzsmooth' ] ) && in_array( $ps[ 'hzsmooth' ], array( 'on', '' ), true ) ? $ps[ 'hzsmooth' ] : '';
              $hason = isset( $ps ) && isset( $ps[ 'hason' ] ) && in_array( $ps[ 'hason' ], array( 'on', '' ), true ) ? $ps[ 'hason' ] : '';
              $ws_name = isset( $s[ 'webyx_section_name' ] ) && '' !== $s[ 'webyx_section_name' ] ? $s[ 'webyx_section_name' ] : 'Section';
              $ws_anchor_name = isset( $s[ 'webyx_section_anchor_name' ] ) && '' !== $s[ 'webyx_section_anchor_name' ] ? str_replace( ' ', '-', trim( $s[ 'webyx_section_anchor_name' ], ' ' ) ) : str_replace( ' ', '-', trim( $ws_name, ' ') );
              $webyx_section_group_name_enable = isset( $s[ 'webyx_section_group_name_enable' ] ) && in_array( $s[ 'webyx_section_group_name_enable' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_group_name_enable' ] : '';
              $ws_group_name = isset( $s[ 'webyx_section_group_name' ] ) ? $s[ 'webyx_section_group_name' ] : '';
              $ws_continuous = isset( $s[ 'webyx_section_continuous_carousel' ] ) && in_array( $s[ 'webyx_section_continuous_carousel' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_continuous_carousel' ] : '';
              $ws_scrollable = isset( $s[ 'webyx_section_scrollable' ] ) && in_array( $s[ 'webyx_section_scrollable' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_scrollable' ] : '';
              $ws_scrollable_hz = isset( $s[ 'webyx_section_scrollable_hz' ] ) && in_array( $s[ 'webyx_section_scrollable_hz' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_scrollable_hz' ] : '';
              $ws_cont_pos_en = isset( $s[ 'webyx_section_cont_pos_enable' ] ) && in_array( $s[ 'webyx_section_cont_pos_enable' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_cont_pos_enable' ] : '';
              $ws_cont_pos = isset( $s[ 'webyx_section_cont_pos' ] ) && in_array( $s[ 'webyx_section_cont_pos' ], array( 'top', 'middle', 'bottom' ), true ) ? $s[ 'webyx_section_cont_pos' ] : 'middle';
              $ws_wrp_cnt = isset( $s[ 'webyx_section_wrapper_cnt' ] ) && in_array( $s[ 'webyx_section_wrapper_cnt' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_wrapper_cnt' ] : '';
              $ws_wrp_cnt_classes = isset( $s[ 'webyx_section_wrapper_cnt_classes' ] ) ? $s[ 'webyx_section_wrapper_cnt_classes' ] : '';
              $cn_type_side_page = 'front' === $ws_type ? 'webyx-main-page' : '';
              $data_continuous_carousel = 'on' === $ws_continuous ? 'data-loop' : '';
              $data_scrollable = 'on' === $ws_scrollable ? 'data-scroll' : '';
              $cn_ovw_page = 'on' === $ws_scrollable ? 'webyx-scrollbar webyx-ovw-scroll' : 'webyx-ovw-hidden';
              $cn_container = $is_container_active ? 'webyx-flex' : '';
              $cn_hzsmooth = $hzsmooth ? '' : ' webyx-hz-item-native';
              $data_anchor = $hason ? 'data-anchor="' . esc_attr( $ws_anchor_name ) . '"' : '';
              if ( $ws_type === 'front' ) {
                if ( $this->is_open_section_scrollable_hz ) {
                  echo '</div></div>'; // cls wrp + wrp slide
                  $this->is_open_section_scrollable_hz = FALSE;
                }
                if ( $this->is_open_section ) {
                  echo '</div>';
                }
                $data_stripe = $webyx_section_group_name_enable && $ws_group_name ? $ws_group_name : $ws_name;
                echo '<div class="webyx-stripe" data-stripe="' . esc_attr( $data_stripe ) . '" ' . esc_attr( $data_continuous_carousel ) . ' data-webyx="webyx-fep-fl">';
                $this->is_open_section = TRUE;
                if ( 'on' === $ws_scrollable_hz ) {
                  echo '<' . tag_escape( $ws_tag_name ) . ' class="' . sanitize_html_class( $cn_type_side_page ) . ' ' . $this->webyx_fep_sanitize_html_classes( $cn_ovw_page ) . ' ' . 'webyx-side-page" data-side-page="' . esc_attr( $ws_name ) . '" data-scroll-hz ' . $data_anchor . '>'; // opn wrp slide
                  echo '<div class="webyx-hz-wrapper webyx-scrollbar' . $cn_hzsmooth . '">'; // opn scrl wrp
                  $this->is_open_section_scrollable_hz = TRUE;
                }
              }
              if ( $this->is_open_section_scrollable_hz ) {
                echo '<div ' . esc_attr( $data_scrollable ) . ' class="' . sanitize_html_class( $cn_bkg ) . ' webyx-hz-item ' . $cn_container . '"' . ' ' . 'data-class="'. sanitize_html_class( $cn_type_side_page ) . ' ' . 'webyx-side-page' . ' ' . $this->webyx_fep_sanitize_html_classes( $cn_ovw_page ) . ' ' . sanitize_html_class( $cn_bkg ) . ' ' . sanitize_html_class( $cn_container ) . '" data-side-page="' . esc_attr( $ws_name ) . '" ' . $data_anchor . '>'; // opn wrp item
              } else {
                echo '<' . tag_escape( $ws_tag_name ) . ' ' . esc_attr( $data_scrollable ) . ' class="' . sanitize_html_class( $cn_type_side_page ) . ' ' . 'webyx-side-page' . ' ' . $this->webyx_fep_sanitize_html_classes( $cn_ovw_page ) . ' ' . sanitize_html_class( $cn_bkg ) . ' ' . sanitize_html_class( $cn_container ) . '" data-side-page="' . esc_attr( $ws_name ) . '" ' . $data_anchor . '>';
              }
              echo $this->webyx_fep_get_section_style_validated( $s, $ps, $cn_bkg, $cn_bkg_video, $cn_wrp_cnt );
              echo $this->webyx_fep_get_bkg_section_video_validated( $s, $cn_bkg_video );
              if ( 'on' === $ws_cont_pos_en ) {
                echo '<div class="webyx-table"><div class="' . sanitize_html_class( 'webyx-table-cell-' . $ws_cont_pos ) . '">';
              }
              if ( 'on' === $ws_wrp_cnt ) {
                $cns_section_wrapper = strlen( $ws_wrp_cnt_classes ) ? "webyx-wrapper-slide-content $cn_wrp_cnt $ws_wrp_cnt_classes" : "webyx-wrapper-slide-content $cn_wrp_cnt";
                echo '<div class="' . $this->webyx_fep_sanitize_html_classes( $cns_section_wrapper ) . ' ' . sanitize_html_class( $cn_container ) . '">';
              }
            } elseif ( 'header' === $ws_type ) {
              echo '<' . tag_escape( $ws_tag_name ) . ' class="webyx-fep-top-header webyx-fep-top-header-hide">';
            }
          }
        }
        public function webyx_fep_frontend_section_after_render ( \Elementor\Element_Base $element ) {
          global $post;
          $s = $element->get_settings_for_display();
          $ps = get_post_meta( $post->ID, '_elementor_page_settings', true );
          $webyx_fep_is_enable = $this->webyx_fep_is_enable( $ps );
          $webyx_section_is_inner = isset( $s[ 'webyx_section_is_inner' ] );
          $is_container_active = $this->webyx_fep_is_container_active();
          $is_inner = $is_container_active ? $webyx_section_is_inner : $element->get_raw_data()[ 'isInner' ];
          $webyx_section_enable = isset( $s[ 'webyx_section_enable' ] ) && in_array( $s[ 'webyx_section_enable' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_enable' ] : '';
          $webyx_section_hide_in_frontend = isset( $s[ 'webyx_section_hide_in_frontend' ] ) && in_array( $s[ 'webyx_section_hide_in_frontend' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_hide_in_frontend' ] : '';
          if ( $webyx_fep_is_enable && 'on' === $webyx_section_enable && '' === $webyx_section_hide_in_frontend && ! $is_inner ) {
            $ws_type = isset( $s[ 'webyx_section_type' ] ) && in_array( $s[ 'webyx_section_type' ], array( 'front', 'side', 'header' ), true ) ? $s[ 'webyx_section_type' ] : 'front';
            $webyx_section_section_tag_name = isset( $s[ 'webyx_section_tag_name' ] ) & in_array( $s[ 'webyx_section_tag_name' ], $this->tag_name, true ) ? $s[ 'webyx_section_tag_name' ] : 'div';
            if ( 'front' === $ws_type || 'side' === $ws_type ) {
              $webyx_section_cont_pos_enable = isset( $s[ 'webyx_section_cont_pos_enable' ] ) && in_array( $s[ 'webyx_section_cont_pos_enable' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_cont_pos_enable' ] : '';
              $webyx_section_wrapper_cnt = isset( $s[ 'webyx_section_wrapper_cnt' ] ) && in_array( $s[ 'webyx_section_wrapper_cnt' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_wrapper_cnt' ] : '';
              if ( 'on' === $webyx_section_wrapper_cnt ) {
                echo '</div>'; 
              }
              if ( 'on' === $webyx_section_cont_pos_enable ) {
                echo '</div></div>';
              }
              if ( $this->is_open_section_scrollable_hz ) {
                echo '</div>'; // cls wrp slide 
              } else {
                echo '</' . tag_escape( $webyx_section_section_tag_name ) . '>'; // cls slide normal
              }
            } elseif ( 'header' === $ws_type ) {
              echo '</' . tag_escape( $webyx_section_section_tag_name ) . '>';
            }
          }
        }
        public function webyx_fep_hd_css_validated ( $ps ) {
          $cssRules = '';
          $cssRulesXs = '';
          $wvhdtype = isset( $ps[ 'wvhdtype' ] ) && in_array( $ps[ 'wvhdtype' ], array( 'blk', 'tmp' ), true ) ? $ps[ 'wvhdtype' ] : 'blk';
          $wvdhdht = isset( $ps[ 'wvdhdht' ] ) && isset( $ps[ 'wvdhdht' ][ 'unit' ] ) && in_array( $ps[ 'wvdhdht' ][ 'unit' ], array( 'px' ), true ) && isset( $ps[ 'wvdhdht' ][ 'size' ] ) && filter_var( $ps[ 'wvdhdht' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'wvdhdht' ] : array( 'unit' => 'px', 'size' => 50 );
          $wvdhdbc = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'wvdhdbc', '#ffffff' );
          $wvdlogo = isset( $ps[ 'wvdlogo' ] ) && in_array( $ps[ 'wvdlogo' ], array( 'on', '' ), true ) ? $ps[ 'wvdlogo' ] : '';
          $cnwrappernav = 'menu-webyx-menu-container';
          $cssRules .= '.webyx-header{height:' . esc_attr( $wvdhdht[ 'size' ] . $wvdhdht[ 'unit' ] ) . ';background-color:' . esc_attr( $wvdhdbc ) . '}';
          if ( 'on' === $wvdlogo ) {
            $wvdlogowt = isset( $ps[ 'wvdlogowt' ] ) && isset( $ps[ 'wvdlogowt' ][ 'unit' ] ) && in_array( $ps[ 'wvdlogowt' ][ 'unit' ], array( 'px' ), true ) && isset( $ps[ 'wvdlogowt' ][ 'size' ] ) && filter_var( $ps[ 'wvdlogowt' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'wvdlogowt' ] : array( 'unit' => 'px', 'size' => 50, );
            $wvdlogoht = isset( $ps[ 'wvdlogoht' ] ) && isset( $ps[ 'wvdlogoht' ][ 'unit' ] ) && in_array( $ps[ 'wvdlogoht' ][ 'unit' ], array( 'px' ), true ) && isset( $ps[ 'wvdlogoht' ][ 'size' ] ) && filter_var( $ps[ 'wvdlogoht' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'wvdlogoht' ] : array( 'unit' => 'px', 'size' => 50, );
            $wvdlogohzpos = isset( $ps[ 'wvdlogohzpos' ] ) && in_array( $ps[ 'wvdlogohzpos' ], array( 'left', 'right' ), true ) ? $ps[ 'wvdlogohzpos' ] : 'left';
            $wvdlogohzposv = isset( $ps[ 'wvdlogohzposv' ] ) && isset( $ps[ 'wvdlogohzposv' ][ 'unit' ] ) && in_array( $ps[ 'wvdlogohzposv' ][ 'unit' ], array( 'px' ), true ) && isset( $ps[ 'wvdlogohzposv' ][ 'size' ] ) && filter_var( $ps[ 'wvdlogohzposv' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'wvdlogohzposv' ] : array( 'unit' => 'px', 'size' => 0, );
            $wvdlogovtpos = isset( $ps[ 'wvdlogovtpos' ] ) && in_array( $ps[ 'wvdlogovtpos' ], array( 'top', 'bottom' ), true ) ? $ps[ 'wvdlogovtpos' ] : 'top';
            $wvdlogovtposv = isset( $ps[ 'wvdlogovtposv' ] ) && isset( $ps[ 'wvdlogovtposv' ][ 'unit' ] ) && in_array( $ps[ 'wvdlogovtposv' ][ 'unit' ], array( 'px' ), true ) && isset( $ps[ 'wvdlogovtposv' ][ 'size' ] ) && filter_var( $ps[ 'wvdlogovtposv' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'wvdlogovtposv' ] : array( 'unit' => 'px', 'size' => 0, );
            $cssRules .= '.webyx-logo-wrapper{width:' . esc_attr( $wvdlogowt[ 'size' ] . $wvdlogowt[ 'unit' ] ) . ';height:' . esc_attr( $wvdlogoht[ 'size' ] . $wvdlogoht[ 'unit' ] ) . ';position:absolute;' . esc_attr( $wvdlogohzpos ) . ':' . esc_attr( $wvdlogohzposv[ 'size' ] ) . esc_attr( $wvdlogohzposv[ 'unit' ] ) . ';' . esc_attr( $wvdlogovtpos ) . ':' . esc_attr( $wvdlogovtposv[ 'size' ] ) . esc_attr( $wvdlogovtposv[ 'unit' ] ) . ';}';
          } else {
            $cssRules .= '.webyx-logo-wrapper{display:none}';
          }
          $wvdnav = isset( $ps[ 'wvdnav' ] ) && in_array( $ps[ 'wvdnav' ], array( 'on', '' ), true ) ? $ps[ 'wvdnav' ] : '';
          $wvmnav = isset( $ps[ 'wvmnav' ] ) && in_array( $ps[ 'wvmnav' ], array( 'on', '' ), true ) ? $ps[ 'wvmnav' ] : '';
          $wvdnavposhz = isset( $ps[ 'wvdnavposhz' ] ) && in_array( $ps[ 'wvdnavposhz' ], array( 'left', 'center', 'right' ), true ) ? $ps[ 'wvdnavposhz' ] : 'center';
          $wvdnavmaren = isset( $ps[ 'wvdnavmaren' ] ) && in_array( $ps[ 'wvdnavmaren' ], array( 'on', '' ), true ) ? $ps[ 'wvdnavmaren' ] : '';
          $wvdnavmar = isset( $ps[ 'wvdnavmar' ] ) && isset( $ps[ 'wvdnavmar' ][ 'unit' ] ) && in_array( $ps[ 'wvdnavmar' ][ 'unit' ], array( 'px' ), true ) && isset( $ps[ 'wvdnavmar' ][ 'top' ] ) && isset( $ps[ 'wvdnavmar' ][ 'right' ] ) && isset( $ps[ 'wvdnavmar' ][ 'bottom' ] ) && isset( $ps[ 'wvdnavmar' ][ 'left' ] ) ? $ps[ 'wvdnavmar' ] : array( 'unit' => 'px', 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'isLinked' => '' );
          $wvdnavitembc = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'wvdnavitembc', '#ffffff' );
          $wvdnavitembclight = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'wvdnavitembclight', '#ffffff' );
          $wvdnavitemdropcontdim = isset( $ps[ 'wvdnavitemdropcontdim' ] ) && isset( $ps[ 'wvdnavitemdropcontdim' ][ 'unit' ] ) && in_array( $ps[ 'wvdnavitemdropcontdim' ][ 'unit' ], array( 'px' ), true ) && isset( $ps[ 'wvdnavitemdropcontdim' ][ 'size' ] ) && filter_var( $ps[ 'wvdnavitemdropcontdim' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'wvdnavitemdropcontdim' ] : array( 'unit' => 'px', 'size' => 250 );
          $wvdnavitemfontdim = isset( $ps[ 'wvdnavitemfontdim' ] ) && isset( $ps[ 'wvdnavitemfontdim' ][ 'unit' ] ) && in_array( $ps[ 'wvdnavitemfontdim' ][ 'unit' ], array( 'px' ), true ) && isset( $ps[ 'wvdnavitemfontdim' ][ 'size' ] ) && filter_var( $ps[ 'wvdnavitemfontdim' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 12, 'max_range' => 32 ) ) ) ? $ps[ 'wvdnavitemfontdim' ] : array( 'unit' => 'px', 'size' => 16 );
          $wvdnavitemdim = isset( $ps[ 'wvdnavitemdim' ] ) && isset( $ps[ 'wvdnavitemdim' ][ 'unit' ] ) && in_array( $ps[ 'wvdnavitemdim' ][ 'unit' ], array( 'px' ), true ) && isset( $ps[ 'wvdnavitemdim' ][ 'size' ] ) && filter_var( $ps[ 'wvdnavitemdim' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 30, 'max_range' => 75 ) ) ) ? $ps[ 'wvdnavitemdim' ] : array( 'unit' => 'px', 'size' => 50 );
          $wvdnavitemtxtc = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'wvdnavitemtxtc', '#000000' );
          $wvdnavitemtxtclight = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'wvdnavitemtxtclight', '#000000' );
          if ( $wvdnav ) {
            $cssRules .= '#webyx-nav ul.webyx-nav-container>li{float:left;position:relative}';
            $cssRules .= '#webyx-nav ul.webyx-nav-container>li>ul{position:absolute}';
            $cssRules .= '#webyx-nav ul li{outline:unset;background-color:white;border-left:unset;transition:unset}';
            $cssRules .= '#webyx-nav ul li:hover{background-color:white;transition:unset;border-left:unset}';
            $cssRules .= '#webyx-toggle-btn{display:none}';
            $cssRules .= '.webyx-header .webyx-nav #webyx-toggle-btn+div{list-style-type:none;padding:0;margin:0;text-align:right}';
            $cssRules .= '.webyx-header .webyx-nav #webyx-toggle-btn+div ul#webyx-nav-container.webyx-nav-container{display:inline-block;-ms-overflow-style:none;scrollbar-width:none}';
            $cssRules .= '.webyx-header .webyx-nav #webyx-toggle-btn+div ul#webyx-nav-container.webyx-nav-container::-webkit-scrollbar{display:none}';
            $cssRules .= '.webyx-header .webyx-nav #webyx-toggle-btn+div.' . sanitize_html_class( $cnwrappernav ) . '{text-align:' . esc_attr( $wvdnavposhz ) . '}';
            if ( $wvdnavmaren ) {
              $cssRules .= '.webyx-header .webyx-nav #webyx-toggle-btn+div.' . sanitize_html_class( $cnwrappernav ) . ' ul#webyx-nav-container.webyx-nav-container{margin:' . esc_attr( $wvdnavmar[ 'top' ] . $wvdnavmar[ 'unit' ] ) . ' ' . esc_attr( $wvdnavmar[ 'right' ] . $wvdnavmar[ 'unit' ] ) . ' ' . esc_attr( $wvdnavmar[ 'bottom' ] . $wvdnavmar[ 'unit' ] ) . ' ' . esc_attr( $wvdnavmar[ 'left' ] . $wvdnavmar[ 'unit' ] ) . '}';
            }
            $cssRules .= '#webyx-nav ul li{background-color:' . esc_attr( $wvdnavitembc ) . '}';
            $cssRules .= '#webyx-nav ul li:hover{background-color:' . esc_attr( $wvdnavitembclight ) . '}';
            $cssRules .= '#webyx-nav ul.webyx-nav-container>li>ul{width:' . esc_attr( $wvdnavitemdropcontdim[ 'size' ] . $wvdnavitemdropcontdim[ 'unit' ] ) . '}';
            $cssRules .= '#webyx-nav a,#webyx-nav a:active,#webyx-nav a:visited,#webyx-nav a:focus{font-size:' . esc_attr( $wvdnavitemfontdim[ 'size' ] . $wvdnavitemfontdim[ 'unit' ] ) . ';color:' . esc_attr( $wvdnavitemtxtc ) . ';height:' . esc_attr( $wvdnavitemdim[ 'size' ] . $wvdnavitemdim[ 'unit' ] ). ';line-height:' . esc_attr( $wvdnavitemdim[ 'size' ] . $wvdnavitemdim[ 'unit' ] ) . '}';
            $cssRules .= '#webyx-nav .menu-item-has-children a{padding-right:' . esc_attr( $wvdnavitemdim[ 'size' ] . $wvdnavitemdim[ 'unit' ] ) . '}';
            $cssRules .= '#webyx-nav span.webyx-menu-sec-spa{font-size:' . esc_attr( $wvdnavitemfontdim[ 'size' ] . $wvdnavitemfontdim[ 'unit' ] ) . ';color:' . esc_attr( $wvdnavitemtxtc ) . ';height:' . esc_attr( $wvdnavitemdim[ 'size' ] . $wvdnavitemdim[ 'unit' ] ) . ';line-height:' . esc_attr( $wvdnavitemdim[ 'size' ] . $wvdnavitemdim[ 'unit' ] ) . '}';
            $cssRules .= '#webyx-nav span.webyx-menu-sec-spa:hover{color:' . esc_attr( $wvdnavitemtxtclight ) . '}';
            $cssRules .= '#webyx-nav a:hover,#webyx-nav a:hover{color:' . esc_attr( $wvdnavitemtxtclight ) . '}';
            $cssRules .= '.menu-item-has-children>.webyx-menu-arrow{color:' . esc_attr( $wvdnavitemtxtc ) . '}';
            $cssRules .= '.menu-item-has-children>.webyx-menu-arrow:hover{color:' . esc_attr( $wvdnavitemtxtclight ) . '}';
            $cssRules .= '.menu-item-has-children>.webyx-menu-arrow::after{line-height:' . esc_attr( $wvdnavitemdim[ 'size' ] . $wvdnavitemdim[ 'unit' ] ) . ';vertical-align:unset}';
          } else {
            if ( $wvmnav ) {
              $cssRules .= '#webyx-nav{display:none}';
            }
          }
          $wvmhdht = isset( $ps[ 'wvmhdht' ] ) && isset( $ps[ 'wvmhdht' ][ 'unit' ] ) && in_array( $ps[ 'wvmhdht' ][ 'unit' ], array( 'px' ), true ) && isset( $ps[ 'wvmhdht' ][ 'size' ] ) && filter_var( $ps[ 'wvmhdht' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'wvmhdht' ] : array( 'unit' => 'px', 'size' => 50 );
          $wvmhdbc = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'wvmhdbc', '#ffffff' );
          $wvmlogo = isset( $ps[ 'wvmlogo' ] ) && in_array( $ps[ 'wvmlogo' ], array( 'on', '' ), true ) ? $ps[ 'wvmlogo' ] : '';
          $cssRulesXs .= '.webyx-header{height:' . esc_attr( $wvmhdht[ 'size' ] . $wvmhdht[ 'unit' ] ) . ';background-color:' . esc_attr( $wvmhdbc ) . '}';
          if ( $wvmlogo ) {
            $wvmlogowt = isset( $ps[ 'wvmlogowt' ] ) && isset( $ps[ 'wvmlogowt' ][ 'unit' ] ) && in_array( $ps[ 'wvmlogowt' ][ 'unit' ], array( 'px' ), true ) && isset( $ps[ 'wvmlogowt' ][ 'size' ] ) && filter_var( $ps[ 'wvmlogowt' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'wvmlogowt' ] : array( 'unit' => 'px', 'size' => 50, );
            $wvmlogoht = isset( $ps[ 'wvmlogoht' ] ) && isset( $ps[ 'wvmlogoht' ][ 'unit' ] ) && in_array( $ps[ 'wvmlogoht' ][ 'unit' ], array( 'px' ), true ) && isset( $ps[ 'wvmlogoht' ][ 'size' ] ) && filter_var( $ps[ 'wvmlogoht' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'wvmlogoht' ] : array( 'unit' => 'px', 'size' => 50, );
            $wvmlogohzpos = isset( $ps[ 'wvmlogohzpos' ] ) && in_array( $ps[ 'wvmlogohzpos' ], array( 'left', 'right' ), true ) ? $ps[ 'wvmlogohzpos' ] : 'left';
            $wvmlogohzposv = isset( $ps[ 'wvmlogohzposv' ] ) && isset( $ps[ 'wvmlogohzposv' ][ 'unit' ] ) && in_array( $ps[ 'wvmlogohzposv' ][ 'unit' ], array( 'px' ), true ) && isset( $ps[ 'wvmlogohzposv' ][ 'size' ] ) && filter_var( $ps[ 'wvmlogohzposv' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'wvmlogohzposv' ] : array( 'unit' => 'px', 'size' => 0, );
            $wvmlogovtpos = isset( $ps[ 'wvmlogovtpos' ] ) && in_array( $ps[ 'wvmlogovtpos' ], array( 'top', 'bottom' ), true ) ? $ps[ 'wvmlogovtpos' ] : 'top';
            $wvmlogovtposv = isset( $ps[ 'wvmlogovtposv' ] ) && isset( $ps[ 'wvmlogovtposv' ][ 'unit' ] ) && in_array( $ps[ 'wvmlogovtposv' ][ 'unit' ], array( 'px' ), true ) && isset( $ps[ 'wvmlogovtposv' ][ 'size' ] ) && filter_var( $ps[ 'wvmlogovtposv' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) )  ? $ps[ 'wvmlogovtposv' ] : array( 'unit' => 'px', 'size' => 0, );
            $cssRulesXs .= '.webyx-logo-wrapper{width:' . esc_attr( $wvmlogowt[ 'size' ] . $wvmlogowt[ 'unit' ] ) . ';height:' . esc_attr( $wvmlogoht[ 'size' ] . $wvmlogoht[ 'unit' ] ) . ';position:absolute;' . esc_attr( $wvmlogohzpos ) . ':' . esc_attr( $wvmlogohzposv[ 'size' ] . $wvmlogohzposv[ 'unit' ] ) . ';' . esc_attr( $wvmlogovtpos ) . ':' . esc_attr( $wvmlogovtposv[ 'size' ] . $wvmlogovtposv[ 'unit' ] ) . '}';
          } else {
            $cssRulesXs .= '.webyx-logo-wrapper{display:none}';
          }
          if ( $wvmnav ) {
            $wvmnavposhz = isset( $ps[ 'wvmnavposhz' ] ) && in_array( $ps[ 'wvmnavposhz' ], array( 'left', 'center', 'right' ), true ) ? $ps[ 'wvmnavposhz' ] : 'left';
            $wvmnavbrgc = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'wvmnavbrgc', '#000000' );
            $wvmnavbrgboc = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'wvmnavbrgboc', '#ffffff' );
            $wvmnavbrgbc = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'wvmnavbrgbc', '#ffffff' );
            $wvmnavmaren = isset( $ps[ 'wvmnavmaren' ] ) && in_array( $ps[ 'wvmnavmaren' ], array( 'on', '' ), true ) ? $ps[ 'wvmnavmaren' ] : '';
            $wvmnavmar = isset( $ps[ 'wvmnavmar' ] ) && isset( $ps[ 'wvmnavmar' ][ 'unit' ] ) && in_array( $ps[ 'wvmnavmar' ][ 'unit' ], array( 'px' ), true ) && isset( $ps[ 'wvmnavmar' ][ 'top' ] ) && isset( $ps[ 'wvmnavmar' ][ 'right' ] ) && isset( $ps[ 'wvmnavmar' ][ 'bottom' ] ) && isset( $ps[ 'wvmnavmar' ][ 'left' ] ) ? $ps[ 'wvmnavmar' ] : array( 'unit' => 'px', 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'isLinked' => '' );
            $wvmnavitembc = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'wvmnavitembc', '#ffffff' );
            $wvmnavitembclight = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'wvmnavitembclight', '#ffffff' );
            $wvmnavitemdropcontdim = isset( $ps[ 'wvmnavitemdropcontdim' ] ) && isset( $ps[ 'wvmnavitemdropcontdim' ][ 'unit' ] ) && in_array( $ps[ 'wvmnavitemdropcontdim' ][ 'unit' ], array( 'px' ), true ) && isset( $ps[ 'wvmnavitemdropcontdim' ][ 'size' ] ) && filter_var( $ps[ 'wvmnavitemdropcontdim' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'wvmnavitemdropcontdim' ] : array( 'unit' => 'px', 'size' => 250 );
            $wvmnavitemfontdim = isset( $ps[ 'wvmnavitemfontdim' ] ) && isset( $ps[ 'wvmnavitemfontdim' ][ 'unit' ] ) && in_array( $ps[ 'wvmnavitemfontdim' ][ 'unit' ], array( 'px' ), true ) && isset( $ps[ 'wvmnavitemfontdim' ][ 'size' ] ) && filter_var( $ps[ 'wvmnavitemfontdim' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 12, 'max_range' => 32 ) ) ) ? $ps[ 'wvmnavitemfontdim' ] : array( 'unit' => 'px', 'size' => 16 );
            $wvmnavitemdim = isset( $ps[ 'wvmnavitemdim' ] ) && isset( $ps[ 'wvmnavitemdim' ][ 'unit' ] ) && in_array( $ps[ 'wvmnavitemdim' ][ 'unit' ], array( 'px' ), true ) && isset( $ps[ 'wvmnavitemdim' ][ 'size' ] ) && filter_var( $ps[ 'wvmnavitemdim' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 30, 'max_range' => 75 ) ) ) ? $ps[ 'wvmnavitemdim' ] : array( 'unit' => 'px', 'size' => 50 );
            $wvmnavitemtxtc = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'wvmnavitemtxtc', '#000000' );
            $wvmnavitemtxtclight = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'wvmnavitemtxtclight', '#000000' );
            if ( $wvmnavmaren ) {
              $cssRulesXs .= '.webyx-header .webyx-nav #webyx-toggle-btn{margin:' . esc_attr( $wvmnavmar[ 'top' ] . $wvmnavmar[ 'unit' ] ) . ' ' . esc_attr( $wvmnavmar[ 'right' ] . $wvmnavmar[ 'unit' ] ) . ' ' . esc_attr( $wvmnavmar[ 'bottom' ] . $wvmnavmar[ 'unit' ] ) . ' ' . esc_attr( $wvmnavmar[ 'left' ] . $wvmnavmar[ 'unit' ] ) . ';background-color:' . esc_attr( $wvmnavbrgbc ) . '}';
            }
            if ( 'center' === $wvmnavposhz ) {
              $cssRulesXs .= '.webyx-header .webyx-nav #webyx-toggle-btn{left:50vw;margin-left:-25px}';
            } else {
              $cssRulesXs .= '.webyx-header .webyx-nav #webyx-toggle-btn{' . esc_attr( $wvmnavposhz ) . ':0}';
            }
            $cssRulesXs .= "#webyx-toggle-btn{display: block}";
            $cssRulesXs .= '.webyx-header .webyx-nav #webyx-toggle-btn+div{list-style-type:none;padding:0;margin:0;position:fixed;left:0;padding-top:50px;transition:all 0.3s ease-in-out;transform:translateX(-300px);background-color:rgb(224,224,224);overflow-y:auto}';
            $cssRulesXs .= '.webyx-header .webyx-nav #webyx-toggle-btn+div ul#webyx-nav-container.webyx-nav-container{height:calc(100vh - 50px);overflow-y:scroll;-ms-overflow-style:none;scrollbar-width:none}';
            $cssRulesXs .= '.webyx-header .webyx-nav #webyx-toggle-btn+div ul#webyx-nav-container.webyx-nav-container::-webkit-scrollbar{display:none}';
            $cssRulesXs .= '.webyx-header .webyx-nav #webyx-toggle-btn+div{left:-100%;width:100%}';
            $cssRulesXs .= '.webyx-header .webyx-nav #webyx-toggle-btn{background-color:' . esc_attr( esc_attr( $wvmnavbrgbc ) ) . '}';
            $cssRulesXs .= '.webyx-header .webyx-nav #webyx-toggle-btn .webyx-bar{background-color:' . esc_attr( $wvmnavbrgc ) . '}';
            $cssRulesXs .= '.webyx-header .webyx-nav #webyx-toggle-btn + div{background-color:' . esc_attr( $wvmnavbrgboc ) . '}';
            $cssRulesXs .= '#webyx-nav ul li{background-color:' . esc_attr( $wvmnavitembc ) . '}';
            $cssRulesXs .= '#webyx-nav ul li:hover{background-color:'. esc_attr( $wvmnavitembclight ) . '}';
            $cssRulesXs .= '#webyx-nav a,#webyx-nav a:active,#webyx-nav a:visited,#webyx-nav a:focus{font-size:' . esc_attr( $wvmnavitemfontdim[ 'size' ] . $wvmnavitemfontdim[ 'unit' ] ) . ';color:' . esc_attr( $wvmnavitemtxtc ) . ';height:' . esc_attr( $wvmnavitemdim[ 'size' ] . $wvmnavitemdim[ 'unit' ] ) . ';line-height:' . esc_attr( $wvmnavitemdim[ 'size' ] . $wvmnavitemdim[ 'unit' ] ) . ';padding-right:' . esc_attr( ( $wvmnavitemdim[ 'size' ] + 10 ) . $wvmnavitemdim[ 'unit' ] ) . '}';
            $cssRulesXs .= '#webyx-nav span.webyx-menu-sec-spa{font-size:' . esc_attr( $wvmnavitemfontdim[ 'size' ] . $wvmnavitemfontdim[ 'unit' ] ) . ';color:' . esc_attr( $wvmnavitemtxtc ) . ';height:' . esc_attr( $wvmnavitemdim[ 'size' ] . $wvmnavitemdim[ 'unit' ] ) . ';line-height:' . esc_attr( $wvmnavitemdim[ 'size' ] . $wvmnavitemdim[ 'unit' ] ) . ';padding-right:' . esc_attr( ( $wvmnavitemdim[ 'size' ] + 10 ) . $wvmnavitemdim[ 'unit' ] ) . '}';
            $cssRulesXs .= '#webyx-nav a:hover,#webyx-nav a:hover{color:' . esc_attr( $wvmnavitemtxtclight ) . '}';
            $cssRulesXs .= '.menu-item-has-children>.webyx-menu-arrow{color:' . esc_attr( $wvmnavitemtxtc ) . ';height:' . esc_attr( $wvmnavitemdim[ 'size' ] . $wvmnavitemdim[ 'unit' ] ) . ';width:' . esc_attr( $wvmnavitemdim[ 'size' ] . $wvmnavitemdim[ 'unit' ] ) . ';line-height:' . esc_attr( $wvmnavitemdim[ 'size' ] . $wvmnavitemdim[ 'unit' ] ) . '}';
            $cssRulesXs .= '.menu-item-has-children>.webyx-menu-arrow:hover{color:' . $wvmnavitemtxtclight . '}';
            $cssRulesXs .= '.menu-item-has-children>.webyx-menu-arrow::after{line-height:' . esc_attr( $wvmnavitemdim[ 'size' ] . $wvmnavitemdim[ 'unit' ] ) . ';vertical-align:unset}';
          } else {
            if ( $wvdnav ) {
              $cssRulesXs .= '#webyx-nav{display:none}';
            }
          }
          $glb_mq_en = isset( $ps[ 'global_webyx_section_mq_enable' ] ) && in_array( $ps[ 'global_webyx_section_mq_enable' ], array( 'on', '' ), true ) ? $ps[ 'global_webyx_section_mq_enable' ] : '';                                    
          $glb_mq_xs = isset( $ps[ 'global_webyx_section_mq_xs' ] ) && ( isset( $ps[ 'global_webyx_section_mq_xs' ][ 'unit' ] ) && in_array( $ps[ 'global_webyx_section_mq_xs' ][ 'unit' ], array( 'px' ), true ) ) && ( isset( $ps[ 'global_webyx_section_mq_xs' ][ 'size' ] ) && filter_var( $ps[ 'global_webyx_section_mq_xs' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ) ? $ps[ 'global_webyx_section_mq_xs' ] : array( 'unit' => 'px', 'size' => 760 );
          $wvhdmqb = isset( $ps[ 'wvhdmqb' ] ) && ( isset( $ps[ 'wvhdmqb' ][ 'unit' ] ) && in_array( $ps[ 'wvhdmqb' ][ 'unit' ], array( 'px' ), true ) ) && ( isset( $ps[ 'wvhdmqb' ][ 'size' ] ) && filter_var( $ps[ 'wvhdmqb' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ) ? $ps[ 'wvhdmqb' ] : array( 'unit' => 'px', 'size' => 760 );
          $wv_hd_mq_um = $glb_mq_en ? $glb_mq_xs[ 'unit' ] : $wvhdmqb[ 'unit' ];
          $wv_hd_mq_val = ( $glb_mq_en ? ( $glb_mq_xs[ 'size' ] + 1 ) : ( $wvhdmqb[ 'size' ] + 1 ) ) . $wv_hd_mq_um;
          $wv_hd_mq_xs_val = ( $glb_mq_en ? $glb_mq_xs[ 'size' ] : $wvhdmqb[ 'size' ] ) . $wv_hd_mq_um;
          $cssMQ = '@media only screen and (min-width:' . esc_attr( $wv_hd_mq_val ) . '){' . $cssRules . '}';
          $cssMQXS = '@media only screen and (max-width:' . esc_attr( $wv_hd_mq_xs_val ) . '){' . $cssRulesXs . '}';
          $css =  $cssMQ . $cssMQXS;
          return ( '' !== $css ? '<style>' . $css . '</style>' : '' );
        }
        public function webyx_fep_print_css_validated ( $ps ) {
          $css = '';
          $css .= $this->webyx_fe_print_cs_css_validated( $ps ); 
          $css .= $this->webyx_fep_print_na_css_validated( $ps ); 
          $css .= $this->webyx_fep_print_nb_css_validated( $ps ); 
          $css .= $this->webyx_fep_print_imwh_css_validated( $ps ); 
          $css .= $this->webyx_fep_print_fs_css_validated( $ps ); 
          $css .= $this->webyx_fep_print_scrlb_css_validated( $ps ); 
          $css .= $this->webyx_fep_get_custom_css_validated( $ps ); 
          $css .= $this->webyx_fep_get_view_css_validated( $ps ); 
          return $css;
        }
        public function webyx_fe_print_cs_css_validated ( $ps ) {
          $hmsd = isset( $ps[ 'hmsd' ] ) && filter_var( $ps[ 'hmsd' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 300, 'max_range' => 1200 ) ) ) ? $ps[ 'hmsd' ] : 900;
          $hmcd = isset( $ps[ 'hmcd' ] ) && in_array( $ps[ 'hmcd' ], $this->cubic_bezier_animation, true ) ? $ps[ 'hmcd' ] : 'cubic-bezier(0.64,0,0.34,1)';
          $vmsd = isset( $ps[ 'vmsd' ] ) && filter_var( $ps[ 'vmsd' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 300, 'max_range' => 1200 ) ) ) ? $ps[ 'vmsd' ] : 900;
          $vmcd = isset( $ps[ 'vmcd' ] ) && in_array( $ps[ 'vmcd' ], $this->cubic_bezier_animation, true ) ? $ps[ 'vmcd' ] : 'cubic-bezier(0.64,0,0.34,1)';
          $hmsm = isset( $ps[ 'hmsm' ] ) && filter_var( $ps[ 'hmsm' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 300, 'max_range' => 1200 ) ) ) ? $ps[ 'hmsm' ] : 300;
          $hmcm = isset( $ps[ 'hmcm' ] ) && in_array( $ps[ 'hmcm' ], $this->cubic_bezier_animation, true ) ? $ps[ 'hmcm' ] : 'cubic-bezier(0.64,0,0.34,1)';
          $vmsm = isset( $ps[ 'vmsm' ] ) && filter_var( $ps[ 'vmsm' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 300, 'max_range' => 1200 ) ) ) ? $ps[ 'vmsm' ] : 300;
          $vmcm = isset( $ps[ 'vmcm' ] ) && in_array( $ps[ 'vmcm' ], $this->cubic_bezier_animation, true ) ? $ps[ 'vmcm' ] : 'cubic-bezier(0.64,0,0.34,1)';
          $body = '';
          $body .= '.webyx-slide-viewport-dsk{transition:left ' . esc_attr( $hmsd ) . 'ms ' . esc_attr( $hmcd ) . ',top ' . esc_attr( $vmsd ) . 'ms ' . esc_attr( $vmcd ) . '}.webyx-slide-viewport-mobile{transition:left ' . esc_attr( $hmsm ) . 'ms ' . esc_attr( $hmcm ) . ',top ' . esc_attr( $vmsm ) . 'ms ' . esc_attr( $vmcm ) . '}';
          $body .= '.webyx-fade-viewport-dsk-hz{transition:opacity ' . esc_attr( $hmsd . 'ms' ) . esc_attr( $hmcd ) . '}';
          $body .= '.webyx-fade-viewport-dsk-vt{transition:opacity ' . esc_attr( $vmsd . 'ms' ) . esc_attr( $vmcd ) . '}';
          $body .= '.webyx-fade-viewport-mob-hz{transition:opacity ' . esc_attr( $hmsm . 'ms' ) . esc_attr( $hmcm ) . '}';
          $body .= '.webyx-fade-viewport-mob-vt{transition:opacity ' . esc_attr( $vmsm . 'ms' ) . esc_attr( $vmcm ) . '}';
          return '<style>' . $body . '</style>';
        }
        public function webyx_fep_print_na_css_validated ( $ps ) {
          $av = isset( $ps[ 'av' ] ) && in_array( $ps[ 'av' ], array( 'on', '' ), true ) ? $ps[ 'av' ] : '';
          $body = '';
          if ( 'on' === $av ) {
            $mvnast = isset( $ps[ 'mvnast' ] ) && in_array( $ps[ 'mvnast' ], array( 'small', 'medium', 'large' ), true )  ? $ps[ 'mvnast' ] : 'medium'; 
            $mvnatt = isset( $ps[ 'mvnatt' ] ) && in_array( $ps[ 'mvnatt' ], array( 'thin', 'standard', 'thick' ), true ) ? $ps[ 'mvnatt' ] : 'standard';
            $mvnact = isset( $ps[ 'mvnact' ] ) && in_array( $ps[ 'mvnact' ], array( 'on', '' ), true ) ? $ps[ 'mvnact' ] : '';
            $mvnaoc = isset( $ps[ 'mvnaoc' ] ) && in_array( $ps[ 'mvnaoc' ], array( 'on', '' ), true ) ? $ps[ 'mvnaoc' ] : '';  
            $mvnaot = isset( $ps[ 'mvnaot' ] ) && filter_var( $ps[ 'mvnaot' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'mvnaot' ] : 0;                
            $mvnaor = isset( $ps[ 'mvnaor' ] ) && filter_var( $ps[ 'mvnaor' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'mvnaor' ] : 0;                 
            $mvnaob = isset( $ps[ 'mvnaob' ] ) && filter_var( $ps[ 'mvnaob' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'mvnaob' ] : 0;                 
            $mvnaol = isset( $ps[ 'mvnaol' ] ) && filter_var( $ps[ 'mvnaol' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'mvnaol' ] : 0;                 
            $mvnaa = isset( $ps[ 'mvnaa' ] ) && in_array( $ps[ 'mvnaa' ], array( 'on', '' ), true ) ? $ps[ 'mvnaa'  ] : '';                
            $mvnaad = isset( $ps[ 'mvnaad' ] ) && in_array( $ps[ 'mvnaad' ], array( 'small', 'medium', 'large' ), true ) ? $ps[ 'mvnaad' ] : 'medium';          
            $mvnaac = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'mvnaac', '#00000066' );
            $mvnac = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'mvnac', '#000000' );
            $mvnacl = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'mvnacl', '#00000066' );
            $mvnatp = isset( $ps[ 'mvnatp' ] ) && in_array( $ps[ 'mvnatp' ], array( 'standard', 'image' ), true )  ? $ps[ 'mvnatp' ] : 'standard';
            $mvnabkgimg = isset( $ps[ 'mvnabkgimg' ] ) ? $ps[ 'mvnabkgimg' ] : array( 'url' => '' );
            if ( 'image' === $mvnatp ) {
              $body .= '.webyx-arrow-icon{display:none}.webyx-arrow-viewport-bkg-area-colour{background-image:url(' . esc_url( $mvnabkgimg[ 'url' ] ) . ');background-repeat:no-repeat;background-position:center;background-size:cover}';
            } else {
              switch ( $mvnast . '-' . $mvnatt ) {
                case 'small-thin': 
                  $body .= '.webyx-arrow-viewport-icon{width:10px;height:10px}.webyx-arrow-viewport-icon-bottom,.webyx-arrow-viewport-icon-top,.webyx-arrow-viewport-icon-left,.webyx-arrow-viewport-icon-right{margin-left:-6px}.webyx-arrow-viewport-icon-borders{border-top-width:2px;border-right-width:2px}';
                  break; 
                case 'medium-thin':
                  $body .= '.webyx-arrow-viewport-icon{width:20px;height:20px}.webyx-arrow-viewport-icon-bottom,.webyx-arrow-viewport-icon-top,.webyx-arrow-viewport-icon-left,.webyx-arrow-viewport-icon-right{margin-left:-11px}.webyx-arrow-viewport-icon-borders{border-top-width:2px;border-right-width:2px}';
                  break; 
                case 'large-thin':
                  $body .= '.webyx-arrow-viewport-icon{width:30px;height:30px}.webyx-arrow-viewport-icon-bottom,.webyx-arrow-viewport-icon-top,.webyx-arrow-viewport-icon-left,.webyx-arrow-viewport-icon-right{margin-left:-16px}.webyx-arrow-viewport-icon-borders{border-top-width:2px;border-right-width:2px}';
                  break; 
                case 'small-standard':
                  $body .= '.webyx-arrow-viewport-icon{width:10px;height:10px}.webyx-arrow-viewport-icon-bottom,.webyx-arrow-viewport-icon-top,.webyx-arrow-viewport-icon-left,.webyx-arrow-viewport-icon-right{margin-left:-6px}.webyx-arrow-viewport-icon-borders{border-top-width:4px;border-right-width:4px}';
                  break; 
                case 'medium-standard':
                  $body .= '.webyx-arrow-viewport-icon{width:20px;height:20px}.webyx-arrow-viewport-icon-bottom,.webyx-arrow-viewport-icon-top,.webyx-arrow-viewport-icon-left,.webyx-arrow-viewport-icon-right{margin-left:-12px}.webyx-arrow-viewport-icon-borders{border-top-width:4px;border-right-width:4px}'; 
                  break; 
                case 'large-standard':
                  $body .= '.webyx-arrow-viewport-icon{width:30px;height:30px}.webyx-arrow-viewport-icon-bottom,.webyx-arrow-viewport-icon-top,.webyx-arrow-viewport-icon-left,.webyx-arrow-viewport-icon-right{margin-left:-16px}.webyx-arrow-viewport-icon-borders{border-top-width:4px;border-right-width:4px}';
                  break; 
                case 'small-thick':
                  $body .= '.webyx-arrow-viewport-icon{width:10px;height:10px}.webyx-arrow-viewport-icon-bottom,.webyx-arrow-viewport-icon-top,.webyx-arrow-viewport-icon-left,.webyx-arrow-viewport-icon-right{margin-left:-9px}.webyx-arrow-viewport-icon-borders{border-top-width:8px;border-right-width:8px}';
                  break; 
                case 'medium-thick':
                  $body .= '.webyx-arrow-viewport-icon{width:20px;height:20px}.webyx-arrow-viewport-icon-bottom,.webyx-arrow-viewport-icon-top,.webyx-arrow-viewport-icon-left,.webyx-arrow-viewport-icon-right{margin-left:-14px}.webyx-arrow-viewport-icon-borders{border-top-width:8px;border-right-width:8px}';
                  break; 
                case 'large-thick':
                  $body .= '.webyx-arrow-viewport-icon{width:30px;height:30px}.webyx-arrow-viewport-icon-bottom,.webyx-arrow-viewport-icon-top,.webyx-arrow-viewport-icon-left,.webyx-arrow-viewport-icon-right{margin-left:-19px}.webyx-arrow-viewport-icon-borders{border-top-width:8px;border-right-width:8px}';
                  break;
              } 
              if ( 'on' === $mvnact ) {
                $body .= '.webyx-arrow-viewport-icon-borders{border-radius:20%}';
              }
              if ( 'on' === $mvnaa ) {
                $body .= '.webyx-arrow-viewport-bkg-area-colour{background-color:' . esc_attr( $mvnaac ) . '}';
              }
            }
            if ( 'on' === $mvnaoc ) {
              $body .= '.webyx-arrow-viewport-top{margin-top:' . esc_attr( $mvnaot ) . 'px}.webyx-arrow-viewport-right{margin-right:' . esc_attr( $mvnaor ) . 'px}.webyx-arrow-viewport-bottom{margin-bottom:' . esc_attr( $mvnaob ) . 'px}.webyx-arrow-viewport-left{margin-left:' . esc_attr( $mvnaol ) . 'px}';
            }
            switch ( $mvnaad ) {
              case 'small': 
                $body .= '.webyx-arrow-viewport{width:80px;height:50px}.webyx-arrow-viewport-top{margin-left:-40px}.webyx-arrow-viewport-right{right:-15px;margin-top:-25px}.webyx-arrow-viewport-bottom{margin-left:-40px}.webyx-arrow-viewport-left{left:-15px;margin-top:-25px}';
                break; 
              case 'medium':
                $body .= '.webyx-arrow-viewport{width:150px;height:70px}.webyx-arrow-viewport-top{margin-left:-75px}.webyx-arrow-viewport-right{right:-40px;margin-top:-35px}.webyx-arrow-viewport-bottom{margin-left:-75px}.webyx-arrow-viewport-left{left:-40px;margin-top:-35px}';
                break; 
              case 'large':
                $body .= '.webyx-arrow-viewport{width:300px;height:90px}.webyx-arrow-viewport-top{margin-left:-150px}.webyx-arrow-viewport-right{right:-105px;margin-top:-45px}.webyx-arrow-viewport-bottom{margin-left:-150px}.webyx-arrow-viewport-left{left:-105px;margin-top:-45px}';
                break;
            }
            $body .= '.webyx-arrow-viewport-icon-borders{border-top-color:' . esc_attr( $mvnac ) . ';border-right-color:' . esc_attr( $mvnac ) . '}.webyx-arrow-viewport-icon-borders-fixed{border-top-color:' . esc_attr( $mvnac ) . ';border-right-color:' . esc_attr( $mvnac ) . '}.webyx-arrow-viewport-icon-borders-visible{border-top-color:' . esc_attr( $mvnac ) . ';border-right-color:' . esc_attr( $mvnac ) . '}';
            $body .= '.webyx-arrow-viewport-icon-borders-fixed{border-top-color:' . esc_attr( $mvnacl ) . ';border-right-color:' . esc_attr( $mvnacl ) . '}.webyx-arrow-viewport-icon-borders-visible{border-top-color:' . esc_attr( $mvnac ) . ';border-right-color:' . esc_attr( $mvnac ) . '}';
            return '<style>' . $body . '</style>';
          } else {
            return $body;
          }
        }
        public function webyx_fep_print_nb_css_validated ( $ps ) {
          $body = '';
          $mvndbst = isset( $ps[ 'mvndbst' ] ) && in_array( $ps[ 'mvndbst' ], array( 'scale', 'stroke', 'small_stroke', 'fill_in', 'fill_up', 'fall', 'puff', 'scale_sq', 'stroke_sq', 'small_stroke_sq', 'fill_in_sq', 'fill_up_sq', 'fall_sq', 'puff_sq', 'scale_sq_rt', 'stroke_sq_rt', 'small_stroke_sq_rt', 'fill_in_sq_rt', 'fill_up_sq_rt', 'fall_sq_rt', 'puff_sq_rt', 'scale_line', 'stroke_line', 'small_stroke_line', 'fill_in_line', 'fill_up_line', 'fall_line', 'puff_line' ), true ) ? $ps[ 'mvndbst' ] : 'scale';               
          $mvndc = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'mvndc', '#000000' );       
          $mvndcl = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'mvndcl', '#00000066' );
          $dbkgace = isset( $ps[ 'dbkgace' ] ) && in_array( $ps[ 'dbkgace' ], array( 'on', '' ), true ) ? $ps[ 'dbkgace' ] : '';                    
          $dbkgac = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'dbkgac', '#00000066' );
          $mvndttace = isset( $ps[ 'mvndttace' ] ) && in_array( $ps[ 'mvndttace' ], array( 'on', '' ), true ) ? $ps[ 'mvndttace' ] : '';                    
          $mvndttac = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'mvndttac', '#ffffff' );
          $dtvoff = isset( $ps[ 'dtvoff' ] ) && in_array( $ps[ 'dtvoff' ], array( 'on', '' ), true ) ? $ps[ 'dtvoff' ] : '';                    
          $dvp = isset( $ps[ 'dvp' ] ) && in_array( $ps[ 'dvp' ], array( 'left', 'right' ), true ) ? $ps[ 'dvp' ] : 'right';               
          $dtvoffdsk = isset( $ps[ 'dtvoffdsk' ] ) && filter_var( $ps[ 'dtvoffdsk' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'dtvoffdsk' ] : 0;                      
          $dtvoffmob = isset( $ps[ 'dtvoffmob' ] ) && filter_var( $ps[ 'dtvoffmob' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'dtvoffmob' ] : 0;                      
          $dthoff = isset( $ps[ 'dthoff' ] ) && in_array( $ps[ 'dthoff' ], array( 'on', '' ), true ) ? $ps[ 'dthoff' ] : '';                    
          $dhp = isset( $ps[ 'dhp' ] ) && in_array( $ps[ 'dhp' ], array( 'top', 'bottom' ), true ) ? $ps[ 'dhp' ] : 'bottom';              
          $dthoffdsk = isset( $ps[ 'dthoffdsk' ] ) && filter_var( $ps[ 'dthoffdsk' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'dthoffdsk' ] : 0;                       
          $dthoffmob = isset( $ps[ 'dthoffmob' ] ) && filter_var( $ps[ 'dthoffmob' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'dthoffmob' ] : 0;                       
          $mvndttc = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'mvndttc', '#000000' );               
          $mvndttcl = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'mvndttcl', '#00000066' );
          switch ( $mvndbst ) { 
            case 'scale':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{background-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{background-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{top:8px;left:8px;background-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{top:8px;left:8px;background-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{background-color:' . esc_attr( $mvndc ) . '}';
              break;
            case 'stroke':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{background-color:' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:50%;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;background-color:rgba(255,255,255,0);border-radius:50%;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease,background-color .3s ease;transition:box-shadow .3s ease,background-color .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:50%;height:100%}.webyx-nav-vt-dsk-left{left:14px}.webyx-nav-vt-dsk-right{right:14px}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:50%;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:50%;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease,background-color .3s ease;transition:box-shadow .3s ease,background-color .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:50%;height:100%}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-top{top:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-bottom{bottom:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk{background-color:' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{position:absolute;top:4px;left:4px;width:12px;height:12px;outline:0;border-radius:50%;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:' . esc_attr( $mvndcl ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;background-color:rgba(255,255,255,0);border-radius:50%;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease,background-color .3s ease;transition:box-shadow .3s ease,background-color .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:50%;height:100%}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:50%;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:rgba(255,255,255,0);border-radius:50%;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease,background-color .3s ease;transition:box-shadow .3s ease,background-color .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:50%;height:100%}';
              break; 
            case 'small_stroke':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:50%;text-indent:-999em;cursor:pointer;overflow:hidden;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;border-radius:50%;background-color:' . esc_attr( $mvndcl ) . ';-webkit-transition:background-color .3s ease,-webkit-transform .3s ease;transition:background-color .3s ease,transform .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk.webyx-dot-vt-dsk::after{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk:hover{background-color:transparent}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:50%;height:100%;-webkit-transform:scale(.3);transform:scale(.3)}.webyx-nav-vt-dsk-left{left:14px}.webyx-nav-vt-dsk-right{right:14px}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:50%;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:' . esc_attr( $mvndcl ) . ';box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;border-radius:50%;background-color:' . esc_attr( $mvndcl ) . ';-webkit-transition:background-color .3s ease,-webkit-transform .3s ease;transition:background-color .3s ease,transform .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob.webyx-dot-vt-mob::after{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob:hover{background-color:transparent}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:50%;height:100%;-webkit-transform:scale(.3);transform:scale(.3)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-top{top:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-bottom{bottom:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:50%;text-indent:-999em;cursor:pointer;overflow:hidden;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;border-radius:50%;background-color:' . esc_attr( $mvndcl ) . ';-webkit-transition:background-color .3s ease,-webkit-transform .3s ease;transition:background-color .3s ease,transform .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk.webyx-dot-hz-dsk::after{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk:hover{background-color:transparent}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:50%;height:100%;-webkit-transform:scale(.3);transform:scale(.3)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:50%;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:' . esc_attr( $mvndcl ) . ';box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;border-radius:50%;background-color:' . esc_attr( $mvndcl ) . ';-webkit-transition:background-color .3s ease,-webkit-transform .3s ease;transition:background-color .3s ease,transform .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob.webyx-dot-hz-mob::after{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob:hover{background-color:transparent}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:50%;height:100%;-webkit-transform:scale(.3);transform:scale(.3)}';
              break; 
            case 'fill_in':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk::after{box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndcl ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:50%;text-indent:-999em;cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;background-color:transparent;border-radius:50%;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{background-color:' . esc_attr( $mvndc ) . ';box-shadow:inset 0 0 0 8px ' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:50%;height:100%}.webyx-nav-vt-dsk-left{left:14px}.webyx-nav-vt-dsk-right{right:14px}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;background-color:transparent;border-radius:50%;text-indent:-999em;cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;background-color:transparent;border-radius:50%;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{background-color:transparent;box-shadow:inset 0 0 0 8px ' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:50%;height:100%}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-top{top:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-bottom{bottom:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk{width:20px;height:20px;position:relative;display:inline-block}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk::after{box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndcl ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{position:absolute;top:4px;left:4px;width:12px;height:12px;outline:0;border-radius:50%;text-indent:-999em;cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;background-color:rgba(255,255,255,0);border-radius:50%;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{background-color:transparent;box-shadow:inset 0 0 0 8px ' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:50%;height:100%}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob{width:20px;height:20px;position:relative;display:inline-block}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;background-color:transparent;border-radius:50%;text-indent:-999em;cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;background-color:transparent;border-radius:50%;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{background-color:transparent;box-shadow:inset 0 0 0 8px ' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:50%;height:100%}';
              break; 
            case 'fill_up':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{background-color:' . esc_attr( $mvndcl ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:50%;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:background .3s ease;transition:background .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:50%;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';-webkit-transition:height .3s ease;transition:height .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:50%;height:100%}.webyx-nav-vt-dsk-left{left:14px}.webyx-nav-vt-dsk-right{right:14px}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:50%;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:background .3s ease;transition:background .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:50%;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';-webkit-transition:height .3s ease;transition:height .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:50%;height:100%;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-top{top:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-bottom{bottom:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk{width:20px;height:20px;position:relative;display:inline-block}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk{background-color:' . esc_attr( $mvndcl ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{position:absolute;top:4px;left:4px;width:12px;height:12px;outline:0;border-radius:50%;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:background .3s ease;transition:background .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:50%;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';-webkit-transition:height .3s ease;transition:height .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:50%;height:100%;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:50%;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:background .3s ease;transition:background .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:50%;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';-webkit-transition:height .3s ease;transition:height .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:50%;height:100%;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}';
              break; 
            case 'fall':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1);background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:50%;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,background-color .3s ease;transition:transform .3s ease,opacity .3s ease,background-color .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;visibility:hidden;background-color:' . esc_attr( $mvndcl ) . ';border-radius:50%;opacity:0;-webkit-transform:translateX(-200%);transform:translateX(-200%);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,visibility 0s .3s;transition:transform .3s ease,opacity .3s ease,visibility 0s .3s}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk.webyx-dot-vt-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;height:100%;border-radius:50%;background-color:transparent;opacity:1;background-color:' . esc_attr( $mvndc ) . ';visibility:visible;-webkit-transition:-webkit-transform .3s ease,opacity .3s ease;transition:transform .3s ease,opacity .3s ease;-webkit-transform:translateX(0);transform:translateX(0)}.webyx-nav-vt-dsk-left{left:14px}.webyx-nav-vt-dsk-right{right:14px}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:50%;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,background-color .3s ease;transition:transform .3s ease,opacity .3s ease,background-color .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;visibility:hidden;background-color:' . esc_attr( $mvndcl ) . ';border-radius:50%;opacity:0;-webkit-transform:translateX(-200%);transform:translateX(-200%);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,visibility 0s .3s;transition:transform .3s ease,opacity .3s ease,visibility 0s .3s}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;height:100%;border-radius:50%;background-color:transparent;opacity:1;background-color:' . esc_attr( $mvndc ) . ';visibility:visible;-webkit-transition:-webkit-transform .3s ease,opacity .3s ease;transition:transform .3s ease,opacity .3s ease;-webkit-transform:translateX(0);transform:translateX(0)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-top{top:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-bottom{bottom:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1);background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{position:absolute;top:4px;left:4px;width:12px;height:12px;outline:0;border-radius:50%;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,background-color .3s ease;transition:transform .3s ease,opacity .3s ease,background-color .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;visibility:hidden;background-color:' . esc_attr( $mvndcl ) . ';border-radius:50%;opacity:0;-webkit-transform:translateY(-200%);transform:translateY(-200%);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,visibility 0s .3s;transition:transform .3s ease,opacity .3s ease,visibility 0s .3s}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk.webyx-dot-hz-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;height:100%;border-radius:50%;background-color:transparent;opacity:1;background-color:' . esc_attr( $mvndc ) . ';visibility:visible;-webkit-transition:-webkit-transform .3s ease,opacity .3s ease;transition:transform .3s ease,opacity .3s ease;-webkit-transform:translateY(0);transform:translateY(0)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:50%;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,background-color .3s ease;transition:transform .3s ease,opacity .3s ease,background-color .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;visibility:hidden;background-color:' . esc_attr( $mvndcl ) . ';border-radius:50%;opacity:0;-webkit-transform:translateY(-200%);transform:translateY(-200%);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,visibility 0s .3s;transition:transform .3s ease,opacity .3s ease,visibility 0s .3s}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob.webyx-dot-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;height:100%;border-radius:50%;background-color:transparent;opacity:1;background-color:' . esc_attr( $mvndc ) . ';visibility:visible;-webkit-transition:-webkit-transform .3s ease,opacity .3s ease;transition:transform .3s ease,opacity .3s ease;-webkit-transform:translateY(0);transform:translateY(0)}';
              break; 
            case 'puff':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk{position:absolute;width:12px;height:12px;top:2px;left:2px;outline:0;border-radius:50%;text-indent:-999em;cursor:pointer;border:2px solid ' . esc_attr( $mvndc ) . ';background:' . esc_attr( $mvndcl ) . ';-webkit-transition:border-color .3s ease;transition:border-color .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk:hover{border-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after{content:"";position:absolute;top:0;left:0;width:100%;height:100%;visibility:hidden;background:' . esc_attr( $mvndcl ) . ';border-radius:50%;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';opacity:0;-webkit-transform:scale(3);transform:scale(3);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease,visibility 0s .3s;transition:opacity .3s ease,transform .3s ease,visibility 0s .3s}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{border:2px solid transparent}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk::after{content:"";position:absolute;top:-2px;left:-2px;height:0;width:100%;height:100%;visibility:visible;border:2px solid transparent;opacity:1;-webkit-transform:scale(1);transform:scale(1);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease;transition:opacity .3s ease,transform .3s ease}.webyx-nav-vt-dsk-left{left:14px}.webyx-nav-vt-dsk-right{right:14px}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{position:absolute;width:8px;height:8px;top:4px;left:4px;outline:0;border-radius:50%;text-indent:-999em;cursor:pointer;border:2px solid ' . esc_attr( $mvndc ) . ';background:' . esc_attr( $mvndcl ) . ';-webkit-transition:border-color .3s ease;transition:border-color .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after{content:"";position:absolute;top:0;left:0;width:100%;height:100%;visibility:hidden;background:' . esc_attr( $mvndcl ) . ';border-radius:50%;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';opacity:0;-webkit-transform:scale(3);transform:scale(3);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease,visibility 0s .3s;transition:opacity .3s ease,transform .3s ease,visibility 0s .3s}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{border:2px solid transparent}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob::after{content:"";position:absolute;top:-2px;left:-2px;height:0;width:100%;height:100%;visibility:visible;border:2px solid transparent;opacity:1;-webkit-transform:scale(1);transform:scale(1);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease;transition:opacity .3s ease,transform .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-top{top:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-bottom{bottom:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk{width:20px;height:20px;position:relative;display:inline-block}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{position:absolute;width:12px;height:12px;top:2px;left:2px;outline:0;border-radius:50%;text-indent:-999em;cursor:pointer;border:2px solid ' . esc_attr( $mvndc ) . ';background:' . esc_attr( $mvndcl ) . ';-webkit-transition:border-color .3s ease;transition:border-color .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk:hover{border-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after{content:"";position:absolute;top:0;left:0;width:100%;height:100%;visibility:hidden;background:' . esc_attr( $mvndcl ) . ';border-radius:50%;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';opacity:0;-webkit-transform:scale(3);transform:scale(3);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease,visibility 0s .3s;transition:opacity .3s ease,transform .3s ease,visibility 0s .3s}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{border:2px solid transparent}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk.webyx-dot-hz-dsk:hover{border-color:transparent}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk::after{content:"";position:absolute;top:-2px;left:-2px;height:0;width:100%;height:100%;visibility:visible;border:2px solid transparent;opacity:1;-webkit-transform:scale(1);transform:scale(1);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease;transition:opacity .3s ease,transform .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-current-hz-persistent-dsk{opacity:1;color:#000}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{position:absolute;width:8px;height:8px;top:4px;left:4px;outline:0;border-radius:50%;text-indent:-999em;cursor:pointer;border:2px solid ' . esc_attr( $mvndc ) . ';background:' . esc_attr( $mvndcl ) . ';-webkit-transition:border-color .3s ease;transition:border-color .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after{content:"";position:absolute;top:0;left:0;width:100%;height:100%;visibility:hidden;background:' . esc_attr( $mvndcl ) . ';border-radius:50%;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';opacity:0;-webkit-transform:scale(3);transform:scale(3);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease,visibility 0s .3s;transition:opacity .3s ease,transform .3s ease,visibility 0s .3s}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{border:2px solid transparent}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob::after{content:"";position:absolute;top:-2px;left:-2px;height:0;width:100%;height:100%;visibility:visible;border:2px solid transparent;opacity:1;-webkit-transform:scale(1);transform:scale(1);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease;transition:opacity .3s ease,transform .3s ease}';
              break; 
            case 'scale_sq':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk,.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk,.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob,.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob,.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob,.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{border-radius:unset}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{background-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{background-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{top:8px;left:8px;background-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{top:8px;left:8px;background-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{background-color:' . esc_attr( $mvndc ) . '}';
              break;
            case 'stroke_sq':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{background-color:' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:unset;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;background-color:rgba(255,255,255,0);border-radius:unset;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease,background-color .3s ease;transition:box-shadow .3s ease,background-color .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}.webyx-nav-vt-dsk-left{left:14px}.webyx-nav-vt-dsk-right{right:14px}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:unset;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease,background-color .3s ease;transition:box-shadow .3s ease,background-color .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-top{top:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-bottom{bottom:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk{background-color:' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{position:absolute;top:4px;left:4px;width:12px;height:12px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:' . esc_attr( $mvndcl ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;background-color:rgba(255,255,255,0);border-radius:unset;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease,background-color .3s ease;transition:box-shadow .3s ease,background-color .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:unset;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:rgba(255,255,255,0);border-radius:unset;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease,background-color .3s ease;transition:box-shadow .3s ease,background-color .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}';
              break;
            case 'small_stroke_sq':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;border-radius:unset;background-color:' . esc_attr( $mvndcl ) . ';-webkit-transition:background-color .3s ease,-webkit-transform .3s ease;transition:background-color .3s ease,transform .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk.webyx-dot-vt-dsk::after{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk:hover{background-color:transparent}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%;-webkit-transform:scale(.3);transform:scale(.3)}.webyx-nav-vt-dsk-left{left:14px}.webyx-nav-vt-dsk-right{right:14px}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:' . esc_attr( $mvndcl ) . ';box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;border-radius:unset;background-color:' . esc_attr( $mvndcl ) . ';-webkit-transition:background-color .3s ease,-webkit-transform .3s ease;transition:background-color .3s ease,transform .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob.webyx-dot-vt-mob::after{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob:hover{background-color:transparent}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%;-webkit-transform:scale(.3);transform:scale(.3)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-top{top:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-bottom{bottom:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;border-radius:unset;background-color:' . esc_attr( $mvndcl ) . ';-webkit-transition:background-color .3s ease,-webkit-transform .3s ease;transition:background-color .3s ease,transform .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk.webyx-dot-hz-dsk::after{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk:hover{background-color:transparent}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%;-webkit-transform:scale(.3);transform:scale(.3)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:' . esc_attr( $mvndcl ) . ';box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;border-radius:unset;background-color:' . esc_attr( $mvndcl ) . ';-webkit-transition:background-color .3s ease,-webkit-transform .3s ease;transition:background-color .3s ease,transform .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob.webyx-dot-hz-mob::after{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob:hover{background-color:transparent}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%;-webkit-transform:scale(.3);transform:scale(.3)}';
              break;
            case 'fill_in_sq':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk::after{box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndcl ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;background-color:transparent;border-radius:unset;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{background-color:' . esc_attr( $mvndc ) . ';box-shadow:inset 0 0 0 8px ' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}.webyx-nav-vt-dsk-left{left:14px}.webyx-nav-vt-dsk-right{right:14px}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;background-color:transparent;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;background-color:transparent;border-radius:unset;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{background-color:transparent;box-shadow:inset 0 0 0 8px ' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-top{top:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-bottom{bottom:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk{width:20px;height:20px;position:relative;display:inline-block}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk::after{box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndcl ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{position:absolute;top:4px;left:4px;width:12px;height:12px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;background-color:rgba(255,255,255,0);border-radius:unset;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{background-color:transparent;box-shadow:inset 0 0 0 8px ' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob{width:20px;height:20px;position:relative;display:inline-block}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;background-color:transparent;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;background-color:transparent;border-radius:unset;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{background-color:transparent;box-shadow:inset 0 0 0 8px ' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}';
              break; 
            case 'fill_up_sq':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{background-color:' . esc_attr( $mvndcl ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:background .3s ease;transition:background .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';-webkit-transition:height .3s ease;transition:height .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}.webyx-nav-vt-dsk-left{left:14px}.webyx-nav-vt-dsk-right{right:14px}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:background .3s ease;transition:background .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';-webkit-transition:height .3s ease;transition:height .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-top{top:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-bottom{bottom:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk{width:20px;height:20px;position:relative;display:inline-block}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk{background-color:' . esc_attr( $mvndcl ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{position:absolute;top:4px;left:4px;width:12px;height:12px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:background .3s ease;transition:background .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';-webkit-transition:height .3s ease;transition:height .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:background .3s ease;transition:background .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';-webkit-transition:height .3s ease;transition:height .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}';
              break;
            case 'fall_sq':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1);background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:unset;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,background-color .3s ease;transition:transform .3s ease,opacity .3s ease,background-color .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;visibility:hidden;background-color:' . esc_attr( $mvndcl ) . ';border-radius:unset;opacity:0;-webkit-transform:translateX(-200%);transform:translateX(-200%);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,visibility 0s .3s;transition:transform .3s ease,opacity .3s ease,visibility 0s .3s}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk.webyx-dot-vt-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;height:100%;border-radius:unset;background-color:transparent;opacity:1;background-color:' . esc_attr( $mvndc ) . ';visibility:visible;-webkit-transition:-webkit-transform .3s ease,opacity .3s ease;transition:transform .3s ease,opacity .3s ease;-webkit-transform:translateX(0);transform:translateX(0)}.webyx-nav-vt-dsk-left{left:14px}.webyx-nav-vt-dsk-right{right:14px}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:unset;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,background-color .3s ease;transition:transform .3s ease,opacity .3s ease,background-color .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;visibility:hidden;background-color:' . esc_attr( $mvndcl ) . ';border-radius:unset;opacity:0;-webkit-transform:translateX(-200%);transform:translateX(-200%);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,visibility 0s .3s;transition:transform .3s ease,opacity .3s ease,visibility 0s .3s}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;height:100%;border-radius:unset;background-color:transparent;opacity:1;background-color:' . esc_attr( $mvndc ) . ';visibility:visible;-webkit-transition:-webkit-transform .3s ease,opacity .3s ease;transition:transform .3s ease,opacity .3s ease;-webkit-transform:translateX(0);transform:translateX(0)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-top{top:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-bottom{bottom:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1);background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{position:absolute;top:4px;left:4px;width:12px;height:12px;outline:0;border-radius:unset;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,background-color .3s ease;transition:transform .3s ease,opacity .3s ease,background-color .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;visibility:hidden;background-color:' . esc_attr( $mvndcl ) . ';border-radius:unset;opacity:0;-webkit-transform:translateY(-200%);transform:translateY(-200%);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,visibility 0s .3s;transition:transform .3s ease,opacity .3s ease,visibility 0s .3s}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk.webyx-dot-hz-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;height:100%;border-radius:unset;background-color:transparent;opacity:1;background-color:' . esc_attr( $mvndc ) . ';visibility:visible;-webkit-transition:-webkit-transform .3s ease,opacity .3s ease;transition:transform .3s ease,opacity .3s ease;-webkit-transform:translateY(0);transform:translateY(0)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:unset;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,background-color .3s ease;transition:transform .3s ease,opacity .3s ease,background-color .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;visibility:hidden;background-color:' . esc_attr( $mvndcl ) . ';border-radius:unset;opacity:0;-webkit-transform:translateY(-200%);transform:translateY(-200%);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,visibility 0s .3s;transition:transform .3s ease,opacity .3s ease,visibility 0s .3s}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob.webyx-dot-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;height:100%;border-radius:unset;background-color:transparent;opacity:1;background-color:' . esc_attr( $mvndc ) . ';visibility:visible;-webkit-transition:-webkit-transform .3s ease,opacity .3s ease;transition:transform .3s ease,opacity .3s ease;-webkit-transform:translateY(0);transform:translateY(0)}';
              break; 
            case 'puff_sq':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk{position:absolute;width:12px;height:12px;top:2px;left:2px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;border:2px solid ' . esc_attr( $mvndc ) . ';background:' . esc_attr( $mvndcl ) . ';-webkit-transition:border-color .3s ease;transition:border-color .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk:hover{border-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after{content:"";position:absolute;top:0;left:0;width:100%;height:100%;visibility:hidden;background:' . esc_attr( $mvndcl ) . ';border-radius:unset;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';opacity:0;-webkit-transform:scale(3);transform:scale(3);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease,visibility 0s .3s;transition:opacity .3s ease,transform .3s ease,visibility 0s .3s}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{border:2px solid transparent}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk::after{content:"";position:absolute;top:-2px;left:-2px;height:0;width:100%;height:100%;visibility:visible;border:2px solid transparent;opacity:1;-webkit-transform:scale(1);transform:scale(1);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease;transition:opacity .3s ease,transform .3s ease}.webyx-nav-vt-dsk-left{left:14px}.webyx-nav-vt-dsk-right{right:14px}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{position:absolute;width:8px;height:8px;top:4px;left:4px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;border:2px solid ' . esc_attr( $mvndc ) . ';background:' . esc_attr( $mvndcl ) . ';-webkit-transition:border-color .3s ease;transition:border-color .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after{content:"";position:absolute;top:0;left:0;width:100%;height:100%;visibility:hidden;background:' . esc_attr( $mvndcl ) . ';border-radius:unset;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';opacity:0;-webkit-transform:scale(3);transform:scale(3);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease,visibility 0s .3s;transition:opacity .3s ease,transform .3s ease,visibility 0s .3s}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{border:2px solid transparent}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob::after{content:"";position:absolute;top:-2px;left:-2px;height:0;width:100%;height:100%;visibility:visible;border:2px solid transparent;opacity:1;-webkit-transform:scale(1);transform:scale(1);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease;transition:opacity .3s ease,transform .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-top{top:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-bottom{bottom:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk{width:20px;height:20px;position:relative;display:inline-block}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{position:absolute;width:12px;height:12px;top:2px;left:2px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;border:2px solid ' . esc_attr( $mvndc ) . ';background:' . esc_attr( $mvndcl ) . ';-webkit-transition:border-color .3s ease;transition:border-color .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk:hover{border-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after{content:"";position:absolute;top:0;left:0;width:100%;height:100%;visibility:hidden;background:' . esc_attr( $mvndcl ) . ';border-radius:unset;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';opacity:0;-webkit-transform:scale(3);transform:scale(3);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease,visibility 0s .3s;transition:opacity .3s ease,transform .3s ease,visibility 0s .3s}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{border:2px solid transparent}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk.webyx-dot-hz-dsk:hover{border-color:transparent}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk::after{content:"";position:absolute;top:-2px;left:-2px;height:0;width:100%;height:100%;visibility:visible;border:2px solid transparent;opacity:1;-webkit-transform:scale(1);transform:scale(1);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease;transition:opacity .3s ease,transform .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-current-hz-persistent-dsk{opacity:1;color:#000}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{position:absolute;width:8px;height:8px;top:4px;left:4px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;border:2px solid ' . esc_attr( $mvndc ) . ';background:' . esc_attr( $mvndcl ) . ';-webkit-transition:border-color .3s ease;transition:border-color .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after{content:"";position:absolute;top:0;left:0;width:100%;height:100%;visibility:hidden;background:' . esc_attr( $mvndcl ) . ';border-radius:unset;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';opacity:0;-webkit-transform:scale(3);transform:scale(3);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease,visibility 0s .3s;transition:opacity .3s ease,transform .3s ease,visibility 0s .3s}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{border:2px solid transparent}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob::after{content:"";position:absolute;top:-2px;left:-2px;height:0;width:100%;height:100%;visibility:visible;border:2px solid transparent;opacity:1;-webkit-transform:scale(1);transform:scale(1);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease;transition:opacity .3s ease,transform .3s ease}';
              break; 
            case 'scale_sq_rt':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk,.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk,.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob,.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob,.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob,.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{border-radius:unset;transform:rotate(45deg)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk,.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk,.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob,.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{transform:scale(2)rotate(45deg)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{background-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{background-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{top:8px;left:8px;background-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{top:8px;left:8px;background-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{background-color:' . esc_attr( $mvndc ) . '}';
              break;
            case 'stroke_sq_rt':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{background-color:' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1)rotate(45deg);-ms-transform:scale(1)rotate(45deg);transform:scale(1)rotate(45deg)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:unset;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;overflow:hidden;-webkit-transform:scale(1)rotate(45deg);-ms-transform:scale(1)rotate(45deg);transform:scale(1)rotate(45deg)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;background-color:rgba(255,255,255,0);border-radius:unset;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease,background-color .3s ease;transition:box-shadow .3s ease,background-color .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}.webyx-nav-vt-dsk-left{left:14px}.webyx-nav-vt-dsk-right{right:14px}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:unset;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;overflow:hidden;-webkit-transform:scale(1)rotate(45deg);-ms-transform:scale(1)rotate(45deg);transform:scale(1)rotate(45deg)}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease,background-color .3s ease;transition:box-shadow .3s ease,background-color .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-top{top:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-bottom{bottom:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk{background-color:' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1)rotate(45deg);-ms-transform:scale(1)rotate(45deg);transform:scale(1)rotate(45deg)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{position:absolute;top:4px;left:4px;width:12px;height:12px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:' . esc_attr( $mvndcl ) . ';-webkit-transform:scale(1)rotate(45deg);-ms-transform:scale(1)rotate(45deg);transform:scale(1)rotate(45deg)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;background-color:rgba(255,255,255,0);border-radius:unset;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease,background-color .3s ease;transition:box-shadow .3s ease,background-color .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:unset;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;overflow:hidden;-webkit-transform:scale(1)rotate(45deg);-ms-transform:scale(1)rotate(45deg);transform:scale(1)rotate(45deg)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:rgba(255,255,255,0);border-radius:unset;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease,background-color .3s ease;transition:box-shadow .3s ease,background-color .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}';
              break;
            case 'small_stroke_sq_rt':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk,.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk,.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob,.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob{transform:rotate(45deg)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;border-radius:unset;background-color:' . esc_attr( $mvndcl ) . ';-webkit-transition:background-color .3s ease,-webkit-transform .3s ease;transition:background-color .3s ease,transform .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk.webyx-dot-vt-dsk::after{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk:hover{background-color:transparent}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%;-webkit-transform:scale(.3);transform:scale(.3)}.webyx-nav-vt-dsk-left{left:14px}.webyx-nav-vt-dsk-right{right:14px}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:' . esc_attr( $mvndcl ) . ';box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;border-radius:unset;background-color:' . esc_attr( $mvndcl ) . ';-webkit-transition:background-color .3s ease,-webkit-transform .3s ease;transition:background-color .3s ease,transform .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob.webyx-dot-vt-mob::after{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob:hover{background-color:transparent}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%;-webkit-transform:scale(.3);transform:scale(.3)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-top{top:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-bottom{bottom:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;border-radius:unset;background-color:' . esc_attr( $mvndcl ) . ';-webkit-transition:background-color .3s ease,-webkit-transform .3s ease;transition:background-color .3s ease,transform .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk.webyx-dot-hz-dsk::after{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk:hover{background-color:transparent}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%;-webkit-transform:scale(.3);transform:scale(.3)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:' . esc_attr( $mvndcl ) . ';box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;border-radius:unset;background-color:' . esc_attr( $mvndcl ) . ';-webkit-transition:background-color .3s ease,-webkit-transform .3s ease;transition:background-color .3s ease,transform .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob.webyx-dot-hz-mob::after{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob:hover{background-color:transparent}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%;-webkit-transform:scale(.3);transform:scale(.3)}';
              break;
            case 'fill_in_sq_rt':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk,.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk,.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob,.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob{transform:rotate(45deg)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk::after{box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndcl ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;background-color:transparent;border-radius:unset;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{background-color:' . esc_attr( $mvndc ) . ';box-shadow:inset 0 0 0 8px ' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}.webyx-nav-vt-dsk-left{left:14px}.webyx-nav-vt-dsk-right{right:14px}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;background-color:transparent;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;background-color:transparent;border-radius:unset;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{background-color:transparent;box-shadow:inset 0 0 0 8px ' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-top{top:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-bottom{bottom:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk{width:20px;height:20px;position:relative;display:inline-block}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk::after{box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndcl ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{position:absolute;top:4px;left:4px;width:12px;height:12px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;background-color:rgba(255,255,255,0);border-radius:unset;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{background-color:transparent;box-shadow:inset 0 0 0 8px ' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob{width:20px;height:20px;position:relative;display:inline-block}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;background-color:transparent;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;background-color:transparent;border-radius:unset;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{background-color:transparent;box-shadow:inset 0 0 0 8px ' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}';
              break;
            case 'fill_up_sq_rt':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk,.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk,.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob,.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob{transform:rotate(45deg)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{background-color:' . esc_attr( $mvndcl ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:background .3s ease;transition:background .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';-webkit-transition:height .3s ease;transition:height .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}.webyx-nav-vt-dsk-left{left:14px}.webyx-nav-vt-dsk-right{right:14px}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:background .3s ease;transition:background .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';-webkit-transition:height .3s ease;transition:height .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-top{top:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-bottom{bottom:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk{width:20px;height:20px;position:relative;display:inline-block}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk{background-color:' . esc_attr( $mvndcl ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{position:absolute;top:4px;left:4px;width:12px;height:12px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:background .3s ease;transition:background .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';-webkit-transition:height .3s ease;transition:height .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:background .3s ease;transition:background .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';-webkit-transition:height .3s ease;transition:height .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}';
              break; 
            case 'fall_sq_rt':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk,.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk,.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob,.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob{transform:rotate(45deg)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1);background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:unset;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,background-color .3s ease;transition:transform .3s ease,opacity .3s ease,background-color .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;visibility:hidden;background-color:' . esc_attr( $mvndcl ) . ';border-radius:unset;opacity:0;-webkit-transform:translateX(-200%);transform:translateX(-200%);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,visibility 0s .3s;transition:transform .3s ease,opacity .3s ease,visibility 0s .3s}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk.webyx-dot-vt-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;height:100%;border-radius:unset;background-color:transparent;opacity:1;background-color:' . esc_attr( $mvndc ) . ';visibility:visible;-webkit-transition:-webkit-transform .3s ease,opacity .3s ease;transition:transform .3s ease,opacity .3s ease;-webkit-transform:translateX(0);transform:translateX(0)}.webyx-nav-vt-dsk-left{left:14px}.webyx-nav-vt-dsk-right{right:14px}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:unset;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,background-color .3s ease;transition:transform .3s ease,opacity .3s ease,background-color .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;visibility:hidden;background-color:' . esc_attr( $mvndcl ) . ';border-radius:unset;opacity:0;-webkit-transform:translateX(-200%);transform:translateX(-200%);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,visibility 0s .3s;transition:transform .3s ease,opacity .3s ease,visibility 0s .3s}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;height:100%;border-radius:unset;background-color:transparent;opacity:1;background-color:' . esc_attr( $mvndc ) . ';visibility:visible;-webkit-transition:-webkit-transform .3s ease,opacity .3s ease;transition:transform .3s ease,opacity .3s ease;-webkit-transform:translateX(0);transform:translateX(0)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-top{top:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-bottom{bottom:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1);background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{position:absolute;top:4px;left:4px;width:12px;height:12px;outline:0;border-radius:unset;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,background-color .3s ease;transition:transform .3s ease,opacity .3s ease,background-color .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;visibility:hidden;background-color:' . esc_attr( $mvndcl ) . ';border-radius:unset;opacity:0;-webkit-transform:translateY(-200%);transform:translateY(-200%);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,visibility 0s .3s;transition:transform .3s ease,opacity .3s ease,visibility 0s .3s}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk.webyx-dot-hz-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;height:100%;border-radius:unset;background-color:transparent;opacity:1;background-color:' . esc_attr( $mvndc ) . ';visibility:visible;-webkit-transition:-webkit-transform .3s ease,opacity .3s ease;transition:transform .3s ease,opacity .3s ease;-webkit-transform:translateY(0);transform:translateY(0)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:unset;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,background-color .3s ease;transition:transform .3s ease,opacity .3s ease,background-color .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;visibility:hidden;background-color:' . esc_attr( $mvndcl ) . ';border-radius:unset;opacity:0;-webkit-transform:translateY(-200%);transform:translateY(-200%);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,visibility 0s .3s;transition:transform .3s ease,opacity .3s ease,visibility 0s .3s}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob.webyx-dot-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;height:100%;border-radius:unset;background-color:transparent;opacity:1;background-color:' . esc_attr( $mvndc ) . ';visibility:visible;-webkit-transition:-webkit-transform .3s ease,opacity .3s ease;transition:transform .3s ease,opacity .3s ease;-webkit-transform:translateY(0);transform:translateY(0)}';
              break; 
            case 'puff_sq_rt':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk,.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk,.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob,.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob{transform:rotate(45deg)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk{position:absolute;width:12px;height:12px;top:2px;left:2px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;border:2px solid ' . esc_attr( $mvndc ) . ';background:' . esc_attr( $mvndcl ) . ';-webkit-transition:border-color .3s ease;transition:border-color .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk:hover{border-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after{content:"";position:absolute;top:0;left:0;width:100%;height:100%;visibility:hidden;background:' . esc_attr( $mvndcl ) . ';border-radius:unset;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';opacity:0;-webkit-transform:scale(3);transform:scale(3);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease,visibility 0s .3s;transition:opacity .3s ease,transform .3s ease,visibility 0s .3s}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{border:2px solid transparent}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk::after{content:"";position:absolute;top:-2px;left:-2px;height:0;width:100%;height:100%;visibility:visible;border:2px solid transparent;opacity:1;-webkit-transform:scale(1);transform:scale(1);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease;transition:opacity .3s ease,transform .3s ease}.webyx-nav-vt-dsk-left{left:14px}.webyx-nav-vt-dsk-right{right:14px}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{position:absolute;width:8px;height:8px;top:4px;left:4px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;border:2px solid ' . esc_attr( $mvndc ) . ';background:' . esc_attr( $mvndcl ) . ';-webkit-transition:border-color .3s ease;transition:border-color .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after{content:"";position:absolute;top:0;left:0;width:100%;height:100%;visibility:hidden;background:' . esc_attr( $mvndcl ) . ';border-radius:unset;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';opacity:0;-webkit-transform:scale(3);transform:scale(3);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease,visibility 0s .3s;transition:opacity .3s ease,transform .3s ease,visibility 0s .3s}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{border:2px solid transparent}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob::after{content:"";position:absolute;top:-2px;left:-2px;height:0;width:100%;height:100%;visibility:visible;border:2px solid transparent;opacity:1;-webkit-transform:scale(1);transform:scale(1);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease;transition:opacity .3s ease,transform .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-top{top:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-bottom{bottom:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk{width:20px;height:20px;position:relative;display:inline-block}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{position:absolute;width:12px;height:12px;top:2px;left:2px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;border:2px solid ' . esc_attr( $mvndc ) . ';background:' . esc_attr( $mvndcl ) . ';-webkit-transition:border-color .3s ease;transition:border-color .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk:hover{border-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after{content:"";position:absolute;top:0;left:0;width:100%;height:100%;visibility:hidden;background:' . esc_attr( $mvndcl ) . ';border-radius:unset;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';opacity:0;-webkit-transform:scale(3);transform:scale(3);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease,visibility 0s .3s;transition:opacity .3s ease,transform .3s ease,visibility 0s .3s}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{border:2px solid transparent}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk.webyx-dot-hz-dsk:hover{border-color:transparent}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk::after{content:"";position:absolute;top:-2px;left:-2px;height:0;width:100%;height:100%;visibility:visible;border:2px solid transparent;opacity:1;-webkit-transform:scale(1);transform:scale(1);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease;transition:opacity .3s ease,transform .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-current-hz-persistent-dsk{opacity:1;color:#000}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{position:absolute;width:8px;height:8px;top:4px;left:4px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;border:2px solid ' . esc_attr( $mvndc ) . ';background:' . esc_attr( $mvndcl ) . ';-webkit-transition:border-color .3s ease;transition:border-color .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after{content:"";position:absolute;top:0;left:0;width:100%;height:100%;visibility:hidden;background:' . esc_attr( $mvndcl ) . ';border-radius:unset;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';opacity:0;-webkit-transform:scale(3);transform:scale(3);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease,visibility 0s .3s;transition:opacity .3s ease,transform .3s ease,visibility 0s .3s}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{border:2px solid transparent}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob::after{content:"";position:absolute;top:-2px;left:-2px;height:0;width:100%;height:100%;visibility:visible;border:2px solid transparent;opacity:1;-webkit-transform:scale(1);transform:scale(1);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease;transition:opacity .3s ease,transform .3s ease}';
              break; 
            case 'scale_line':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk,.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk,.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob,.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob,.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob,.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{border-radius:unset;width:7px;height:4px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{background-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{background-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{top:8px;left:8px;background-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{top:8px;left:8px;background-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{background-color:' . esc_attr( $mvndc ) . '}';
              break;
            case 'stroke_line':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{background-color:' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:unset;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;background-color:rgba(255,255,255,0);border-radius:unset;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease,background-color .3s ease;transition:box-shadow .3s ease,background-color .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}.webyx-nav-vt-dsk-left{left:14px}.webyx-nav-vt-dsk-right{right:14px}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{position:absolute;width:8px;height:8px;top:6px;left:3px;outline:0;border-radius:unset;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease,background-color .3s ease;transition:box-shadow .3s ease,background-color .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-top{top:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-bottom{bottom:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk{background-color:' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{position:absolute;top:4px;left:4px;width:12px;height:12px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:' . esc_attr( $mvndcl ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;background-color:rgba(255,255,255,0);border-radius:unset;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease,background-color .3s ease;transition:box-shadow .3s ease,background-color .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:unset;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:rgba(255,255,255,0);border-radius:unset;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease,background-color .3s ease;transition:box-shadow .3s ease,background-color .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk,.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk,.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob,.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob,.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob,.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{width:14px;height:8px}';
              break;
            case 'small_stroke_line':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;border-radius:unset;background-color:' . esc_attr( $mvndcl ) . ';-webkit-transition:background-color .3s ease,-webkit-transform .3s ease;transition:background-color .3s ease,transform .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk.webyx-dot-vt-dsk::after{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk:hover{background-color:transparent}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%;-webkit-transform:scale(.3);transform:scale(.3)}.webyx-nav-vt-dsk-left{left:14px}.webyx-nav-vt-dsk-right{right:14px}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:' . esc_attr( $mvndcl ) . ';box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;border-radius:unset;background-color:' . esc_attr( $mvndcl ) . ';-webkit-transition:background-color .3s ease,-webkit-transform .3s ease;transition:background-color .3s ease,transform .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob.webyx-dot-vt-mob::after{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob:hover{background-color:transparent}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%;-webkit-transform:scale(.3);transform:scale(.3)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-top{top:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-bottom{bottom:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;border-radius:unset;background-color:' . esc_attr( $mvndcl ) . ';-webkit-transition:background-color .3s ease,-webkit-transform .3s ease;transition:background-color .3s ease,transform .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk.webyx-dot-hz-dsk::after{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk:hover{background-color:transparent}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%;-webkit-transform:scale(.3);transform:scale(.3)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:' . esc_attr( $mvndcl ) . ';box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;border-radius:unset;background-color:' . esc_attr( $mvndcl ) . ';-webkit-transition:background-color .3s ease,-webkit-transform .3s ease;transition:background-color .3s ease,transform .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob.webyx-dot-hz-mob::after{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob:hover{background-color:transparent}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%;-webkit-transform:scale(.3);transform:scale(.3)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk,.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk,.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob,.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob,.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob,.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{width:10px;height:8px}';
              break;
            case 'fill_in_line':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk::after{box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndcl ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;background-color:transparent;border-radius:unset;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{background-color:' . esc_attr( $mvndc ) . ';box-shadow:inset 0 0 0 8px ' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}.webyx-nav-vt-dsk-left{left:14px}.webyx-nav-vt-dsk-right{right:14px}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;background-color:transparent;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;background-color:transparent;border-radius:unset;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{background-color:transparent;box-shadow:inset 0 0 0 8px ' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-top{top:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-bottom{bottom:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk{width:20px;height:20px;position:relative;display:inline-block}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk::after{box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndcl ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{position:absolute;top:4px;left:4px;width:12px;height:12px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;background-color:rgba(255,255,255,0);border-radius:unset;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{background-color:transparent;box-shadow:inset 0 0 0 8px ' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob{width:20px;height:20px;position:relative;display:inline-block}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;background-color:transparent;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;background-color:transparent;border-radius:unset;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{background-color:transparent;box-shadow:inset 0 0 0 8px ' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk,.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk,.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob,.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob,.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob,.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{width:10px;height:8px}';
              break; 
            case 'fill_up_line':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{background-color:' . esc_attr( $mvndcl ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:background .3s ease;transition:background .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';-webkit-transition:height .3s ease;transition:height .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%}.webyx-nav-vt-dsk-left{left:14px}.webyx-nav-vt-dsk-right{right:14px}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:background .3s ease;transition:background .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';-webkit-transition:height .3s ease;transition:height .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-top{top:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-bottom{bottom:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk{width:20px;height:20px;position:relative;display:inline-block}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk{background-color:' . esc_attr( $mvndcl ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{position:absolute;top:4px;left:4px;width:12px;height:12px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:background .3s ease;transition:background .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';-webkit-transition:height .3s ease;transition:height .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:transparent;box-shadow:inset 0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transition:background .3s ease;transition:background .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';-webkit-transition:height .3s ease;transition:height .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:unset;height:100%;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk,.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk,.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob,.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob,.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob,.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{width:10px;height:8px}';
              break;
            case 'fall_line':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1);background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:unset;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,background-color .3s ease;transition:transform .3s ease,opacity .3s ease,background-color .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;visibility:hidden;background-color:' . esc_attr( $mvndcl ) . ';border-radius:unset;opacity:0;-webkit-transform:translateX(-200%);transform:translateX(-200%);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,visibility 0s .3s;transition:transform .3s ease,opacity .3s ease,visibility 0s .3s}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk.webyx-dot-vt-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;height:100%;border-radius:unset;background-color:transparent;opacity:1;background-color:' . esc_attr( $mvndc ) . ';visibility:visible;-webkit-transition:-webkit-transform .3s ease,opacity .3s ease;transition:transform .3s ease,opacity .3s ease;-webkit-transform:translateX(0);transform:translateX(0)}.webyx-nav-vt-dsk-left{left:14px}.webyx-nav-vt-dsk-right{right:14px}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:unset;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,background-color .3s ease;transition:transform .3s ease,opacity .3s ease,background-color .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;visibility:hidden;background-color:' . esc_attr( $mvndcl ) . ';border-radius:unset;opacity:0;-webkit-transform:translateX(-200%);transform:translateX(-200%);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,visibility 0s .3s;transition:transform .3s ease,opacity .3s ease,visibility 0s .3s}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;height:100%;border-radius:unset;background-color:transparent;opacity:1;background-color:' . esc_attr( $mvndc ) . ';visibility:visible;-webkit-transition:-webkit-transform .3s ease,opacity .3s ease;transition:transform .3s ease,opacity .3s ease;-webkit-transform:translateX(0);transform:translateX(0)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-top{top:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-bottom{bottom:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1);background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{position:absolute;top:4px;left:4px;width:12px;height:12px;outline:0;border-radius:unset;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,background-color .3s ease;transition:transform .3s ease,opacity .3s ease,background-color .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;visibility:hidden;background-color:' . esc_attr( $mvndcl ) . ';border-radius:unset;opacity:0;-webkit-transform:translateY(-200%);transform:translateY(-200%);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,visibility 0s .3s;transition:transform .3s ease,opacity .3s ease,visibility 0s .3s}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk.webyx-dot-hz-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;height:100%;border-radius:unset;background-color:transparent;opacity:1;background-color:' . esc_attr( $mvndc ) . ';visibility:visible;-webkit-transition:-webkit-transform .3s ease,opacity .3s ease;transition:transform .3s ease,opacity .3s ease;-webkit-transform:translateY(0);transform:translateY(0)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:unset;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,background-color .3s ease;transition:transform .3s ease,opacity .3s ease,background-color .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;visibility:hidden;background-color:' . esc_attr( $mvndcl ) . ';border-radius:unset;opacity:0;-webkit-transform:translateY(-200%);transform:translateY(-200%);-webkit-transition:-webkit-transform .3s ease,opacity .3s ease,visibility 0s .3s;transition:transform .3s ease,opacity .3s ease,visibility 0s .3s}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob.webyx-dot-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;height:100%;border-radius:unset;background-color:transparent;opacity:1;background-color:' . esc_attr( $mvndc ) . ';visibility:visible;-webkit-transition:-webkit-transform .3s ease,opacity .3s ease;transition:transform .3s ease,opacity .3s ease;-webkit-transform:translateY(0);transform:translateY(0)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk,.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk,.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob,.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob,.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob,.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{width:12px;height:8px}';
              break; 
            case 'puff_line':
              $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk{position:absolute;width:12px;height:12px;top:2px;left:2px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;border:2px solid ' . esc_attr( $mvndc ) . ';background:' . esc_attr( $mvndcl ) . ';-webkit-transition:border-color .3s ease;transition:border-color .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk:hover{border-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after{content:"";position:absolute;top:0;left:0;width:100%;height:100%;visibility:hidden;background:' . esc_attr( $mvndcl ) . ';border-radius:unset;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';opacity:0;-webkit-transform:scale(3);transform:scale(3);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease,visibility 0s .3s;transition:opacity .3s ease,transform .3s ease,visibility 0s .3s}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{border:2px solid transparent}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk::after{content:"";position:absolute;top:-2px;left:-2px;height:0;width:100%;height:100%;visibility:visible;border:2px solid transparent;opacity:1;-webkit-transform:scale(1);transform:scale(1);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease;transition:opacity .3s ease,transform .3s ease}.webyx-nav-vt-dsk-left{left:14px}.webyx-nav-vt-dsk-right{right:14px}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{position:absolute;width:8px;height:8px;top:4px;left:4px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;border:2px solid ' . esc_attr( $mvndc ) . ';background:' . esc_attr( $mvndcl ) . ';-webkit-transition:border-color .3s ease;transition:border-color .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after{content:"";position:absolute;top:0;left:0;width:100%;height:100%;visibility:hidden;background:' . esc_attr( $mvndcl ) . ';border-radius:unset;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';opacity:0;-webkit-transform:scale(3);transform:scale(3);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease,visibility 0s .3s;transition:opacity .3s ease,transform .3s ease,visibility 0s .3s}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{border:2px solid transparent}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob::after{content:"";position:absolute;top:-2px;left:-2px;height:0;width:100%;height:100%;visibility:visible;border:2px solid transparent;opacity:1;-webkit-transform:scale(1);transform:scale(1);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease;transition:opacity .3s ease,transform .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-top{top:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-bottom{bottom:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk{width:20px;height:20px;position:relative;display:inline-block}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{position:absolute;width:12px;height:12px;top:2px;left:2px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;border:2px solid ' . esc_attr( $mvndc ) . ';background:' . esc_attr( $mvndcl ) . ';-webkit-transition:border-color .3s ease;transition:border-color .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk:hover{border-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after{content:"";position:absolute;top:0;left:0;width:100%;height:100%;visibility:hidden;background:' . esc_attr( $mvndcl ) . ';border-radius:unset;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';opacity:0;-webkit-transform:scale(3);transform:scale(3);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease,visibility 0s .3s;transition:opacity .3s ease,transform .3s ease,visibility 0s .3s}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{border:2px solid transparent}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk.webyx-dot-hz-dsk:hover{border-color:transparent}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk::after{content:"";position:absolute;top:-2px;left:-2px;height:0;width:100%;height:100%;visibility:visible;border:2px solid transparent;opacity:1;-webkit-transform:scale(1);transform:scale(1);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease;transition:opacity .3s ease,transform .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-current-hz-persistent-dsk{opacity:1;color:#000}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{position:absolute;width:8px;height:8px;top:4px;left:4px;outline:0;border-radius:unset;text-indent:-999em;cursor:pointer;border:2px solid ' . esc_attr( $mvndc ) . ';background:' . esc_attr( $mvndcl ) . ';-webkit-transition:border-color .3s ease;transition:border-color .3s ease;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after{content:"";position:absolute;top:0;left:0;width:100%;height:100%;visibility:hidden;background:' . esc_attr( $mvndcl ) . ';border-radius:unset;box-shadow:0 0 1px ' . esc_attr( $mvndc ) . ';opacity:0;-webkit-transform:scale(3);transform:scale(3);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease,visibility 0s .3s;transition:opacity .3s ease,transform .3s ease,visibility 0s .3s}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{border:2px solid transparent}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob::after{content:"";position:absolute;top:-2px;left:-2px;height:0;width:100%;height:100%;visibility:visible;border:2px solid transparent;opacity:1;-webkit-transform:scale(1);transform:scale(1);-webkit-transition:opacity .3s ease,-webkit-transform .3s ease;transition:opacity .3s ease,transform .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk,.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk,.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob,.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob,.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob,.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{width:10px;height:6px}';
              break;
          }
          if ( 'on' === $dbkgace ) {
            $body .= '.webyx-dots-wrapper-bkg-color{background-color:' . esc_attr( $dbkgac ) . '}';
          }
          if ( 'on' === $mvndttace ) {
            $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk, .webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk{background-color:' .esc_attr( $mvndttac ) . '}';
          }
          if ( 'on' === $dtvoff ) { 
            $body .= '.webyx-nav-vt-dsk-' . esc_attr( $dvp ) . '{' . esc_attr( $dvp ) . ':' . esc_attr( $dtvoffdsk ) . 'px}.webyx-nav-vt-mob-' . esc_attr( $dvp ) . '{' . esc_attr( $dvp ) . ':' . esc_attr( $dtvoffmob ) . 'px}';
          }
          if ( 'on' === $dthoff ) { 
            $body .= '.webyx-nav-hz-dsk-' .esc_attr( $dhp ) . '{' . esc_attr( $dhp) . ':' . esc_attr( $dthoffdsk ) . 'px}.webyx-nav-hz-mob-' . esc_attr( $dhp ) . '{' .  esc_attr( $dhp ) . ':'. esc_attr( $dthoffmob ) . '}px}';
          }
          $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-current-vt-dsk,.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-current-vt-dsk+.webyx-dot-tt-vt-dsk, .webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-current-hz-dsk,.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-current-hz-dsk+.webyx-dot-tt-hz-dsk,.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-current-vt-persistent-dsk, .webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-current-hz-persistent-dsk{color:' . esc_attr( $mvndttc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-current-vt-dsk,.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-tt-vt-dsk,.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-current-hz-dsk,.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-tt-hz-dsk{color:' . esc_attr( $mvndttcl ) . '}';
          return '<style>' . $body . '</style>';
        }
        public function webyx_fep_print_imwh_css_validated ( $ps ) {
          $nvvw = isset( $ps[ 'nvvw' ] ) && in_array( $ps[ 'nvvw' ], array( 'on', '' ), true ) ? $ps[ 'nvvw' ] : '';
          $avvd = isset( $ps[ 'avvd' ] ) && in_array( $ps[ 'avvd' ], array( 'on', '' ), true ) ? $ps[ 'avvd' ] : '';
          $body = '';
          if ( 'on' === $nvvw && 'on' === $avvd ) {
            $msiwc = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'msiwc', '#000000' );                      
            $msiwbce = isset( $ps[ 'msiwbce' ] ) && in_array( $ps[ 'msiwbce' ], array( 'on', '' ), true ) ? $ps[ 'msiwbce' ] : '';                                   
            $nvvwofe = isset( $ps[ 'nvvwofe' ] ) && in_array( $ps[ 'nvvwofe' ], array( 'on', '' ), true ) ? $ps[ 'nvvwofe' ] : '';                                   
            $nvvwof = isset( $ps[ 'nvvwof' ] ) && isset( $ps[ 'nvvwof' ][ 'unit' ] ) && isset( $ps[ 'nvvwof' ][ 'size' ] ) ? $ps[ 'nvvwof'  ] : array( 'unit' => 'px', 'size' => 0 ); 
            $msiwbc = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'msiwbc', '#ffffff' );                
            if ( 'on' === $nvvwofe ) {
              $body .= '.webyx-icon-scroll-wrapper{bottom:' . esc_attr( $nvvwof[ 'size' ] ) . esc_attr( $nvvwof[ 'unit' ] ) . '}';
            }
            if ( 'on' === $msiwbce ) {
              $body .= '.webyx-icon-scroll-wrapper .webyx-icon-scroll-mouse{background-color:' . esc_attr( $msiwbc ) . '}';
            }
            $body .= '.webyx-icon-scroll-wrapper .webyx-icon-scroll-mouse{border-color:' . esc_attr( $msiwc ) . '}.webyx-icon-scroll-wrapper .webyx-icon-scroll-wheel{background:' . esc_attr( $msiwc ) . '}';
            return '<style>' . $body . '</style>';
          } else {
            return $body;
          }
        }
        public function webyx_fep_print_fs_css_validated ( $ps ) {
          $fsb = isset( $ps[ 'fsb' ] ) && in_array( $ps[ 'fsb' ], array( 'on', '' ), true ) ? $ps[ 'fsb' ] : ''; 
          $body = '';
          if ( 'on' === $fsb ) {
            $fsc = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'fsc', '#000000' ); 
            $fsdt = isset( $ps[ 'fsdt' ] ) && in_array( $ps[ 'fsdt' ], array( '2px', '4px', '6px' ), true ) ? $ps[ 'fsdt' ] : '4px';                 
            $fsboff = isset( $ps[ 'fsboff' ] ) && in_array( $ps[ 'fsboff' ], array( 'on', '' ), true ) ? $ps[ 'fsboff' ] : '';                    
            $fsbce = isset( $ps[ 'fsbce' ] ) && in_array( $ps[ 'fsbce' ], array( 'on', '' ), true ) ? $ps[ 'fsbce' ] : '';                    
            $body .= '.webyx-full-screen-button-wrapper .webyx-full-screen-button-part-top-left-outer{border-top-width:' . esc_attr( $fsdt ) . ';border-left-width:'. esc_attr( $fsdt ) . '}';
            $body .= '.webyx-full-screen-button-wrapper .webyx-full-screen-button-part-top-left-inner{border-bottom-width:' . esc_attr( $fsdt ) . ';border-right-width:' . esc_attr( $fsdt ) . '}';
            $body .= '.webyx-full-screen-button-wrapper .webyx-full-screen-button-part-top-right-outer{border-top-width:' . esc_attr( $fsdt ) . ';border-right-width:' . esc_attr( $fsdt ) . '}';
            $body .= '.webyx-full-screen-button-wrapper .webyx-full-screen-button-part-top-right-inner{border-bottom-width:' . esc_attr( $fsdt ) . ';border-left-width:' . esc_attr( $fsdt ) . '}';
            $body .= '.webyx-full-screen-button-wrapper .webyx-full-screen-button-part-bottom-left-outer{border-bottom-width:' . esc_attr( $fsdt ) . ';border-left-width:' . esc_attr( $fsdt ) . '}';
            $body .= '.webyx-full-screen-button-wrapper .webyx-full-screen-button-part-bottom-left-inner{border-top-width:' . esc_attr( $fsdt ) . ';border-right-width:' . esc_attr( $fsdt ) . '}';
            $body .= '.webyx-full-screen-button-wrapper .webyx-full-screen-button-part-bottom-right-outer{border-bottom-width:' . esc_attr( $fsdt ) . ';border-right-width:' . esc_attr( $fsdt ) . '}';
            $body .= '.webyx-full-screen-button-wrapper .webyx-full-screen-button-part-bottom-right-inner{border-top-width:' . esc_attr( $fsdt ) . ';border-left-width:' . esc_attr( $fsdt ) . '}';
            $body .= ".webyx-full-screen-button-wrapper .webyx-full-screen-button-part-bottom-left-inner,
                      .webyx-full-screen-button-wrapper .webyx-full-screen-button-part-bottom-left-outer,
                      .webyx-full-screen-button-wrapper .webyx-full-screen-button-part-bottom-right-inner,
                      .webyx-full-screen-button-wrapper .webyx-full-screen-button-part-bottom-right-outer,
                      .webyx-full-screen-button-wrapper .webyx-full-screen-button-part-top-left-inner,
                      .webyx-full-screen-button-wrapper .webyx-full-screen-button-part-top-left-outer,
                      .webyx-full-screen-button-wrapper .webyx-full-screen-button-part-top-right-inner,
                      .webyx-full-screen-button-wrapper .webyx-full-screen-button-part-top-right-outer{border-color:" . esc_attr( $fsc ) . '}';
            if ( 'on' === $fsboff ) {
              $fsp = isset( $ps[ 'fsp' ] ) && in_array( $ps[ 'fsp' ], array( 'left', 'right' ), true ) ? $ps[ 'fsp' ] : 'right';
              $fsofft = isset( $ps[ 'fsofft' ] ) && filter_var( $ps[ 'fsofft' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'fsofft' ] : 0;     
              $fsoffl = isset( $ps[ 'fsoffl' ] ) && filter_var( $ps[ 'fsoffl' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'fsoffl' ] : 0;      
              $fsoffr = isset( $ps[ 'fsoffr' ] ) && filter_var( $ps[ 'fsoffr' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'fsoffr' ] : 0;      
              switch ( $fsp ) {
                case 'left':
                  $body .= '.webyx-full-screen-button-wrapper.webyx-full-screen-button-wrapper-left{top:' . esc_attr( $fsofft ) . 'px;left:' . esc_attr( $fsoffl ) . 'px}';
                  break;
                case 'right':
                  $body .= '.webyx-full-screen-button-wrapper.webyx-full-screen-button-wrapper-right{top:' . esc_attr( $fsofft ) . 'px;right:' . esc_attr( $fsoffr ) . 'px}';
                  break;
              }
            }
            if ( 'on' === $fsbce ) {
              $fsbc = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'fsbc', '#ffffff00' );
              $body .= '.webyx-full-screen-button-wrapper-background-color{background-color:' . esc_attr( $fsbc ) . '}';
            }
            return '<style>' . $body . '</style>';
          } else {
            return $body;
          }
        }
        public function webyx_fep_print_scrlb_css_validated ( $ps ) {
          $scrlbd = isset( $ps[ 'scrlbd' ] ) && in_array( $ps[ 'scrlbd' ], array( 'on', '' ), true ) ? $ps[ 'scrlbd' ] : '';
          $body = '';
          if ( 'on' === $scrlbd ) {
            $body .= '.webyx-scrollbar::-webkit-scrollbar{display:none}.webyx-scrollbar{-ms-overflow-style:none;scrollbar-width:none}';
            return '<style>' . $body . '</style>';
          } else {
            return $body;
          }
        }
        private function webyx_fe_css_validate ( $css ) {
          if ( preg_match( '#</?\w+#', $css ) ) {
            return false;
          }
          return $css;
        }
        public function webyx_fep_get_custom_css_validated ( $ps ) {
          $ccss = isset( $ps[ 'ccss' ] ) && in_array( $ps[ 'ccss' ], array( 'on', '' ), true ) ? $ps[ 'ccss' ] : '';
          $ccssp = isset( $ps[ 'ccssp' ] ) && $this->webyx_fe_css_validate( $ps[ 'ccssp' ] ) ? $ps[ 'ccssp' ] : '';
          $body = '';
          if ( 'on' === $ccss && '' !== $ccssp ) {
            $body .= $ccssp;
            return '<style>' . $body . '</style>';
          } else {
            return $body;
          }
        }
        public function webyx_fep_get_view_css_validated ( $ps ) {
          $body = '';
          $wvtype = isset( $ps[ 'wvtype' ] ) && in_array( $ps[ 'wvtype' ], array( 'full', 'header', 'custom' ), true ) ? $ps[ 'wvtype' ] : 'full';
          $glb_mq_en = isset( $ps[ 'global_webyx_section_mq_enable' ] ) && in_array( $ps[ 'global_webyx_section_mq_enable' ], array( 'on', '' ), true ) ? $ps[ 'global_webyx_section_mq_enable' ] : '';                                    
          $glb_mq_xs = isset( $ps[ 'global_webyx_section_mq_xs' ] ) && ( isset( $ps[ 'global_webyx_section_mq_xs' ][ 'unit' ] ) && in_array( $ps[ 'global_webyx_section_mq_xs' ][ 'unit' ], array( 'px' ), true ) ) && ( isset( $ps[ 'global_webyx_section_mq_xs' ][ 'size' ] ) && filter_var( $ps[ 'global_webyx_section_mq_xs' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ) ? $ps[ 'global_webyx_section_mq_xs' ] : array( 'unit' => 'px', 'size' => 760 );
          if ( 'full' === $wvtype ) {
            $body .= '.webyx-view{position:fixed;width:100vw;height:100%}';
            return '<style>' . $body . '</style>';
          }
          if ( 'header' === $wvtype ) {
            $wvhdmqb = isset( $ps[ 'wvhdmqb' ] ) && ( isset( $ps[ 'wvhdmqb' ][ 'unit' ] ) && in_array( $ps[ 'wvhdmqb' ][ 'unit' ], array( 'px' ), true ) ) && ( isset( $ps[ 'wvhdmqb' ][ 'size' ] ) && filter_var( $ps[ 'wvhdmqb' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ) ? $ps[ 'wvhdmqb' ] : array( 'unit' => 'px', 'size' => 760 );
            $wvdhdht = isset( $ps[ 'wvdhdht' ] ) && isset( $ps[ 'wvdhdht' ][ 'unit' ] ) && in_array( $ps[ 'wvdhdht' ][ 'unit' ], array( 'px' ), true ) && isset( $ps[ 'wvdhdht' ][ 'size' ] ) && filter_var( $ps[ 'wvdhdht' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'wvdhdht' ] : array( 'unit' => 'px', 'size' => 50 );
            $wvmhdht = isset( $ps[ 'wvmhdht' ] ) && isset( $ps[ 'wvmhdht' ][ 'unit' ] ) && in_array( $ps[ 'wvmhdht' ][ 'unit' ], array( 'px' ), true ) && isset( $ps[ 'wvmhdht' ][ 'size' ] ) && filter_var( $ps[ 'wvmhdht' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'wvmhdht' ] : array( 'unit' => 'px', 'size' => 50 );
            $wv_hd_mq_um = $glb_mq_en ? $glb_mq_xs[ 'unit' ] : $wvhdmqb[ 'unit' ];
            $wv_hd_mq_val = ( $glb_mq_en ? ( $glb_mq_xs[ 'size' ] + 1 ) : ( $wvhdmqb[ 'size' ] + 1 ) ) . $wv_hd_mq_um;
            $wv_hd_mq_xs_val = ( $glb_mq_en ? $glb_mq_xs[ 'size' ] : $wvhdmqb[ 'size' ] ) . $wv_hd_mq_um;
            $body .= '@media only screen and (max-width:' . esc_attr( $wv_hd_mq_xs_val ) . '){.webyx-view{position:relative;width:100vw;height:calc(100% - ' . esc_attr( $wvmhdht[ 'size' ] . $wvmhdht[ 'unit' ] ) . ');top:' . esc_attr( $wvmhdht[ 'size' ] . $wvmhdht[ 'unit' ] ) . '}}';
            $body .= '@media only screen and (min-width:' . esc_attr( $wv_hd_mq_val ) . '){.webyx-view{position:relative;width:100vw;height:calc(100% - '. esc_attr( $wvdhdht[ 'size' ] . $wvdhdht[ 'unit' ] ). ');top:' . esc_attr( $wvdhdht[ 'size' ] . $wvdhdht[ 'unit' ] ) . '}}';
            return '<style>' . $body . '</style>';
          }
          if ( 'custom' === $wvtype ) {
            $wvmpos = isset( $ps[ 'wvmpos' ] ) && in_array( $ps[ 'wvtype' ], array( 'static', 'relative', 'absolute' ), true ) ? $ps[ 'wvmpos' ] : 'static';
            $wvmwt = isset( $ps[ 'wvmwt' ] ) && isset( $ps[ 'wvmwt' ][ 'unit' ] ) && in_array( $ps[ 'wvmwt' ][ 'unit' ], array( 'px', '%', 'vw' ), true ) && isset( $ps[ 'wvmwt' ][ 'size' ] ) && filter_var( $ps[ 'wvmwt' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'wvmwt' ] : array( 'unit' => '%',  'size' => 100 );
            $wvmht = isset( $ps[ 'wvmht' ] ) && isset( $ps[ 'wvmht' ][ 'unit' ] ) && in_array( $ps[ 'wvmht' ][ 'unit' ], array( 'px', '%', 'vh' ), true ) && isset( $ps[ 'wvmht' ][ 'size' ] ) && filter_var( $ps[ 'wvmht' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'wvmht' ] : array( 'unit' => 'px', 'size' => 800 );
            $wvmstmaren = isset( $ps[ 'wvmstmaren' ] ) && in_array( $ps[ 'wvmstmaren' ], array( 'on', '' ), true )? $ps[ 'wvmstmaren' ] : '';
            $wvmstmar = isset( $ps[ 'wvmstmar' ] ) && isset( $ps[ 'wvmstmar' ][ 'unit' ] ) && in_array( $ps[ 'wvmstmar' ][ 'unit' ], array( 'px', '%' ), true ) && isset( $ps[ 'wvmstmar' ][ 'top' ] ) && isset( $ps[ 'wvmstmar' ][ 'right' ] ) && isset( $ps[ 'wvmstmar' ][ 'bottom' ] ) && isset( $ps[ 'wvmstmar' ][ 'left' ] ) ? $ps[ 'wvmstmar' ] : array( 'unit' => 'px', 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'isLinked' => '' );
            $wvmrelposx = isset( $ps[ 'wvmrelposx' ] ) && in_array( $ps[ 'wvmrelposx' ], array( 'left', 'right' ), true ) ? $ps[ 'wvmrelposx' ] : 'left';
            $wvmrelposxval = isset( $ps[ 'wvmrelposxval' ] ) && isset( $ps[ 'wvmrelposxval' ][ 'unit' ] ) && in_array( $ps[ 'wvmrelposxval' ][ 'unit' ], array( 'px', '%', 'vw' ), true ) && isset( $ps[ 'wvmrelposxval' ][ 'size' ] ) && filter_var( $ps[ 'wvmrelposxval' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'wvmrelposxval' ] : array( 'unit' => 'px', 'size' => 0 );
            $wvmrelposy = isset( $ps[ 'wvmrelposy' ] ) && in_array( $ps[ 'wvmrelposy' ], array( 'top', 'bottom' ), true ) ? $ps[ 'wvmrelposy' ] : 'top';
            $wvmrelposyval = isset( $ps[ 'wvmrelposyval' ] ) && isset( $ps[ 'wvmrelposyval' ][ 'unit' ] ) && in_array( $ps[ 'wvmrelposyval' ][ 'unit' ], array( 'px', '%', 'vh' ), true ) && isset( $ps[ 'wvmrelposyval' ][ 'size' ] ) && filter_var( $ps[ 'wvmrelposyval' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'wvmrelposyval' ] : array( 'unit' => 'px', 'size' => 0 );
            $wvmabsposx = isset( $ps[ 'wvmabsposx' ] ) && in_array( $ps[ 'wvmabsposx' ], array( 'left', 'right' ), true ) ? $ps[ 'wvmabsposx' ] : 'left';
            $wvmabsposxval = isset( $ps[ 'wvmabsposxval' ] ) && isset( $ps[ 'wvmabsposxval' ][ 'unit' ] ) && in_array( $ps[ 'wvmabsposxval' ][ 'unit' ], array( 'px', '%', 'vw' ), true ) && isset( $ps[ 'wvmabsposxval' ][ 'size' ] ) && filter_var( $ps[ 'wvmabsposxval' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'wvmabsposxval' ] : array( 'unit' => 'px', 'size' => 0 );
            $wvmabsposy = isset( $ps[ 'wvmabsposy' ] ) && in_array( $ps[ 'wvmabsposy' ], array( 'top', 'bottom' ), true ) ? $ps[ 'wvmabsposy' ] : 'top';
            $wvmabsposyval = isset( $ps[ 'wvmabsposyval' ] ) && isset( $ps[ 'wvmabsposyval' ][ 'unit' ] ) && in_array( $ps[ 'wvmabsposyval' ][ 'unit' ], array( 'px', '%', 'vh' ), true ) && isset( $ps[ 'wvmabsposyval' ][ 'size' ] ) && filter_var( $ps[ 'wvmabsposyval' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'wvmabsposyval' ] : array( 'unit' => 'px', 'size' => 0 );
            $wvdpos = isset( $ps[ 'wvdpos' ] ) && in_array( $ps[ 'wvdpos' ], array( 'static', 'relative', 'absolute' ), true ) ? $ps[ 'wvdpos' ] : 'static';
            $wvdwt = isset( $ps[ 'wvdwt' ] ) && isset( $ps[ 'wvdwt' ][ 'unit' ] ) && in_array( $ps[ 'wvdwt' ][ 'unit' ], array( 'px', '%', 'vw' ), true ) && isset( $ps[ 'wvdwt' ][ 'size' ] ) && filter_var( $ps[ 'wvdwt' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'wvdwt' ] : array( 'unit' => '%',  'size' => 100 );
            $wvdht = isset( $ps[ 'wvdht' ] ) && isset( $ps[ 'wvdht' ][ 'unit' ] ) && in_array( $ps[ 'wvdht' ][ 'unit' ], array( 'px', '%', 'vh' ), true ) && isset( $ps[ 'wvdht' ][ 'size' ] ) && filter_var( $ps[ 'wvdht' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'wvdht' ] : array( 'unit' => 'px', 'size' => 800 );
            $wvdstmaren = isset( $ps[ 'wvdstmaren' ] ) && in_array( $ps[ 'wvdstmaren' ], array( 'on', '' ), true ) ? $ps[ 'wvdstmaren' ] : '';
            $wvdstmar = isset( $ps[ 'wvdstmar'  ] ) && isset( $ps[ 'wvdstmar' ][ 'unit' ] ) && in_array( $ps[ 'wvdstmar' ][ 'unit' ], array( 'px', '%' ), true ) && isset( $ps[ 'wvdstmar' ][ 'top' ] ) && isset( $ps[ 'wvdstmar' ][ 'right' ] ) && isset( $ps[ 'wvdstmar' ][ 'bottom' ] ) && isset( $ps[ 'wvdstmar' ][ 'left' ] ) ? $ps[ 'wvdstmar' ] : array( 'unit' => 'px', 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'isLinked' => '' );
            $wvdrelposx = isset( $ps[ 'wvdrelposx' ] ) && in_array( $ps[ 'wvdrelposx' ], array( 'left', 'right' ), true ) ? $ps[ 'wvdrelposx' ] : 'left';
            $wvdrelposxval = isset( $ps[ 'wvdrelposxval' ] ) && isset( $ps[ 'wvdrelposxval' ][ 'unit' ] ) && in_array( $ps[ 'wvdrelposxval' ][ 'unit' ], array( 'px', '%', 'vw' ), true ) && isset( $ps[ 'wvdrelposxval' ][ 'size' ] ) && filter_var( $ps[ 'wvdrelposxval' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'wvdrelposxval' ] : array( 'unit' => 'px', 'size' => 0 );
            $wvdrelposy = isset( $ps[ 'wvdrelposy' ] ) && in_array( $ps[ 'wvdrelposy' ], array( 'top', 'bottom' ), true ) ? $ps[ 'wvdrelposy' ] : 'top';
            $wvdrelposyval = isset( $ps[ 'wvdrelposyval' ] ) && isset( $ps[ 'wvdrelposyval' ][ 'unit' ] ) && in_array( $ps[ 'wvdrelposyval' ][ 'unit' ], array( 'px', '%', 'vh' ), true ) && isset( $ps[ 'wvdrelposyval' ][ 'size' ] ) && filter_var( $ps[ 'wvdrelposyval' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'wvdrelposyval' ] : array( 'unit' => 'px', 'size' => 0 );
            $wvdabsposx = isset( $ps[ 'wvdabsposx' ] ) && in_array( $ps[ 'wvdabsposx' ], array( 'left', 'right' ), true ) ? $ps[ 'wvdabsposx' ] : 'left';
            $wvdabsposxval = isset( $ps[ 'wvdabsposxval' ] ) && isset( $ps[ 'wvdabsposxval' ][ 'unit' ] ) && in_array( $ps[ 'wvdabsposxval' ][ 'unit' ], array( 'px', '%', 'vw' ), true ) && isset( $ps[ 'wvdabsposxval' ][ 'size' ] ) && filter_var( $ps[ 'wvdabsposxval' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'wvdabsposxval' ] : array( 'unit' => 'px', 'size' => 0 );
            $wvdabsposy = isset( $ps[ 'wvdabsposy' ] ) && in_array( $ps[ 'wvdabsposy' ], array( 'top', 'bottom' ), true ) ? $ps[ 'wvdabsposy' ] : 'top';
            $wvdabsposyval = isset( $ps[ 'wvdabsposyval' ] ) && isset( $ps[ 'wvdabsposyval' ][ 'unit' ] ) && in_array( $ps[ 'wvdabsposyval' ][ 'unit' ], array( 'px', '%', 'vh' ), true ) && isset( $ps[ 'wvdabsposyval' ][ 'size' ] ) && filter_var( $ps[ 'wvdabsposyval' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'wvdabsposyval' ] : array( 'unit' => 'px', 'size' => 0 );
            $css_mob = '';
            $css_dsk = '';
            if ( 'on' === $wvmstmaren ) {
              $css_mob .= 'margin: ' . esc_attr( $wvmstmar[ 'top' ] . $wvmstmar[ 'unit' ] ) . ' ' . esc_attr( $wvmstmar[ 'right' ] . $wvmstmar[ 'unit' ] ) . ' ' . esc_attr( $wvmstmar[ 'bottom' ] . $wvmstmar[ 'unit' ] ) . ' ' . esc_attr( $wvmstmar[ 'left' ] . $wvmstmar[ 'unit' ] ) . ';';
            }
            if ( 'relative' === $wvmpos ) {
              if ( $wvmrelposx ) {
                $css_mob .= esc_attr( $wvmrelposx ) . ':'. esc_attr( $wvmrelposxval[ 'size' ] . $wvmrelposxval[ 'unit' ] ) . ';';
              }
              if ( $wvmrelposy ) {
                $css_mob .= esc_attr( $wvmrelposy ) . ':' . esc_attr( $wvmrelposyval[ 'size' ] . $wvmrelposyval[ 'unit' ] ) . ';';
              }
            }
            if ( 'absolute' === $wvmpos ) {
              if ( $wvmabsposx ) {
                $css_mob .= esc_attr( $wvmabsposx ) . ':' . esc_attr( $wvmabsposxval[ 'size' ] . $wvmabsposxval[ 'unit' ] ) . ';';
              }
              if ( $wvmabsposy ) {
                $css_mob .= esc_attr( $wvmabsposy ) . ':' . esc_attr( $wvmabsposyval[ 'size' ] . $wvmabsposyval[ 'unit' ] ) . ';';
              }
            }
            if ( 'on' === $wvdstmaren ) {
              $css_dsk .= 'margin: ' . esc_attr( $wvdstmar[ 'top' ] . $wvdstmar[ 'unit' ] ) . ' ' . esc_attr( $wvdstmar[ 'right' ] . $wvdstmar[ 'unit' ] ) . ' ' . esc_attr( $wvdstmar[ 'bottom' ] . $wvdstmar[ 'unit' ] ) . ' ' . esc_attr( $wvdstmar[ 'left' ] . $wvdstmar[ 'unit' ] ) . ';';
            }
            if ( 'relative' === $wvdpos ) {
              if ( $wvdrelposx ) {
                $css_dsk .= esc_attr( $wvdrelposx ) . ':' . esc_attr( $wvdrelposxval[ 'size' ] . $wvdrelposxval[ 'unit' ] ) . ';';
              }
              if ( $wvdrelposy ) {
                $css_dsk .= esc_attr( $wvdrelposy ) . ':' . esc_attr( $wvdrelposyval[ 'size' ] . $wvdrelposyval[ 'unit' ] ) . ';';
              }
            }
            if ( 'absolute' === $wvdpos ) {
              if ( $wvdabsposx ) {
                $css_dsk .= esc_attr( $wvdabsposx ) . ':' . esc_attr( $wvdabsposxval[ 'size' ] . $wvdabsposxval[ 'unit' ] ) . ';';
              }
              if ( $wvdabsposy ) {
                $css_dsk .= esc_attr( $wvdabsposy ) . ':' . esc_attr( $wvdabsposyval[ 'size' ] . $wvdabsposyval[ 'unit' ] ) . ';';
              }
            }
            $wvmqb = isset( $ps[ 'wvmqb' ] ) && ( isset( $ps[ 'wvmqb' ][ 'unit' ] ) && in_array( $ps[ 'wvmqb' ][ 'unit' ], array( 'px' ), true ) ) && ( isset( $ps[ 'wvmqb' ][ 'size' ] ) && filter_var( $ps[ 'wvmqb' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ) ? $ps[ 'wvmqb' ] : array( 'unit' => 'px', 'size' => 760 );
            $wv_hd_mq_um = $glb_mq_en   ? $glb_mq_xs[ 'unit' ] : $wvmqb[ 'unit' ];
            $wv_hd_mq_val = ( $glb_mq_en ? ( $glb_mq_xs[ 'size' ] + 1 ) : ( $wvmqb[ 'size' ] + 1 ) ) . $wv_hd_mq_um;
            $wv_hd_mq_xs_val = ( $glb_mq_en ? $glb_mq_xs[ 'size' ] : $wvmqb[ 'size' ] ) . $wv_hd_mq_um;
            $body .= '@media only screen and (max-width:' . esc_attr( $wv_hd_mq_xs_val ) . '){.webyx-view{position:' . esc_attr( $wvmpos ) . ';width:' . esc_attr( $wvmwt[ 'size' ] . $wvmwt[ 'unit' ] ) . ';height:' . esc_attr( $wvmht[ 'size' ] . $wvmht[ 'unit' ] ) . ';' . $css_mob . '}}';
            $body .= '@media only screen and (min-width:' . esc_attr( $wv_hd_mq_val ) . '){.webyx-view{position:' . esc_attr( $wvdpos ) . ';width:' . esc_attr( $wvdwt[ 'size' ] . $wvdwt[ 'unit' ] ) . ';height:' . esc_attr( $wvdht[ 'size' ] . $wvdht[ 'unit' ] ) . ';' . $css_dsk . '}}';
            return '<style>' . $body . '</style>';
          }
        }
        public function webyx_fep_get_splash_screen_validated ( $ps ) {
          $ils = isset( $ps[ 'ils' ] ) && in_array( $ps[ 'ils' ], array( 'on', '' ), true ) ? $ps[ 'ils' ] : '';
          $ilst = isset( $ps[ 'ilst' ] ) && in_array( $ps[ 'ilst' ], array( 'default', 'custom' ), true ) ? $ps[ 'ilst' ] : 'default'; 
          $ilsbc = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'ilsbc', '#9933CC' ); 
          $ilssbc = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'ilssbc', '#FFFFFF' );
          $ilscmt = isset( $ps[ 'ilscmt' ] ) && 'string' === gettype( $ps[ 'ilscmt' ] ) ? $ps[ 'ilscmt' ] : '';
          $ilscmtc = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'ilscmtc', '#000000' );
          $ilscbc = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'ilscbc', '#FFFFFF' );
          $ilscbiurl = isset( $ps[ 'ilscbiurl' ] ) ? $ps[ 'ilscbiurl' ] : array( 'url' => '' );
          if ( 'on' === $ils ) {
            switch ( $ilst ) {
              case 'default':
                return '<div class="webyx-splash" style="background-color:' . esc_attr( $ilsbc ) . ';z-index:10000"><div class="webyx-spinner" style="background-color:' . esc_attr( $ilssbc ) . '"></div></div>';
                // return '<style>.webyx-splash{z-index:10000;background-color:' . esc_attr( $ilsbc ) . '}.webyx-spinner{background-color:' . esc_attr( $ilssbc ) . '}</style><div class="webyx-splash"><div class="webyx-spinner"></div></div>';
              case 'custom':
                if ( isset( $ilscbiurl[ 'url' ] ) && '' !== $ilscbiurl[ 'url' ] ) {
                  return '<div class="webyx-splash" style="z-index:10000;color:'. esc_attr( $ilscmtc ) . '">' . ( $ilscbiurl[ 'url' ] ? '<div class="webyx-custom-splash-bkg-img" style="background-image:url(' . esc_url( $ilscbiurl[ 'url' ] ) . ')"></div>' : '' ) . '<div class="webyx-custom-splash-txt webyx-animate-flicker">' . esc_html( $ilscmt ) . '</div></div>';
                }
                return '<div class="webyx-splash" style="z-index:10000;color:'. esc_attr( $ilscmtc ) . ';background-color:' . esc_attr( $ilscbc ) . '"><div class="webyx-custom-splash-txt webyx-animate-flicker">' . esc_html( $ilscmt ) . '</div></div>';
            }
          } else {
            return '<div class="webyx-splash" style="z-index:10000;background-color:#FFFFFF"></div>';
          }
        }
        public function webyx_fep_get_audio_player_validated ( $ps ) {
          $bkgAudioPage = isset( $ps[ 'bkgAudioPage' ] ) && in_array( $ps[ 'bkgAudioPage' ], array( 'on', '' ), true ) ? $ps[ 'bkgAudioPage' ] : ''; 
          $bkg_audio_page_wrp = '';
          if ( 'on' === $bkgAudioPage ) {
            $bkg_audio_page_wrp .= $this->webyx_fep_get_audio_player_css( $ps );
            $bkg_audio_page_wrp .= '<div id="webyx-audio-player"></div>';
          }
          return $bkg_audio_page_wrp;
        }
        public function webyx_fep_get_audio_player_css ( $ps ) {
          $bkgAudioPageDsk = isset( $ps[ 'bkgAudioPageDsk' ] ) && in_array( $ps[ 'bkgAudioPageDsk' ], array( 'on', '' ), true ) ? $ps[ 'bkgAudioPageDsk' ] : '';
          $bkgAudioPageMob = isset( $ps[ 'bkgAudioPageMob' ] ) && in_array( $ps[ 'bkgAudioPageMob' ], array( 'on', '' ), true ) ? $ps[ 'bkgAudioPageMob' ] : '';
          $glb_mq_en = isset( $ps[ 'global_webyx_section_mq_enable' ] ) && in_array( $ps[ 'global_webyx_section_mq_enable' ], array( 'on', '' ), true ) ? $ps[ 'global_webyx_section_mq_enable' ] : '';                                    
          $glb_mq_xs = isset( $ps[ 'global_webyx_section_mq_xs' ] ) && ( isset( $ps[ 'global_webyx_section_mq_xs' ][ 'unit' ] ) && in_array( $ps[ 'global_webyx_section_mq_xs' ][ 'unit' ], array( 'px' ), true ) ) && ( isset( $ps[ 'global_webyx_section_mq_xs' ][ 'size' ] ) && filter_var( $ps[ 'global_webyx_section_mq_xs' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ) ? $ps[ 'global_webyx_section_mq_xs' ] : array( 'unit' => 'px', 'size' => 760 );
          $bkgAudioMQXs = isset( $ps[ 'bkgAudioMQXs' ] ) ? $ps[ 'bkgAudioMQXs' ] : 760; 
          $bkg_audio_mq_um = 'on' === $glb_mq_en ? $glb_mq_xs[ 'unit' ] : 'px';
          $bkg_audio_mq_val = ( 'on' === $glb_mq_en ? ( $glb_mq_xs[ 'size' ] + 1 ) : ( $bkgAudioMQXs + 1 ) ) . $bkg_audio_mq_um;
          $bkg_audio_mq_xs_val = ( 'on' === $glb_mq_en ? ( $glb_mq_xs[ 'size' ] ) : $bkgAudioMQXs ) . $bkg_audio_mq_um;
          $bkg_audio_css_dsk = $this->webyx_fep_get_audio_player_css_dsk( $ps );
          $bkg_audio_css_mob = $this->webyx_fep_get_audio_player_css_mob( $ps );
          $cssMQ = $bkgAudioPageDsk ? '@media only screen and (min-width:' . esc_attr( $bkg_audio_mq_val ) . '){' . esc_attr( $bkg_audio_css_dsk ) . '}' : '@media only screen and (min-width:' . esc_attr( $bkg_audio_mq_val ) . '){' . esc_attr( $bkg_audio_css_dsk ) . '}'; 
          $cssMQXS = $bkgAudioPageMob ? '@media only screen and (max-width:' . esc_attr( $bkg_audio_mq_xs_val ) . '){' . esc_attr( $bkg_audio_css_mob ) . '}' : '@media only screen and (max-width:' . esc_attr( $bkg_audio_mq_xs_val ) . '){' . esc_attr( $bkg_audio_css_mob ) . '}';     
          $css = $cssMQ . $cssMQXS;
          return $css !== '' ? '<style>' . $css . '</style>' : '';
        }
        public function webyx_fep_get_audio_player_css_dsk ( $ps ) {
          $cn_aup = 'webyx-bkg-audio-player';
          $bkgAudioPositionHz = isset( $ps[ 'bkgAudioPositionHz' ] ) && in_array( $ps[ 'bkgAudioPositionHz' ], array( 'left', 'right' ), true ) ? $ps[ 'bkgAudioPositionHz' ] : 'left';
          $bkgAudioPositionHzVal = isset( $ps[ 'bkgAudioPositionHzVal' ] ) && ( isset( $ps[ 'bkgAudioPositionHzVal' ][ 'unit' ] ) && in_array( $ps[ 'bkgAudioPositionHzVal' ][ 'unit' ], array( 'px', '%', 'vw' ), true ) ) && ( isset( $ps[ 'bkgAudioPositionHzVal' ][ 'size' ] ) && filter_var( $ps[ 'bkgAudioPositionHzVal' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ) ? $ps[ 'bkgAudioPositionHzVal' ] : array( 'unit' => 'px', 'size' => 25 );
          $bkgAudioPositionVt = isset( $ps[ 'bkgAudioPositionVt' ] ) && in_array( $ps[ 'bkgAudioPositionVt' ], array( 'top', 'bottom' ), true ) ? $ps[ 'bkgAudioPositionVt' ] : 'bottom';
          $bkgAudioPositionVtVal = isset( $ps[ 'bkgAudioPositionVtVal' ] ) && ( isset( $ps[ 'bkgAudioPositionVtVal' ][ 'unit' ] ) && in_array( $ps[ 'bkgAudioPositionVtVal' ][ 'unit' ], array( 'px', '%', 'vh' ), true ) ) && ( isset( $ps[ 'bkgAudioPositionVtVal' ][ 'size' ] ) && filter_var( $ps[ 'bkgAudioPositionVtVal' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ) ? $ps[ 'bkgAudioPositionVtVal' ] : array( 'unit' => 'px', 'size' => 25 ); 
          $bkgAudioBtnSize = isset( $ps[ 'bkgAudioBtnSize' ] ) && in_array( $ps[ 'bkgAudioBtnSize' ], array( 'small', 'medium', 'large' ), true ) ? $ps[ 'bkgAudioBtnSize' ] : 'medium'; 
          $bkgAudioBtnIcon = isset( $ps[ 'bkgAudioBtnIcon' ] ) && in_array( $ps[ 'bkgAudioBtnIcon' ], array( 'on', '' ), true ) ? $ps[ 'bkgAudioBtnIcon' ] : '';
          $bkgAudioBtnBkg = isset( $ps[ 'bkgAudioBtnBkg' ] ) && in_array( $ps[ 'bkgAudioBtnBkg' ], array( 'color', 'image' ), true ) ? $ps[ 'bkgAudioBtnBkg' ] : 'color';
          $bkgAudioBtnColor = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'bkgAudioBtnColor', '#9933cc' );
          $bkgAudioBtnColorLight = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'bkgAudioBtnColorLight', '#bb64ea' );
          $bkgAudioBtnUrlImage = isset( $ps[ 'bkgAudioBtnUrlImage' ] ) ? $ps[ 'bkgAudioBtnUrlImage' ] : array( 'url' => '' );
          $aup_btn_size = $this->webyx_fep_get_aup_btn_size( $bkgAudioBtnSize );
          $aup_icon = $this->webyx_fep_get_aup_icon( $bkgAudioBtnIcon );
          $aup_btn_bkg = $this->webyx_fep_get_aup_btn_bkg( $bkgAudioBtnBkg, $bkgAudioBtnColor, $bkgAudioBtnColorLight, $bkgAudioBtnUrlImage );
          return '.' . sanitize_html_class( $cn_aup ) . '{position:absolute;' . esc_attr( $bkgAudioPositionHz ) . ':' . esc_attr( $bkgAudioPositionHzVal[ 'size' ] . $bkgAudioPositionHzVal[ 'unit' ] ) . ';' . esc_attr( $bkgAudioPositionVt ) . ':' . esc_attr( $bkgAudioPositionVtVal[ 'size' ] . $bkgAudioPositionVtVal[ 'unit' ] ) . ';z-index:9997}' . $aup_btn_size . $aup_icon . $aup_btn_bkg;
        }
        public function webyx_fep_get_audio_player_css_mob ( $ps ) {
          $cn_aup = 'webyx-bkg-audio-player-xs';
          $bkgAudioPositionHzXs = isset( $ps[ 'bkgAudioPositionHzXs' ] ) && in_array( $ps[ 'bkgAudioPositionHzXs' ], array( 'left', 'right' ), true ) ? $ps[ 'bkgAudioPositionHzXs' ] : 'left';
          $bkgAudioPositionHzValXs = isset( $ps[ 'bkgAudioPositionHzValXs' ] ) && ( isset( $ps[ 'bkgAudioPositionHzValXs' ][ 'unit' ] ) && in_array( $ps[ 'bkgAudioPositionHzValXs' ][ 'unit' ], array( 'px', '%', 'vw' ), true ) ) && ( isset( $ps[ 'bkgAudioPositionHzValXs' ][ 'size' ] ) && filter_var( $ps[ 'bkgAudioPositionHzValXs' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ) ? $ps[ 'bkgAudioPositionHzValXs' ] : array( 'unit' => 'px', 'size' => 25 );
          $bkgAudioPositionVtXs = isset( $ps[ 'bkgAudioPositionVtXs' ] ) && in_array( $ps[ 'bkgAudioPositionVtXs' ], array( 'top', 'bottom' ), true ) ? $ps[ 'bkgAudioPositionVtXs' ] : 'bottom';
          $bkgAudioPositionVtValXs = isset( $ps[ 'bkgAudioPositionVtValXs' ] ) && ( isset( $ps[ 'bkgAudioPositionVtValXs' ][ 'unit' ] ) && in_array( $ps[ 'bkgAudioPositionVtValXs' ][ 'unit' ], array( 'px', '%', 'vw' ), true ) ) && ( isset( $ps[ 'bkgAudioPositionVtValXs' ][ 'size' ] ) && filter_var( $ps[ 'bkgAudioPositionVtValXs' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ) ? $ps[ 'bkgAudioPositionVtValXs' ] : array( 'unit' => 'px', 'size' => 25 ); 
          $bkgAudioBtnSizeXs = isset( $ps[ 'bkgAudioBtnSizeXs' ] ) && in_array( $ps[ 'bkgAudioBtnSizeXs' ], array( 'small', 'medium', 'large' ), true ) ? $ps[ 'bkgAudioBtnSizeXs' ] : 'medium'; 
          $bkgAudioBtnIconXs = isset( $ps[ 'bkgAudioBtnIconXs' ] ) && in_array( $ps[ 'bkgAudioBtnIconXs' ], array( 'on', '' ), true ) ? $ps[ 'bkgAudioBtnIconXs' ] : '';
          $bkgAudioBtnBkgXs = isset( $ps[ 'bkgAudioBtnBkgXs' ] ) && in_array( $ps[ 'bkgAudioBtnBkgXs' ], array( 'color', 'image' ), true ) ? $ps[ 'bkgAudioBtnBkgXs' ] : 'color';
          $bkgAudioBtnColorXs = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'bkgAudioBtnColorXs', '#9933cc' );
          $bkgAudioBtnColorLightXs = $this->webyx_fep_get_global_elmnt_control_value( $ps, 'bkgAudioBtnColorLightXs', '#bb64ea' );
          $bkgAudioBtnUrlImageXs = isset( $ps[ 'bkgAudioBtnUrlImageXs' ] ) ? $ps[ 'bkgAudioBtnUrlImageXs' ] : array( 'url' => '' );
          $aup_btn_size = $this->webyx_fep_get_aup_btn_size( $bkgAudioBtnSizeXs );
          $aup_icon = $this->webyx_fep_get_aup_icon( $bkgAudioBtnIconXs );
          $aup_btn_bkg = $this->webyx_fep_get_aup_btn_bkg( $bkgAudioBtnBkgXs, $bkgAudioBtnColorXs, $bkgAudioBtnColorLightXs, $bkgAudioBtnUrlImageXs );
          return '.' . sanitize_html_class( $cn_aup ) . '{position:absolute;'. esc_attr( $bkgAudioPositionHzXs ) . ':' . esc_attr( $bkgAudioPositionHzValXs[ 'size' ] . $bkgAudioPositionHzValXs[ 'unit' ] ) . ';'. esc_attr( $bkgAudioPositionVtXs ) . ':' . esc_attr( $bkgAudioPositionVtValXs[ 'size' ] . $bkgAudioPositionVtValXs[ 'unit' ] ) . ';z-index:9997}' . $aup_btn_size . $aup_icon . $aup_btn_bkg;
        }
        public function webyx_fep_get_aup_btn_size ( $bkgAudioBtnSize ) {
          switch ( $bkgAudioBtnSize ) {
            case 'small':
              return '.webyx-player{width:40px;height:40px}';
            case 'medium':
              return '.webyx-player{width:55px;height:55px}';
            case 'large':
              return '.webyx-player{width:70px;height:70px}';
          }
        }
        public function webyx_fep_get_aup_icon ( $bkgAudioBtnIcon ) {
          if ( '' === $bkgAudioBtnIcon ) {
            return '.webyx-player-btn-img{display:none}';
          }
          return '';
        }
        public function webyx_fep_get_aup_btn_bkg ( $bkgAudioBtnBkg, $bkgAudioBtnColor, $bkgAudioBtnColorLight, $bkgAudioBtnUrlImage ) {
          switch ( $bkgAudioBtnBkg ) {
            case 'color':
              return '.webyx-player-btn{background-color:' . esc_attr( $bkgAudioBtnColor ) . '}.webyx-player-btn:hover{background-color:' . esc_attr( $bkgAudioBtnColorLight ) . '}';
            case 'image':
              return '.webyx-player-btn{background-image:url(' . esc_url( $bkgAudioBtnUrlImage[ 'url' ] ) . ');background-repeat:no-repeat;background-position:center;background-size:cover}';
          }
        }
        public function webyx_fep_frontend_enqueue_assets () {
          add_action( 
            'elementor/frontend/after_enqueue_scripts', 
            array(
              $this, 
              'webyx_fep_frontend_enqueue_scripts' 
            )
          );
          add_action( 
            'elementor/frontend/after_enqueue_styles', 
            array( 
              $this, 
              'webyx_fep_frontend_enqueue_style' 
            ) 
          );
        }
        public function webyx_fep_frontend_enqueue_scripts () {
          global $post;
          $ps = get_post_meta( $post->ID, '_elementor_page_settings', true );
          $is_frontend_view = $this->webyx_fep_is_frontend_view();
          $webyx_fep_is_enable = $this->webyx_fep_is_enable( $ps );
          $webyx_fep_menu = get_option( 'webyx_fep_menu', 'true' );
          if ( $webyx_fep_is_enable && $is_frontend_view ) {
            $fn = WEBYX_FEP_ASSET_MIN ? 'assets/js/webyx.min.js' : 'assets/js/webyx.js';
            $path = plugins_url( 
              $fn, 
              __FILE__ 
            );
            wp_register_script( 
              'webyx-fep-core-script', 
              $path,
              array(),
              filemtime( 
                plugin_dir_path( __FILE__ ) . $fn
              )
            );
            wp_enqueue_script( 'webyx-fep-core-script' );
            wp_localize_script(
              'webyx-fep-core-script',
              '_wrg',
              array( '_wrga' => filter_var( get_option( 'webyx_fep_ak' ), FILTER_VALIDATE_BOOLEAN ), )
            );
            if ( $webyx_fep_menu ) {
              $fn_wm = WEBYX_FEP_ASSET_MIN ? 'assets/js/webyx-menu.min.js' : 'assets/js/webyx-menu.js';
              $path = plugins_url( 
                $fn_wm, 
                __FILE__ 
              );
              wp_register_script( 
                'webyx-fep-menu-script', 
                $path,
                array(),
                filemtime( 
                  plugin_dir_path( __FILE__ ) . $fn_wm
                )
              );
              wp_enqueue_script( 'webyx-fep-menu-script' );
            }
          }
        }
        public function webyx_fep_frontend_enqueue_style () {
          global $post;
          $ps = get_post_meta( $post->ID, '_elementor_page_settings', true );
          $is_frontend_view = $this->webyx_fep_is_frontend_view();
          $webyx_fep_is_enable = $this->webyx_fep_is_enable( $ps );
          if ( $webyx_fep_is_enable && $is_frontend_view ) {
            $fn = WEBYX_FEP_ASSET_MIN ? 'assets/css/webyx.min.css' : 'assets/css/webyx.css';
            $path = plugins_url( 
              $fn, 
              __FILE__ 
            );
            wp_register_style( 
              'webyx-fep-core-style', 
              $path,
              array(),
              filemtime( 
                plugin_dir_path( __FILE__ ) . $fn 
              )
            );
            wp_enqueue_style( 'webyx-fep-core-style' );
          }
        }
        public function webyx_fep_get_settings_validated ( $ps ) {
          $cnf_s = array(
            'wvhdtype' => array(
              'value'     => 'blk',
              'values'    => array( 
                'linear'  => 'blk', 
                'easeout' => 'tmp', 
              ),
              'data_type' => 'string',
            ),
            'epart' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'scry' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'scrlreset' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'nosi' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'nositr' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'nosiw' => array(
              'value'  => 900,
              'values' => array( 
                'min_range' => 0, 
                'max_range' => 5000, 
              ),
              'data_type' => 'number',
            ),
            'nosih' => array(
              'value'  => 900,
              'values' => array( 
                'min_range' => 0, 
                'max_range' => 5000, 
              ),
              'data_type' => 'number',
            ),
            'nosiafh' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'nosian' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'vtcnosi' => array(
              'value'     => 'easeout',
              'values'    => array( 
                'linear'  => 'linear', 
                'easeout' => 'easeout', 
                'arc'     => 'arc', 
                'quad'    => 'quad', 
                'cube'    => 'cube', 
              ),
              'data_type' => 'string',
            ),
            'vtsnosi' => array(
              'value'  => 1000,
              'values' => array( 
                'min_range' => 300, 
                'max_range' => 1200, 
              ),
              'data_type' => 'number',
            ),
            'cv' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'cvantp' => array(
              'value'  => 'slide',
              'values' => array( 
                'toggle' => 'toggle', 
                'slide'  => 'slide', 
                'fade'   => 'fade',
              ),
              'data_type' => 'string'
            ),
            'pv' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'pvo' => array(
              'value'  => 40,
              'values' => array( 
                '10%' => 90, 
                '20%' => 80, 
                '30%' => 70, 
                '40%' => 60, 
                '50%' => 50, 
                '60%' => 40, 
                '70%' => 30, 
                '80%' => 20, 
                '90%' => 10, 
              ),
              'data_type' => 'number',
            ),
            'ch' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'chantp' => array(
              'value'  => 'slide',
              'values' => array( 
                'toggle' => 'toggle', 
                'slide'  => 'slide', 
                'fade'   => 'fade',
              ),
              'data_type' => 'string'
            ),
            'ph' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'pho' => array(
              'value'  => 40,
              'values' => array( 
                '10%' => 90, 
                '20%' => 80, 
                '30%' => 70, 
                '40%' => 60, 
                '50%' => 50, 
                '60%' => 40, 
                '70%' => 30, 
                '80%' => 20, 
                '90%' => 10, 
              ),
              'data_type' => 'number',
            ),
            'av' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'avven' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'avhen' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'avf' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'avvd' => array( 
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'nvvw' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'iwhf' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'dv' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'dvp' => array(
              'value'  => 'right',
              'values' => array( 
                'left'  => 'left', 
                'right' => 'right', 
              ),
              'data_type' => 'string'
            ),
            'dtv' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'dtvcp' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'dh' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'dhp' => array(
              'value'  => 'bottom',
              'values' => array( 
                'top'    => 'top', 
                'bottom' => 'bottom', 
              ),
              'data_type' => 'string'
            ),
            'dhs' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'dth' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'dthcp' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'mvndtane' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'fsb' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'fsp' => array(
              'value'  => 'right',
              'values' => array( 
                'left'  => 'left', 
                'right' => 'right', 
              ),
              'data_type' => 'string'
            ),
            'kn' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'fdskm' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'thrX' => array(
              'value'  => 25,
              'values' => array(
                '10 px'  => 10,
                '25 px'  => 20,
                '50 px'  => 50,
                '75 px'  => 75,
                '100 px' => 100,
              ),
              'data_type' => 'number'
            ),
            'thrY' => array(
              'value'  => 50,
              'values' => array(
                '10 px'  => 10,
                '25 px'  => 20,
                '50 px'  => 50,
                '75 px'  => 75,
                '100 px' => 100,
              ),
              'data_type' => 'number'
            ),
            'swx' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'mwh' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'bkgAudioPage' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'bkgAudioAutoClosed' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'bkgAudioAutoClosedTimer' => array(
              'value'  => 5,
              'values' => array( 
                'min_range' => 5, 
                'max_range' => 20, 
              ),
              'data_type' => 'number',
            ),
            'bkgAudioMQXs' => array(
              'value'  => 760,
              'values' => array( 
                'min_range' => 0, 
                'max_range' => 5000, 
              ),
              'data_type' => 'number',
            ),
            'bkgAudioPageDsk' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'bkgAudioUrl' => array(
              'value'     => '',
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'url',
            ),
            'bkgAudioPreload' => array(
              'value'  => 'auto',
              'values' => array( 
                'none'     => 'none', 
                'auto'     => 'auto', 
                'metadata' => 'metadata', 
              ),
              'data_type' => 'string',
            ),
            'bkgAudioControls' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'bkgAudioMuted' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'bkgAudioAutoplay' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'bkgAudioLoop' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'bkgAudioPageMob' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'bkgAudioUrlXs' => array(
              'value'     => '',
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'url',
            ),
            'bkgAudioPreloadXs' => array(
              'value'  => 'auto',
              'values' => array( 
                'none'     => 'none', 
                'auto'     => 'auto', 
                'metadata' => 'metadata', 
              ),
              'data_type' => 'string',
            ),
            'bkgAudioControlsXs' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'bkgAudioMutedXs' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'bkgAudioAutoplayXs' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'bkgAudioLoopXs' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'ilsctmen' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ), 
            'ils' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ), 
            'ilscsi' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ), 
            'ilsctm' => array(
              'value'  => 1,
              'values' => array( 
                'min_range' => 1, 
                'max_range' => 10, 
              ),
              'data_type' => 'number',
            ),
            'hzscrllstyle' => array(
              'value'     => 'vt',
              'values'    => array( 
                'vertical (vertical scroll)'  => 'vt', 
                'classic (horizontal scroll)' => 'hz', 
                'both (vertical + classic)'   => 'vthz', 
              ),
              'data_type' => 'string',
            ),
            'hzscrllvm' => array(
              'value'  => 2,
              'values' => array( 
                'min_range' => 1, 
                'max_range' => 10, 
              ),
              'data_type' => 'number',
            ),
            'hzscrllvd' => array(
              'value'  => 2,
              'values' => array( 
                'min_range' => 1, 
                'max_range' => 100, 
              ),
              'data_type' => 'number',
            ),
            'hason' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'hzsmooth' => array(
              'value'     => FALSE,
              'values'    => array( TRUE, FALSE ),
              'data_type' => 'boolean',
            ),
            'hzsmoothtime' => array(
              'value'  => 0.8,
              'values' => array( 
                'min_range' => 0.8, 
                'max_range' => 2, 
              ),
              'data_type' => 'number',
            ),
          );
          return $this->webyx_fep_get_cast_settings( $cnf_s, $ps );
        }
        public function webyx_fep_get_cast_settings ( $cnf_s, $ps ) {
          $pre = '<script>var wbySet=';
          $post = '</script>';
          $body = array();
          foreach ( $cnf_s as $cnf_key => $cnf_value ) {
            if ( isset( $ps[ $cnf_key ] ) ) {
              $cnf_data_type = $cnf_value[ 'data_type' ];
              $value = $ps[ $cnf_key ];
              switch ( $cnf_data_type ) {
                case 'string':
                  $body[ $cnf_key ] = $value;
                  break;
                case 'number':
                  $body[ $cnf_key ] = intval( $value );
                  break;
                case 'boolean':
                  $body[ $cnf_key ] = 'on' === $value ? TRUE : FALSE;
                  break;
                case 'url':
                  $body[ $cnf_key ] = $value[ 'url' ];
                  break;
              }
            } else {
              $body[ $cnf_key ] = $cnf_value[ 'value' ];
            }
          }
          return $pre . json_encode( $body, true ) . $post;
        }
        public function webyx_fep_get_event_hooks ( $ps ) {
          $hk_set = array(
            'obl' => array(
              'event_name'     => 'onBeforeLeave',
              'attr_code_name' => 'oblc',
              'params'         => array( 'webyx' )
            ),
            'oae' => array(
              'event_name'     => 'onAfterEnter',
              'attr_code_name' => 'oaec',
              'params'         => array( 'webyx' )
            ),
            'oblya' => array(
              'event_name'     => 'onBeforeLeaveYaxis',
              'attr_code_name' => 'oblyac',
              'params'         => array( 'iCurY', 'iTrgY', 'iCurX', 'webyx' )
            ),
            'oaeya' => array(
              'event_name'     => 'onAfterEnterYaxis',
              'attr_code_name' => 'oaeyac',
              'params'         => array( 'iCurY', 'iPrevY', 'iPrevX', 'webyx' )
            ),
            'oblxa' => array(
              'event_name'     => 'onBeforeLeaveXaxis',
              'attr_code_name' => 'oblxac',
              'params'         => array( 'iCurX', 'iTrgX', 'iCurY', 'webyx' )
            ),
            'oaexa' => array(
              'event_name'     => 'onAfterEnterXaxis',
              'attr_code_name' => 'oaexac',
              'params'         => array( 'iCurX', 'iPrevX', 'iCurY', 'webyx' )
            ),
            'oalw' => array(
              'event_name'     => 'onAfterLoadWebyx',
              'attr_code_name' => 'oalwc',
              'params'         => array( 'webyx' )
            ),
          );
          return $this->webyx_fep_get_cast_event_hooks( $hk_set, $ps );
        }
        public function webyx_fep_get_cast_event_hooks ( $hk_set, $ps ) {
          $hkse = ( isset( $ps[ 'hkse' ] ) && 'on' === $ps[ 'hkse' ] );
          if ( $hkse ) {
            $pre  = '<script>wbySet.hooks={';
            $post = '}</script>';
            $code = '';
            foreach ( $hk_set as $hook_attr => $hook_obj ) {
              if ( isset( $ps[ $hook_attr ] ) && 'on' === $ps[ $hook_attr ] ) {
                $code .= "{$hook_obj[ 'event_name' ]}:";
                $code .= "function(";
                $paramLength = count( $hk_set[ $hook_attr ][ 'params' ] );
                $params = $hk_set[ $hook_attr ][ 'params' ];
                for ( $i = 0; $i < $paramLength; $i++ ) {
                  $hook_param = $params[ $i ];
                  if ( $i === ( $paramLength - 1 ) ) {
                    $code .= "{$hook_param}) {";
                  } else {
                    $code .= "{$hook_param},";
                  }
                }
                $code .= isset( $ps[ $hk_set[ $hook_attr ]['attr_code_name'] ] ) ? $ps[ $hk_set[ $hook_attr ]['attr_code_name'] ] : '';
                $code .= '},';
              }
            }
            return $pre . $code . $post;
          }
          return '';
        }
        public function webyx_fep_get_bkg_section_video_validated ( $s, $cn_bkg_video ) {
          $wsb = isset( $s[ 'webyx_section_background' ] ) && in_array( $s[ 'webyx_section_background' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_background' ] : '';
          $wsb_dsk = isset( $s[ 'webyx_section_background_dsk' ] ) && in_array( $s[ 'webyx_section_background_dsk' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_background_dsk' ] : '';
          $ws_foreground_object = isset( $s[ 'webyx_section_foreground_object' ] ) && in_array( $s[ 'webyx_section_foreground_object' ], array( 'color', 'image', 'video' ), true ) ? $s[ 'webyx_section_foreground_object' ] : 'color';
          $ws_video_el = '';
          if ( 'on' === $wsb && 'on' === $wsb_dsk && 'video' === $ws_foreground_object ) {
            $wsb_video_url = isset( $s[ 'webyx_section_background_video' ] ) ? $s[ 'webyx_section_background_video' ] : array( 'url' => '' );
            $wsb_video_poster = isset( $s[ 'webyx_section_background_video_poster_enable' ] ) && in_array( $s[ 'webyx_section_background_video_poster_enable' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_background_video_poster_enable' ] : '';
            $wsb_video_poster_url = isset( $s[ 'webyx_section_background_video_poster' ] ) ? $s[ 'webyx_section_background_video_poster' ] : array( 'url' => '' );
            $wsb_video_preload = isset( $s[ 'webyx_section_background_video_preload' ] ) && in_array( $s[ 'webyx_section_background_video_preload' ], array( 'none', 'auto', 'metadata' ), true ) ? $s[ 'webyx_section_background_video_preload' ] : 'auto';
            $wsb_video_controls = ( isset( $s[ 'webyx_section_background_video_controls' ] ) && 'on' === $s[ 'webyx_section_background_video_controls' ] ) ? 'controls ' : '';
            $wsb_video_muted = ( isset( $s[ 'webyx_section_background_video_muted' ] ) && 'on' === $s[ 'webyx_section_background_video_muted' ] ) ? 'muted ' : '';
            $wsb_video_autoplay = ( isset( $s[ 'webyx_section_background_video_autoplay' ] ) && 'on' === $s[ 'webyx_section_background_video_autoplay' ] ) ? 'autoplay ' : '';
            $wsb_video_loop = ( isset( $s[ 'webyx_section_background_video_loop' ] ) && 'on' === $s[ 'webyx_section_background_video_loop' ] ) ? 'loop ' : '';
            $attr_video_validated = ( 'on' === $wsb_video_poster && '' !== $wsb_video_poster_url[ 'url' ] ? 'poster="'. esc_url( $wsb_video_poster_url[ 'url' ] ) . '" ' : '' ) . 'preload="' . esc_attr( $wsb_video_preload ) . '" ' . esc_attr( $wsb_video_controls ) . esc_attr( $wsb_video_muted ) . esc_attr( $wsb_video_autoplay ) . esc_attr( $wsb_video_loop );
            $ws_video_el .= '<video class="webyx-video-bkg ' . sanitize_html_class( $cn_bkg_video ) . '" ' . $attr_video_validated . '><source src="' . esc_url( $wsb_video_url[ 'url' ] ) . '"/></video>';
          }
          $wsb_mob = isset( $s[ 'webyx_section_background_mob' ] ) && in_array( $s[ 'webyx_section_background_mob' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_background_mob' ] : '';
          $ws_foreground_object_mob = isset( $s[ 'webyx_section_foreground_object_mob' ] ) && in_array( $s[ 'webyx_section_foreground_object_mob' ], array( 'color', 'image', 'video' ), true ) ? $s[ 'webyx_section_foreground_object_mob' ] : 'color';
          if ( 'on' === $wsb && 'on' === $wsb_mob && 'video' === $ws_foreground_object_mob ) {
            $wsb_video_url_mob = isset( $s[ 'webyx_section_background_video_mob' ] ) ? $s[ 'webyx_section_background_video_mob' ] : array( 'url' => '' );
            $wsb_video_poster_mob = isset( $s[ 'webyx_section_background_video_poster_enable_mob' ] ) && in_array( $s[ 'webyx_section_background_video_poster_enable_mob' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_background_video_poster_enable_mob' ] : '';
            $wsb_video_poster_url_mob = isset( $s[ 'webyx_section_background_video_poster_mob' ] ) ? $s[ 'webyx_section_background_video_poster_mob' ] : array( 'url' => '' );
            $wsb_video_preload_mob = isset( $s[ 'webyx_section_background_video_preload_mob' ] ) && in_array( $s[ 'webyx_section_background_video_preload_mob' ], array( 'none', 'auto', 'metadata' ), true ) ? $s[ 'webyx_section_background_video_preload_mob' ] : 'auto';
            $wsb_video_controls_mob = ( isset( $s[ 'webyx_section_background_video_controls_mob' ] ) && 'on' === $s[ 'webyx_section_background_video_controls_mob' ] ) ? 'control' : '';
            $wsb_video_muted_mob = ( isset( $s[ 'webyx_section_background_video_muted_mob' ] ) && 'on' === $s[ 'webyx_section_background_video_muted_mob' ] ) ? 'muted ' : '';
            $wsb_video_autoplay_mob = ( isset( $s[ 'webyx_section_background_video_autoplay_mob' ] ) && 'on' === $s[ 'webyx_section_background_video_autoplay_mob' ] ) ? 'autoplay ' : '';
            $wsb_video_loop_mob = ( isset( $s[ 'webyx_section_background_video_loop_mob' ] ) && 'on' === $s[ 'webyx_section_background_video_loop_mob' ] ) ? 'loop ' : '';
            $attr_video_mob_validated = ( 'on' === $wsb_video_poster_mob && '' !== $wsb_video_poster_url_mob[ 'url' ] ? 'poster="'. esc_url( $wsb_video_poster_url_mob[ 'url' ] ) . '" ' : '' ) . 'preload="' . esc_attr( $wsb_video_preload_mob ) . '" ' . esc_attr( $wsb_video_controls_mob ) . esc_attr( $wsb_video_muted_mob ) . esc_attr( $wsb_video_autoplay_mob ) . esc_attr( $wsb_video_loop_mob );
            $ws_video_el .= '<video class="webyx-video-bkg ' . sanitize_html_class( $cn_bkg_video . '-xs' ) . '" ' . $attr_video_mob_validated . '><source src="' . esc_url( $wsb_video_url_mob[ 'url' ] ) . '"/></video>';
          }
          return $ws_video_el;
        }
        public function webyx_fep_get_section_style_validated ( $s, $ps, $cn_bkg, $cn_bkg_video, $cn_wrp_cnt ) {
          $glb_mq_en = isset( $ps[ 'global_webyx_section_mq_enable' ] ) && in_array( $ps[ 'global_webyx_section_mq_enable' ], array( 'on', '' ), true ) ? $ps[ 'global_webyx_section_mq_enable' ] : '';                           
          $glb_mq_xs = isset( $ps[ 'global_webyx_section_mq_xs' ] ) && ( isset( $ps[ 'global_webyx_section_mq_xs' ][ 'unit' ] ) && in_array( $ps[ 'global_webyx_section_mq_xs' ][ 'unit' ], array( 'px' ), true ) ) && ( isset( $ps[ 'global_webyx_section_mq_xs' ][ 'size' ] ) && filter_var( $ps[ 'global_webyx_section_mq_xs' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ) ? $ps[ 'global_webyx_section_mq_xs' ] : array( 'unit' => 'px', 'size' => 760 );
          $mq_xs = isset( $s[ 'webyx_section_mq_xs' ] ) && ( isset( $s[ 'webyx_section_mq_xs' ][ 'unit' ] ) && in_array( $s[ 'webyx_section_mq_xs' ][ 'unit' ], array( 'px' ), true ) ) && ( isset( $s[ 'webyx_section_mq_xs' ][ 'size' ] ) && filter_var( $s[ 'webyx_section_mq_xs' ][ 'size' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ) ? $s[ 'webyx_section_mq_xs' ] : array( 'unit' => 'px', 'size' => 760 );
          $mq_val = ( $glb_mq_en ? ( $glb_mq_xs[ 'size' ] + 1 ) : ( $mq_xs[ 'size' ] + 1 ) ) . ( $glb_mq_en ? $glb_mq_xs[ 'unit' ] : $mq_xs[ 'unit' ] );
          $mq_xs_val = ( $glb_mq_en ? ( $glb_mq_xs[ 'size' ] ) : $mq_xs[ 'size' ] ) . ( $glb_mq_en ? $glb_mq_xs[ 'unit' ] : $mq_xs[ 'unit' ] );
          $props = $this->webyx_fep_get_css_section_props_validated( $s, $cn_bkg, $cn_bkg_video, $cn_wrp_cnt );
          $props_xs = $this->webyx_fep_get_css_section_props_xs_validated( $s, $cn_bkg, $cn_bkg_video, $cn_wrp_cnt );
          $css_mq = $props ? '@media only screen and (min-width:' . esc_attr( $mq_val ) . '){' . $props . '}' : '';
          $css_mq_xs = $props_xs ? '@media only screen and (max-width:' . esc_attr( $mq_xs_val ) . '){' . $props_xs . '}' : '';
          $css = $css_mq_xs . $css_mq;
          return $css !== '' ? '<style>' . $css . '</style>' : '';
        }
        public function webyx_fep_get_css_section_props_validated ( $s, $cn_bkg, $cn_bkg_video, $cn_wrp_cnt ) {
          $props = '';
          $props .= $this->webyx_fep_get_bkg_css_rules_validated( $s, $cn_bkg, $cn_bkg_video );
          $props .= $this->webyx_fep_get_cnt_wrp_css_rules_validated( $s, $cn_bkg, $cn_wrp_cnt );
          return $props;
        }
        public function webyx_fep_get_css_section_props_xs_validated ( $s, $cn_bkg, $cn_bkg_video, $cn_wrp_cnt ) {
          $props_xs = '';
          $props_xs .= $this->webyx_fep_get_bkg_css_rules_xs_validated( $s, $cn_bkg, $cn_bkg_video );
          $props_xs .= $this->webyx_fep_get_cnt_wrp_css_rules_xs_validated( $s, $cn_bkg, $cn_wrp_cnt );
          return $props_xs;
        }
        public function webyx_fep_get_bkg_css_rules_validated ( $s, $cn_bkg, $cn_bkg_video ) {
          $ws_bkg = isset( $s[ 'webyx_section_background' ] ) && in_array( $s[ 'webyx_section_background' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_background' ] : ''; 
          $ws_bkg_dsk = isset( $s[ 'webyx_section_background_dsk' ] ) && in_array( $s[ 'webyx_section_background_dsk' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_background_dsk' ] : '';
          $ws_bkg_mob = isset( $s[ 'webyx_section_background_mob' ] ) && in_array( $s[ 'webyx_section_background_mob' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_background_mob' ] : '';
          if ( 'on' === $ws_bkg ) {
            if ( 'on' === $ws_bkg_dsk ) {
              $ws_foreground_object = isset( $s[ 'webyx_section_foreground_object' ] ) && in_array( $s[ 'webyx_section_foreground_object' ], array( 'color', 'image', 'video' ), true ) ? $s[ 'webyx_section_foreground_object' ] : 'color';
              switch ( $ws_foreground_object ) {
                case 'color':
                  $ws_bkg_colour = $this->webyx_fep_get_global_elmnt_control_value( $s, 'webyx_section_background_colour', '#ffffff' );
                  if ( '' !== $ws_bkg_colour ) {
                    return '.' . sanitize_html_class( $cn_bkg ) . '{background-color:' . esc_attr( $ws_bkg_colour ) . '}.' . sanitize_html_class( $cn_bkg_video ) . ',.' . sanitize_html_class( $cn_bkg_video . '-xs' ) . '{display:none}';
                  }
                  return '';
                case 'image':
                  $section_background_image_url = isset( $s[ 'webyx_section_background_image' ] ) ? $s[ 'webyx_section_background_image' ] : array( 'url' => '' );
                  if ( isset( $section_background_image_url[ 'url' ] ) && '' !== $section_background_image_url[ 'url' ] ) {
                    $ws_bkg_image_size = isset( $s[ 'webyx_section_background_image_size' ] ) && in_array( $s[ 'webyx_section_background_image_size' ], array( 'auto', 'cover', 'contain' ), true ) ? $s[ 'webyx_section_background_image_size' ] : 'cover';
                    $ws_bkg_image_position = isset( $s[ 'webyx_section_background_image_position' ] ) && in_array( $s[ 'webyx_section_background_image_position' ], array( 'left top', 'left center', 'left bottom', 'right top', 'right center', 'right bottom', 'center top', 'center center', 'center bottom' ), true ) ? $s[ 'webyx_section_background_image_position' ] : 'center center';
                    $ws_bkg_image_repeat = isset( $s[ 'webyx_section_background_image_repeat' ] ) && in_array( $s[ 'webyx_section_background_image_repeat' ], array( 'repeat', 'no-repeat' ), true ) ? $s[ 'webyx_section_background_image_repeat' ] : 'no-repeat';
                    $ws_bkg_image_attachment = isset( $s[ 'webyx_section_background_image_attachment' ] ) && in_array( $s[ 'webyx_section_background_image_attachment' ], array( 'fixed', 'scroll' ), true ) ? $s[ 'webyx_section_background_image_attachment' ] : 'scroll';
                    return '.' . sanitize_html_class( $cn_bkg ) . '{background-image:url(' . esc_url( $section_background_image_url[ 'url' ] ) . ');background-size:' . esc_attr( $ws_bkg_image_size ) . ';background-position:' . esc_attr( $ws_bkg_image_position ) . ';background-repeat:' . esc_attr( $ws_bkg_image_repeat ) . ';background-attachment:' . esc_attr( $ws_bkg_image_attachment ) . '}.' . sanitize_html_class( $cn_bkg_video ) . ',.' . sanitize_html_class( $cn_bkg_video . '-xs' ) . '{display:none}';
                  }
                  return '';
                case 'video':
                  return '.' . sanitize_html_class( $cn_bkg_video . '-xs' ) . '{display:none}';
                default:
                  return '';
              }
            }
            if ( 'on' === $ws_bkg_mob ) {
              return '.' . sanitize_html_class( $cn_bkg_video . '-xs' ) . '{display:none}';
            }
          } 
          return '';
        }
        public function webyx_fep_get_bkg_css_rules_xs_validated ( $s, $cn_bkg, $cn_bkg_video ) {
          $ws_bkg = isset( $s[ 'webyx_section_background' ] ) && in_array( $s[ 'webyx_section_background' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_background' ] : ''; 
          $ws_bkg_dsk = isset( $s[ 'webyx_section_background_dsk' ] ) && in_array( $s[ 'webyx_section_background_dsk' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_background_dsk' ] : '';
          $ws_bkg_mob = isset( $s[ 'webyx_section_background_mob' ] ) && in_array( $s[ 'webyx_section_background_mob' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_background_mob' ] : '';
          if ( 'on' === $ws_bkg ) {
            if ( 'on' === $ws_bkg_mob ) {
              $ws_foreground_object_mob = isset( $s[ 'webyx_section_foreground_object_mob' ] ) && in_array( $s[ 'webyx_section_foreground_object_mob' ], array( 'color', 'image', 'video' ), true ) ? $s[ 'webyx_section_foreground_object_mob' ] : 'color';
              switch ( $ws_foreground_object_mob ) {
                case 'color':
                  $ws_bkg_colour_mob = $this->webyx_fep_get_global_elmnt_control_value( $s, 'webyx_section_background_colour_mob', '#ffffff' );
                  if ( '' !== $ws_bkg_colour_mob ) {
                    return '.' . sanitize_html_class( $cn_bkg ) . '{background-color:' . esc_attr( $ws_bkg_colour_mob ) . '}.' . sanitize_html_class( $cn_bkg_video ) . ',.' . sanitize_html_class( $cn_bkg_video . '-xs' ) . '{display:none}';
                  }
                  return '';
                case 'image':
                  $section_background_image_url_mob = isset( $s[ 'webyx_section_background_image_mob' ] ) ? $s[ 'webyx_section_background_image_mob' ] : array( 'url' => '' );
                  if ( isset( $section_background_image_url_mob[ 'url' ] ) && '' !== $section_background_image_url_mob[ 'url' ] ) {
                    $ws_bkg_image_size_mob = isset( $s[ 'webyx_section_background_image_size_mob' ] ) && in_array( $s[ 'webyx_section_background_image_size_mob' ], array( 'auto', 'cover', 'contain' ), true ) ? $s[ 'webyx_section_background_image_size_mob' ] : 'cover';
                    $ws_bkg_image_position_mob = isset( $s[ 'webyx_section_background_image_position_mob' ] ) && in_array( $s[ 'webyx_section_background_image_position_mob' ], array( 'left top', 'left center', 'left bottom', 'right top', 'right center', 'right bottom', 'center top', 'center center', 'center bottom' ), true ) ? $s[ 'webyx_section_background_image_position_mob' ] : 'center center';
                    $ws_bkg_image_repeat_mob = isset( $s[ 'webyx_section_background_image_repeat_mob' ] ) && in_array( $s[ 'webyx_section_background_image_repeat_mob' ], array( 'repeat', 'no-repeat' ), true ) ? $s[ 'webyx_section_background_image_repeat_mob' ] : 'no-repeat';
                    $ws_bkg_image_attachment_mob = isset( $s[ 'webyx_section_background_image_attachment_mob' ] ) && in_array( $s[ 'webyx_section_background_image_attachment_mob' ], array( 'fixed', 'scroll' ), true ) ? $s[ 'webyx_section_background_image_attachment_mob' ] : 'scroll';
                    return '.' . sanitize_html_class( $cn_bkg ) . '{background-image:url(' . esc_url( $section_background_image_url_mob[ 'url' ] ) . ');background-size:' . esc_attr( $ws_bkg_image_size_mob ) . ';background-position:' . esc_attr( $ws_bkg_image_position_mob ) . ';background-repeat:' . esc_attr( $ws_bkg_image_repeat_mob ) . ';background-attachment:' . esc_attr( $ws_bkg_image_attachment_mob ) . '}.' . sanitize_html_class( $cn_bkg_video ) . ',.' . sanitize_html_class( $cn_bkg_video . '-xs' ) . '{display:none}';
                  }
                  return '';
                case 'video':
                  return '.' . sanitize_html_class( $cn_bkg_video ) . '{display:none}';
                default:
                  return '';
              }
            }
            if ( 'on' === $ws_bkg_dsk )  {
              return '.' . sanitize_html_class( $cn_bkg_video ) . '{display:none}';
            }
          } 
          return '';
        }
        public function webyx_fep_get_cnt_wrp_css_rules_validated ( $s, $cn_bkg, $cn_wrp_cnt ) {
          $ws_wrp_cnt = isset( $s[ 'webyx_section_wrapper_cnt' ] ) && in_array( $s[ 'webyx_section_wrapper_cnt' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_wrapper_cnt' ] : '';
          $ws_wrp_cnt_mar_en_dsk = isset( $s[ 'webyx_section_wrapper_cnt_margin_enable_dsk' ] ) && in_array( $s[ 'webyx_section_wrapper_cnt_margin_enable_dsk' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_wrapper_cnt_margin_enable_dsk' ] : '';
          $ws_wrp_cnt_mar_dsk = isset( $s[ 'webyx_section_wrapper_cnt_margin_dsk' ] ) ? $s[ 'webyx_section_wrapper_cnt_margin_dsk' ] : array( 'unit' => 'px', 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'isLinked' => '' );
          $ws_wrp_cnt_pad_en_dsk = isset( $s[ 'webyx_section_wrapper_cnt_padding_enable_dsk' ] ) && in_array( $s[ 'webyx_section_wrapper_cnt_padding_enable_dsk' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_wrapper_cnt_padding_enable_dsk' ] : '';
          $ws_wrp_cnt_padding_dsk = isset( $s[ 'webyx_section_wrapper_cnt_padding_dsk' ] ) ? $s[ 'webyx_section_wrapper_cnt_padding_dsk' ] : array( 'unit' => 'px', 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'isLinked' => '' );
          $props = '';
          if ( 'on' === $ws_wrp_cnt ) {
            if ( 'on' === $ws_wrp_cnt_mar_en_dsk ) {
              $m = $ws_wrp_cnt_mar_dsk;
              $props .= 'margin:' . esc_attr( $m[ 'top' ] ) . esc_attr( $m[ 'unit' ] ) . ' ' . esc_attr( $m[ 'right' ] ) . esc_attr( $m[ 'unit' ] ) . ' ' .  esc_attr( $m[ 'bottom' ] ) . esc_attr( $m[ 'unit' ] ) . ' ' . esc_attr( $m[ 'left' ] ) . esc_attr( $m[ 'unit' ] ) . ';';
            }
            if ( 'on' === $ws_wrp_cnt_pad_en_dsk ) {
              $p = $ws_wrp_cnt_padding_dsk;
              $props .= 'padding:' . esc_attr( $p[ 'top' ] ) . esc_attr( $p[ 'unit' ] ) . ' ' . esc_attr( $p[ 'right' ] ) . esc_attr( $p[ 'unit' ] ) . ' ' .  esc_attr( $p[ 'bottom' ] ) . esc_attr( $p[ 'unit' ] ) . ' ' . esc_attr( $p[ 'left' ] ) . esc_attr( $p[ 'unit' ] ) . ';';
            }
          }
          $css = $props ? '.webyx-wrapper-slide-content.' . sanitize_html_class( $cn_wrp_cnt ) . '{' . $props . '}' : '';
          return $css;
        }
        public function webyx_fep_get_cnt_wrp_css_rules_xs_validated ( $s, $cn_bkg, $cn_wrp_cnt ) {
          $ws_wrp_cnt = isset( $s[ 'webyx_section_wrapper_cnt' ] ) && in_array( $s[ 'webyx_section_wrapper_cnt' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_wrapper_cnt' ] : '';
          $ws_wrp_cnt_mar_en_mob = isset( $s[ 'webyx_section_wrapper_cnt_margin_enable_mob' ] ) && in_array( $s[ 'webyx_section_wrapper_cnt_margin_enable_mob' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_wrapper_cnt_margin_enable_mob' ]  : '';
          $ws_wrp_cnt_mar_mob = isset( $s[ 'webyx_section_wrapper_cnt_margin_mob' ] ) ? $s[ 'webyx_section_wrapper_cnt_margin_mob' ] : array( 'unit' => 'px', 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'isLinked' => '' ); 
          $ws_wrp_cnt_pad_en_mob = isset( $s[ 'webyx_section_wrapper_cnt_padding_enable_mob' ] ) && in_array( $s[ 'webyx_section_wrapper_cnt_padding_enable_mob' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_wrapper_cnt_padding_enable_mob' ] : '';
          $ws_wrp_cnt_pad_mob = isset( $s[ 'webyx_section_wrapper_cnt_padding_mob' ] ) ? $s[ 'webyx_section_wrapper_cnt_padding_mob' ] : array( 'unit' => 'px', 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'isLinked' => '' ); 
          $props_mob = '';
          if ( 'on' === $ws_wrp_cnt ) {
            if ( 'on' === $ws_wrp_cnt_mar_en_mob ) {
              $m = $ws_wrp_cnt_mar_mob;
              $props_mob .= 'margin:' . esc_attr( $m[ 'top' ] ) . esc_attr( $m[ 'unit' ] ) . ' ' . esc_attr( $m[ 'right' ] ) . esc_attr( $m[ 'unit' ] ) . ' ' . esc_attr( $m[ 'bottom' ] ) . esc_attr( $m[ 'unit' ] ) . ' ' . esc_attr( $m[ 'left' ] ) .esc_attr( $m[ 'unit' ] ) . ';';
            }
            if ( 'on' === $ws_wrp_cnt_pad_en_mob ) {
              $p = $ws_wrp_cnt_pad_mob;
              $props_mob .= 'padding:' . esc_attr( $p[ 'top' ] ) . esc_attr( $p[ 'unit' ] ) . ' ' . esc_attr( $p[ 'right' ] ) . esc_attr( $p[ 'unit' ] ) . ' ' . esc_attr( $p[ 'bottom' ] ) . esc_attr( $p[ 'unit' ] ) . ' ' . esc_attr( $p[ 'left' ] ) . esc_attr( $p[ 'unit' ] ) . ';';
            }
          }
          $css = $props_mob ? '.webyx-wrapper-slide-content.' . sanitize_html_class( $cn_wrp_cnt ) . '{' . $props_mob . '}' : '';
          return $css;
        }
        public function webyx_fep_handle_update () {
          add_filter( 
            'plugins_api', 
            array(
              $this,
              'webyx_fep_plugin_info'
            ), 
            20, 
            3
          );
          add_filter( 
            'site_transient_update_plugins',
            array(
              $this, 
              'webyx_fep_push_update'
            ),
            1
          );
          add_action( 
            'in_plugin_update_message-webyx-fep/webyx-fep.php', 
            array(
              $this,
              'webyx_fep_update_message' 
            ),
            10, 
            2 
          );
          add_action( 
            'upgrader_process_complete', 
            array( 
              $this, 'webyx_fep_plugin_purge' 
            ), 
            10, 
            2 
          );
        }
        public function webyx_fep_plugin_purge ( $upgrader, $options ) {
          if ( $this->cache_allowed && 'update' === $options['action'] && 'plugin' === $options[ 'type' ] ) {
            delete_transient( $this->cache_key );
          }
        }
        public function webyx_fep_plugin_info_request () {
          $remote = get_transient( $this->cache_key );
          if ( false === $remote || ! $this->cache_allowed ) {
            $url = $this->api . '/webyx/v1/info';
            $body = array(
              'webyx_lk'                   => get_option( 'webyx_fep_lk' ),
              'webyx_product_id'           => $this->product_id,
              'webyx_product_user_version' => WEBYX_FEP_VERSION,
            );
            $args = array(
              'method'  => 'POST',
              'timeout' => 60,
              'headers' => array(
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
              ),
              'body' => json_encode( $body ),
            );
            $remote = wp_remote_post( $url, $args );
            if(
              is_wp_error( $remote )
              || 200 !== wp_remote_retrieve_response_code( $remote )
              || empty( wp_remote_retrieve_body( $remote ) )
              || isset( $remote->err )
            ) {
              return false;
            }
            set_transient( $this->cache_key, $remote, DAY_IN_SECONDS );
          }
          $remote = json_decode( wp_remote_retrieve_body( $remote ) );
          return $remote;
        }
        public function webyx_fep_plugin_info ( $res, $action, $args ) {
          if ( 'plugin_information' !== $action ) {
            return $res;
          }
          if ( plugin_basename( __DIR__ ) !== $args->slug ) {
            return $res;
          }
          $remote = $this->webyx_fep_plugin_info_request();
          if( ! $remote ) {
            return $res;
          }
          $res = new stdClass();
          $res->name = $remote->name;
          $res->slug = $remote->slug;
          $res->author = $remote->author;
          $res->version = $remote->version;
          $res->tested = $remote->tested;
          $res->requires = $remote->requires;
          $res->requires_php = $remote->requires_php;
          $res->download_link = $remote->download_url;
          $res->trunk = $remote->download_url;
          $res->last_updated = $remote->last_updated;
          $res->banners = $remote->banners;
          $res->sections = array(
            'description' => $remote->sections->description,
            'installation' => $remote->sections->installation,
            'changelog' => $remote->sections->changelog
          );
          if( ! empty( $remote->sections->screenshots ) ) {
            $res->sections[ 'screenshots' ] = $remote->sections->screenshots;
          }
          $res->banners = array(
            'low' => $remote->banners->low,
            'high' => $remote->banners->high
          );
          return $res;
        }
        public function webyx_fep_push_update ( $transient ) {
          if ( empty( $transient->checked ) ) {
            return $transient;
          }
          $remote = $this->webyx_fep_plugin_info_request();
          if (
            $remote
            && isset( $remote->version ) 
            && isset( $remote->requires ) 
            && isset( $remote->requires_php ) 
            && version_compare( WEBYX_FEP_VERSION, $remote->version, '<' )
            && version_compare( $remote->requires, get_bloginfo( 'version' ), '<' )
            && version_compare( $remote->requires_php, PHP_VERSION, '<' )
          ) {
            $res = new stdClass();
            $res->slug = $remote->slug;
            $res->plugin = plugin_basename( __FILE__ );
            $res->new_version = $remote->version;
            $res->tested = $remote->tested;
            $res->package = $remote->download_url;
            $transient->response[ $res->plugin ] = $res;
            $transient->checked[ $res->plugin  ] = $remote->version;
          }
          return $transient;
        }
        public function webyx_fep_update_message ( $plugin_info_array, $plugin_info_object ) {
          $plugins_options_url = get_admin_url() . 'options-general.php?page=webyx_fep_plugin_settings';
          if ( empty( $plugin_info_array[ 'package' ] ) ) {
            echo ' Please renew your license to update. You can change your license key in <a href=' . esc_url( $plugins_options_url ) . '>Webyx FE Pro Settings</a>';
          }
        }
        public function webyx_fep_rest_auth () {
          add_action( 
            'rest_api_init',
            array( 
              $this, 
              'webyx_fep_register_rest_auth' 
            ), 
            10 
          );
        }
        public function webyx_fep_register_rest_auth () {
          $version   = '1';
          $namespace = 'webyx/v' . $version; 
          register_rest_route( 
            $namespace, 
            '/active',
            array(
              array(
                'methods'  => WP_REST_Server::CREATABLE,
                'callback' => array( 
                  $this, 
                  'webyx_fep_active' 
                ),
                'permission_callback' => array( 
                  $this, 
                  'webyx_fep_security_on_auth_api' 
                ),
                'args' => $this->webyx_fep_auth_endpoint_args(),
              )
            ) 
          );
          register_rest_route( 
            $namespace, 
            '/deactive',
            array(
              array(
                'methods'  => WP_REST_Server::CREATABLE,
                'callback' => array( 
                  $this, 
                  'webyx_fep_deactive' 
                ),
                'permission_callback' => array( 
                  $this, 
                  'webyx_fep_security_on_auth_api' 
                ),
                'args' => $this->webyx_fep_auth_endpoint_args(),
              )
            ) 
          );
          register_rest_route( 
            $namespace, 
            '/version',
            array(
              array(
                'methods'  => WP_REST_Server::CREATABLE,
                'callback' => array( 
                  $this, 
                  'webyx_fep_version' 
                ),
                'permission_callback' => array( 
                  $this, 
                  'webyx_fep_security_on_auth_api' 
                ),
                'args' => $this->webyx_fep_auth_endpoint_args(),
              )
            ) 
          );
          register_rest_route( 
            $namespace, 
            '/update',
            array(
              array(
                'methods'  => WP_REST_Server::CREATABLE,
                'callback' => array( 
                  $this, 
                  'webyx_fep_update' 
                ),
                'permission_callback' => array( 
                  $this, 
                  'webyx_fep_security_on_auth_api' 
                ),
                'args' => $this->webyx_fep_update_endpoint_args(),
              )
            ) 
          );
          register_rest_route( 
            $namespace, 
            '/active/support',
            array(
              array(
                'methods'  => WP_REST_Server::CREATABLE,
                'callback' => array( 
                  $this, 
                  'webyx_fep_active_support' 
                ),
                'permission_callback' => array( 
                  $this, 
                  'webyx_fep_security_on_auth_api' 
                ),
                'args' => $this->webyx_fep_auth_endpoint_args(),
              )
            ) 
          );
          register_rest_route( 
            $namespace, 
            '/deactive/support',
            array(
              array(
                'methods'  => WP_REST_Server::CREATABLE,
                'callback' => array( 
                  $this, 
                  'webyx_fep_deactive_support' 
                ),
                'permission_callback' => array( 
                  $this, 
                  'webyx_fep_security_on_auth_api' 
                ),
                'args' => $this->webyx_fep_auth_endpoint_args(),
              )
            ) 
          );
          register_rest_route( 
            $namespace, 
            '/support',
            array(
              array(
                'methods'  => WP_REST_Server::CREATABLE,
                'callback' => array( 
                  $this, 
                  'webyx_fep_support' 
                ),
                'permission_callback' => array( 
                  $this, 
                  'webyx_fep_security_on_auth_api' 
                ),
                'args' => $this->webyx_fep_support_endpoint_args(),
              )
            ) 
          );
        }
        public function webyx_fep_security_on_auth_api () {
          return current_user_can( 'edit_posts' );
        }
        public function webyx_fep_auth_endpoint_args () {
          $args = array();
          $args[ 'webyx_lk' ] = array(
            'type' => 'string',
            'validate_callback' => array( 
              $this, 
              'webyx_fep_active_endpoint_validate_args' 
            ),
            'required' => true
          );
          return $args;
        }
        public function webyx_fep_update_endpoint_args () {
          $args = array();
          $args[ 'webyx_fep_download_url' ] = array(
            'type' => 'string',
            'validate_callback' => array( 
              $this, 
              'webyx_fep_update_endpoint_validate_args_download_url' 
            ),
            'required' => true
          );
          $args[ 'webyx_fep_version' ] = array(
            'type' => 'string',
            'validate_callback' => array( 
              $this, 
              'webyx_fep_update_endpoint_validate_args_version' 
            ),
            'required' => true
          );
          return $args;
        }
        public function webyx_fep_support_endpoint_args () {
          $args = array();
          $args[ 'webyx_lk' ] = array(
            'type' => 'string',
            'validate_callback' => array( 
              $this, 
              'webyx_fep_active_endpoint_validate_args' 
            ),
            'required' => true
          );
          $args[ 'webyx_ls' ] = array(
            'type' => 'string',
            'validate_callback' => array( 
              $this, 
              'webyx_fep_active_endpoint_validate_args' 
            ),
            'required' => true
          );
          $args[ 'webyx_support_email' ] = array(
            'type' => 'string',
            'required' => true
          );
          $args[ 'webyx_support_message' ] = array(
            'type' => 'string',
            'required' => true
          );
          $args[ 'webyx_policy_data' ] = array(
            'type' => 'string',
            'validate_callback' => array( 
              $this, 
              'webyx_fep_support_endpoint_validate_args' 
            ),
            'required' => true
          );
          return $args;
        }
        public function webyx_fep_active_endpoint_validate_args ( $value, $request, $param ) {
          $pattern = '/^\w{8}-\w{8}-\w{8}-\w{8}$/';
          return preg_match( $pattern, $value );
        }
        public function webyx_fep_support_endpoint_validate_args ( $value, $request, $param ) {
          return 'true' === $value;
        }
        public function webyx_fep_update_endpoint_validate_args_download_url ( $value, $request, $param ) {
          $parsed_url = parse_url( $value );
          $scheme = isset( $parsed_url[ 'scheme' ] ) && ( WEBYX_FEP_ASSET_MIN ? 'https' === $parsed_url[ 'scheme' ] : 'http' === $parsed_url[ 'scheme' ] );
          $host = isset( $parsed_url[ 'host' ] ) && ( WEBYX_FEP_ASSET_MIN ? 'webyx.it' === $parsed_url[ 'host' ] : TRUE );
          $path = isset( $parsed_url[ 'path' ] ) && '/webyx/v1/update' === $parsed_url[ 'path' ];
          if ( isset( $parsed_url[ 'query' ] ) ) {
            parse_str( $parsed_url[ 'query' ], $params );
          }
          $query = isset( $params ) && isset( $params[ 'token' ] ) && isset( $params[ 'version' ] );
          return $scheme && $host && $path && $query;
        }
        public function webyx_fep_update_endpoint_validate_args_version ( $value, $request, $param ) {
          $pattern = '/^([1-9]\d*|0)(\.(([1-9]\d*)|0)){0,3}$/';
          return preg_match( $pattern, $value );
        }
        public function webyx_fep_remote_resp_handler ( $resp, $ctx ) {
          $body = array(
            $ctx   => NULL,
            'err' => array(
              'type'        => $ctx,
              'description' => 'error-remote-post',
              'message'     => 'An unexpected error occurred while connecting to our server! Please contact us.',
            )
          );
          if ( ! is_wp_error( $resp ) ) {
            $body = json_decode( wp_remote_retrieve_body( $resp ) );
          } else {
            $error_message = $resp->get_error_message();
            $body[ 'err' ] = array(
              'type'        => $ctx,
              'description' => $error_message,
              'message'     => 'An unexpected error occurred while connecting to our server! This error comes from settings or configurations of your server/hosting. We recommend starting with simple solutions like temporarily disabling your WordPress firewall. Then, you can move on to checking your SSL and DNS settings, along with server resource limits. Finally, if all else fails, it may be time to contact your web host for assistance.'
            );
          }
          return $body;
        }
        public function webyx_fep_active ( $request ) {
          $params = $request->get_params();
          $webyx_lk = $params[ 'webyx_lk' ];
          $ctx = 'active';
          $resp = wp_remote_post(
            $this->api . '/webyx/v1/active',
            array(
              'method' => 'POST',
              'timeout' => 60,
              'headers' => array( 
                'Content-Type' => 'application/json' 
              ),
              'data_format' => 'body',
              'body'        => json_encode(
                array(
                  'webyx_lk'                   => $webyx_lk,
                  'webyx_product_id'           => $this->product_id,
                  'webyx_product_user_version' => WEBYX_FEP_VERSION,
                )
              )
            )
          );
          return $this->webyx_fep_remote_resp_handler( $resp, $ctx );
        }
        public function webyx_fep_deactive ( $request ) {
          $params = $request->get_params();
          $webyx_lk = $params[ 'webyx_lk' ];
          $ctx = 'deactive';
          $resp = wp_remote_post(
            $this->api . '/webyx/v1/deactive',
            array(
              'method' => 'POST',
              'timeout' => 60,
              'headers' => array( 
                'Content-Type' => 'application/json',
              ),
              'data_format' => 'body',
              'body'        => json_encode(
                array(
                  'webyx_lk'                   => $webyx_lk,
                  'webyx_product_id'           => $this->product_id,
                  'webyx_product_user_version' => WEBYX_FEP_VERSION,
                )
              )
            )
          );
          return $this->webyx_fep_remote_resp_handler( $resp, $ctx );
        }
        public function webyx_fep_active_support ( $request ) {
          $params = $request->get_params();
          $webyx_lk = $params[ 'webyx_lk' ];
          $ctx = 'active';
          $resp = wp_remote_post(
            $this->api . '/webyx/v1/active',
            array(
              'method' => 'POST',
              'timeout' => 60,
              'headers' => array( 
                'Content-Type' => 'application/json' 
              ),
              'data_format' => 'body',
              'body'        => json_encode(
                array(
                  'webyx_lk'                   => $webyx_lk,
                  'webyx_product_id'           => $this->support_id,
                  'webyx_product_user_version' => WEBYX_FEP_VERSION,
                )
              )
            )
          );
          return $this->webyx_fep_remote_resp_handler( $resp, $ctx );
        }
        public function webyx_fep_deactive_support ( $request ) {
          $params = $request->get_params();
          $webyx_lk = $params[ 'webyx_lk' ];
          $ctx = 'deactive';
          $resp = wp_remote_post(
            $this->api . '/webyx/v1/deactive',
            array(
              'method' => 'POST',
              'timeout' => 60,
              'headers' => array( 
                'Content-Type' => 'application/json',
              ),
              'data_format' => 'body',
              'body'        => json_encode(
                array(
                  'webyx_lk'                   => $webyx_lk,
                  'webyx_product_id'           => $this->support_id,
                  'webyx_product_user_version' => WEBYX_FEP_VERSION,
                )
              )
            )
          );
          return $this->webyx_fep_remote_resp_handler( $resp, $ctx );
        }
        public function webyx_fep_support ( $request ) {
          $params = $request->get_params();
          $webyx_lk = $params[ 'webyx_lk' ];
          $webyx_ls = $params[ 'webyx_ls' ];
          $webyx_policy_data = $params[ 'webyx_policy_data' ];
          $webyx_support_email = sanitize_textarea_field( $params[ 'webyx_support_email' ] );
          $webyx_support_message = sanitize_textarea_field( $params[ 'webyx_support_message' ] );
          $webyx_wp_version = esc_html( get_bloginfo( 'version' ) );
          $webyx_template = esc_html( get_template() );
          $ctx = 'support';
          $resp = wp_remote_post(
            $this->api . '/webyx/v1/support',
            array(
              'method' => 'POST',
              'timeout' => 60,
              'headers' => array( 
                'Content-Type' => 'application/json',
              ),
              'data_format' => 'body',
              'body'        => json_encode(
                array(
                  'webyx_lk'                   => $webyx_lk,
                  'webyx_product_id'           => $this->product_id,
                  'webyx_product_user_version' => WEBYX_FEP_VERSION,
                  'webyx_ls'                   => $webyx_ls,
                  'webyx_support_id'           => $this->support_id,
                  'webyx_support_email'        => $webyx_support_email,
                  'webyx_support_message'      => $webyx_support_message,
                  'webyx_wp_version'           => $webyx_wp_version,
                  'webyx_template'             => $webyx_template,
                  'webyx_php_version'          => PHP_VERSION,
                  'webyx_policy_data'          => $webyx_policy_data,
                )
              )
            )
          );
          return $this->webyx_fep_remote_resp_handler( $resp, $ctx );
        }
        public function webyx_fep_version ( $request ) {
          $params = $request->get_params();
          $webyx_lk = $params[ 'webyx_lk' ];
          $ctx = 'version';
          $resp = wp_remote_post(
            $this->api . '/webyx/v1/version',
            array(
              'method' => 'POST',
              'timeout' => 60,
              'headers' => array( 
                'Content-Type' => 'application/json',
              ),
              'data_format' => 'body',
              'body'        => json_encode(
                array(
                  'webyx_lk'                   => $webyx_lk,
                  'webyx_product_id'           => $this->product_id,
                  'webyx_product_user_version' => WEBYX_FEP_VERSION,
                )
              )
            )
          );
          return $this->webyx_fep_remote_resp_handler( $resp, $ctx );
        }
        public function webyx_fep_update ( $request ) {
          $params = $request->get_params();
          $webyx_fep_download_url = $params[ 'webyx_fep_download_url' ];
          $webyx_fep_version = $params[ 'webyx_fep_version' ];
          $payload = array( 
            'update' => NULL, 
            'err' => array(
              'message' => 'An unexpected error occurred during rollback to previous version! Please contact us.'
            ),
          );
          if ( WEBYX_FEP_ASSET_MIN ) {
            $this->webyx_fep_package_rollback( $webyx_fep_download_url, $webyx_fep_version );
            if ( TRUE === $this->webyx_fep_rollback_run() ) {
              $payload = array( 
                'update' => TRUE, 
                'err' => NULL,
              );
            }
          }
          return rest_ensure_response( $payload );
        }
        private function webyx_fep_package_rollback ( $webyx_fep_download_url, $webyx_fep_version ) {
          $update_plugins = get_site_transient( 'update_plugins' );
          if ( ! is_object( $update_plugins ) ) {
            $update_plugins = new \stdClass();
          }
          $plugin_info = new \stdClass();
          $plugin_info->new_version = $webyx_fep_version;
          $plugin_info->slug = $this->slug;
          $plugin_info->package = $webyx_fep_download_url;
          $plugin_info->url = 'https://webyx.it/';
          $update_plugins->response[ $this->plugin_name ] = $plugin_info;
          set_site_transient( 'update_plugins', $update_plugins );
          if ( $this->cache_allowed ) {
            delete_transient( $this->cache_key );
          }
        }
        private function webyx_fep_rollback_run () {
          require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
          require_once( ABSPATH . 'wp-admin/includes/class-plugin-upgrader-skin.php' );
          require_once( ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php' );
          require_once( ABSPATH . 'wp-admin/includes/misc.php' );
          require_once( ABSPATH . 'wp-admin/includes/file.php' );
          require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
          $upgrader = new Plugin_Upgrader( new WP_Ajax_Upgrader_Skin() );
          $upgrader_state = $upgrader->upgrade( $this->plugin_name );
          return $upgrader_state;
        }
      }
      Webyx_Pro_For_Elementor::webyx_fep_get_instance();
    }
  }
<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://cometleads.com
 * @since      1.0.0
 *
 * @package    Gull_Wordpress_Plugin
 * @subpackage Gull_Wordpress_Plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Gull_Wordpress_Plugin
 * @subpackage Gull_Wordpress_Plugin/admin
 */
class CMTLC_GULL_Admin {
	private $plugin_name;
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function load_assets($hook) {
	  if ($hook != 'settings_page_' . CMTLC_GULL_PLUGIN_SLUG) {
	    return;
    }

    // include bootstrap and parsley (a form validation library)
?>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.8.1/parsley.min.js" integrity="sha256-XqEmjxbIPXDk11mQpk9cpZxYT+8mRyVIkko8mQzX3y8=" crossorigin="anonymous"></script>
<?php
    error_log(plugin_dir_url(__FILE__));
    wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'assets/options.css');
    wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'assets/options.js', array('jquery'));
  }

	public function admin_menu() {
	  add_options_page(__(CMTLC_GULL_BRAND_NAME), __(CMTLC_GULL_BRAND_NAME), 'manage_options', CMTLC_GULL_PLUGIN_SLUG, array($this, 'gull_options_page'));
  }

  public function gull_options_page() {
    require_once(plugin_dir_path(__FILE__) . 'options.php');
  }

  public function admin_init() {
	  register_setting(CMTLC_GULL_OPTION_GROUP_NAME, CMTLC_GULL_OPTION_NAME);
	  add_settings_section('setting-section-1', '', array($this, 'setting_section_1_callback'), CMTLC_GULL_PLUGIN_SLUG);
	  add_settings_field(
	    'switch',
      __('Enable ' . CMTLC_GULL_BRAND_NAME . '
        <br/>
        <small>Include the widget to your web pages?</small>
      ', CMTLC_GULL_TEXT_DOMAIN),
      array($this, 'setting_switch_callback'),
      CMTLC_GULL_PLUGIN_SLUG,
      'setting-section-1');
	  add_settings_field('key',
      __('Key' . '
        <br/>
        <small>A unique identifier to your site.</small>
        <br/>
        <small>You may find the key from 
          <a href="https://'.CMTLC_GULL_SERVER_HOST.'/#/sites?url='
          .(isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on" ? "https://" : "http://")
          .$_SERVER['HTTP_HOST']
          .'" target="_blank">'
          .CMTLC_GULL_BRAND_NAME
          .' website</a>, and please paste here.
        </small>
      ', CMTLC_GULL_TEXT_DOMAIN),
      array($this, 'setting_script_callback'),
      CMTLC_GULL_PLUGIN_SLUG,
      'setting-section-1');
  }

  public function setting_section_1_callback() {
	  _e('');
  }

  public function setting_switch_callback() {
	  $status = 'disabled';
	  $options = get_option(CMTLC_GULL_OPTION_NAME);
	  if (isset($options['status'])) {
	    $status = $options['status'];
    }

    // an hacky way to submit unchecked checkbox value
    if ($status == 'disabled') {
	    echo '<label class="switch disabled">';
    } else {
	    echo '<label class="switch">';
    }
    echo '<input type="hidden" name="' . CMTLC_GULL_OPTION_NAME . '[status]" value="off">
          <input type="checkbox" name="' . CMTLC_GULL_OPTION_NAME . '[status]" value="on"';
    if ($status == 'disabled') {
      echo 'disabled>';
    } else if ($status == 'off') {
      echo '>';
    } else {
	    echo 'checked>';
    }
    echo '<span class="slider round"></span></label>';
    if ($status == 'disabled') {
      echo '<span id="switch-error-message" class="ml-2" style="color: red;">Disabled. A correct key is required below.</span>';
    }
  }

  public function setting_script_callback() {
	  $siteId = '';
	  $options = get_option(CMTLC_GULL_OPTION_NAME);
	  if (isset($options['siteId'])) {
	    $siteId = $options['siteId'];
    }

    echo '<input id="gull-key" 
    data-parsley-trigger="change" 
    data-parsley-required-message="This field is required." 
    data-parsley-pattern="[0-9a-fA-F]{24}" 
    required 
    data-parsley-pattern-message="Incorrect key. It shall be something like 5b4ef0931fd5e14001c62b7a" 
    class="form-control" 
    name="'.CMTLC_GULL_OPTION_NAME.'[siteId]"
    value="'.$siteId.'">';
  }

  public function plugin_action_links_callback($links) {
	  $links[] = '<a href="' . esc_url( get_admin_url(null, "options-general.php?page=" . $this->plugin_name)) . '">Settings</a>"';
	  return $links;
  }

  public function inject_public_script() {
	  $options = get_option(CMTLC_GULL_OPTION_NAME);
	  if (isset($options['status']) and $options['status'] == 'on' and isset($options['siteId'])) {
	    $siteId = $options['siteId'];
      $script = '<script type="text/javascript" src="https://'.CMTLC_GULL_SERVER_HOST.'/api/v1/widget.js?siteId='.$siteId.'" async></script>';
      error_log('injecting script ' . $script);
      echo $script;
    } else {
	    error_log('injection is off');
    }
  }
}

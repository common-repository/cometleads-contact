<?php
/**
 * Plugin Options page
 *
 * User: Jason Huang
 * Date: 7/20/18
 * Time: 5:45 PM
 */
$options = get_option(CMTLC_GULL_OPTION_NAME);

$running_status = 'disabled';
if (isset($options['status'])) {
  $running_status = $options['status']; // 'on' or 'off'
}

$script = '';
if (isset($options['script'])) {
  $script = $options['script'];
}

?>
<div class="container card">
  <h2><?php _e(CMTLC_GULL_BRAND_NAME, CMTLC_GULL_TEXT_DOMAIN); ?></h2>
  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#status">Status</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#settings">Settings</a>
    </li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="status">
      <div class="status-circle d-flex justify-content-center align-items-center
          <?php
            if ($running_status == 'disabled') {
              echo ' grey">
                    <span>Unavailable
                      <br/>
                      <span style="font-size: 0.75rem">
                        check settings
                      </span>
                    </span>';
            } else if ($running_status == 'off') {
              echo ' red"><span>Stopped</span>';
            } else {
              echo ' green"><span>Running</span>';
            }
          ?>
      </div>
    </div>
    <div class="tab-pane" id="settings">
      <form action="options.php" method="POST" data-parsley-validate>
        <?php
        settings_fields(CMTLC_GULL_OPTION_GROUP_NAME);
        do_settings_sections(CMTLC_GULL_PLUGIN_SLUG);
        submit_button();
        ?>
      </form>
    </div>
  </div>
</div>
